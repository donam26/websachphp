<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_SHIPPING = 'shipping';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_COD = 'cod';
    public const PAYMENT_VNPAY = 'vnpay';

    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_FAILED = 'failed';
    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    public const FREESHIP_THRESHOLD = 250000;
    public const SHIPPING_FEE = 30000;

    protected $fillable = [
        'code',
        'user_id',
        'subtotal',
        'shipping_fee',
        'discount_amount',
        'total_amount',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'note',
        'status',
        'payment_method',
        'payment_status',
        'payment_ref',
        'paid_at',
        'cancelled_at',
        'discount_id',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'shipping_fee' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (Order $order) {
            if (!$order->code) {
                $order->code = 'BS' . now()->format('ymd') . Str::upper(Str::random(5));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->items();
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class)->orderBy('created_at');
    }

    public function history()
    {
        return $this->histories();
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ xác nhận',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_SHIPPING => 'Đang giao',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã huỷ',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONFIRMED => 'info',
            self::STATUS_SHIPPING => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
        ][$this->status] ?? 'secondary';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return [
            self::PAYMENT_STATUS_PENDING => 'Chưa thanh toán',
            self::PAYMENT_STATUS_PAID => 'Đã thanh toán',
            self::PAYMENT_STATUS_FAILED => 'Thanh toán thất bại',
            self::PAYMENT_STATUS_REFUNDED => 'Đã hoàn tiền',
        ][$this->payment_status] ?? $this->payment_status;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return [
            self::PAYMENT_COD => 'Thanh toán khi nhận hàng',
            self::PAYMENT_VNPAY => 'VNPAY',
        ][$this->payment_method] ?? $this->payment_method;
    }

    public function canBeCancelledByUser(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED])
            && $this->payment_status !== self::PAYMENT_STATUS_PAID;
    }

    public function canBeUpdatedByAdmin(): bool
    {
        return !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }
}
