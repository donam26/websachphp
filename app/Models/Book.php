<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publish_year',
        'description',
        'category_id',
        'price',
        'quantity',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'publish_year' => 'integer',
    ];

    protected $appends = ['is_available', 'image_url'];

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'available' && $this->quantity > 0;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/books/' . $this->image);
        }
        return 'https://placehold.co/300x400/eef2ff/4f46e5?text=' . urlencode($this->title ?? 'Book');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('quantity', '>', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    /**
     * Canonical author display string per ERD (Book n—n Author).
     * Reads the authors relationship, falling back to the legacy
     * books.author column for rows not yet backfilled.
     */
    public function getAuthorNamesAttribute(): string
    {
        $names = $this->authors->pluck('name')->filter()->implode(', ');

        return $names !== '' ? $names : (string) ($this->attributes['author'] ?? '');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Average star rating (0..5, one decimal). Uses the withAvg-aggregated
     * column when present, then a loaded relation, else a lightweight query.
     */
    public function getAverageRatingAttribute(): float
    {
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            return round((float) $this->attributes['reviews_avg_rating'], 1);
        }
        if ($this->relationLoaded('reviews')) {
            return round((float) $this->reviews->avg('rating'), 1);
        }
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function stockImportItems()
    {
        return $this->hasMany(StockImportItem::class);
    }
}
