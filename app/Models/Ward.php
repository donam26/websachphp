<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['name', 'code', 'district_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
} 