<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'content',
        'name',
        'type',
        'formality',
        'province_id',
        'province_name',
        'district_id',
        'district_name',
        'ward_id',
        'ward_name',
        'house_number',
        'price',
        'currency',
        'acreage',
        'width',
        'length',
        'floor_number',
        'room_number_total',
        'direction',
        'balcony_direction',
        'host_name',
        'host_phone1',
        'host_phone2',
        'status',
        'is_hot',
        'show_in_web',
        'texture',
        'street_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'acreage' => 'decimal:2',
        'width' => 'decimal:2',
        'length' => 'decimal:2',
        'floor_number' => 'integer',
        'room_number_total' => 'integer',
        'is_hot' => 'boolean',
        'show_in_web' => 'boolean'
    ];

    public $incrementing = true;
    protected $primaryKey = 'code';

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_code', 'code');
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class, 'product_code', 'code');
    }
}
