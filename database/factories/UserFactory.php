<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rawNickname = strtolower($this->faker->unique()->userName());
        $nickname = preg_replace('/[^a-z0-9_-]/', '_', $rawNickname) ?? 'user';
        $nickname = substr($nickname, 0, 20);

        return [
            'nickname' => $nickname,
            'grade' => $this->faker->randomElement([3, 4, 5]),
            'role' => 'student',
            'total_points' => 0,
            'avatar_id' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
