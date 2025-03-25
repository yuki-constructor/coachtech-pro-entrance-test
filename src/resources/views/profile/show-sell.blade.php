<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>プロフィール</title>
    <link rel="stylesheet" href="{{ asset('css/profile/show-sell.css') }}" />
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
            <a href="#" class="menu__left-link">出品した商品</a>
            <a href="{{ route('profile.show.buy') }}" class="menu__right-link">購入した商品</a>
        </div>
        <div class="item-list">
            @foreach ($items as $item)
                <div class="item">
                    <img class="item-image" src="{{ asset('storage/photos/item_images/' . $item->item_image) }}"
                        alt="">
                    <div class="item-name">{{ $item->item_name }}</div>
                </div>
            @endforeach

            {{-- <div class="item">
          <div class="item-image">商品画像</div>
          <div class="item-name">商品名</div>
        </div>
        <div class="item">
          <div class="item-image">商品画像</div>
          <div class="item-name">商品名</div>
        </div>
        <div class="item">
          <div class="item-image">商品画像</div>
          <div class="item-name">商品名</div>
        </div>
        <div class="item">
          <div class="item-image">商品画像</div>
          <div class="item-name">商品名</div>
        </div> --}}
        </div>
    </main>
</body>

</html>
