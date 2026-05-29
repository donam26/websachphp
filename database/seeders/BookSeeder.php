<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run()
    {
        $categories = ['Văn học', 'Kinh tế', 'Kỹ năng sống', 'Thiếu nhi', 'Giáo khoa'];

        $authors = Author::factory()->count(20)->create();

        foreach ($categories as $name) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );

            Book::factory()->count(10)->create([
                'category_id' => $category->id,
            ])->each(function (Book $book) use ($authors) {
                $picked = $authors->random(rand(1, 2));
                $book->authors()->sync($picked->pluck('id')->all());
                // Keep the legacy denormalised mirror in sync.
                $book->forceFill(['author' => $picked->pluck('name')->implode(', ')])->save();
            });
        }
    }
}
