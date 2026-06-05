<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockImport extends Model
{
    use HasFactory;

    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'code',
        'supplier_id',
        'user_id',
        'total_amount',
        'note',
        'status',
        'imported_at',
        'cancelled_at',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'imported_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (StockImport $import) {
            if (!$import->code) {
                $import->code = 'PN' . now()->format('ymd') . Str::upper(Str::random(5));
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(StockImportItem::class);
    }

    public function getTotalQuantityAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return [
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
        ][$this->status] ?? 'secondary';
    }

    public function canBeCancelled(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
