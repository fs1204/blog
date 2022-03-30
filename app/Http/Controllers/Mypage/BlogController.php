<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogSaveRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = auth()->user()->blogs()->paginate(10);
        return view('mypage.index', compact('blogs'));
    }

    public function create()
    {
        return view('mypage.blog.create');
    }

    public function store(BlogSaveRequest $request)
    {
        $data = $request->validated();

        $data['is_open'] = $request->boolean('is_open');

        if ($request->hasFile('pict')) {
            $data['pict'] = base64_encode(file_get_contents($request->pict->getRealPath()));
        }

        $blog = auth()->user()->blogs()->create($data);

        return redirect()->route('mypage.blog.edit', $blog)->with('message', 'ブログを投稿しました。');
    }

    public function edit(Blog $blog)
    {
        if (auth()->user()->isNot($blog->user)) {
            abort(403);
        }
        $data = old() ?: $blog;
        return view('mypage.blog.edit', compact('data', 'blog'));
    }

    public function update(Blog $blog, BlogSaveRequest $request)
    {
        if ($request->user()->isNot($blog->user)) {
            abort(403);
        }

        $data = $request->validated();

        $data['is_open'] = $request->boolean('is_open');

        if ($request->hasFile('pict')) {
            $blog->deletePictFile();
            $data['pict'] = $request->file('pict')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('mypage.blog.edit', $blog)->with('message', 'ブログを更新しました。');
    }

    public function destroy(Blog $blog, Request $request)
    {
        if ($request->user()->isNot($blog->user)) {
            abort(403);
        }

        try {
            DB::beginTransaction();
            $blog->delete();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }

        return redirect()->route('mypage.blog');
    }
}
