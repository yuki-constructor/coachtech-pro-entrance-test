<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>プロフィール（取引中の商品）</title>
    <link rel="stylesheet" href="{{ asset('css/profile/show-transaction.css') }}" />
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
        <div class="form-group">
            <div class="user-profile">
                <div class="user-profile-image-placeholder">
                    <img class="user-profile-image"
                        src="{{ asset('storage/photos/profile_images/' . $user->profile_image) }}" alt="">
                </div>
                <p>{{ $user->name }}</p>
                <a href="{{ route('profile.edit') }}" class="user-profile-edit__btn">プロフィールを編集</a>
            </div>
        </div>
        <div class="menu">
            <a href="{{ route('profile.show.sell') }}" class="menu__left-link">出品した商品</a>
            <a href="{{ route('profile.show.buy') }}" class="menu__center-link">購入した商品</a>
            <a href="#" class="menu__right-link">取引中の商品</a>
        </div>
        <div class="item-list">
            @foreach ($transactions as $transaction)
                <a href="{{ route('transaction.show', ['transactionId' => $transaction->id]) }}">
                    <div class="item">
                        {{-- 商品画像 --}}
                        <img class="item-image"
                            src="{{ asset('storage/photos/item_images/' . $transaction->purchase->item->item_image) }}"
                            alt="">

                        <div class="item-name">{{ $transaction->purchase->item->item_name }}</div>

                        {{-- 通知マーク (未読が1件以上の場合のみ表示) --}}
                        {{-- @if (isset($unreadCounts[$transaction->id]) && $unreadCounts[$transaction->id] > 0)
                        <span class="notification-badge">
                            {{ $unreadCounts[$transaction->id] }}
                        </span>
                    @endif --}}

                        @if ($transaction->unread_count > 0)
                            <span class="notification-badge">
                                {{ $transaction->unread_count }}
                            </span>
                        @endif

                    </div>
                </a>
            @endforeach

        </div>
    </main>
</body>

</html>
