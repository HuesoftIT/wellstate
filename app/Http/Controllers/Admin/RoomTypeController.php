<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\RoomType\UpdateRoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class RoomTypeController extends Controller
{
    /**
     * Danh sách loại phòng
     */
    public function index(Request $request)
    {
        $query = RoomType::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $roomTypes = $query
            ->sortable(['id' => 'desc']) 
            ->paginate(config('settings.perpage', 10));

        return view('admin.room_types.index', compact('roomTypes'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        return view('admin.room_types.create');
    }

    /**
     * Lưu loại phòng mới
     */
    public function store(StoreRoomTypeRequest $request)
    {
        DB::transaction(function () use ($request) {
            RoomType::create($request->validated());
        });

        Alert::success(__('Tạo loại phòng thành công'));

        return redirect()->route('room-types.index');
    }

    /**
     * Chi tiết
     */
    public function show($id)
    {
        $roomType = RoomType::findOrFail($id);

        return view('admin.room_types.show', compact('roomType'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $roomType = RoomType::findOrFail($id);

        return view('admin.room_types.edit', compact('roomType'));
    }

    /**
     * Cập nhật
     */
    public function update(UpdateRoomTypeRequest $request, $id)
    {
        $roomType = RoomType::findOrFail($id);
        DB::transaction(function () use ($roomType, $request) {
            $roomType->update($request->validated());
        });

        Alert::success(__('Cập nhật loại phòng thành công'));

        return redirect()->route('room-types.index');
    }

    /**
     * Xóa (hard delete – không soft delete)
     */
    public function destroy($id)
    {
        $roomType = RoomType::findOrFail($id);

        $roomType->delete();

        Alert::success(__('Đã xóa loại phòng'));

        return redirect()->back();
    }
}
