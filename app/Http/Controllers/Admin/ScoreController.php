<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Level;
use App\Models\Quiz\LevelResult;
use App\Models\User;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index(Request $request)
    {
        $grades = Grade::orderBy('order')->get();
        $levels = collect();

        $query = LevelResult::with(['user', 'level.grade'])
            ->join('levels', 'level_results.level_id', '=', 'levels.id')
            ->join('grades', 'levels.grade_id', '=', 'grades.id')
            ->join('users', 'level_results.user_id', '=', 'users.id')
            ->select('level_results.*')
            ->orderBy('level_results.completed_at', 'desc');

        if ($request->filled('grade_id')) {
            $query->where('grades.id', $request->grade_id);
            $levels = Level::where('grade_id', $request->grade_id)->orderBy('order')->get();
        }

        if ($request->filled('level_id')) {
            $query->where('level_results.level_id', $request->level_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->search . '%')
                  ->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('level_results.completed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('level_results.completed_at', '<=', $request->date_to);
        }

        // Sortable
        $sortBy = $request->get('sort', 'completed_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['score', 'completed_at', 'total_correct', 'stars'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy("level_results.{$sortBy}", $direction);
        }

        $scores = $query->paginate(15)->withQueryString();

        return view('admin.scores.index', compact('scores', 'grades', 'levels'));
    }

    public function export(Request $request)
    {
        $query = LevelResult::with(['user', 'level.grade'])
            ->join('levels', 'level_results.level_id', '=', 'levels.id')
            ->join('grades', 'levels.grade_id', '=', 'grades.id')
            ->join('users', 'level_results.user_id', '=', 'users.id')
            ->select('level_results.*')
            ->orderBy('grades.order')
            ->orderBy('levels.order')
            ->orderBy('users.name');

        if ($request->filled('grade_id')) {
            $query->where('grades.id', $request->grade_id);
        }
        if ($request->filled('level_id')) {
            $query->where('level_results.level_id', $request->level_id);
        }

        $results = $query->get();

        $filename = 'laporan-skor-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($results) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Nama Siswa', 'Email', 'Kelas', 'Level',
                'Skor', 'Bintang', 'Total Benar', 'Total Soal',
                'Timeout', 'Tanggal Selesai'
            ]);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result->user->name,
                    $result->user->email,
                    $result->level->grade->name ?? '-',
                    $result->level->name ?? '-',
                    number_format($result->score, 2),
                    $result->stars,
                    $result->total_correct,
                    $result->total_questions,
                    $result->total_timeout,
                    $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
