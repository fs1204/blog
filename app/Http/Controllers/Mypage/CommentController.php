<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'name' => [],
            'body' => ['required', 'string'],
        ]);

        Comment::create([
            'blog_id' => $blog->id,
            'name' => $data['name'],
            'body' => $data['body'],
        ]);

        return redirect()->route('blog.show', $blog);
    }

    public function destroy(Request $request, Comment $comment)
    {
        if ($request->user()->isNot($comment->blog->user)) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('mypage.blog.edit', $comment->blog);
    }
}
