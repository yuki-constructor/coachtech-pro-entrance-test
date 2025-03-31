<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>取引完了の通知</title>
</head>

<body>
    <p>{{ $buyer->name }} さんが下記の取引を完了し、{{ $seller->name }} さんを評価しました。</p>
    <p>内容を確認し、{{ $buyer->name }} さんの評価をお願いします。</p>

    <h3>【取引内容】</h3>
    <hr>
    <p><strong>購入者のお名前:</strong> {{ $buyer->name }} さん</p>
    <p><strong>取引商品:</strong> {{ $transaction->purchase->item->item_name }}</p>
    <p><strong>取引完了日:</strong> {{ $transaction->buyer_completed_at }}</p>
    <hr>

    <p>※このメールは配信専用のアドレスで送信されています。</p>
    <p>このメールに返信されても確認・返信できませんので、ご了承ください。</p>
</body>

</html>
