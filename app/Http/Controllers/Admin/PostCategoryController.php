<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCategoryRequest;
use App\Http\Requests\UpdatePostCategoryRequest;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PostCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PostCategory::root()
            ->with(['children' => function ($q) {
                $q->orderBy('order');
            }]);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $postCategories = $query
            ->orderBy('order')
            ->paginate(config('settings.perpage', 10));

        return view(
            'admin.post_categories.index',
            compact('postCategories')
        );
    }

    public function create()
    {
        $post_categories_parents = PostCategory::root()->where('is_active', 1)->get();
        return view('admin.post_categories.create', compact('post_categories_parents'));
    }

    /**
     * Lưu danh mục mới
     */
    public function store(StorePostCategoryRequest $request)
    {
        PostCategory::create($request->validated());

        Alert::success(__('Tạo danh mục bài viết thành công'));

        return redirect()->route('post-categories.index');
    }

    public function show($id, Request $request)
    {
        $postCategory = PostCategory::with('children')->findOrFail($id);

        $backUrl = $request->get('back_url');

        return view(
            'admin.post_categories.show',
            compact('postCategory', 'backUrl')
        );
    }


    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $postCategory = PostCategory::findOrFail($id);

        $post_categories_parents = PostCategory::root()
            ->where('is_active', 1)
            ->where('id', '!=', $id)
            ->get();

        return view(
            'admin.post_categories.edit',
            compact('postCategory', 'post_categories_parents')
        );
    }

    /**
     * Cập nhật danh mục
     */
    public function update(UpdatePostCategoryRequest $request, $id)
    {
        $postCategory = PostCategory::findOrFail($id);

        $postCategory->update($request->validated());

        Alert::success(__('Cập nhật danh mục bài viết thành công'));

        return redirect()->route('post-categories.index');
    }

    /**
     * Xóa mềm
     */
    public function destroy($id)
    {
        $postCategory = PostCategory::findOrFail($id);
        $postCategory->delete();

        Alert::success(__('Đã xóa danh mục bài viết'));

        return redirect()->route('post-categories.index');
    }

    /**
     * Danh sách đã xóa
     */
    public function trash(Request $request)
    {
        $query = PostCategory::onlyTrashed();

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $postCategories = $query
            ->orderBy('deleted_at', 'desc')
            ->paginate(config('settings.perpage', 10));

        return view('admin.post_categories.trash', compact('postCategories'));
    }

    /**
     * Khôi phục
     */
    public function restore($id)
    {
        $postCategory = PostCategory::onlyTrashed()->findOrFail($id);
        $postCategory->restore();

        Alert::success(__('Khôi phục danh mục bài viết thành công'));

        return redirect()->back();
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        $postCategory = PostCategory::onlyTrashed()->findOrFail($id);
        $postCategory->forceDelete();

        Alert::success(__('Đã xóa vĩnh viễn danh mục bài viết'));

        return redirect()->back();
    }
}
