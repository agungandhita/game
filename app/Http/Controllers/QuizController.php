<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\SubmitAnswerRequest;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Level;
use App\Models\Quiz\LevelResult;
use App\Models\Quiz\QuizSession;
use App\Services\Quiz\QuizServiceInterface;
use App\Enums\SessionStatus;

class QuizController extends Controller
{
    public function __construct(
        private QuizServiceInterface $quizService
    ) {}

    // ── Halaman pilih kelas ───────────────────────────────────────────────
    public function index()
    {
        $grades   = Grade::with('levels')->orderBy('order')->get();
        $progress = collect($this->quizService->getUserProgress(auth()->id()))
            ->keyBy(fn($p) => $p->levelId);

        return view('quiz.index', compact('grades', 'progress'));
    }

    // ── Halaman pilih level ───────────────────────────────────────────────
    public function levels(Grade $grade)
    {
        $levels   = $grade->levels()->orderBy('order')->get();
        $progress = collect($this->quizService->getUserProgress(auth()->id()))
            ->keyBy(fn($p) => $p->levelId);

        return view('quiz.levels', compact('grade', 'levels', 'progress'));
    }

    // ── Mulai / lanjutkan sesi ────────────────────────────────────────────
    public function start(Level $level)
    {
        $progress = collect($this->quizService->getUserProgress(auth()->id()))
            ->keyBy(fn($p) => $p->levelId);

        $levelProgress = $progress->get($level->id);

        if (!$levelProgress || $levelProgress->status === 'locked') {
            return back()->with('error', 'Level ini masih terkunci! Selesaikan level sebelumnya dulu. 🔒');
        }

        // Cek apakah ada sesi in_progress yang belum selesai
        $existingSession = QuizSession::where('user_id', auth()->id())
            ->where('level_id', $level->id)
            ->where('status', SessionStatus::InProgress)
            ->first();

        if ($existingSession) {
            return redirect()->route('quiz.play', $existingSession->id);
        }

        $sessionDto = $this->quizService->startSession(auth()->id(), $level->id);

        return redirect()->route('quiz.play', $sessionDto->sessionId);
    }

    // ── Halaman soal ──────────────────────────────────────────────────────
    public function play(QuizSession $session)
    {
        if ($session->user_id !== auth()->id()) abort(403);

        // Jika sesi sudah selesai, redirect ke result
        if ($session->status === SessionStatus::Completed) {
            $result = LevelResult::where('user_id', auth()->id())
                ->where('level_id', $session->level_id)
                ->latest('completed_at')
                ->first();
            return $result
                ? redirect()->route('quiz.result', $result->id)
                : redirect()->route('quiz.index');
        }

        $currentQuestion = $this->quizService->getCurrentQuestion($session);

        // Semua soal sudah dijawab → selesai
        if (!$currentQuestion) {
            $resultDto = $this->quizService->finishSession($session->id);
            $result    = LevelResult::where('user_id', auth()->id())
                ->where('level_id', $session->level_id)
                ->latest('completed_at')
                ->first();
            return redirect()->route('quiz.result', $result->id);
        }

        $totalQuestions  = $session->level->questions()->count();
        $answeredCount   = $session->answers()->count();
        $currentIndex    = $answeredCount + 1;
        $timePerQuestion = $session->level->time_per_question ?? 30;

        return view('quiz.play', compact(
            'session', 'currentQuestion', 'totalQuestions', 'currentIndex', 'timePerQuestion'
        ));
    }

    // ── Submit jawaban ────────────────────────────────────────────────────
    public function answer(SubmitAnswerRequest $request, QuizSession $session)
    {
        if ($session->user_id !== auth()->id()) abort(403);

        $this->quizService->submitAnswer(
            $session->id,
            $request->input('question_id'),
            $request->input('selected_option_id'),
            $request->boolean('is_timeout', false),
            $request->input('time_spent') ? (int) $request->input('time_spent') : null,
        );

        // Cek soal berikutnya
        $session->refresh();
        $nextQuestion = $this->quizService->getCurrentQuestion($session);

        if ($nextQuestion) {
            return redirect()->route('quiz.play', $session->id);
        }

        // Semua soal selesai
        $this->quizService->finishSession($session->id);

        $result = LevelResult::where('user_id', auth()->id())
            ->where('level_id', $session->level_id)
            ->latest('completed_at')
            ->first();

        return redirect()->route('quiz.result', $result->id);
    }

    // ── Paksa selesai sesi (POST) ─────────────────────────────────────────
    public function finish(QuizSession $session)
    {
        if ($session->user_id !== auth()->id()) abort(403);

        $this->quizService->finishSession($session->id);

        $result = LevelResult::where('user_id', auth()->id())
            ->where('level_id', $session->level_id)
            ->latest('completed_at')
            ->first();

        return redirect()->route('quiz.result', $result->id);
    }

    // ── Halaman hasil ─────────────────────────────────────────────────────
    public function result(LevelResult $result)
    {
        if ($result->user_id !== auth()->id()) abort(403);

        $session = QuizSession::with(['answers.question.options', 'answers.selectedOption'])
            ->where('user_id', $result->user_id)
            ->where('level_id', $result->level_id)
            ->whereNotNull('finished_at')
            ->latest('finished_at')
            ->first();

        // Level berikutnya
        $nextLevel = $result->level->grade->levels()
            ->where('order', '>', $result->level->order)
            ->orderBy('order')
            ->first();

        return view('quiz.result', compact('result', 'session', 'nextLevel'));
    }
}
