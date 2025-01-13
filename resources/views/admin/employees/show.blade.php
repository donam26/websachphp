@extends('layouts.admin')

@section('title', 'Chi tiết nhân viên')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết nhân viên</h1>
        <div>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i>Chỉnh sửa
            </a>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin cơ bản</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px"><strong>Mã nhân viên:</strong></td>
                            <td>{{ $employee->employee_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>Họ và tên:</strong></td>
                            <td>{{ $employee->full_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $employee->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số điện thoại:</strong></td>
                            <td>{{ $employee->phone ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ngày sinh:</strong></td>
                            <td>{{ $employee->date_of_birth ? $employee->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Giới tính:</strong></td>
                            <td>{{ $employee->gender_text }}</td>
                        </tr>
                        <tr>
                            <td><strong>Quốc tịch:</strong></td>
                            <td>{{ $employee->nationality ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tình trạng hôn nhân:</strong></td>
                            <td>{{ $employee->marital_status_text }}</td>
                        </tr>
                        <tr>
                            <td><strong>Địa chỉ:</strong></td>
                            <td>{{ $employee->address ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin công việc -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin công việc</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px"><strong>Vị trí:</strong></td>
                            <td>{{ $employee->position }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phòng ban:</strong></td>
                            <td>{{ $employee->department }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ngày vào làm:</strong></td>
                            <td>{{ $employee->hire_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Lương:</strong></td>
                            <td>{{ number_format($employee->salary, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>{!! $employee->status_badge !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin giấy tờ -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin giấy tờ</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px"><strong>CMND/CCCD:</strong></td>
                            <td>{{ $employee->identity_number ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ngày cấp:</strong></td>
                            <td>{{ $employee->identity_date ? $employee->identity_date->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nơi cấp:</strong></td>
                            <td>{{ $employee->identity_place ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Mã số thuế:</strong></td>
                            <td>{{ $employee->tax_code ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin ngân hàng -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin ngân hàng</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px"><strong>Số tài khoản:</strong></td>
                            <td>{{ $employee->bank_account ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tên ngân hàng:</strong></td>
                            <td>{{ $employee->bank_name ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Chi nhánh:</strong></td>
                            <td>{{ $employee->bank_branch ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin học vấn -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin học vấn</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px"><strong>Trình độ:</strong></td>
                            <td>{{ $employee->education_level ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Chuyên ngành:</strong></td>
                            <td>{{ $employee->education_major ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nơi đào tạo:</strong></td>
                            <td>{{ $employee->education_place ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin liên hệ khẩn cấp -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin liên hệ khẩn cấp</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px"><strong>Họ tên:</strong></td>
                            <td>{{ $employee->emergency_contact_name ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số điện thoại:</strong></td>
                            <td>{{ $employee->emergency_contact_phone ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Mối quan hệ:</strong></td>
                            <td>{{ $employee->emergency_contact_relationship ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @if($employee->note)
        <!-- Ghi chú -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Ghi chú</h5>
                    <p class="mb-0">{{ $employee->note }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 