<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Models\Image;
use App\Models\ImageCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ImageController extends Controller
{

    public function index(Request $request)
    {
        $query = Image::query()->with('category');


        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($qc) use ($search) {
                        $qc->where('name', 'like', "%{$search}%");
                    });
            });
        }


        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('image_category_id')) {
            $query->where('image_category_id', $request->image_category_id);
        }

        $images = $query
            ->orderBy('order')
            ->orderByDesc('id')
            ->paginate(config('settings.perpage', 10));

        $categories = ImageCategory::pluck('name', 'id');

        return view('admin.images.index', compact('images', 'categories'));
    }

    public function create()
    {
        $categories = ImageCategory::pluck('name', 'id');

        return view('admin.images.create', compact('categories'));
    }

    /**
     * Lưu image
     */
    public function store(StoreImageRequest $request)
    {
        Image::create($request->validated());

        Alert::success('Tạo image thành công');

        return redirect()->route('images.index');
    }

    /**
     * Chi tiết image
     */
    public function show($id)
    {
        $image = Image::with('category')->findOrFail($id);

        return view('admin.images.show', compact('image'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        $categories = ImageCategory::orderBy('name')->pluck('name', 'id');

        return view('admin.images.edit', compact('image', 'categories'));
    }

    /**
     * Cập nhật image
     */
    public function update(UpdateImageRequest $request, $id)
    {
        $image = Image::findOrFail($id);
        $image->update($request->validated());

        Alert::success('Cập nhật image thành công');

        return redirect()->route('images.index');
    }

    /**
     * Xóa mềm
     */
    public function destroy($id)
    {
        Image::findOrFail($id)->delete();

        Alert::success('Đã xóa image');

        return redirect()->route('images.index');
    }

    /**
     * Thùng rác
     */
    public function trash(Request $request)
    {
        $query = Image::onlyTrashed();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $images = $query
            ->orderByDesc('deleted_at')
            ->paginate(config('settings.perpage', 10));

        return view('admin.images.trash', compact('images'));
    }

    /**
     * Khôi phục
     */
    public function restore($id)
    {
        Image::onlyTrashed()->findOrFail($id)->restore();

        Alert::success('Khôi phục image thành công');

        return redirect()->back();
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        Image::onlyTrashed()->findOrFail($id)->forceDelete();

        Alert::success('Đã xóa vĩnh viễn image');

        return redirect()->back();
    }
}
