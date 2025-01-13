<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    protected function getValidationRules($employeeId = null)
    {
        return [
            'employee_code' => 'required|unique:employees,employee_code,' . $employeeId,
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employeeId,
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'address' => 'nullable|string',
            'identity_number' => 'nullable|string|unique:employees,identity_number,' . $employeeId,
            'identity_date' => 'nullable|date',
            'identity_place' => 'nullable|string|max:255',
            'tax_code' => 'nullable|string|unique:employees,tax_code,' . $employeeId,
            'bank_account' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'education_major' => 'nullable|string|max:255',
            'education_place' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'gender' => 'nullable|in:male,female,other',
            'nationality' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'status' => 'required|in:active,inactive,on_leave'
        ];
    }

    public function index(Request $request)
    {
        $query = Employee::query();

        // Filter by employee code
        if ($request->filled('employee_code')) {
            $query->where('employee_code', 'like', '%' . $request->employee_code . '%');
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by hire date range
        if ($request->filled('hire_date_from')) {
            $query->whereDate('hire_date', '>=', $request->hire_date_from);
        }
        if ($request->filled('hire_date_to')) {
            $query->whereDate('hire_date', '<=', $request->hire_date_to);
        }

        // Get unique departments and positions for filter dropdowns
        $departments = Employee::distinct()->pluck('department');
        $positions = Employee::distinct()->pluck('position');

        $employees = $query->latest()->paginate(10)->withQueryString();

        return view('admin.employees.index', compact('employees', 'departments', 'positions'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            Employee::create($request->all());
            
            DB::commit();
            return redirect()->route('admin.employees.index')
                ->with('success', 'Thêm nhân viên thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Employee $employee)
    {
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules($employee->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $employee->update($request->all());
            
            DB::commit();
            return redirect()->route('admin.employees.index')
                ->with('success', 'Cập nhật thông tin nhân viên thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            DB::beginTransaction();
            
            $employee->delete();
            
            DB::commit();
            return redirect()->route('admin.employees.index')
                ->with('success', 'Xóa nhân viên thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
} 