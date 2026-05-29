<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $customers = User::where('role', '!=', User::ROLE_ADMIN)->pluck('id');

        if ($customers->isEmpty()) {
            return;
        }

        Book::inRandomOrder()->take(30)->get()->each(function (Book $book) use ($customers) {
            $reviewers = $customers->shuffle()->take(rand(1, min(3, $customers->count())));

            foreach ($reviewers as $userId) {
                Review::updateOrCreate(
                    ['user_id' => $userId, 'book_id' => $book->id],
                    Review::factory()->make()->only(['rating', 'comment', 'is_verified_purchase'])
                );
            }
        });
    }
}
