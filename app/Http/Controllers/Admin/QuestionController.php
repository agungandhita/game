<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with('level.world');

        if ($request->has('search')) {
            $query->where('content', 'like', "%{$request->search}%");
        }

        if ($request->has('level_id') && $request->level_id != '') {
            $query->where('level_id', $request->level_id);
        }

        $questions = $query->latest()->paginate(10);
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
            'level_id' => 'required|exists:levels,id',
            'content' => 'required',
            'type' => 'required|in:multiple_choice,essay,counting,matching',
            'image' => 'nullable|image|max:2048',
            'options.*.content' => 'required_if:type,multiple_choice',
            'correct_option' => 'required_if:type,multiple_choice',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('questions', 'public');
        }

        $question = Question::create([
            'level_id' => $request->level_id,
            'content' => $request->content,
            'type' => $request->type,
            'image_path' => $imagePath,
        ]);

        if ($request->type == 'multiple_choice') {
            foreach ($request->options as $key => $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'content' => $optionData['content'],
                    'is_correct' => $key == $request->correct_option,
                ]);
            }
        }

        Alert::success('Berhasil', 'Soal berhasil ditambahkan!');

        return redirect()->route('admin.questions.index');
    }

    public function edit(Question $question)
    {
        $levels = Level::with('world')->get();
        $question->load('options');

        return view('admin.questions.edit', compact('question', 'levels'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
            'content' => 'required',
            'type' => 'required|in:multiple_choice,essay,counting,matching',
            'image' => 'nullable|image|max:2048',
            'options.*.content' => 'required_if:type,multiple_choice',
            'correct_option' => 'required_if:type,multiple_choice',
        ]);

        if ($request->hasFile('image')) {
            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }
            $question->image_path = $request->file('image')->store('questions', 'public');
        }

        $question->update([
            'level_id' => $request->level_id,
            'content' => $request->content,
            'type' => $request->type,
        ]);

        if ($request->type == 'multiple_choice' && $request->has('options')) {
            $question->options()->delete();
            foreach ($request->options as $key => $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'content' => $optionData['content'],
                    'is_correct' => $key == $request->correct_option,
                ]);
            }
        } else {
            $question->options()->delete();
        }

        Alert::success('Berhasil', 'Soal berhasil diperbarui!');

        return redirect()->route('admin.questions.index');
    }

    public function destroy(Question $question)
    {
        if ($question->image_path) {
            Storage::disk('public')->delete($question->image_path);
        }
        $question->delete();

        Alert::success('Berhasil', 'Soal berhasil dihapus!');

        return redirect()->route('admin.questions.index');
    }
}
