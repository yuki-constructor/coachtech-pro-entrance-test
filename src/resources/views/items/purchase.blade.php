<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入確認</title>
    <link rel="stylesheet" href="{{ asset('css/items/purchase.css') }}">
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

    <main class="purchase-confirmation">
        <section class="item-info">
            <div class="item-image">
                <img class="image-placeholder" src="{{ asset('storage/photos/item_images/' . $item->item_image) }}"
                    alt="">
            </div>
            <div class="item-details">
                <h1 class="item-name">{{ $item->item_name }}</h1>
                <p class="item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </section>

        <section class="order-details">
            <form action="{{ route('item.purchase.payment', ['itemId' => $item->id]) }}" method="POST">
                @csrf
                <h2>支払い方法</h2>
                <div class="payment">
                    <select class="payment-method" name="payment_method">
                        <option value="" selected hidden>選択してください</option>
                        {{-- <option value="convenience_store">コンビニ支払い</option> --}}
                        <option value="konbini">コンビニ支払い</option>
                        <option value="card">カード支払い</option>
                    </select>
                </div>

                <div class="address">
                    <div class="address-address">
                        <h2>配送先</h2>
                        <p>〒 {{ $user->postal_code }}</p>
                        <p> {{ $user->address }}</p>
                        <p> {{ $user->building }}</p>
                    </div>
                    <div class="address-edit">
                        <a href="{{ route('profile.edit.address', ['itemId' => $item->id]) }}"
                            class="address-edit-btn">変更する</a>
                    </div>
                </div>
        </section>

        <section class="total-details">
            <div class="total-item border-next">
                <span>商品代金</span>
                <span class="total-details-content">¥ {{ number_format($item->price) }}</span>
            </div>
            <div class="total-item border-next">
                <span>支払い方法</span>
                <span class="total-details-content">○○払い</span>
            </div>
        </section>
        <section class="purchase">
            <button class="purchase-button">購入する</button>
            </form>
        </section>

    </main>
</body>

</html>
