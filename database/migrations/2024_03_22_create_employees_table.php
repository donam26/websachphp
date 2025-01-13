<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('position');
            $table->string('department');
            $table->date('date_of_birth')->nullable();
            $table->date('hire_date');
            $table->decimal('salary', 12, 2);
            $table->text('address')->nullable();
            $table->string('identity_number')->unique()->nullable(); // CMND/CCCD
            $table->date('identity_date')->nullable(); // Ngày cấp CMND/CCCD
            $table->string('identity_place')->nullable(); // Nơi cấp CMND/CCCD
            $table->string('tax_code')->unique()->nullable(); // Mã số thuế
            $table->string('bank_account')->nullable(); // Số tài khoản ngân hàng
            $table->string('bank_name')->nullable(); // Tên ngân hàng
            $table->string('bank_branch')->nullable(); // Chi nhánh ngân hàng
            $table->string('education_level')->nullable(); // Trình độ học vấn
            $table->string('education_major')->nullable(); // Chuyên ngành
            $table->string('education_place')->nullable(); // Nơi đào tạo
            $table->string('marital_status')->nullable(); // Tình trạng hôn nhân
            $table->string('gender')->nullable(); // Giới tính
            $table->string('nationality')->nullable(); // Quốc tịch
            $table->string('emergency_contact_name')->nullable(); // Tên người liên hệ khẩn cấp
            $table->string('emergency_contact_phone')->nullable(); // SĐT người liên hệ khẩn cấp
            $table->string('emergency_contact_relationship')->nullable(); // Mối quan hệ với người liên hệ khẩn cấp
            $table->text('note')->nullable(); // Ghi chú
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}; 