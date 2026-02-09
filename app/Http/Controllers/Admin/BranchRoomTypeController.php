<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRoomType\StoreBranchRoomTypeRequest;
use App\Http\Requests\BranchRoomType\UpdateBranchRoomTypeRequest;
use App\Models\Branch;
use App\Models\BranchRoomType;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BranchRoomTypeController extends Controller
{
    public function getRoomTypesById($id)
    {
        $roomTypes = BranchRoomType::with('roomType')
            ->where('branch_id', $id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id'        => $item->room_type_id,
                    'code'      => $item->roomType->code,
                    'name'      => $item->roomType->name,
                    'price'     => (float) $item->price,
                    'capacity'  => $item->capacity,
                ];
            });

        return response()->json([
            'data' => $roomTypes
        ]);
    }
    public function index(Request $request)
    {
        $query = BranchRoomType::with(['branch', 'roomType']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $branchRoomTypes = $query
            ->sortable(['id' => 'desc'])
            ->paginate(config('settings.perpage', 10));

        $branches   = Branch::active()->pluck('name', 'id')->toArray();
        $roomTypes  = RoomType::active()->pluck('name', 'id')->toArray();

        return view('admin.branch_room_types.index', compact(
            'branchRoomTypes',
            'branches',
            'roomTypes'
        ));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $branches  = Branch::where('is_active', 1)->pluck('name', 'id');
        $roomTypes = RoomType::where('is_active', 1)->pluck('name', 'id');

        return view('admin.branch_room_types.create', compact('branches', 'roomTypes'));
    }

    /**
     * Lưu mới
     */
    public function store(StoreBranchRoomTypeRequest $request)
    {
        DB::transaction(function () use ($request) {
            BranchRoomType::create($request->validated());
        });

        Alert::success(__('Tạo loại phòng cho chi nhánh thành công'));

        return redirect()->route('branch-room-types.index');
    }

    /**
     * Chi tiết
     */
    public function show($id)
    {
        $branchRoomType = BranchRoomType::with(['branch', 'roomType'])->findOrFail($id);

        return view('admin.branch_room_types.show', compact('branchRoomType'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $branchRoomType = BranchRoomType::findOrFail($id);

        $branches  = Branch::active()->pluck('name', 'id');
        $roomTypes = RoomType::active()->pluck('name', 'id');

        return view(
            'admin.branch_room_types.edit',
            compact('branchRoomType', 'branches', 'roomTypes')
        );
    }

    /**
     * Cập nhật
     */
    public function update(UpdateBranchRoomTypeRequest $request, $id)
    {
        $branchRoomType = BranchRoomType::findOrFail($id);

        DB::transaction(function () use ($branchRoomType, $request) {
            $branchRoomType->update($request->validated());
        });

        Alert::success(__('Cập nhật loại phòng chi nhánh thành công'));

        return redirect()->route('branch-room-types.index');
    }

    /**
     * Xóa (soft delete)
     */
    public function destroy($id)
    {
        $branchRoomType = BranchRoomType::findOrFail($id);
        $branchRoomType->delete();

        Alert::success(__('Đã xóa loại phòng khỏi chi nhánh'));

        return redirect()->back();
    }
}
