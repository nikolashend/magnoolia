<?php

namespace App\Http\Controllers\Admin\Magnoolia;

use App\Http\Controllers\Controller;
use App\Models\MagnooliaMediaItem;
use App\Models\MagnooliaUnit;
use App\Services\Magnoolia\MagnooliaAuditService;
use App\Services\Magnoolia\MagnooliaMediaService;
use Illuminate\Http\Request;

class MagnooliaMediaController extends Controller
{
    public function __construct(
        private readonly MagnooliaMediaService $media,
        private readonly MagnooliaAuditService $audit,
    ) {
    }

    public function index(Request $request)
    {
        $query = MagnooliaMediaItem::query();
        if ($cat = $request->query('category')) {
            $query->where('category', $cat);
        }
        if ($search = trim((string) $request->query('q'))) {
            $query->where('title', 'like', '%' . $search . '%');
        }
        if ($request->query('missing_alt') === '1') {
            $query->whereNull('alt_et')->whereNull('alt_ru')->whereNull('alt_en');
        }
        $items = $query->orderByDesc('id')->paginate(24)->withQueryString();
        $categories = MagnooliaMediaItem::CATEGORIES;
        $units = MagnooliaUnit::query()->orderBy('sort_order')->get(['unit_key', 'address']);

        return view('admin.magnoolia.media-index', compact('items', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:12288|mimes:jpg,jpeg,png,webp,svg,pdf', // 12 MB
            'title' => 'nullable|string|max:190',
            'category' => 'required|string|in:' . implode(',', array_keys(MagnooliaMediaItem::CATEGORIES)),
            'alt_et' => 'nullable|string|max:255',
            'alt_ru' => 'nullable|string|max:255',
            'alt_en' => 'nullable|string|max:255',
        ]);

        $item = $this->media->store($request->file('file'), [
            'title' => $validated['title'] ?? $request->file('file')->getClientOriginalName(),
            'category' => $validated['category'],
            'alt_et' => $validated['alt_et'] ?? null,
            'alt_ru' => $validated['alt_ru'] ?? null,
            'alt_en' => $validated['alt_en'] ?? null,
            'uploaded_by' => (int) $request->user()->id,
        ]);

        $this->audit->log('media_uploaded', (int) $request->user()->id, 'media', (string) $item->id, null,
            ['title' => $item->title, 'category' => $item->category, 'public_path' => $item->public_path],
            'Media uploaded', $request->ip(), $request->userAgent());

        return back()->with('status', "Uploaded \"{$item->title}\". Add alt text and assign it where needed.");
    }

    public function update(Request $request, MagnooliaMediaItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:190',
            'category' => 'required|string|in:' . implode(',', array_keys(MagnooliaMediaItem::CATEGORIES)),
            'alt_et' => 'nullable|string|max:255',
            'alt_ru' => 'nullable|string|max:255',
            'alt_en' => 'nullable|string|max:255',
            'assignment_target' => 'nullable|string|max:190',
        ]);

        $before = $item->toArray();
        $item->fill($validated)->save();

        // Real assignment: "unit:{unitKey}:floor1|floor2" updates that unit's DRAFT
        // floor-plan field, so it flows to the public site only after Publish.
        $assignNote = '';
        if (preg_match('/^unit:([\w\-]+):(floor1|floor2)$/', (string) ($validated['assignment_target'] ?? ''), $m) && $item->public_path) {
            $unit = MagnooliaUnit::query()->where('unit_key', $m[1])->first();
            if ($unit) {
                $field = $m[2] === 'floor1' ? 'floorplan_floor_1' : 'floorplan_floor_2';
                $unitBefore = $unit->toArray();
                $unit->{$field} = $item->public_path;
                $unit->lock_version = ((int) $unit->lock_version) + 1;
                $unit->updated_by = (int) $request->user()->id;
                $unit->save();
                $this->audit->log('unit_updated', (int) $request->user()->id, 'unit', $unit->unit_key, $unitBefore, $unit->toArray(),
                    "Assigned media #{$item->id} to {$field} (draft — publish to go live)", $request->ip(), $request->userAgent());
                $assignNote = " Assigned to {$unit->address} {$m[2]} (draft).";
            }
        }

        $this->audit->log('media_updated', (int) $request->user()->id, 'media', (string) $item->id, $before, $item->toArray(),
            'Media metadata updated', $request->ip(), $request->userAgent());

        return back()->with('status', "Saved \"{$item->title}\".{$assignNote}");
    }

    public function destroy(Request $request, MagnooliaMediaItem $item)
    {
        // Delete guard: do not remove media referenced by the active publication
        // unless the admin explicitly confirms.
        if ($this->media->isUsedInActivePublication($item) && !$request->boolean('confirm_used')) {
            return back()->withErrors([
                'media' => "\"{$item->title}\" is used by the live published site. Re-confirm deletion to proceed (it may break a public image until you publish a replacement).",
            ]);
        }

        $title = $item->title;
        $snapshot = $item->toArray();
        $this->media->delete($item);
        $this->audit->log('media_deleted', (int) $request->user()->id, 'media', $snapshot['id'] ?? null, $snapshot, null,
            'Media deleted', $request->ip(), $request->userAgent());

        return back()->with('status', "Deleted \"{$title}\".");
    }
}
