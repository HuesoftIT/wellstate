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
        $query = ImageCategory::query()->with(['images' => function ($q) use ($request) {

            if ($request->filled('is_active')) {
                $q->where('is_active', $request->is_active);
            }

            $q->orderBy('order')
                ->orderByDesc('id');
        }]);


        // search theo tên category
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('image_category_id')) {
            $query->where('id', $request->image_category_id);
        }

        $categories = $query->paginate(config('settings.perpage', 10));

        $filterCategories = ImageCategory::pluck('name', 'id');

        return view('admin.images.index', compact('categories', 'filterCategories'));
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
        $maxOrder = Image::where('image_category_id', $request->image_category_id)
            ->max('order') ?? 0;

        foreach ($request->file('images') as $index => $file) {

            Image::create([
                'image_category_id' => $request->image_category_id,
                'image' => $file, // observer xử lý upload + resize
                'order' => $maxOrder + $index + 1,
                'is_active' => $request->is_active
            ]);
        }

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

        $data = [
            'image_category_id' => $request->image_category_id,
            'is_active' => $request->is_active
        ];

        // Nếu có upload ảnh mới → replace
        if ($request->hasFile('image')) {

            // xóa ảnh cũ nếu có
            if ($image->image && \Storage::disk('public')->exists($image->image)) {
                \Storage::disk('public')->delete($image->image);
            }

            // gán ảnh mới (observer sẽ resize + upload)
            $data['image'] = $request->file('image');
        }

        $image->update($data);

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
