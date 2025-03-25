<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/items/mylist.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="header-left">
                <img src="{{ asset('storage/photos/logo_images/logo.svg') }}" alt="COACHTECH ロゴ" class="logo" />
            </div>
            <div class="header-center">
                {{-- <input type="text" class="search-bar" value="{{ session('search_keyword', ' ') }}"
                    placeholder="なにをお探しですか？" /> --}}
                <input type="text" class="search-bar"
                    value="@if (!empty($keyword)) {{ $keyword }} @endif" placeholder="なにをお探しですか？" />
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
        @auth
            <div class="menu">
                {{-- <a href="{{route("items.recommend.after-login")}}" class="menu__left-link">おすすめ</a> --}}
                <a href="{{ route('items.index') }}" class="menu__left-link">おすすめ</a>
                <a href="#" class="menu__right-link">マイリスト</a>
            </div>

            {{-- 商品一覧（いいねした商品） --}}
            <div class="item-list">
                {{-- @foreach ($items as $item)
                    <div class="item">
                        <a href="{{ route('item.show', ['itemId' => $item->id]) }}">
                            <img class="item-image" src="{{ asset('storage/photos/item_images/' . $item->item_image) }}"
                                alt="{{ $item->item_name }}">
                            <div class="item-name">
                                <p>{{ $item->item_name }}</p>
                            </div>
                        </a>
                    </div>
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

                {{-- 商品一覧（検索した商品） --}}
                @if (!empty($searchItems))
                    @foreach ($searchItems as $searchItem)
                        @if ($searchItem->purchases()->exists())
                            <div class="item">
                                <img class="item-sold-image"
                                    src="{{ asset('storage/photos/item_images/' . $searchItem->item_image) }}"
                                    alt="">
                                <div class="item-sold-name">
                                    <p>SOLD {{ $searchItem->item_name }}</p>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('item.show', ['itemId' => $searchItem->id]) }}">
                                <div class="item">
                                    <img class="item-image"
                                        src="{{ asset('storage/photos/item_images/' . $searchItem->item_image) }}"
                                        alt="{{ $searchItem->item_name }}">
                                    <div class="item-name">
                                        <p>{{ $searchItem->item_name }}</p>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
            </div>
            @endif


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
        </div>
      </div> --}}
        @endauth

        @guest
            <div class="menu">
                <a href="{{ route('items.index') }}" class="menu__left-link">おすすめ</a>
                <a href="#" class="menu__right-link">マイリスト</a>
            </div>
            <div class="item-list">
            </div>
        @endguest

    </main>
</body>

</html>
