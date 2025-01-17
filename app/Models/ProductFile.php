<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFile extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'path',
        'type',
        'size',
        'description'
    ];

    protected $casts = [
        'size' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'code');
    }
} 