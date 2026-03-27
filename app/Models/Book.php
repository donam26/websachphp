<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'brand',
        'description',
        'category_id',
        'price',
        'quantity',
        'image',
        'status',
        'sizes',
        'colors',
        'material',
        'gender'
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'quantity' => 'integer'
    ];

    protected $appends = ['is_available', 'sizes_array', 'colors_array'];

    public function getIsAvailableAttribute()
    {
        return $this->quantity > 0;
    }

    public function getSizesArrayAttribute()
    {
        return $this->sizes ? explode(',', $this->sizes) : [];
    }

    public function getColorsArrayAttribute()
    {
        return $this->colors ? explode(',', $this->colors) : [];
    }

    public function getBrandNameAttribute()
    {
        return $this->brand ?: $this->author;
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

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }
}
