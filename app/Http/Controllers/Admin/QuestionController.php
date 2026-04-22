<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Question;
use App\DTOs\Question\CreateQuestionDTO;
use App\DTOs\Question\UpdateQuestionDTO;
use App\Services\Question\QuestionServiceInterface;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class QuestionController extends Controller
{
    public function __construct(private QuestionServiceInterface $questionService)
    {
    }

    public function index(Request $request)
    {
        $questions = $this->questionService->getPaginated($request->search, $request->level_id);
        $levels = Level::with('world')->get();

        return view('admin.questions.index', compact('questions', 'levels'));
    }

    public function create()
    {
        $levels = Level::with('world')->get();

        return view('admin.questions.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required|in:1,2,3,4,5,6',
            'sequence' => 'required|integer|min:1',
            'content' => 'required',
            'type' => 'required|in:multiple_choice,essay,counting,matching',
            'image' => 'nullable|image|max:2048',
            'options.*.content' => 'required_if:type,multiple_choice,matching',
            'options.*.label' => 'required_if:type,matching',
            'correct_option' => 'required_if:type,multiple_choice',
        ]);

        $level = \App\Models\Level::where('sequence', $request->sequence)
            ->whereHas('world', function($q) use($request){
                $q->where('class', $request->grade);
            })->first();

        if (!$level) {
            return back()->withErrors(['sequence' => 'Tingkat/Level tidak ditemukan untuk Kelas ini.'])->withInput();
        }

        $request->merge(['level_id' => $level->id]);

        $dto = CreateQuestionDTO::fromRequest($request);
        $this->questionService->create($dto);

        Alert::success('Berhasil', 'Soal berhasil ditambahkan!');

        return redirect()->route('admin.questions.index');
    }

    public function edit(Question $question)
    {
        $question->load('options', 'level.world');
        
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'grade' => 'required|in:1,2,3,4,5,6',
            'sequence' => 'required|integer|min:1',
            'content' => 'required',
            'type' => 'required|in:multiple_choice,essay,counting,matching',
            'image' => 'nullable|image|max:2048',
            'options.*.content' => 'required_if:type,multiple_choice,matching',
            'options.*.label' => 'required_if:type,matching',
            'correct_option' => 'required_if:type,multiple_choice',
        ]);

        $level = \App\Models\Level::where('sequence', $request->sequence)
            ->whereHas('world', function($q) use($request){
                $q->where('class', $request->grade);
            })->first();

        if (!$level) {
            return back()->withErrors(['sequence' => 'Tingkat/Level tidak ditemukan untuk Kelas ini.'])->withInput();
        }

        $request->merge(['level_id' => $level->id]);

        $dto = UpdateQuestionDTO::fromRequest($request);
        $this->questionService->update($question, $dto);

        Alert::success('Berhasil', 'Soal berhasil diperbarui!');

        return redirect()->route('admin.questions.index');
    }

    public function destroy(Question $question)
    {
        $this->questionService->delete($question);

        Alert::success('Berhasil', 'Soal berhasil dihapus!');

        return redirect()->route('admin.questions.index');
    }
}
