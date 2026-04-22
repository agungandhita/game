<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardServiceInterface;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private DashboardServiceInterface $dashboardService)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $worlds = $this->dashboardService->getAllWorlds($user);

        return view('dashboard.index', [
            'user' => $user,
            'worlds' => $worlds,
        ]);
    }

    public function world($slug)
    {
        $data = $this->dashboardService->getWorldWithProgressBySlug($slug, Auth::id());

        return view('dashboard.world', $data);
    }
}
