<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Backfill the new Author entity from the legacy books.author string column.
 *
 * Additive migration: books.author is preserved as a denormalised mirror so the
 * running app keeps working; the book_author relationship becomes the canonical
 * source per the ERD (Book n—n Author). Safe to run on an empty table (fresh
 * install seeds authors directly) — it simply finds no rows to backfill.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('books')->orderBy('id')->select('id', 'author')
            ->chunkById(100, function ($books) {
                foreach ($books as $book) {
                    $name = trim((string) $book->author);
                    if ($name === '') {
                        continue;
                    }

                    $authorId = DB::table('authors')->where('name', $name)->value('id')
                        ?? DB::table('authors')->insertGetId([
                            'name' => $name,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    $linked = DB::table('book_author')
                        ->where('book_id', $book->id)
                        ->where('author_id', $authorId)
                        ->exists();

                    if (!$linked) {
                        DB::table('book_author')->insert([
                            'book_id' => $book->id,
                            'author_id' => $authorId,
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        // No-op: backfilled pivot rows cannot be reliably distinguished from
        // ones created later through the UI, so they are left intact. A full
        // rollback drops the book_author table via its own migration's down().
    }
};
