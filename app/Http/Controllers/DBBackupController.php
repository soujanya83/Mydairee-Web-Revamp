<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class DBBackupController extends Controller
{
    public function runBackup()
    {
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

        // XAMPP mysqldump full path
        $mysqldumpPath = 'D:\\xampp\\mysql\\bin\\mysqldump.exe';  /////////   currently used local path....
        // Build and execute command
        $command = "\"{$mysqldumpPath}\" --user={$username} --password={$password} --host={$host} --port={$port} {$database} > \"{$sqlPath}\"";
        exec($command, $output, $result);

        if ($result !== 0 || !file_exists($sqlPath) || filesize($sqlPath) === 0) {
            return response()->json(['status' => 'error', 'message' => '❌ Backup failed or file is empty.']);
        }

        $this->cleanOldBackups($storagePath);

        return response()->json([
            'status' => 'success',
            'message' => '✅ Database backup completed.',
            'file' => $fileName
        ]);
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
                    }
                } catch (\Exception $e) {
                    // Skip invalid formats
                }
            }
        }
    }
}
