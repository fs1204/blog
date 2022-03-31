@extends('layouts.app')

@section('content')

<h1>マイブログ更新</h1>

<form method="post" enctype="multipart/form-data">
@csrf

@include('inc.error')

@include('inc.message')

タイトル：<input type="text" name="title" style="width: 400px" value="{{ $data['title'] }}">
<br><br>
本文：<textarea name="body" style="width: 600px; height: 200px">{{ $data['body'] }}</textarea>
<br>
<label>公開する：<input type="checkbox" name="is_open" value="1" {{ $data['is_open'] ? 'checked' : '' }}></label>
<br>
画像：<input type="file" name="pict">
@if($blog->pict)
<p>
    <img src="{{ Storage::url($blog->pict) }}" width="400" height="auto">
</p>
@endif
<br>
<input type="submit" value="更新する">

</form>

<br>

@foreach($comments = $blog->comments()->latest()->paginate(3) as $comment)
    <hr>
    <p>{{ $comment->name }} ({{ $comment->created_at }}) </p>
    <p>{{!! nl2br(e($comment->body)) !!}}</p>
    <form method="post" action="{{ route('comments.destroy', $comment) }}" id="delete-comment">
        @method('DELETE') @csrf
            <span class="delete" style="margin-right: auto;">削除</span>
    </form>
@endforeach
{{ $blog->comments()->latest()->paginate(3)->links(); }}

@endsection

