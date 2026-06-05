<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockImportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_import_id',
        'book_id',
        'book_title',
        'quantity',
        'import_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'import_price' => 'float',
    ];

    public function stockImport()
    {
        return $this->belongsTo(StockImport::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->import_price * $this->quantity;
    }
}
