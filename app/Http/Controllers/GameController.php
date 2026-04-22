<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Level;
use App\Models\QuestionOption;
use App\Models\User;
use App\Models\UserProgress;
use App\Services\Game\GameServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function __construct(private GameServiceInterface $gameService)
    {
    }

    public function show(Level $level)
    {
        $level->load(['questions.options', 'world']);

        return view('game.show', compact('level'));
    }

    public function submit(Request $request, Level $level)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $result = $this->gameService->submitAnswers(Auth::id(), $level, $validated['answers']);

        return response()->json($result);
    }
}
