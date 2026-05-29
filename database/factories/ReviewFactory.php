<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'rating' => $this->faker->numberBetween(3, 5),
            'comment' => $this->faker->boolean(80) ? $this->faker->sentence(rand(6, 18)) : null,
            'is_verified_purchase' => $this->faker->boolean(70),
        ];
    }
}
