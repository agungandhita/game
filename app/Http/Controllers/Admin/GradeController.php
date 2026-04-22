<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Services\Grade\GradeServiceInterface;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct(private GradeServiceInterface $gradeService)
    {
    }

    public function index(Request $request)
    {
        $grades = $this->gradeService->getPaginated($request->search, $request->level_id);
        $levels = Level::all();

        return view('admin.grades.index', compact('grades', 'levels'));
    }

    public function show($id)
    {
        $grade = $this->gradeService->getById($id);

        return view('admin.grades.show', compact('grade'));
    }

    public function export(Request $request)
    {
        $fileName = 'grades.csv';
        $grades = $this->gradeService->getAllForExport();

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
