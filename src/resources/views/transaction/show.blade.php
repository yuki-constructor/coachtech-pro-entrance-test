<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>取引チャット画面</title>
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

    <main class="chat-container">

        <div class="sidebar">
            <span class="sidebar__title">その他の取引</span>
            <ul>
                @foreach ($otherTransactions as $other)
                    <li class="sidebar__list">
                        <a href="{{ route('transaction.show', ['transactionId' => $other->id]) }}"
                            onclick="saveDraftMessage({{ $transaction->id }})">{{ $other->purchase->item->item_name }}</a>
                    </li>
                @endforeach
                {{-- @foreach ($otherTransactions as $other)
                    <li class="sidebar__list">
                        <a href="{{ route('transaction.show', ['transactionId' => $other->id]) }}"
                            ({{ $transaction->id }}) ">
                            {{ $other->purchase->item->item_name }}
                        </a>
                    </li>
 @endforeach --}}
            </ul>
        </div>

        <div class="chat-header">
            <div class="chat-header-left">
                <div class="partner-user">
                    <img class="partner-user-image"
                        src="{{ asset('storage/photos/profile_images/' . $partner->profile_image) }}" alt="">
                    <span class="partner-user-name">{{ $partner->name }}さんとの取引画面</span>
                </div>
            </div>
            <div class="chat-header-right">
                @if (Auth::id() === $transaction->purchase->user_id && $transaction->status == 0)
                    <form action="{{ route('transaction.complete', $transaction->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="complete-button">取引を完了する</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="item-info">
            <img class="item-image"
                src="{{ asset('storage/photos/item_images/' . $transaction->purchase->item->item_image) }}"
                alt="商品画像">
            <div class="item-details">
                <p class="item-name">{{ $transaction->purchase->item->item_name }}</p>
                <p class="item-price">{{ number_format($transaction->purchase->item->price) }} 円</p>
            </div>
        </div>

        <div class="chat">
            {{-- @foreach ($messages as $message)
                <div class="chat-message {{ $message->user_id === Auth::id() ? 'my-message' : 'partner-message' }}">
                    <div class="user-info">
                        <img src="{{ $message->user->profile_image ? asset('storage/photos/profile_images/' . $message->user->profile_image) : asset('images/default-avatar.png') }}"
                            alt="ユーザー画像">
                        <span>{{ $message->user->name }}</span>
                    </div>
                    <p>{{ $message->message }}</p>
                </div>
            @endforeach --}}

            {{-- <div class="chat-main">
                <div class="chat-partner">
                    <div class="chat-user">
                        <div class="chat-user-image"></div>
                        <span class="chat-user-name">ユーザー名</span>
                    </div>
                    <div class="chat-box">
                        <p class="chat-text">こちらにコメントが入ります。</p>
                    </div>
                </div>
                <div class="chat-myself">
                    <div class="chat-user">
                        <div class="chat-user-image"></div>
                        <span class="chat-user-name">ユーザー名</span>
                    </div>
                    <div class="chat-box">
                        <p class="chat-text">こちらにコメントが入ります。</p>
                    </div>
                </div>
            </div> --}}

            <div class="chat-main">
                @foreach ($messages as $message)
                    <div class="{{ $message['chatClass'] }}">
                        <div class="chat-user">
                            <div class="chat-user-image">
                                {{-- <img src="{{ $message['user']->profile_image ? asset('storage/photos/profile_images/' . $message['user']->profile_image) : asset('images/default-avatar.png') }}"
                                    alt="ユーザー画像"> --}}
                                <img src="{{ asset('storage/photos/profile_images/' . $message['user']->profile_image) }}"
                                    alt="">
                            </div>
                            <span class="chat-user-name">{{ $message['user']->name }}</span>
                        </div>
                        <div class="chat-box">
                            <p class="chat-text">{{ $message['message']->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>

        <div class="send-chat">
            {{-- エラー表示 --}}
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- チャット投稿 --}}
            <form class="send-chat__form"
                action="{{ route('transaction.message.store', ['transactionId' => $transaction->id]) }}" method="POST"
                enctype="multipart/form-data" onsubmit="clearDraftMessage({{ $transaction->id }})">
                @csrf
                <div class="send-chat__form--left">
                    <input id="chatMessage" class="send-chat__input" type="text" name="message"
                        placeholder="取引メッセージを記入してください" value="{{ session('draft_message') ?? '' }}">
                </div>
                <div class="send-chat__form--right">
                    {{-- <a href="" class="send-chat-add-img__btn">画像を追加</a> --}}
                    <label>画像を追加
                        <input type="file" name="chat_image" class="send-chat__file-input">
                    </label>
                    {{-- <button class="send-chat__btn" type="submit"><img class="send-chat__btn--img"
                            src="{{ asset('storage/photos/logo_images/send-btn.png') }}" alt=""></button> --}}
                    <button class="send-chat__btn" type="submit">
                        <img class="send-chat__btn--img" src="{{ asset('storage/photos/logo_images/send-btn.png') }}"
                            alt="送信">
                    </button>

                </div>
            </form>
        </div>

        <script>
            // メッセージをLocalStorageに保存
            function saveDraftMessage(transactionId) {
                const message = document.getElementById('chatMessage').value;
                if (message.trim() !== '') {
                    localStorage.setItem(`draft_message_${transactionId}`, message);
                }
            }

            // メッセージをLocalStorageから取得
            function loadDraftMessage(transactionId) {
                const draft = localStorage.getItem(`draft_message_${transactionId}`);
                if (draft) {
                    document.getElementById('chatMessage').value = draft;
                }
            }

            // メッセージをLocalStorageから削除
            function clearDraftMessage(transactionId) {
                localStorage.removeItem(`draft_message_${transactionId}`);
            }

            document.addEventListener('DOMContentLoaded', function() {
                loadDraftMessage({{ $transaction->id }});
            });
        </script>

    </main>
</body>

</html>
