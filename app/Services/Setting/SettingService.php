<?php

namespace App\Services\Setting;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class SettingService implements SettingServiceInterface
{
    public function createBackup(): void
    {
        try {
            $filename = 'backup-'.now()->format('Y-m-d-H-i-s').'.txt';
            $payload = json_encode([
                'created_at' => now()->toDateTimeString(),
                'type' => 'placeholder',
            ], JSON_THROW_ON_ERROR);

            Storage::disk('local')->put('backups/'.$filename, $payload);
        } catch (\Exception $e) {
            throw new RuntimeException('Backup gagal: ' . $e->getMessage());
        }
    }
}
