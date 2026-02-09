<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkingShiftRequest;
use App\Http\Requests\UpdateWorkingShiftRequest;
use App\Models\WorkingShift;
use App\Models\Branch;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WorkingShiftController extends Controller
{
    /**
     * Danh sách ca làm việc
     */
    public function index(Request $request)
    {
        $query = WorkingShift::with('branch');

        // Tìm theo tên ca
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo chi nhánh
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Lọc trạng thái
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $workingShifts = $query
            ->orderBy('start_time')
            ->paginate(config('settings.perpage', 10));

        $branches = Branch::active()->pluck('name', 'id');

        return view('admin.working_shifts.index', compact('workingShifts', 'branches'));
    }

    /**
     * Form tạo ca làm việc
     */
    public function create()
    {
        $branches = Branch::active()->get();

        return view('admin.working_shifts.create', compact('branches'));
    }

    /**
     * Lưu ca làm việc
     */
    public function store(StoreWorkingShiftRequest $request)
    {
        WorkingShift::create($request->validated());

        Alert::success(__('Tạo ca làm việc thành công'));

        return redirect()->route('working-shifts.index');
    }

    /**
     * Chi tiết ca làm việc
     */
    public function show($id)
    {
        $workingShift = WorkingShift::with([
            'branch',
            'employees', 
        ])->findOrFail($id);

        return view('admin.working_shifts.show', compact('workingShift'));
    }


    /**
     * Form chỉnh sửa ca làm việc
     */
    public function edit($id)
    {
        $workingShift = WorkingShift::findOrFail($id);
        $branches = Branch::active()->get();

        return view('admin.working_shifts.edit', compact('workingShift', 'branches'));
    }

    /**
     * Cập nhật ca làm việc
     */
    public function update(UpdateWorkingShiftRequest $request, $id)
    {
        $workingShift = WorkingShift::findOrFail($id);
        $workingShift->update($request->validated());

        Alert::success(__('Cập nhật ca làm việc thành công'));

        return redirect()->route('working-shifts.index');
    }

    /**
     * Xóa ca làm việc
     */
    public function destroy($id)
    {
        $workingShift = WorkingShift::findOrFail($id);

        // Optional: không cho xóa nếu đã phân ca
        if ($workingShift->employeeWorkingShifts()->exists()) {
            Alert::error(__('Ca làm việc đã được phân cho nhân viên'));
            return back();
        }

        $workingShift->delete();

        Alert::success(__('Đã xóa ca làm việc'));

        return redirect()->route('working-shifts.index');
    }

    /**
     * Bật / tắt ca làm việc
     */
    public function active($id)
    {
        $workingShift = WorkingShift::findOrFail($id);
        $workingShift->update([
            'is_active' => ! $workingShift->is_active,
        ]);

        Alert::success(__('Cập nhật trạng thái ca làm việc'));

        return back();
    }
}
