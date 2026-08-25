<?php

namespace App\Http\Controllers\Admin\Magnoolia;

use App\Http\Controllers\Controller;
use App\Models\MagnooliaList;
use App\Models\MagnooliaListItem;
use App\Models\MagnooliaMediaItem;
use App\Services\Magnoolia\MagnooliaAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Phase 36, Module C — the "Lists" editor.
 *
 * One screen serves every list; the type's field set decides which inputs appear
 * (see config/magnoolia_list_types.php). Everything written here is draft only —
 * the public site changes when someone runs Publish Website Changes, exactly as
 * for texts and pictures (Phase 36 decision 2).
 */
class MagnooliaListController extends Controller
{
    public function __construct(private readonly MagnooliaAuditService $audit)
    {
    }

    public function index()
    {
        $definitions = MagnooliaList::definitions();
        $rows = MagnooliaList::query()->withCount('items')->get()->keyBy('list_key');

        $grouped = [];
        foreach ($definitions as $key => $definition) {
            $grouped[$definition['page'] ?? 'muu'][$key] = $definition + [
                'row'   => $rows[$key] ?? null,
                'count' => $rows[$key]->items_count ?? 0,
                'type_label' => config('magnoolia_list_types', [])[$definition['type']]['label'] ?? $definition['type'],
            ];
        }

        return view('admin.magnoolia.lists-index', [
            'grouped' => $grouped,
            'pages'   => $this->pageLabels(),
            // Nothing seeded yet is the one state the screen cannot fix by itself.
            'unseeded' => collect($definitions)->keys()
                ->reject(fn ($key) => ($rows[$key]->items_count ?? 0) > 0)->values()->all(),
        ]);
    }

    public function edit(string $listKey)
    {
        $definition = $this->definitionOr404($listKey);
        $list = $this->listFor($listKey, $definition);

        return view('admin.magnoolia.lists-edit', [
            'list'       => $list,
            'definition' => $definition,
            'fields'     => $list->fields(),
            'items'      => $list->items()->with('mediaItem')->get(),
            'library'    => MagnooliaMediaItem::query()->orderBy('category')->orderBy('title')->get(),
            'locales'    => ['et' => 'Eesti', 'ru' => 'Vene', 'en' => 'Inglise'],
        ]);
    }

    /**
     * Save every entry of one list in a single submit — values, order and which
     * entries are shown. Order arrives as a hidden field per row that the drag
     * handles rewrite, so reordering works without JavaScript having to save.
     */
    public function update(Request $request, string $listKey): RedirectResponse
    {
        $definition = $this->definitionOr404($listKey);
        $list = $this->listFor($listKey, $definition);
        $fields = $list->fields();

        $submitted = $request->input('items', []);
        $before = $list->items()->count();

        DB::transaction(function () use ($list, $fields, $submitted, $request) {
            $keep = [];

            foreach (array_values($submitted) as $position => $row) {
                $payload = ['et' => [], 'ru' => [], 'en' => []];
                $meta = [];

                foreach ($fields as $field => $spec) {
                    if (($spec['kind'] ?? 'text') === 'image') {
                        continue; // stored as media_item_id, below
                    }

                    if ($spec['t'] ?? false) {
                        foreach (['et', 'ru', 'en'] as $locale) {
                            $value = $row[$locale][$field] ?? null;
                            if ($spec['kind'] === 'lines') {
                                $value = $this->splitLines($value);
                            }
                            if (filled($value)) {
                                $payload[$locale][$field] = $value;
                            }
                        }
                        continue;
                    }

                    $value = $row['meta'][$field] ?? null;
                    $meta[$field] = match ($spec['kind']) {
                        'bool'  => (bool) $value,
                        'lines' => $this->splitLines($value),
                        default => is_string($value) ? trim($value) : $value,
                    };
                }

                // An entry with nothing in it is a leftover "add" click, not content.
                // Judged on the required fields only: a spec row carries a default
                // badge, so "has any value at all" would keep every empty row.
                if ($this->isBlank($fields, $payload['et'], $meta, $row['media_item_id'] ?? null)) {
                    continue;
                }

                $attributes = [
                    'sort_order'    => $position,
                    'is_active'     => (bool) ($row['is_active'] ?? false),
                    'media_item_id' => filled($row['media_item_id'] ?? null) ? (int) $row['media_item_id'] : null,
                    'payload_et'    => $payload['et'],
                    'payload_ru'    => $payload['ru'],
                    'payload_en'    => $payload['en'],
                    'meta'          => $meta,
                    'updated_by'    => $request->user()?->id,
                ];

                $existing = filled($row['id'] ?? null)
                    ? $list->items()->whereKey($row['id'])->first()
                    : null;

                if ($existing) {
                    $existing->update($attributes);
                    $keep[] = $existing->id;
                } else {
                    $keep[] = $list->items()->create($attributes)->id;
                }
            }

            // Rows removed in the browser are gone from the submit — delete them.
            $list->items()->whereNotIn('id', $keep ?: [0])->delete();
            $list->fill(['updated_by' => $request->user()?->id])->save();
        });

        $after = $list->items()->count();
        $this->audit->log('list_updated', $request->user()?->id, 'list', $listKey, ['items' => $before], ['items' => $after]);

        return back()->with('status', 'Salvestatud. Avalikule lehele jõuab pärast „Publish Website Changes“.');
    }

    /** Add one blank entry, so the editor never needs a JavaScript row template. */
    public function addItem(Request $request, string $listKey): RedirectResponse
    {
        $definition = $this->definitionOr404($listKey);
        $list = $this->listFor($listKey, $definition);

        $meta = [];
        foreach ($list->fields() as $field => $spec) {
            if (isset($spec['default'])) {
                $meta[$field] = $spec['default'];
            }
        }

        MagnooliaListItem::query()->create([
            'list_id'    => $list->id,
            'sort_order' => (int) $list->items()->max('sort_order') + 1,
            'is_active'  => true,
            'meta'       => $meta,
            'updated_by' => $request->user()?->id,
        ]);

        return back()->with('status', 'Lisatud tühi rida — täida ja salvesta.');
    }

    public function destroyItem(Request $request, string $listKey, int $item): RedirectResponse
    {
        $definition = $this->definitionOr404($listKey);
        $list = $this->listFor($listKey, $definition);

        // whereKey on the list's own relation: an id from another list cannot be
        // deleted by editing the URL.
        $list->items()->whereKey($item)->delete();
        $this->audit->log('list_item_deleted', $request->user()?->id, 'list', $listKey . '#' . $item);

        return back()->with('status', 'Rida kustutatud.');
    }

    /**
     * Is this submitted row empty enough to drop?
     *
     * Required fields decide. A type that declares none falls back to "nothing was
     * typed anywhere", so a list whose fields are all optional still keeps its rows.
     */
    private function isBlank(array $fields, array $estonian, array $meta, mixed $mediaItemId): bool
    {
        if (filled($mediaItemId)) {
            return false;
        }

        $required = array_filter($fields, fn ($spec) => $spec['required'] ?? false);

        if ($required !== []) {
            foreach ($required as $field => $spec) {
                $value = ($spec['t'] ?? false) ? ($estonian[$field] ?? null) : ($meta[$field] ?? null);
                if (filled($value)) {
                    return false;
                }
            }

            return true;
        }

        return $estonian === [] && array_filter($meta, fn ($v) => filled($v)) === [];
    }

    private function definitionOr404(string $listKey): array
    {
        $definition = MagnooliaList::definition($listKey);
        abort_if($definition === null, 404);

        return $definition;
    }

    /** The row is created on first visit — the registry is the source of truth. */
    private function listFor(string $listKey, array $definition): MagnooliaList
    {
        $list = MagnooliaList::query()->firstOrCreate(
            ['list_key' => $listKey],
            ['type' => $definition['type'], 'page' => $definition['page'] ?? null]
        );

        if ($list->type !== $definition['type'] || $list->page !== ($definition['page'] ?? null)) {
            $list->fill(['type' => $definition['type'], 'page' => $definition['page'] ?? null])->save();
        }

        return $list;
    }

    /** @return array<int, string> */
    private function splitLines(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value), 'strlen'));
        }

        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $value) ?: []), 'strlen'));
    }

    private function pageLabels(): array
    {
        return [
            'home'         => 'Avaleht',
            'arhitektuur'  => 'Arhitektuur',
            'sisedisain'   => 'Siseviimistlus',
            'galerii'      => 'Galerii',
            'kkk'          => 'KKK',
            'arendajast'   => 'Arendajast',
            'muu'          => 'Muu',
        ];
    }
}
