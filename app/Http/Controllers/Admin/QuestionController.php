<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuestionRequest;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Level;
use App\Models\Quiz\Option;
use App\Models\Quiz\Question;
use App\Enums\OptionLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $grades = Grade::orderBy('order')->get();
        $levels = collect();

        $query = Question::with(['level.grade', 'options'])
            ->join('levels', 'questions.level_id', '=', 'levels.id')
            ->join('grades', 'levels.grade_id', '=', 'grades.id')
            ->select('questions.*')
            ->orderBy('grades.order')
            ->orderBy('levels.order')
            ->orderBy('questions.order');

        if ($request->filled('grade_id')) {
            $query->where('grades.id', $request->grade_id);
            $levels = Level::where('grade_id', $request->grade_id)->orderBy('order')->get();
        }

        if ($request->filled('level_id')) {
            $query->where('questions.level_id', $request->level_id);
        }

        if ($request->filled('search')) {
            $query->where('questions.question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->paginate(15)->withQueryString();

        return view('admin.questions.index', compact('questions', 'grades', 'levels'));
    }

    public function create()
    {
        $grades = Grade::orderBy('order')->get();
        return view('admin.questions.create', compact('grades'));
    }

    public function store(StoreQuestionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $lastOrder = Question::where('level_id', $request->level_id)->max('order') ?? 0;

            $question = Question::create([
                'level_id'      => $request->level_id,
                'question_text' => $request->question_text,
                'order'         => $lastOrder + 1,
            ]);

            $labels = [OptionLabel::A, OptionLabel::B, OptionLabel::C, OptionLabel::D];
            foreach ($labels as $index => $label) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $request->options[$index],
                    'label'       => $label,
                    'is_correct'  => (int) $request->correct_option === $index,
                ]);
            }
        });

        return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil ditambahkan!');
    }

    public function edit(Question $question)
    {
        $question->load(['level.grade', 'options' => fn($q) => $q->orderBy('label')]);
        $grades = Grade::orderBy('order')->get();
        return view('admin.questions.edit', compact('question', 'grades'));
    }

    public function update(StoreQuestionRequest $request, Question $question)
    {
        DB::transaction(function () use ($request, $question) {
            $question->update([
                'level_id'      => $request->level_id,
                'question_text' => $request->question_text,
            ]);

            $labels = [OptionLabel::A, OptionLabel::B, OptionLabel::C, OptionLabel::D];
            $options = $question->options()->orderBy('label')->get();

            foreach ($options as $index => $option) {
                $option->update([
                    'option_text' => $request->options[$index],
                    'label'       => $labels[$index],
                    'is_correct'  => (int) $request->correct_option === $index,
                ]);
            }
        });

        return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy(Question $question)
    {
        $question->options()->delete();
        $question->delete();

        return redirect()->route('admin.questions.index')->with('success', 'Soal berhasil dihapus!');
    }
}
