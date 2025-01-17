<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_code',
        'path',
        'order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'code');
    }
} 