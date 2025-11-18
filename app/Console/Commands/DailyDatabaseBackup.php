<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class DailyDatabaseBackup extends Command
{
    protected $signature = 'db:daily-backup';
    protected $description = 'Create a .sql backup of the database and delete old backups';

    public function handle()
    {
        $this->info('🚀 Starting database backup...');

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host     = config('database.connections.mysql.host');
        $port     = config('database.connections.mysql.port');

        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $fileName = "{$database}_backup_{$date}.sql";

        $storagePath = storage_path('app/backups');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $sqlPath = "{$storagePath}/{$fileName}";

        // Full path to mysqldump for XAMPP
        $mysqldumpPath = 'D:\\xampp\\mysql\\bin\\mysqldump.exe';  /////////   currently used local path....

        // Build and run command
        $command = "\"{$mysqldumpPath}\" --user={$username} --password={$password} --host={$host} --port={$port} {$database} > \"{$sqlPath}\"";
        exec($command, $output, $result);

        // Check if backup succeeded
        if ($result !== 0 || !file_exists($sqlPath) || filesize($sqlPath) === 0) {
            $this->error("❌ Database backup failed or file is empty.");
            return;
        }

        $this->info("✅ Backup completed: {$fileName}");
        $this->cleanOldBackups($storagePath);
        // Clean old backups

    }

    protected function cleanOldBackups($path)
    {
        $now = Carbon::now();
        $files = File::files($path);
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            if (preg_match('/\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}/', $fileName, $matches)) {
                try {
                    $fileDate = Carbon::createFromFormat('Y-m-d_H-i-s', $matches[0]);

                    if ($fileDate->diffInDays($now) >= 7) {
                        File::delete($file);
                        $this->info("🗑️ Deleted old backup: {$fileName}");
                    }
                } catch (\Exception $e) {
                    $this->error("⚠️ Error parsing date for file {$fileName}: " . $e->getMessage());
                }
            } else {
                $this->warn("⛔ Could not parse date from filename: {$fileName}");
            }
        }
    }
}
