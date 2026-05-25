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
        'description',
        'category_id',
        'price',
        'compare_price',
        'quantity',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected $appends = ['is_available', 'image_url', 'discount_percent'];

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'available' && $this->quantity > 0;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/books/' . $this->image);
        }
        return 'https://placehold.co/300x400/f4f6f8/c92127?text=' . urlencode($this->title ?? 'Book');
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
