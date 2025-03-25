<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
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

    <main class="item-detail">
        {{-- <div class="item-image">
      <div class="image-placeholder"> --}}
        <img class="item-image" src="{{ asset('storage/photos/item_images/' . $item->item_image) }}" alt="">
        {{-- </div>
    </div> --}}
        <div class="item-info">
            <h1 class="item-title">{{ $item->item_name }}</h1>
            <p class="item-brand">{{ $item->brand }}</p>
            <p class="item-price">¥{{ number_format($item->price) }}（税込）</p>
            <!-- <table class="item-reviews">
      <tr>
        <th class="item-star"> &#9734</th>
      <th class="item-balloon"> </th>
    </tr>
    <tr>
      <td>3</td>
      <td>1</td>
    </tr>
      </table> -->
            <div class="item-reviews">

                {{-- いいね機能 --}}

                {{-- ログイン中のユーザーはいいねが押せる --}}
                @auth
                    <form action="{{ route('like', ['itemId' => $item->id]) }}" method="POST">
                        {{-- <form action="{{ route('like', $item->id) }}" method="POST"> --}}
                        @csrf
                        <button type="submit" class="item-reviews__btn">
                            @if (auth()->user()->likeItem->contains($item->id))
                                <!-- いいね済み（黄色の星を表示） -->
                                <img class="item-star" src="{{ asset('storage/photos/logo_images/star-yellow.png') }}"
                                    alt="いいね">
                            @else
                                <!-- いいねしていない（灰色の星を表示） -->
                                <img class="item-star" src="{{ asset('storage/photos/logo_images/star.png') }}"
                                    alt="いいね">
                            @endif
                        </button>
                    </form>
                @endauth

                {{-- ゲストはいいねが押せない --}}
                @guest
                    <img class="item-star" src="{{ asset('storage/photos/logo_images/star.png') }}" alt="いいね">
                @endguest

                <img class="item-balloon" src="{{ asset('storage/photos/logo_images/baloon.png') }}" alt="">

                <!-- 「いいね」数表示 -->
                <p class="item-like-count">{{ $item->userLike()->count() }}</p>
                <!-- 「コメント」数表示 -->
                <p class="item-comment-count">{{ $item->comments->count() }}</p>
            </div>
            <div class="item-actions">

                {{-- ログイン中のユーザーは購入画面へ --}}
                @auth
                    <a href="{{ route('item.purchase', ['itemId' => $item->id]) }}" class="buy-button">購入手続きへ</a>
                @endauth

                {{-- ゲストはログイン画面へ --}}
                @guest
                    <a href="{{ route('login') }}" class="buy-button">購入手続きへ</a>
                @endguest

            </div>
            <section class="item-description">
                <h2>商品説明</h2>
                <div class="item-description-color">
                    <div class="item-description-title">
                        カラー：
                    </div>
                    <div class="item-description-tags">
                        <p>グレー</p>
                    </div>
                </div>
                <div class="item-description-condition">
                    <div class="item-description-title">
                        {{-- {{$condition->condition_name}} --}}
                    </div>
                    <div class="item-description-state">
                        <p>{{ $item->description }}</p>
                    </div>
                </div>
            </section>
            <section class="item-details">
                <h2>商品の情報</h2>
                <div class="item-details-container">
                    <div class="item-details-title">カテゴリー
                    </div>
                    <div class="item-details-category">
                        @foreach ($categories as $category)
                            <p>{{ $category->category_name }}</p>
                        @endforeach

                        {{-- <p>洋服</p>
          <p>メンズ</p>
          <p>洋服</p>
          <p>メンズ</p>
          <p>洋服</p>
          <p>メンズ</p>
          <p>洋服</p>
          <p>メンズ</p> --}}
                    </div>
                    <div class="item-details-title">商品の状態</div>
                    <div class="item-details-condition">
                        <p>{{ $condition->condition_name }}</p>
                        {{-- @foreach ($condition as $condition)
    <p>{{ $condition->condition_name }}</p> --}}
                        {{-- @endforeach --}}
                    </div>
                </div>
            </section>
            <section class="comments">

                <!-- コメント数を表示 -->
                <h2>コメント({{ $item->comments->count() }})</h2>

                <!-- コメント一覧 -->
                {{-- <div class="comment">
          <div class="comment-user-image"></div>
          <span class="comment-user-name">admin</span>
        </div>
         <div class="comment-box">
        <input type="text" class="comment-text" placeholder="こちらにコメントが入ります。">
      </div> --}}

                {{-- @foreach ($item->comments as $comment) --}}
                @foreach ($comments as $comment)
                    <div class="comment-user">
                        <img class="comment-user-image"
                            src="{{ asset('storage/photos/profile_images/' . $comment->user->profile_image) }}"
                            alt="">
                        <span class="comment-user-name">{{ $comment->user->name }}</span>
                    </div>
                    <div class="comment-box">
                        <p class="comment-text">{{ $comment->comment }}</p>
                    </div>
                @endforeach

                <!-- コメント投稿フォーム -->
                <form class="comment-form" action="{{ route('comment', ['itemId' => $item->id]) }}" method="POST">
                    @csrf
                    <label class="comment-textarea__label" for="comment">商品へのコメント</label>
                    {{-- エラーメッセージ --}}
                    @if ($errors->has('comment'))
                        <div class="error-message">
                            <ul>
                                @foreach ($errors->get('comment') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <textarea class="comment-textarea" name="comment" id="comment" rows="2" placeholder=""></textarea>
                    {{-- ログイン中のユーザーはコメントできる --}}
                    @auth
                        <button type="submit" class="comment-submit">コメントを送信する</button>
                    @endauth
                    {{-- ゲストはコメントできない --}}
                    @guest
                        <button class="comment-submit">コメントを送信する</button>
                    @endguest
                </form>
            </section>
        </div>
    </main>
</body>

</html>
