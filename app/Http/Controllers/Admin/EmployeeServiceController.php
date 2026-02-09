<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeServiceRequest;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Branch;
use App\Models\ServiceCategory;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class EmployeeServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['branch', 'services']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('employee_name')) {
            $query->where('name', 'LIKE', '%' . $request->employee_name . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $employees = $query
            ->orderByDesc('id')
            ->paginate(config('settings.perpage', 10))
            ->appends($request->query());

        $branches = Branch::active()->pluck('name', 'id');

        return view(
            'admin.employee_services.index',
            compact('employees', 'branches')
        );
    }



    /**
     * Form gán dịch vụ cho nhân viên
     */
    public function create()
    {
        $branches  = Branch::active()->get();
        $employees = Employee::active()->get();
        $serviceCategories = ServiceCategory::with([
            'services' => function ($q) {
                $q->active();
            }
        ])->active()->get();


        return view(
            'admin.employee_services.create',
            compact('branches', 'employees', 'serviceCategories')
        );
    }

    /**
     * Lưu gán dịch vụ
     */
    public function store(EmployeeServiceRequest $request)
    {
        $employee = Employee::findOrFail($request->employee_id);

        // Sync dịch vụ
        $employee->services()->sync($request->service_ids);

        Alert::success(__('Gán dịch vụ cho nhân viên thành công'));

        return redirect()->route('employee-services.index');
    }

    /**
     * Chi tiết dịch vụ nhân viên phụ trách
     */
    public function show($id)
    {
        $employee = Employee::with([
            'branch',
            'services.serviceCategory'
        ])->findOrFail($id);

        return view('admin.employee_services.show', compact('employee'));
    }


    public function edit($id)
    {
        $employee = Employee::with([
            'branch',
            'services'
        ])->findOrFail($id);

        $branches  = Branch::active()->get();
        $employees = Employee::active()->get();

        $serviceCategories = ServiceCategory::with([
            'services' => function ($q) {
                $q->active();
            }
        ])->active()->get();

        $selectedServiceIds = $employee->services
            ->pluck('id')
            ->toArray();

        return view(
            'admin.employee_services.edit',
            compact(
                'employee',
                'branches',
                'employees',
                'serviceCategories',
                'selectedServiceIds'
            )
        );
    }




    public function update(EmployeeServiceRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $employee->update([
            'is_active' => $request->is_active ?? 1,
        ]);

        $employee->services()->sync($request->service_ids);

        Alert::success(__('Cập nhật dịch vụ phụ trách thành công'));

        return redirect()->route('employee-services.index');
    }

    /**
     * Xóa toàn bộ dịch vụ phụ trách của nhân viên
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->services()->detach();

        Alert::success(__('Đã xóa toàn bộ dịch vụ phụ trách'));

        return redirect()->route('employee-services.index');
    }
}
