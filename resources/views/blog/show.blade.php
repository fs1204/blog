@extends('layouts.app')

@section('content')

<h1>{{ $blog->title }}</h1>
<div>{!! nl2br(e($blog->body)) !!}</div>

@if($blog->pict)
<p>
    <img src="{{ Storage::url($blog->pict) }}" width="400" height="auto">
</p>
@endif

<p>書き手：{{ $blog->user->name }}</p>
<p>更新日時：{{ $blog->updated_at }}</p>

<h2>コメント</h2>


@auth
<form method="post" action="{{ route('comments.store', $blog) }}" class="comment-form">
@csrf
@include('inc.error')

<input type="hidden" name="name" value="{{ auth()->user()->name }}">
<br>
本文：<textarea name="body" style="width: 600px; height: 50px">{{ old('body') }}</textarea>
<br>
<input type="submit">

</form>
<br>
@endauth

@foreach($blog->comments()->latest()->paginate(3) as $comment)
<hr>
<p>{{ $comment->name }}</p>
<p>{{!! nl2br(e($comment->body)) !!}}</p>
<p>({{ $comment->created_at }})</p>
@endforeach
{{ $comments = $blog->comments()->latest()->paginate(3); }}

@endsection
