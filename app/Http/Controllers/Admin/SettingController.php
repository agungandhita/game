<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Setting\SettingServiceInterface;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function __construct(private SettingServiceInterface $settingService)
    {
    }

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
            $this->settingService->createBackup();
            Alert::success('Berhasil', 'Backup berhasil dibuat!');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
        }

        return back();
    }
}
