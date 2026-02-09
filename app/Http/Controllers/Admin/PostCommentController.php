<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PostCommentController extends Controller
{
    /**
     * Danh sách bình luận
     */
    public function index(Request $request)
    {
        $query = PostComment::with(['post', 'customer', 'parent']);

        // Tìm theo nội dung
        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        if ($request->has('is_approved') && $request->is_approved !== '') {
            $query->where('is_approved', $request->is_approved);
        }

        if ($request->has('is_spam') && $request->is_spam !== '') {
            $query->where('is_spam', $request->is_spam);
        }


        $comments = $query
            ->latest()
            ->paginate(config('settings.perpage', 10));

        return view('admin.post_comments.index', compact('comments'));
    }

    /**
     * Xem chi tiết bình luận
     */
    public function show($id)
    {
        $comment = PostComment::with([
            'post',
            'customer',
            'parent',
            'replies.customer'
        ])->findOrFail($id);

        return view('admin.post_comments.show', compact('comment'));
    }

    /**
     * Xóa mềm bình luận
     */
    public function destroy($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();

        Alert::success(__('Đã xóa bình luận'));

        return view(
            'admin.post_comments.index'
        );
    }

    /**
     * Duyệt bình luận
     */
    public function approve($id)
    {
        $comment = PostComment::findOrFail($id);

        $comment->update([
            'is_approved' => 1,
            'is_spam'     => 0,
        ]);

        Alert::success(__('Đã duyệt bình luận'));

        return redirect()->back();
    }

    /**
     * Đánh dấu spam
     */
    public function spam($id)
    {
        $comment = PostComment::findOrFail($id);

        $comment->update([
            'is_spam'     => 1,
            'is_approved' => 0,
        ]);

        Alert::warning(__('Đã đánh dấu bình luận là spam'));

        return redirect()->back();
    }
}
