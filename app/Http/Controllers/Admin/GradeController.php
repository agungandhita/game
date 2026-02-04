<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = UserProgress::with(['user', 'level.world']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('nickname', 'like', "%{$search}%");
                })->orWhereHas('level', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('level_id') && $request->level_id != '') {
            $query->where('level_id', $request->level_id);
        }

        $grades = $query->latest()->paginate(10);
        $levels = Level::all();

        return view('admin.grades.index', compact('grades', 'levels'));
    }

    public function show($id)
    {
        $grade = UserProgress::with(['user', 'level.world', 'level.questions'])->findOrFail($id);

        return view('admin.grades.show', compact('grade'));
    }

    public function export(Request $request)
    {
        $fileName = 'grades.csv';
        $grades = UserProgress::with(['user', 'level'])->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['User', 'Level', 'Score', 'Stars', 'Completed', 'Attempts', 'Date'];

        $callback = function () use ($grades, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($grades as $grade) {
                $row['User'] = $grade->user->nickname ?? 'Unknown';
                $row['Level'] = $grade->level->title ?? 'Unknown';
                $row['Score'] = $grade->score;
                $row['Stars'] = $grade->stars;
                $row['Completed'] = $grade->is_completed ? 'Yes' : 'No';
                $row['Attempts'] = $grade->attempts;
                $row['Date'] = $grade->created_at;

                fputcsv($file, [$row['User'], $row['Level'], $row['Score'], $row['Stars'], $row['Completed'], $row['Attempts'], $row['Date']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
