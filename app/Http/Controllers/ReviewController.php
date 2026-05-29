<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
        ]);

        $userId = auth()->id();

        // Mark as a verified purchase if the customer has an order containing this book.
        $purchased = $book->orderItems()
            ->whereHas('order', fn ($q) => $q->where('user_id', $userId))
            ->exists();

        Review::updateOrCreate(
            ['user_id' => $userId, 'book_id' => $book->id],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'is_verified_purchase' => $purchased,
            ]
        );

        return redirect()->route('books.show', $book)
            ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function destroy(Review $review)
    {
        abort_if($review->user_id !== auth()->id(), 403);

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Đã xoá đánh giá của bạn');
    }
}
