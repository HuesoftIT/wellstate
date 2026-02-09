<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PostController extends Controller
{
    /**
     * Danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = Post::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('post_category_id')) {
            $query->where('post_category_id', $request->post_category_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $posts = $query
            ->orderByDesc('id')
            ->paginate(config('settings.perpage', 10));

        $categories = PostCategory::active()
            ->child()
            ->with('parent')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => $item->parent->name . ' → ' . $item->name
                ];
            });


        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $categories = PostCategory::active()
            ->child()
            ->with('parent')
            ->orderBy('name')
            ->get();


        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Lưu bài viết
     */
    public function store(StorePostRequest $request)
    {
        Post::create($request->validated());

        Alert::success(__('Tạo bài viết thành công'));

        return redirect()->route('posts.index');
    }

    /**
     * Chi tiết bài viết
     */
    public function show($id, Request $request)
    {
        $post = Post::with('category')->findOrFail($id);
        $backUrl = $request->get('back_url');

        return view('admin.posts.show', compact('post', 'backUrl'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::active()
            ->child()
            ->with('parent')
            ->orderBy('name')
            ->get();


        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Cập nhật bài viết
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->validated());

        Alert::success(__('Cập nhật bài viết thành công'));

        return redirect()->route('posts.index');
    }

    /**
     * Xóa mềm
     */
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();

        Alert::success(__('Đã xóa bài viết'));

        return redirect()->route('posts.index');
    }

    /**
     * Thùng rác
     */
    public function trash(Request $request)
    {
        $query = Post::onlyTrashed()->with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query
            ->orderByDesc('deleted_at')
            ->paginate(config('settings.perpage', 10));

        return view('admin.posts.trash', compact('posts'));
    }

    /**
     * Khôi phục
     */
    public function restore($id)
    {
        Post::onlyTrashed()->findOrFail($id)->restore();

        Alert::success(__('Khôi phục bài viết thành công'));

        return redirect()->back();
    }

    /**
     * Xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        Post::onlyTrashed()->findOrFail($id)->forceDelete();

        Alert::success(__('Đã xóa vĩnh viễn bài viết'));

        return redirect()->back();
    }
}
