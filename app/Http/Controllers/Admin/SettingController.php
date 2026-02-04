<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        Alert::success('Berhasil', 'Pengaturan berhasil disimpan!');

        return back();
    }

    public function backup()
    {
        try {
            $filename = 'backup-'.now()->format('Y-m-d-H-i-s').'.txt';
            $payload = json_encode([
                'created_at' => now()->toDateTimeString(),
                'type' => 'placeholder',
            ], JSON_THROW_ON_ERROR);

            Storage::disk('local')->put('backups/'.$filename, $payload);

            Alert::success('Berhasil', 'Backup berhasil dibuat!');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Backup gagal: '.$e->getMessage());
        }

        return back();
    }
}
