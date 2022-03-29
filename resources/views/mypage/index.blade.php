@extends('layouts.app')
@section('content')

<h1>マイブログ一覧</h1>

<div class="nav">
    <a href="{{ route('mypage.blog.create') }}">ブログ新規登録</a>
    <a href="{{ route('mypage.delete.index', auth()->user()) }}" id="delete_user">アカウント削除</a>
</div>

<hr>

<table>
    <tr>
        <th>ブログ名</th>
    </tr>

    @foreach ($blogs as $blog)
    <tr>
        <td>
            <a href="{{ route('mypage.blog.edit', $blog) }}">{{ $blog->title }}</a>
        </td>
        <td>
            <form method="post" action="{{ route('mypage.blog.delete', $blog) }}">
                @csrf
                @method('delete')
                <span class="delete">削除</span>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{ $blogs->links() }}

@endsection
