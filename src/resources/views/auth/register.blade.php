<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>会員登録</title>
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}" />
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
                <h1 class="title">会員登録</h1>
                <form class="form" action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-group__label" for="name">ユーザー名</label>
                        <div>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('name'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('name') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <input class="form-group__input" type="text" id="name" name="name"
                            value="{{ old('name') }}" />
                    </div>
                    <div class="form-group">
                        <label class="form-group__label" for="email">メールアドレス</label>
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
                        <input class="form-group__input" {{-- type="email" --}} id="email" name="email"
                            value="{{ old('email') }}" />
                    </div>
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
                    <div class="form-group">
                        <label class="form-group__label" for="confirm-password">確認用パスワード</label>
                        <div>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('password_confirmation'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('password_confirmation') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <input class="form-group__input" type="password" id="password_confirmation"
                            name="password_confirmation" />
                    </div>
                    {{-- <div class="form-group">
            <input
              class="form-group__input"
              type="hidden"
              id="is_first_login"
              name="is_first_login"
               value="1"
            />
          </div> --}}
                    <button type="submit" class="form-group__submit-btn">登録する</button>
                </form>
                <p class="login-link">
                    <a class="login-link__link-btn" href="{{ route('login') }}">ログインはこちら</a>
                </p>
            </div>
        </div>
    </main>
</body>

</html>
