<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RecycleBinService;

class PurgeRecycleBin extends Command
{
    protected $signature = 'recycle-bin:purge';

    protected $description = 'Permanently delete trashed program plans, observations, reflections, and snapshots older than 7 days';

    public function handle(RecycleBinService $recycleBinService): int
    {
        $result = $recycleBinService->purgeExpiredItems();

        $this->info('Purged ' . $result['program_plans'] . ' program plans, ' . $result['observations'] . ' observations, ' . $result['reflections'] . ' reflections, and ' . $result['snapshots'] . ' snapshots.');

        return self::SUCCESS;
    }
}