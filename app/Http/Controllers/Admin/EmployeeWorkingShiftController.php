<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeWorkingShiftRequest;
use App\Http\Requests\UpdateEmployeeWorkingShiftRequest;
use App\Models\Branch;
use App\Models\EmployeeWorkingShift;
use App\Models\Employee;
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class EmployeeWorkingShiftController extends Controller
{
    /**
     * Danh sách phân ca
     */
    public function index(Request $request)
    {
        $query = EmployeeWorkingShift::query()
            ->with([
                'employee.branch',
                'workingShift.branch',
            ]);


        if ($request->filled('branch_id')) {
            $branchId = $request->branch_id;

            $query->whereHas('employee', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }


        if ($request->filled('employee_name')) {
            $employeeName = $request->employee_name;

            $query->whereHas('employee', function ($q) use ($employeeName) {
                $q->where('name', 'like', '%' . $employeeName . '%');
            });
        }


        if ($request->filled('working_shift_id')) {
            $query->where('working_shift_id', $request->working_shift_id);
        }


        if ($request->filled('work_date')) {
            $query->whereDate('work_date', $request->work_date);
        }

        $employeeWorkingShifts = $query
            ->orderBy('work_date', 'desc')
            ->paginate(config('settings.perpage', 10))
            ->withQueryString();


        $branches      = Branch::active()->pluck('name', 'id');
        $employees     = Employee::active()->pluck('name', 'id');
        $workingShifts = WorkingShift::active()->pluck('name', 'id');

        return view(
            'admin.employee_working_shifts.index',
            compact(
                'employeeWorkingShifts',
                'branches',
                'employees',
                'workingShifts'
            )
        );
    }


    public function employeesByBranch($id)
    {
        return Employee::query()
            ->where('branch_id', $id)
            ->where('is_active', 1)
            ->select('id', 'code', 'name')
            ->orderBy('name')
            ->get();
    }

    public function workingShiftsByBranch($id)
    {
        return WorkingShift::query()
            ->where('branch_id', $id)
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get();
    }


    public function create(Request $request)
    {

        $employees = Employee::with('branch')->active()->get();

        $workingShifts = collect();

        if ($request->filled('employee_id')) {
            $employee = Employee::findOrFail($request->employee_id);

            $workingShifts = WorkingShift::where('branch_id', $employee->branch_id)
                ->where('is_active', 1)
                ->orderBy('start_time')
                ->get();
        }

        $branches = Branch::active()->get();
        return view(
            'admin.employee_working_shifts.create',
            compact('employees', 'workingShifts', 'branches')
        );
    }


    public function store(StoreEmployeeWorkingShiftRequest $request)
    {
        $data = $request->validated();

        $branchId       = $data['branch_id'];
        $workingShiftId = $data['working_shift_id'];
        $employeeIds    = $data['employee_ids'];
        $applyType      = $data['apply_type'];


        $fromDate = match ($applyType) {
            'single'  => Carbon::parse($data['from_date']),
            '7_days'  => Carbon::parse($data['from_date']),
            'month'   => Carbon::parse($data['from_date'])->startOfMonth(),
            'range'   => Carbon::parse($data['from_date']),
        };

        $toDate = match ($applyType) {
            'single'  => Carbon::parse($data['from_date']),
            '7_days'  => Carbon::parse($data['from_date'])->addDays(6),
            'month'   => Carbon::parse($data['from_date'])->endOfMonth(),
            'range'   => Carbon::parse($data['to_date']),
        };

        $dates = CarbonPeriod::create($fromDate, $toDate);

        $dateValues = collect($dates)
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();


        $hasConflict = EmployeeWorkingShift::query()
            ->whereIn('employee_id', $employeeIds)
            ->where('working_shift_id', $workingShiftId)
            ->whereIn('work_date', $dateValues)
            ->exists();

        if ($hasConflict) {
            return back()
                ->withErrors([
                    'employee_ids' => 'Một hoặc nhiều nhân viên đã có ca làm trong khoảng thời gian này',
                ])
                ->withInput();
        }


        $now  = now();
        $rows = [];

        foreach ($dateValues as $date) {
            foreach ($employeeIds as $employeeId) {
                $rows[] = [
                    'employee_id'      => $employeeId,
                    'working_shift_id' => $workingShiftId,
                    'work_date'        => $date,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }
        }


        DB::transaction(function () use ($rows) {
            EmployeeWorkingShift::insert($rows);
        });

        Alert::success(__('Phân ca thành công'));

        return redirect()->route('employee-working-shifts.index');
    }
    public function calendar()
    {
        $branches = Branch::active()->get(['id', 'name']);

        return view('admin.employee_working_shifts.calendar', compact('branches'));
    }


    public function calendarEvents(Request $request)
    {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end   = Carbon::parse($request->end)->format('Y-m-d');

        $events = EmployeeWorkingShift::with([
            'employee:id,name,branch_id',
            'employee.branch:id,name',
            'workingShift:id,name,start_time,end_time',
        ])
            ->whereBetween('work_date', [$start, $end])
            ->get()
            ->groupBy(
                fn($item) =>
                $item->work_date->format('Y-m-d') . '_' . $item->working_shift_id
            );

        return response()->json(
            $events->map(function ($group) {
                $first = $group->first();
                $date  = $first->work_date->format('Y-m-d');

                return [
                    'title' => $first->workingShift->name
                        . ' (' . $group->count() . ' NV)',

                    'start' => $date . 'T' . $first->workingShift->start_time,
                    'end'   => $date . 'T' . $first->workingShift->end_time,

                    'extendedProps' => [
                        'branch' => $first->employee->branch->name ?? '',
                        'employees' => $group
                            ->pluck('employee.name')
                            ->unique()
                            ->values()
                            ->toArray(),
                    ],
                ];
            })->values()
        );
    }




    public function show($id)
    {
        $employeeWorkingShift = EmployeeWorkingShift::with([
            'employee.branch:id,name',
            'workingShift.branch:id,name',
        ])->findOrFail($id);

        return view(
            'admin.employee_working_shifts.show',
            compact('employeeWorkingShift')
        );
    }

    public function edit($id)
    {
        $employeeWorkingShift = EmployeeWorkingShift::with([
            'employee.branch',
            'workingShift',
        ])->findOrFail($id);

        $branchId = $employeeWorkingShift->employee->branch_id;

        $employees = Employee::where('branch_id', $branchId)
            ->active()
            ->orderBy('name')
            ->get();

        $workingShifts = WorkingShift::where('branch_id', $branchId)
            ->active()
            ->orderBy('start_time')
            ->get();

        $branches = Branch::active()->get();
        return view(
            'admin.employee_working_shifts.edit',
            compact('employeeWorkingShift', 'employees', 'workingShifts', 'branches')
        );
    }

    public function update(UpdateEmployeeWorkingShiftRequest $request, $id)
    {
        $employeeWorkingShift = EmployeeWorkingShift::with('employee')->findOrFail($id);
        $data = $request->validated();

        $employee = Employee::findOrFail($data['employee_id']);
        if ($employeeWorkingShift->employee->branch_id !== $employee->branch_id) {
            return back()
                ->withErrors([
                    'employee_id' => __('Nhân viên không thuộc cùng chi nhánh'),
                ])
                ->withInput();
        }

        $exists = EmployeeWorkingShift::where('id', '!=', $id)
            ->where('employee_id', $data['employee_id'])
            ->where('working_shift_id', $data['working_shift_id'])
            ->whereDate('work_date', $data['work_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'working_shift_id' => __('Nhân viên đã được phân ca này trong ngày'),
                ])
                ->withInput();
        }

        $employeeWorkingShift->update([
            'employee_id'      => $data['employee_id'],
            'working_shift_id' => $data['working_shift_id'],
            'work_date'        => $data['work_date'],
        ]);

        Alert::success(__('Cập nhật phân ca thành công'));

        return redirect()->route('employee-working-shifts.index');
    }


    /**
     * Xóa phân ca
     */
    public function destroy($id)
    {
        $employeeWorkingShift = EmployeeWorkingShift::findOrFail($id);
        $employeeWorkingShift->delete();

        Alert::success(__('Đã xóa phân ca'));

        return back();
    }
}
