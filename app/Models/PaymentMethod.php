<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * Các phương thức "hệ thống" có hành vi gắn với code (xử lý trong code).
     * Không cho phép đổi code hoặc xoá để tránh hỏng luồng thanh toán.
     */
    public const SYSTEM_CODES = [
        Order::PAYMENT_COD,
        Order::PAYMENT_VNPAY,
    ];

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Sắp xếp theo thứ tự hiển thị rồi tới id. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Phương thức hệ thống (cod/vnpay) — code khoá, không xoá được. */
    public function isSystem(): bool
    {
        return in_array($this->code, self::SYSTEM_CODES, true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
