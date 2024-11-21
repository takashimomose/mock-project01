<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Contact Form')</title> <!-- 各ページで異なるタイトルを指定可能 -->
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

    <style>
        /* デフォルトの画像 */
        .responsive-logo {
            content: url("{{ asset('images/logo.svg') }}");
        }

        /* 画面幅が1110px以下の場合に切り替え */
        @media (max-width: 1110px) {
            .responsive-logo {
                content: url("{{ asset('images/logo-tablet.svg') }}");
            }
        }
    </style>

    <!-- 各ページで追加のCSSを読み込む -->
    @stack('css')
</head>

<body>
    <header class="header">
        <div class="header-wrapper">
            <h1 class="header-logo">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('images/logo.svg') }}" class="responsive-logo" alt="CoachTech">
                </a>
            </h1>
            <!-- ログインしている場合もしていない場合も表示 -->
            <form action="" method="GET" class="header-search-form">
                @csrf
                <input class="header-search-input" type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？">
            </form>
            <nav class="header-nav">
                <ul class="header-nav-list">
                    @if (Auth::check())
                        <!-- ログインしている場合 -->
                        <li class="header-nav-item">
                            <form class="header-nav-logout" action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit">ログアウト</button>
                            </form>
                        </li>
                    @else
                        <!-- ログインしていない場合 -->
                        <li class="header-nav-item">
                            <a class="header-nav-login" href="{{ route('login') }}">ログイン</a>
                        </li>
                    @endif
                    <li class="header-nav-item"><a href="{{ route('profile') }}" class="header-nav-link">マイページ</a></li>
                    <li class="header-nav-item"><a href="{{ route('sell.show') }}" class="header-sell-button">出品</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    @yield('content') <!-- ここに各ページのコンテンツが挿入されます -->
    <footer class="footer"></footer>
</body>

</html>
