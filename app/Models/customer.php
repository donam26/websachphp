<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class customer extends Model
{
    use HasFactory, SoftDeletes;




    protected $fillable = [
        'name',
        'ward_id',
        'user_id',
        'district_id',
        'province_id',
        'street_id',
        'ward_name',
        'house_number',
        'district_name',
        // 'province_name',
        'province_id',
        'formality',
        'type',
        'currency',
        'price',
        'width',
        'length',
        'acreage',
        'is_hot',
        'host_name',
        'host_phone1',
        'host_phone2'

    ];

    protected $casts = [
        'price' => 'decimal:2',
        'transfer_price' => 'decimal:2',
        'input_price' => 'decimal:2',
        'width' => 'decimal:2',
        'length' => 'decimal:2',
        'acreage' => 'decimal:2',
        'floor_area' => 'decimal:2',
        'rental_area' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'car_parking_fee' => 'decimal:2',
        'moto_parking_fee' => 'decimal:2',
        'overtime_fee' => 'decimal:2',
        'electricity_fee' => 'decimal:2',
        'commission' => 'decimal:2',
        'total_price' => 'decimal:2',
        'area_by_book' => 'decimal:2',
        'rating_stars' => 'decimal:1',
        'show_in_web' => 'boolean',
        'has_corner' => 'boolean',
        'elevator' => 'boolean',
        'terrace' => 'boolean',
        'basement' => 'boolean',
        'is_rent_all_apartment' => 'boolean',
        'is_hot' => 'boolean',
        'is_hidden_phone' => 'boolean',
        'expire_contract_date' => 'date'
    ];

    // public function getStatusBadgeAttribute()
    // {
    //     return match($this->status) {
    //         'active' => '<span class="badge bg-success">Đang hoạt động</span>',
    //         'inactive' => '<span class="badge bg-danger">Ngừng hoạt động</span>',
    //         'pending' => '<span class="badge bg-warning">Chờ duyệt</span>',
    //         'sold' => '<span class="badge bg-info">Đã bán</span>',
    //         'rented' => '<span class="badge bg-primary">Đã cho thuê</span>',
    //         default => '<span class="badge bg-secondary">Không xác định</span>'
    //     };
    // }

    public function getFormattedPriceAttribute()
    {
        if (!$this->price) return 'Liên hệ';
        return number_format($this->price, 0, ',', '.') . ' ' . ($this->currency ?? 'VNĐ');
    }

    public function getFormattedAreaAttribute()
    {
        if (!$this->acreage) return 'Chưa cập nhật';
        return number_format($this->acreage, 2, ',', '.') . ' m²';
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->house_number,
            $this->street,
            $this->ward_name,
            $this->district_name,
            $this->province_name
        ]);
        return implode(', ', $parts) ?: 'Chưa cập nhật địa chỉ';
    }
}
