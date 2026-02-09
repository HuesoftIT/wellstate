<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
    /**
     * Danh sách nhân viên
     */
    public function index(Request $request)
    {
        $query = Employee::with('branch');

        // Tìm theo tên / mã / phone
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo chi nhánh
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Lọc trạng thái
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $employees = $query
            ->orderByDesc('id')
            ->paginate(config('settings.perpage', 10));

        $branches = Branch::active()->pluck('name', 'id');

        return view('admin.employees.index', compact('employees', 'branches'));
    }

    /**
     * Form tạo nhân viên
     */
    public function create()
    {
        $branches = Branch::active()->get();

        return view('admin.employees.create', compact('branches'));
    }

    /**
     * Lưu nhân viên
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        unset($data['avatar']);

        Employee::create($data);

        Alert::success(__('Tạo nhân viên thành công'));

        return redirect()->route('employees.index');
    }


    /**
     * Chi tiết nhân viên
     */
    public function show($id, Request $request)
    {
        $employee = Employee::with(['branch', 'services', 'workingTimes'])
            ->findOrFail($id);

        $backUrl = $request->get('back_url');

        return view('admin.employees.show', compact('employee', 'backUrl'));
    }

    /**
     * Form chỉnh sửa nhân viên
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $branches = Branch::active()->get();

        return view('admin.employees.edit', compact('employee', 'branches'));
    }

    /**
     * Cập nhật nhân viên
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->validated());

        Alert::success(__('Cập nhật nhân viên thành công'));

        return redirect()->route('employees.index');
    }

    /**
     * Xóa nhân viên (soft delete)
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // (Nâng cao – optional)
        // if ($employee->bookingGuestServices()->exists()) {
        //     Alert::error(__('Nhân viên đang có lịch booking'));
        //     return back();
        // }

        $employee->delete();

        Alert::success(__('Đã xóa nhân viên'));

        return redirect()->route('employees.index');
    }
}
