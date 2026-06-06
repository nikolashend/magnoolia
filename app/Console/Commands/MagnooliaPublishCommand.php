<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Magnoolia\MagnooliaPublicationService;
use App\Services\Magnoolia\MagnooliaValidationService;
use Illuminate\Console\Command;

class MagnooliaPublishCommand extends Command
{
    protected $signature = 'magnoolia:publish {--note=CLI publish : Publication note} {--force : Skip duplicate-check warning}';

    protected $description = 'Publish Magnoolia draft to public site (creates active publication in DB)';

    public function __construct(
        private readonly MagnooliaPublicationService $publicationService,
        private readonly MagnooliaValidationService $validationService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $note = (string) ($this->option('note') ?: 'CLI publish');

        $admin = User::query()->where('role', 'magnoolia_admin')->orderBy('id')->first();
        if (!$admin) {
            $this->error('No magnoolia_admin user found. Run: php artisan magnoolia:admin:create');
            return self::FAILURE;
        }

        $this->line("Publishing as: {$admin->email} (ID {$admin->id})");
        $this->line("Note: {$note}");

        $validation = $this->validationService->validateDraft();

        if (!empty($validation['blockers'])) {
            $this->error('Publication blocked by ' . count($validation['blockers']) . ' validation error(s):');
            foreach ($validation['blockers'] as $b) {
                $this->error("  • {$b}");
            }
            return self::FAILURE;
        }

        if (!empty($validation['warnings'])) {
            $this->warn(count($validation['warnings']) . ' warning(s):');
            foreach ($validation['warnings'] as $w) {
                $this->warn("  • {$w}");
            }
            $this->line('Proceeding with warnings (confirm_warnings mode).');
        }

        $result = $this->publicationService->publish($admin->id, $note);

        if (!($result['ok'] ?? false)) {
            if ($result['duplicate'] ?? false) {
                $this->warn('No changes: published data is identical to current version. Nothing to publish.');
                return self::SUCCESS;
            }
            $this->error('Publication failed: ' . ($result['message'] ?? 'unknown error'));
            return self::FAILURE;
        }

        $publication = $result['publication'];
        $this->info("Publication created successfully.");
        $this->line("Version:    {$publication->version}");
        $this->line("Published:  {$publication->published_at}");
        $this->line("Note:       {$publication->publication_note}");

        return self::SUCCESS;
    }
}
