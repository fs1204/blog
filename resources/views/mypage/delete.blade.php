@extends('layouts.app')

@section('content')

<h1>アカウント削除</h1>

<form method="post">
@csrf
@method('delete')

@include('inc.error')

@include('inc.message')

メールアドレス:
<input type="text" name="email" value="{{ old('email') }}">
<br>
パスワード:
<input type="password" name="password">
<br><br>
<span id="delete-user">アカウント削除</span>

</form>

@endsection
