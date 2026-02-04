<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all levels
        $levels = \App\Models\Level::all();

        foreach ($levels as $level) {
            $worldSlug = $level->world->slug;

            // Generate 5 questions per level
            for ($i = 1; $i <= 5; $i++) {
                $content = '';
                $correctAnswer = 0;
                $options = [];

                if ($worldSlug == 'dunia-angka') {
                    // Class 3: Penjumlahan & Pengurangan
                    $a = rand(10, 50) + ($level->sequence * 5);
                    $b = rand(5, 20);
                    if (rand(0, 1)) {
                        $content = "Berapakah hasil dari $a + $b?";
                        $correctAnswer = $a + $b;
                    } else {
                        $content = "Berapakah hasil dari $a - $b?";
                        $correctAnswer = $a - $b;
                    }
                } elseif ($worldSlug == 'dunia-pecahan') {
                    // Class 4: Pecahan Sederhana (Visual representation logic simplified to text for now)
                    // e.g. 1/2 + 1/2 = 1
                    $denom = rand(2, 4) * 2; // 2, 4, 6, 8
                    $num1 = 1;
                    $num2 = 1;
                    $content = "Berapakah hasil dari $num1/$denom + $num2/$denom?";
                    // Simplified: just asking for numerator sum if denominator is same
                    $correctAnswer = ($num1 + $num2).'/'.$denom;

                    // Override options generation for string answers
                    $wrong1 = ($num1 + $num2 + 1).'/'.$denom;
                    $wrong2 = ($num1 + $num2 - 1).'/'.$denom;
                    $wrong3 = ($num1 + $num2 + 2).'/'.$denom;

                    $options = [
                        ['content' => $correctAnswer, 'is_correct' => true],
                        ['content' => $wrong1, 'is_correct' => false],
                        ['content' => $wrong2, 'is_correct' => false],
                        ['content' => $wrong3, 'is_correct' => false],
                    ];
                } elseif ($worldSlug == 'dunia-tantangan') {
                    // Class 5: Perkalian & Pembagian
                    if (rand(0, 1)) {
                        $a = rand(5, 12) + $level->sequence;
                        $b = rand(3, 9);
                        $content = "Berapakah hasil dari $a x $b?";
                        $correctAnswer = $a * $b;
                    } else {
                        $b = rand(2, 9);
                        $result = rand(5, 12) + $level->sequence;
                        $a = $b * $result; // Ensure clean division
                        $content = "Berapakah hasil dari $a : $b?";
                        $correctAnswer = $result;
                    }
                }

                $question = \App\Models\Question::create([
                    'level_id' => $level->id,
                    'content' => $content,
                    'type' => 'multiple_choice',
                ]);

                // Generate numeric options if not set (for dunia-angka and dunia-tantangan)
                if (empty($options)) {
                    $wrong1 = $correctAnswer + rand(1, 5);
                    $wrong2 = $correctAnswer - rand(1, 5);
                    $wrong3 = $correctAnswer + rand(6, 10);
                    // Ensure unique options
                    while ($wrong1 == $correctAnswer) {
                        $wrong1++;
                    }
                    while ($wrong2 == $correctAnswer || $wrong2 == $wrong1) {
                        $wrong2--;
                    }
                    while ($wrong3 == $correctAnswer || $wrong3 == $wrong1 || $wrong3 == $wrong2) {
                        $wrong3++;
                    }

                    $options = [
                        ['content' => $correctAnswer, 'is_correct' => true],
                        ['content' => $wrong1, 'is_correct' => false],
                        ['content' => $wrong2, 'is_correct' => false],
                        ['content' => $wrong3, 'is_correct' => false],
                    ];
                }

                shuffle($options);

                foreach ($options as $option) {
                    \App\Models\QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $option['content'],
                        'is_correct' => $option['is_correct'],
                    ]);
                }
            }
        }
    }
}
