<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLevelRequest;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $grades = Grade::orderBy('order')->get();
        $query = Level::with(['grade', 'questions'])->orderBy('grade_id')->orderBy('order');

        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        $levels = $query->get()->map(function ($level) {
            $level->total_questions = $level->questions->count();
            return $level;
        });

        return view('admin.levels.index', compact('levels', 'grades'));
    }

    public function edit(Level $level)
    {
        $level->load('grade');
        return view('admin.levels.edit', compact('level'));
    }

    public function update(UpdateLevelRequest $request, Level $level)
    {
        $level->update([
            'name'             => $request->name ?? $level->name,
            'time_per_question' => $request->time_per_question,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.grades.show', $level->grade_id)
            ->with('success', 'Level berhasil diperbarui!');
    }
}
