@extends('layouts.app')

@section('content')

<h1>ログイン画面</h1>

<form method="post">
@csrf

@include('inc.error')

@include('inc.message')

メールアドレス:
<input type="text" name="email" value="{{ old('email') ?? 'aaa@bbb.net' }}">
<br>
パスワード:
<input type="password" name="password" value="hogehoge">
<br><br>
<input type="submit" value=" ログイン ">

</form>

<p style="margin-top:30px;">
    <a href="{{ route('signup') }}">新規ユーザー登録</a>
</p>

@endsection
