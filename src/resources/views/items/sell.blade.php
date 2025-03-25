<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>商品の出品</title>
    <link rel="stylesheet" href="{{ asset('css/items/sell.css') }}" />
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
                <h1 class="form-title">商品の出品</h1>
                <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <section class="form-section">
                        <!-- <h2 class="section-title-image">商品画像</h2> -->
                        <label class="form-label" for="item-image">商品画像
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('item_image'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('item_image') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="item-image">
                                <label>画像を選択する
                                    <input type="file" id="item-image" name="item_image" /></label>
                                <!-- <button class="upload-button" type="submit">画像を選択する</button> -->
                                <!-- <button>画像を選択する</button> -->
                            </div>
                        </label>
                    </section>

                    <section class="form-section">
                        <h2 class="section-title">商品の詳細</h2>
                        <div class="form-group">
                            <label for="item-category-tag" class="form-label">カテゴリー</label>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('categories'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('categories') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="item-category">
                                {{-- @foreach ($categories as $category)
<label
                    ><input
                      type="checkbox"
                      id=""
                      name="categories[]"
                      value="{{$category->id}}"
                    />{{$category->category_name}}</label
                  >
@endforeach --}}

                                {{-- @foreach ($categories as $category)
<label><input type="checkbox" name="categories[]" value="{{$category->id}}" @if (in_array($category->id, old('categories', $item->categories->pluck('id')->all()))) checked @endif>{{$category->category_name}}</label>
@endforeach --}}

                                @foreach ($categories as $category)
                                    <label><input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            @if (in_array($category->id, old('categories', []))) checked @endif>{{ $category->category_name }}</label>
                                @endforeach
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="item-condition" class="form-label">商品の状態</label>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('condition'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('condition') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <select id="item-condition" class="item-condition__select" name="condition">
                                <option value="" selected hidden>選択してください</option>
                                @foreach ($conditions as $condition)
                                    <option value="{{ $condition->id }}"
                                        @if (old('condition') == $condition->id) selected @endif>
                                        {{ $condition->condition_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </section>

                    <section class="form-section">
                        <h2 class="section-title">商品名と説明</h2>
                        <div class="form-group">
                            <label for="item-name" class="form-label">商品名</label>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('item_name'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('item_name') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="text" name="item_name" id="item-name" class="item-name__input"
                                value="{{ old('item_name') }}" />
                        </div>
                        <div class="form-group">
                            <label for="item-description" class="form-label">商品の説明</label>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('description'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('description') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <textarea id="item-description" name="description" class="item-description__textarea" placeholder="">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="price" class="form-label">販売価格</label>
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('price'))
                                <div class="error-message">
                                    <ul>
                                        @foreach ($errors->get('price') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="number" id="item-price" name="price" class="item-price__input"
                                placeholder="¥" value="{{ old('price') }}" />
                        </div>
                    </section>

                    <button class="submit-button">出品する</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
