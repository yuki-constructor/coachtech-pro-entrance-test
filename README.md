# アプリケーション名

coachtechフリマ

# 環境構築

## ⓵ リポジトリをクローン

以下のコマンドで、Git リポジトリをクローンします。

$ git clone git@github.com:yuki-constructor/coachtech-mock-case1-2.git

## ⓶.env ファイルの作成

以下のコマンドで、 src ディレクトリに移動し、.env.example を .env にコピーします。

$ cd coachtech-mock-case1-2/src/

$ cp .env.example .env


.env ファイルを開いて、以下の設定を変更します。


APP_TIMEZONE=Asia/Tokyo

APP_LOCALE=ja


DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DDB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass


MAIL_MAILER=smtp 

MAIL_HOST=mailpit 

MAIL_PORT=1025 

MAIL_USERNAME=null 

MAIL_PASSWORD=null 

MAIL_ENCRYPTION=null 

MAIL_FROM_ADDRESS=hello@example.com 

MAIL_FROM_NAME="${APP_NAME}" 


## ⓷Docker コンテナのビルドと起動

以下のコマンドで、Docker コンテナを起動します。

$ docker-compose up --build -d

## ⓸PHP コンテナ内にログイン

以下のコマンドで、PHP コンテナに接続します。

$ docker-compose exec php bash

## ⓹composer のインストール

以下のコマンドで、composer をインストールします。

$ composer install

## ⓺ アプリケーションキーの生成

以下のコマンドで、Laravel のアプリケーションキーを生成します。

$ php artisan key:generate

## ⓻ シンボリックリンクを設定

以下のコマンドで、画像を公開ディレクトリからアクセス可能にするために、シンボリックリンクを設定します。

$ php artisan storage:link

## ⓼ データベースのマイグレーション

以下のコマンドで、データベースをセットアップするために、マイグレーションを実行します。

$ php artisan migrate

 INFO  Nothing to migrate.と表示された場合は、次に進んでください。

## ⑨phpMyAdmin の動作確認

<http://localhost:8080> にアクセスすることで、phpMyAdmin を確認できます。

## ⓾ データベースのシーディング

以下のコマンドで、データベースにサンプルデータを挿入するためにシーディングを実行します。

$ php artisan migrate:fresh --seed

## ⑪ アプリケーションの動作確認

<http://localhost/items> にアクセスすることで、アプリケーションが動作していることを確認できます。

もし、エラーとなった場合、ルートディレクトリで以下のコマンドを実行し、ディレクトリ書き込み権限を設定することで改善するか確認してください。

sudo chmod -R 777 src/_　

（PHP コンテナ内に入っている場合は、以下を実行）

chmod -R 777 www/._ 

## ⑫Mailpit の設定

### ⑫-1. 　 .env ファイルの修正

⓶.env ファイルの作成にて完了済み。

### ⑫-2. 　 config/mail.php ファイルにて Mailpit の設定

以下のように設定を確認・修正してください。

return [

    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 1025),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Laravel'),
    ],

    'markdown' => [
        'theme' => 'default',

        'paths' => [
             resource_path('views/emails'), // カスタムビュー用ディレクトリ
        resource_path('views/vendor/mail'), // デフォルトのオーバーライド用
          ],

],

];

以下のコマンドを実行  
$ php artisan config:clear   
$ php artisan cache:clear

### ⑫-3. 　 Mailpit の動作確認

<http://localhost:8025> にアクセスすることで、Mailpit を確認できます。

## ⑬Stripe の設定

### ⑬- 1. 　 Stripe アカウントの作成

<https://dashboard.stripe.com/register>　にアクセスし、Stripe アカウントを作成します。  
Stripe アカウントを作成した後、以下の２点を行ってください。

１．Stripe のダッシュボードで「コンビニ払い」を有効化
設定（ダッシュボード右上の歯車マーク）＞製品の設定＞決済＞決済手段＞店舗支払い＞コンビニ決済＞有効にする

２．Stripe のダッシュボードで、テスト用の公開可能キー（publishable_key）と秘密キー（secret_key）を確認

### ⑬- 2.　テスト用の公開可能キー（publishable_key）と秘密キー（secret_key）を.env ファイルに設定

.env ファイル

STRIPE_PUBLIC_KEY=your_test_stripe_public_key
STRIPE_SECRET_KEY=your_test_stripe_secret_key

以下のコマンドを実行  
$ php artisan config:clear  
$ php artisan cache:clear  

### ⑬-3. 　 Stripe パッケージをインストール

以下のコマンドで、tripe 用のパッケージをインストールします。

$ composer require stripe/stripe-php

## ⑭ Fortify の設定

### ⑭-1. 　 Fortify パッケージのインストール

以下のコマンドで、 Fortify 用のパッケージをインストールします。

$composer require laravel/fortify

### ⑭-2. 　 Fortify の設定ファイルの公開

以下のコマンドで、設定ファイルとリソースを公開します。

$ php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider" --tag=config

これで、config/fortify.php という設定ファイルが作成されます。

### ⑭-3. 　 Fortify のサービスプロバイダを登録

Laravel 11 では、Fortify はデフォルトでサービスプロバイダが登録されています。

# アプリケーション使用時の注意点

## ホーム画面のURLについて
ホーム画面のURLは、<http://localhost/items>です。

## ログインの試行について

### ログイン時に必要なメールアドレスとパスワード
シーディングによって、サンプルユーザーが10人作成されます。

メールアドレス：<http://localhost:8080> にアクセスし、phpMyAdmin で確認できます。

パスワード：すべてのサンプルユーザーのパスワードは「123456789」で統一しています。

### メール認証について
ログイン時に認証メールが届きます。

<http://localhost:8025> にアクセスし、Mailpit でメールを確認できます。

届いた認証メール本文の認証リンクをクリックすると、ログインが完了します。

## 購入処理の試行について

支払い方法を選択し、「購入する」ボタンを押すと、Stripeの決済画面に遷移します。

カード払いの場合：<https://docs.stripe.com/testing?testing-method=card-numbers#cards>に記載の、テストカード情報を入力してください。

コンビニ払いの場合：環境構築＞⑬Stripe の設定＞⑬- 1. Stripe アカウントの作成＞
１．Stripe のダッシュボードで「コンビニ払い」を有効化　を行っていないと、コンビニ払いができません。

また、コンビニ払いは、支払いの完了が確認できないため、Stripeの決済画面で手続きが終わっても、購入完了とならず、画面遷移しません。


# 使用技術(実行環境)

Laravel Framework 11.3.2  
PHP 8.2 以上  
Mailpit  
Fortify  
Stripe 16.4  

# ER 図

![er](https://github.com/user-attachments/assets/04f934aa-53fb-4c79-aecf-1e7b9ac7fe95)

# URL

開発環境：[git@github.com:yuki-constructor/coachtech-mock-case1-2.git](https://github.com/yuki-constructor/coachtech-mock-case1-2.git)
# coachtech-pro-entrance-test
# coachtech-pro-entrance-test
