<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/transaction/show.css') }}" />
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
        <div class="chat-container">
            <div class="sidebar">
                <h3>その他の取引</h3>
                {{-- @foreach ($otherTransactions as $other)
                    <a href="{{ route('transaction.show', $other->id) }}" class="sidebar-item">
                        {{ $other->purchase->item->item_name }}
                    </a>
                @endforeach --}}
            </div>

            <div class="chat-header">
                <h2>{{ $partner->name }} さんとの取引</h2>
            </div>
            <div class="item-info">
                <img src="{{ asset('storage/photos/item_images/' . $transaction->purchase->item->item_image) }}"
                    alt="商品画像">
                <div>
                    <h3>{{ $transaction->purchase->item->item_name }}</h3>
                    <p>{{ number_format($transaction->purchase->item->price) }} 円</p>
                </div>
            </div>

            <div class="chat-main">
                @foreach ($messages as $message)
                    <div class="chat-message {{ $message->user_id === Auth::id() ? 'my-message' : 'partner-message' }}">
                        <div class="user-info">
                            <img src="{{ $message->user->profile_image ? asset('storage/photos/profile_images/' . $message->user->profile_image) : asset('images/default-avatar.png') }}"
                                alt="ユーザー画像">
                            <span>{{ $message->user->name }}</span>
                        </div>
                        <p>{{ $message->message }}</p>
                    </div>
                @endforeach
            {{-- </div> --}}
            {{-- <div class="chat-messages"> --}}
                <form action="{{ route('transaction.message.store', $transaction->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="message" placeholder="取引メッセージを記入してください" required>
                    <button type="submit">送信</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
