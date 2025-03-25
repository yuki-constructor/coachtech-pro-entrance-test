<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>送付先住所変更</title>
    <link rel="stylesheet" href="{{ asset('css/profile/edit-address.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="header-left">
                <img src="{{ asset('storage/photos/logo_images/logo.svg') }}" alt="COACHTECH ロゴ" class="logo" />
            </div>
            <div class="header-center">
                <form action="{{ route('items.search') }}">
                    @csrf
                    <input class="search-bar" type="text" name="item_name" placeholder="なにをお探しですか？" />
                </form>
            </div>
            <div class="header-right">
                <nav class="nav">
                    <ul class="nav__ul">
                        <!-- <li><a href="#" class="nav__left-link">ログアウト</a></li> -->
                        <!-- <li><a href="#" class="nav__center-link">マイページ</a></li>
              <li><a href="#" class="nav__right-link">出品</a></li> -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav__left-link">
                                    ログアウト
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('profile.show.sell') }}" method="GET">
                                @csrf
                                <button type="submit" class="nav__center-link">
                                    マイページ
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('item.store') }}" method="GET">
                                @csrf
                                <button type="submit" class="nav__right-link">出品</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container-wrap">
            <div class="container">
                <h1 class="title">住所の変更</h1>
                <form class="form" action="{{ route('profile.update.address', ['itemId' => $item->id]) }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-group__label" for="postal_code">郵便番号</label>
                        <div>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('postal_code'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('postal_code') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <input class="form-group__input" type="text" id="postal_code" name="postal_code"
                            {{-- value="@if ($errors->has('postal_code') || $errors->has('address') || $errors->has('building')){{old("postal_code")}}@else{{$user->postal_code}}@endif" --}}
                            value="{{ $errors->any() ? old('postal_code') : $user->postal_code }}" />
                    </div>
                    <div class="form-group">
                        <label class="form-group__label" for="address">住所</label>
                        <div>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('address'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('address') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <input class="form-group__input" type="text" id="address" name="address"
                            {{-- value="@if ($errors->has('postal_code') || $errors->has('address') || $errors->has('building')){{old("address")}}@else{{$user->address}}@endif" --}} value="{{ $errors->any() ? old('address') : $user->address }}" />
                    </div>
                    <div class="form-group">
                        <label class="form-group__label" for="building">建物名</label>
                        <div>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('building'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('building') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <input class="form-group__input" type="text" id="building" name="building"
                            {{-- value="@if ($errors->has('postal_code') || $errors->has('address') || $errors->has('building')){{old("building")}}@else{{$user->building}}@endif" --}} value="{{ $errors->any() ? old('building') : $user->building }}" />
                    </div>
                    <button type="submit" class="form-group__submit-btn">
                        更新する
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
