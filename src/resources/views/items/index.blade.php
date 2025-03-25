<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="header-left">
                <img src="{{ asset('storage/photos/logo_images/logo.svg') }}" alt="COACHTECH ロゴ" class="logo" />
            </div>

            {{-- 商品検索 --}}

            {{-- <div class="header-center">
                <input type="text" class="search-bar" placeholder="なにをお探しですか？" />
            </div> --}}
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
        <div class="menu">
            <a href="#" class="menu__left-link">おすすめ</a>
            {{-- ログイン中のユーザーはマイリスト画面へ --}}
            @auth
                <a href="{{ route('items.index.mylist') }}" class="menu__right-link">マイリスト</a>
            @endauth
            {{-- ゲストはログイン画面へ --}}
            @guest
                <a href="{{ route('items.index.mylist') }}" class="menu__right-link">マイリスト</a>
            @endguest
        </div>

        {{-- 商品一覧 --}}
        <div class="item-list">
            {{-- @foreach ($items as $item)
                <a href="{{ route('item.show', ['itemId' => $item->id]) }}">
                    <div class="item">
                        <img class="item-image" src="{{ asset('storage/photos/item_images/' . $item->item_image) }}"
                            alt="">
                        <div class="item-name">
                            <p>{{ $item->item_name }}</p>
                        </div>
                    </div>
                </a>
            @endforeach --}}

            @foreach ($items as $item)
                @if ($item->purchases()->exists())
                    <div class="item">
                        <img class="item-sold-image"
                            src="{{ asset('storage/photos/item_images/' . $item->item_image) }}" alt="">
                        <div class="item-sold-name">
                            <p>SOLD {{ $item->item_name }}</p>
                        </div>
                    </div>
                @else
                    <a href="{{ route('item.show', ['itemId' => $item->id]) }}">
                        <div class="item">
                            <img class="item-image"
                                src="{{ asset('storage/photos/item_images/' . $item->item_image) }}" alt="">
                            <div class="item-name">
                                <p>{{ $item->item_name }}</p>
                            </div>
                        </div>
                    </a>
                @endif
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
        </div>
        <div class="item">
          <div class="item-image">商品画像</div>
          <div class="item-name">商品名</div>
        </div> --}}
        </div>
    </main>

</body>

</html>
