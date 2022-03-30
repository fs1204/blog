@extends('layouts.app')

@section('content')

<h1>マイブログ新規登録</h1>

<form method="post" enctype="multipart/form-data">
@csrf

@include('inc.error')

タイトル：<input type="text" name="title" style="width: 400px" value="{{ old('title') }}">
<br>
本文：<textarea name="body" style="width: 600px; height: 200px">{{ old('body') }}</textarea>
<br>
<label>公開する：<input type="checkbox" name="is_open" value="1" {{ old('is_open') ? 'checked' : '' }}></label>
<br>
画像：<input type="file" name="image">

<br><br>
<input type="submit">

</form>

@endsection
