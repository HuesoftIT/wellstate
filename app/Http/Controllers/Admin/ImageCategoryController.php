<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageCategoryRequest;
use App\Http\Requests\UpdateImageCategoryRequest;
use App\Models\ImageCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ImageCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ImageCategory::withCount('images');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $categories = $query
            ->orderByDesc('id')
            ->paginate(config('settings.perpage', 10));

        return view('admin.image_categories.index', compact('categories'));
    }


    /**
     * Form tạo mới
     */
    public function create()
    {
        return view('admin.image_categories.create');
    }

    /**
     * Lưu category
     */
    public function store(StoreImageCategoryRequest $request)
    {
        ImageCategory::create($request->validated());

        Alert::success('Tạo danh mục hình ảnh thành công');

        return redirect()->route('image-categories.index');
    }

    /**
     * Chi tiết category
     */
    public function show($id)
    {
        $imageCategory = ImageCategory::withCount('images')->findOrFail($id);

        return view('admin.image_categories.show', compact('imageCategory'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $category = ImageCategory::findOrFail($id);

        return view('admin.image_categories.edit', compact('category'));
    }

    /**
     * Cập nhật category
     */
    public function update(UpdateImageCategoryRequest $request, $id)
    {
        $category = ImageCategory::findOrFail($id);
        $category->update($request->validated());

        Alert::success('Cập nhật danh mục thành công');

        return redirect()->route('image-categories.index');
    }

    /**
     * Xóa mềm
     */
    public function destroy($id)
    {
        ImageCategory::findOrFail($id)->delete();

        Alert::success('Đã xóa danh mục');

        return redirect()->route('image-categories.index');
    }

    /**
     * Thùng rác
     */
    public function trash(Request $request)
    {
        $query = ImageCategory::onlyTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query
            ->orderByDesc('deleted_at')
            ->paginate(config('settings.perpage', 10));

        return view('admin.image_categories.trash', compact('categories'));
    }

    /**
     * Khôi phục
     */
    public function restore($id)
    {
        ImageCategory::onlyTrashed()->findOrFail($id)->restore();

        Alert::success('Khôi phục danh mục thành công');

        return redirect()->back();
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        ImageCategory::onlyTrashed()->findOrFail($id)->forceDelete();

        Alert::success('Đã xóa vĩnh viễn danh mục');

        return redirect()->back();
    }
}
