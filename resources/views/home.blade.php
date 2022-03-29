@extends('layouts.app')

@section('content')
<h1>ブログ一覧</h1>

<table>
    @foreach($blogs as $blog)
    <tr>
        <td>
            <a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a>
        </td>
        <td>
            {{ $blog->user->name }}
        </td>
        <td>
            （{{ $blog->comments_count }}件のコメント）
        </td>
        <td>
            <small>{{ $blog->updated_at }}</small>
        </td>
    </tr>
    @endforeach
</table>

<br>
{{ $blogs->links() }}

@endsection
