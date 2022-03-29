<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ブログ</title>
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>
<div class="container">
    <nav>
        <ul>
            <li><a href="/">TOP（ブログ一覧）</a></li>

            @auth
                <li><a href="{{ route('mypage.blog') }}">マイブログ一覧</a></li>
                <li>ようこそ {{ auth()->user()->name }} さん!</li>
                <li>
                    <form method="post" action="{{ route('mypage.logout') }}">
                        @csrf
                        <span id="logout">ログアウト</span>
                    </form>
                </li>
            @endauth
            @guest
                <li><a href="{{ route(('login')) }}">ログイン</a></li>
            @endguest
        </ul>
    </nav>

    @yield('content')
</div>

<script src="{{ url('js/main.js') }}"></script>
</body>
</html>
