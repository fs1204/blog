<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            "is_open" => $this->faker->randomElement([true, true, true, true, false]),
            'title' => $this->faker->realText(15),
            'body' => $this->faker->realText(500),
            'updated_at' => $this->faker->dateTimeBetween('-10days', '0days'),
        ];
    }

    public function seeding()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_open' => $this->faker->biasedNumberBetween(0, 1, ['\Faker\Provider\Biased', 'linearHigh']),
            ];
        });
    }

    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_open' => Blog::CLOSED,
            ];
        });
    }

    public function validData($overrides = [])
    {
        return array_merge([
            'title' => 'ブログのタイトル',
            'body' => 'ブログの本文',
            'is_open' => '1',
        ], $overrides);
    }
}
