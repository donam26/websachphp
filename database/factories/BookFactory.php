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

        return [
            'title' => ucfirst($this->faker->words(rand(2, 5), true)),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->unique()->isbn13(),
            'publish_year' => $this->faker->numberBetween(1990, 2024),
            'description' => $this->faker->paragraphs(4, true),
            'price' => $price,
            'quantity' => $this->faker->numberBetween(0, 100),
            'image' => null,
            'status' => 'available',
        ];
    }
}
