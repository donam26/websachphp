<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run()
    {
        $categories = ['Văn học', 'Kinh tế', 'Kỹ năng sống', 'Thiếu nhi', 'Giáo khoa'];

        foreach ($categories as $name) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );

            Book::factory()->count(10)->create([
                'category_id' => $category->id,
            ]);
        }
    }
}
