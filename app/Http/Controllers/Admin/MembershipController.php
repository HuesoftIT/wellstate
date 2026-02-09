<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembershipRequest;
use App\Http\Requests\UpdateMembershipRequest;
use App\Models\Membership;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MembershipController extends Controller
{
    /**
     * Danh sách membership
     */
    public function index(Request $request)
    {
        $query = Membership::query();

        // Tìm theo tên / code
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc trạng thái
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $memberships = $query
            ->orderBy('priority')
            ->paginate(config('settings.perpage', 10));

        return view('admin.memberships.index', compact('memberships'));
    }

    /**
     * Form tạo mới membership
     */
    public function create()
    {
        return view('admin.memberships.create');
    }

    /**
     * Lưu membership
     */
    public function store(StoreMembershipRequest $request)
    {
        Membership::create($request->validated());

        Alert::success(__('Tạo membership thành công'));

        return redirect()->route('memberships.index');
    }

    /**
     * Chi tiết membership
     */
    public function show($id, Request $request)
    {
        $membership = Membership::findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.memberships.show', compact('membership', 'backUrl'));
    }

    /**
     * Form chỉnh sửa membership
     */
    public function edit($id)
    {
        $membership = Membership::findOrFail($id);

        return view('admin.memberships.edit', compact('membership'));
    }

    /**
     * Cập nhật membership
     */
    public function update(UpdateMembershipRequest $request, $id)
    {
        $membership = Membership::findOrFail($id);
        $membership->update($request->validated());

        Alert::success(__('Cập nhật membership thành công'));

        return redirect()->route('memberships.index');
    }

    /**
     * Xóa membership (soft logic – nên hạn chế)
     */
    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);

        // (nâng cao) có thể check đang được customer dùng không
        // if ($membership->customers()->exists()) { ... }

        $membership->delete();

        Alert::success(__('Đã xóa membership'));

        return redirect()->route('memberships.index');
    }
}
