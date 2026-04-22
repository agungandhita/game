<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminServiceInterface;

class AdminController extends Controller
{
    public function __construct(private AdminServiceInterface $adminService)
    {
    }

    public function index()
    {
        $stats = $this->adminService->getDashboardStats();

        return view('admin.dashboard.index', compact('stats'));
    }
}
