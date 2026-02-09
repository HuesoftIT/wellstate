<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BranchController extends Controller
{
    /**
     * Danh sách chi nhánh
     */
    public function index(Request $request)
    {
        $query = Branch::query();

        // Tìm theo tên / code / phone
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc trạng thái
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $branches = $query
            ->orderByDesc('id')
            ->paginate(config('settings.perpage', 10));

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Form tạo chi nhánh
     */
    public function create()
    {
        return view('admin.branches.create');
    }

   
    public function store(StoreBranchRequest $request)
    {
        Branch::create($request->validated());

        Alert::success(__('Tạo chi nhánh thành công'));

        return redirect()->route('branches.index');
    }

    /**
     * Chi tiết chi nhánh
     */
    public function show($id, Request $request)
    {
        $branch  = Branch::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.branches.show', compact('branch', 'backUrl'));
    }

    /**
     * Form chỉnh sửa chi nhánh
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);

        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Cập nhật chi nhánh
     */
    public function update(UpdateBranchRequest $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update($request->validated());

        Alert::success(__('Cập nhật chi nhánh thành công'));

        return redirect()->route('branches.index');
    }

    /**
     * Xóa chi nhánh (soft delete)
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);

        // (Nâng cao) check đang có nhân viên / booking
        // if ($branch->staff()->exists()) { ... }

        $branch->delete();

        Alert::success(__('Đã xóa chi nhánh'));

        return redirect()->route('branches.index');
    }
}
