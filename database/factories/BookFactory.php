<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        $price = $this->faker->numberBetween(50000, 500000);
        $hasDiscount = $this->faker->boolean(40);

        return [
            'title' => ucfirst($this->faker->words(rand(2, 5), true)),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraphs(4, true),
            'price' => $price,
            'compare_price' => $hasDiscount ? round($price * $this->faker->randomFloat(2, 1.1, 1.6), -3) : null,
            'quantity' => $this->faker->numberBetween(0, 100),
            'image' => null,
            'status' => 'available',
        ];
    }
}
