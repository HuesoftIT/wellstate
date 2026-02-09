<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceCategoryRequest;
use App\Http\Requests\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    /**
     * Danh sách danh mục dịch vụ
     */
    public function index(Request $request)
    {
        $query = ServiceCategory::sortable();

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc trạng thái
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $serviceCategories = $query
            ->paginate(config('settings.perpage', 10));

        return view('admin.service_categories.index', compact('serviceCategories'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        return view('admin.service_categories.create');
    }

    /**
     * Lưu danh mục mới
     */
    public function store(StoreServiceCategoryRequest $request)
    {
        ServiceCategory::create($request->validated());

        Alert::success('Tạo danh mục dịch vụ thành công');

        return redirect()->route('service-categories.index');

        return redirect('admin/service-categories');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $serviceCategories = ServiceCategory::findOrFail($id);

        return view('admin.service_categories.edit', compact('serviceCategories'));
    }

    public function show($id, Request $request)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        $locale = app()->getLocale();

        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('admin.service_categories.show', compact('serviceCategory', 'backUrl', 'locale'));
    }


    /**
     * Cập nhật danh mục
     */
    public function update(UpdateServiceCategoryRequest $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $category->update($request->validated());

        Alert::success('Cập nhật danh mục dịch vụ thành công');


        return redirect('admin/service-categories');
    }

    /**
     * Xóa mềm
     */
    public function destroy($id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->delete();

        Alert::success(__('Đã xóa danh mục dịch vụ'));

        return redirect('admin/service-categories');
    }

    /**
     * Danh sách đã xóa
     */
    public function trash()
    {
        $categories = ServiceCategory::onlyTrashed()->paginate(10);

        return view('admin.service_categories.trash', compact('categories'));
    }

    /**
     * Khôi phục
     */
    public function restore($id)
    {
        ServiceCategory::onlyTrashed()->findOrFail($id)->restore();

        Alert::success(__('Khôi phục danh mục thành công'));

        return redirect()->back();
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        ServiceCategory::onlyTrashed()->findOrFail($id)->forceDelete();

        Alert::success(__('Đã xóa vĩnh viễn danh mục'));

        return redirect()->back();
    }
}
