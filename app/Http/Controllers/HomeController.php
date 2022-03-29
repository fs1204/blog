<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')
                    ->withCount('comments')
                    ->where('is_open', Blog::OPEN)
                    ->orderByDesc('comments_count')
                    ->latest('updated_at')
                    ->paginate(10);

        return view('home', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_open, 403);
        return view('blog.show', compact('blog'));
    }
}
