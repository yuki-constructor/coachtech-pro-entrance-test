<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="header-left">
                <img src="{{ asset('storage/photos/logo_images/logo.svg') }}" alt="COACHTECH ロゴ" class="logo" />
            </div>
            <div class="header-center">

            </div>
            <div class="header-right">

            </div>
        </div>
    </header>

    <main>
        <div class="container-wrap">
            <div class="container">

                {{-- ▼▼▼▼▼▼▼▼▼▼▼▼（メッセージ表示） --}}
                <div class="message">
                    @if (session()->has('error'))
                        <p>{{ session()->get('error') }}</p>
                    @endif
                    @if (session()->has('login-message'))
                        <p>{{ session()->get('login-message') }}</p>
                    @endif
                </div>
                {{-- ▲▲▲▲▲▲▲▲▲▲▲▲ --}}

                <h1 class="title">ログイン</h1>
                <form class="form" action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-group__label" for="email">ユーザー名／メールアドレス</label>
                        <div>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('email'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('email') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <input class="form-group__input" type="text" id="email" name="email" />
                    </div>
                    <!-- <div class="form-group">
            <label class="form-group__label" for="email">メールアドレス</label>
            <input
              class="form-group__input"
              type="email"
              id="email"
              name="email"
              required
            />
          </div> -->
                    <div class="form-group">
                        <label class="form-group__label" for="password">パスワード</label>
                        <div>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('password'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('password') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <input class="form-group__input" type="password" id="password" name="password" />
                    </div>
                    <!-- <div class="form-group">
            <label class="form-group__label" for="confirm-password"
              >確認用パスワード</label
            >
            <input
              class="form-group__input"
              type="password"
              id="confirm-password"
              name="confirm-password"
              required
            />
          </div> -->
                    <button type="submit" class="form-group__submit-btn">ログインする</button>
                </form>
                <p class="login-link">
                    <a class="login-link__link-btn" href="{{ route('register') }}">会員登録はこちら</a>
                </p>
            </div>
        </div>
    </main>
</body>

</html>
