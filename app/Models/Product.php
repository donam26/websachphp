<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'title',
        'name',
        'content',
        'ward_id',
        'ward_name',
        'district_id',
        'district_name',
        'province_id',
        'province_name',
        'street',
        'house_number',
        'formality',
        'type',
        'price',
        'currency',
        'acreage',
        'width',
        'length',
        'floor_number',
        'room_number_total',
        'direction',
        'host_name',
        'host_phone1',
        'host_phone2',
        'status',
        'is_hot',
        'show_in_web'
    ];

    protected $casts = [
        'price' => 'float',
        'acreage' => 'float',
        'width' => 'float',
        'length' => 'float',
        'floor_number' => 'integer',
        'room_number_total' => 'integer',
        'is_hot' => 'boolean',
        'show_in_web' => 'boolean'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_code', 'code');
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class, 'product_code', 'code');
    }
}
