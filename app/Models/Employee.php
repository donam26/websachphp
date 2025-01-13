<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_code',
        'full_name',
        'email',
        'phone',
        'position',
        'department',
        'date_of_birth',
        'hire_date',
        'salary',
        'address',
        'identity_number',
        'identity_date',
        'identity_place',
        'tax_code',
        'bank_account',
        'bank_name',
        'bank_branch',
        'education_level',
        'education_major',
        'education_place',
        'marital_status',
        'gender',
        'nationality',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'note',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'identity_date' => 'date',
        'salary' => 'decimal:2'
    ];

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success">Đang làm việc</span>',
            'inactive' => '<span class="badge bg-danger">Đã nghỉ việc</span>',
            'on_leave' => '<span class="badge bg-warning">Tạm nghỉ</span>',
            default => '<span class="badge bg-secondary">Không xác định</span>'
        };
    }

    public function getGenderTextAttribute()
    {
        return match($this->gender) {
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác',
            default => 'Không xác định'
        };
    }

    public function getMaritalStatusTextAttribute()
    {
        return match($this->marital_status) {
            'single' => 'Độc thân',
            'married' => 'Đã kết hôn',
            'divorced' => 'Đã ly hôn',
            'widowed' => 'Góa phụ/phu',
            default => 'Không xác định'
        };
    }
} 