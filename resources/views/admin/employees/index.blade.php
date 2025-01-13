@extends('layouts.admin')

@section('title', 'Quản lý nhân viên')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý nhân viên</h1>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm nhân viên mới
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.employees.index') }}" method="GET" id="filter-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="employee_code" class="form-label">Mã nhân viên</label>
                        <input type="text" 
                               class="form-control" 
                               id="employee_code" 
                               name="employee_code"
                               value="{{ request('employee_code') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="name" class="form-label">Tên nhân viên</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name"
                               value="{{ request('name') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email"
                               value="{{ request('email') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="department" class="form-label">Phòng ban</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">Tất cả phòng ban</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="position" class="form-label">Vị trí</label>
                        <select class="form-select" id="position" name="position">
                            <option value="">Tất cả vị trí</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>
                                    {{ $pos }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang làm việc</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã nghỉ việc</option>
                            <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>Tạm nghỉ</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="hire_date_from" class="form-label">Ngày vào làm từ</label>
                        <input type="date" 
                               class="form-control" 
                               id="hire_date_from" 
                               name="hire_date_from"
                               value="{{ request('hire_date_from') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="hire_date_to" class="form-label">Ngày vào làm đến</label>
                        <input type="date" 
                               class="form-control" 
                               id="hire_date_to" 
                               name="hire_date_to"
                               value="{{ request('hire_date_to') }}">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã NV</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vị trí</th>
                            <th>Phòng ban</th>
                            <th>Ngày vào làm</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->hire_date->format('d/m/Y') }}</td>
                            <td>{!! $employee->status_badge !!}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.employees.show', $employee) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.employees.edit', $employee) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.employees.destroy', $employee) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto submit form when select fields change
    document.querySelectorAll('#filter-form select').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });
</script>
@endpush

@endsection 