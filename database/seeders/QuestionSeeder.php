<?php

namespace Database\Seeders;

use App\Models\Quiz\Level;
use App\Models\Quiz\Question;
use App\Models\Quiz\Option;
use App\Enums\OptionLabel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $levels = Level::with('grade')->get();

        foreach ($levels as $level) {
            $gradeOrder = $level->grade->order;
            
            for ($i = 1; $i <= 5; $i++) {
                $questionData = $this->generateQuestionData($gradeOrder);
                
                $question = Question::create([
                    'id' => Str::uuid(),
                    'level_id' => $level->id,
                    'question_text' => $questionData['text'],
                    'order' => $i,
                ]);

                $labels = [OptionLabel::A, OptionLabel::B, OptionLabel::C, OptionLabel::D];
                $correctIndex = rand(0, 3);
                
                foreach ($labels as $index => $label) {
                    Option::create([
                        'id' => Str::uuid(),
                        'question_id' => $question->id,
                        'option_text' => $index === $correctIndex ? $questionData['correct'] : $questionData['wrong'][$index] ?? rand(10, 100),
                        'is_correct' => $index === $correctIndex,
                        'label' => $label,
                    ]);
                }
            }
        }
    }

    private function generateQuestionData(int $gradeOrder): array
    {
        if ($gradeOrder <= 2) {
            $a = rand(1, 10);
            $b = rand(1, 10);
            return [
                'text' => "$a + $b = ...",
                'correct' => (string)($a + $b),
                'wrong' => [(string)($a + $b + 1), (string)($a + $b - 1), (string)($a + $b + 2), (string)($a + $b - 2)]
            ];
        }

        if ($gradeOrder <= 4) {
            $a = rand(2, 10);
            $b = rand(2, 10);
            return [
                'text' => "Berapa hasil perkalian $a x $b?",
                'correct' => (string)($a * $b),
                'wrong' => [(string)($a * $b + 10), (string)($a * $b - 10), (string)($a * ($b + 1)), (string)(($a + 1) * $b)]
            ];
        }

        $a = rand(1, 4);
        $b = rand(5, 10);
        return [
            'text' => "Pecahan $a/$b jika dijadikan desimal adalah...",
            'correct' => (string)round($a / $b, 2),
            'wrong' => [(string)round(($a+1)/$b, 2), (string)round($a/($b+1), 2), (string)round(($a+2)/$b, 2), "0." . rand(11, 99)]
        ];
    }
}
