<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\World;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $worlds = World::orderBy('class')->get();

        return view('dashboard.index', [
            'user' => $user,
            'worlds' => $worlds,
        ]);
    }

    public function world($slug)
    {
        $world = World::where('slug', $slug)->with('levels')->firstOrFail();
        $user = Auth::user();

        $progress = collect();
        if ($user instanceof User) {
            $progress = $user->progress()
                ->whereIn('level_id', $world->levels->pluck('id'))
                ->get()
                ->keyBy('level_id');
        }

        return view('dashboard.world', [
            'world' => $world,
            'user' => $user,
            'progress' => $progress,
        ]);
    }
}
