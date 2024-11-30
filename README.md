# フリマアプリ
以下の手順に従って、Laravelアプリケーションのセットアップを行ってください。

## 環境構築

### 1. インストールディレクトリへの移動
まず、プロジェクトをインストールするディレクトリに移動します。

```bash
$ cd coachtech/laravel
```

### 2. リポジトリのクローン
次に、リポジトリをクローンします。

```bash
$ git clone git@github.com:takashimomose/mock-project01.git
```

### 3. Dockerコンテナの作成
クローンしたプロジェクトディレクトリへ移動し、Dockerコンテナをビルドおよび起動します。

```bash
$ cd mock-project01
$ docker-compose up -d --build
```

### 4. PHPコンテナへのアクセス
PHPコンテナに入るには、以下のコマンドを実行します。

```bash
$ docker-compose exec php bash
```

### 5. Composerのインストール
コンテナ内でComposerの依存関係をインストールします。

```bash
$ composer install
```

### 6. .envファイルの作成
既に存在している.env.exampleファイルを利用して.envファイルを作成します。以下のコマンドを実行して.envファイルを作成してください。

```bash
$ cp .env.example .env
```

### 7. .envファイルの編集
.envファイルを編集し、DB接続情報を以下のように設定してください。

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
同様に.envファイル内のメール接続情報を以下のように設定してください。
MAIL_USERNAMEとMAIL_PASSWORDは自身のMailtrapアカウントのダッシュボードで確認し、入力します。
Mailtrapダッシュボード：https://mailtrap.io/home

```bash
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=あなたのメールアドレス
MAIL_PASSWORD=あなたのメールアカウントのパスワード
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=mock-project01@example.com
MAIL_FROM_NAME="${APP_NAME}"
```
続いて、StripeのAPIキーを以下のように設定してください。
APIキーは自身のStripeアカウントのダッシュボードで確認し、入力します。
Stripeダッシュボード：https://dashboard.stripe.com/test/apikeys

```bash
STRIPE_KEY=あなたのPublishable key
STRIPE_SECRET=あなたのSecret key
```

### 8. データベースの確認
docker-compose.ymlで設定したphpMyAdminにアクセスし、データベースが存在しているかを確認します。

phpMyAdmin URL: http://localhost:8080/

### 9. アプリケーションキーの生成
以下のコマンドを実行してアプリケーションキーを生成してください。

```bash
$ php artisan key:generate
```

### 10. DB内にテーブルを作成
以下のコマンドを実行してDBにテーブルを作成してください。

```bash
$ php artisan migrate
```

### 11. DBテーブルにデータを挿入
以下のコマンドを実行してDBテーブルにデータを挿入してください。

```bash
$ php artisan db:seed
```

### 12. アプリケーションへのアクセス
アプリケーションにアクセスするには、以下のURLにアクセスします。

アプリケーション URL: http://localhost/
もしアプリケーションにアクセスできない場合、以下のコマンドを実行してパーミッションを修正してください。

```bash
$ sudo chmod -R 777 src/*
```

### 13. ストレージのシンボリックリンクを設定
プロフィール画像、商品画像が格納されているstorage/app/public内のファイルを公開するためにpublic/storageディレクトリにシンボリックリンクを設定してください。

```bash
$ php artisan storage:link
```

### 14.再度動作確認
再度ブラウザで以下にアクセスし、フリマアプリのトップ画面（商品一覧画面）が正しく表示されていることを確認してください。
http://localhost/

以下のテストユーザーがDBに作成済みのためログインに使用可能です。

```bash
テストユーザー1
メールアドレス：testuser1@example.com
パスワード：12345678

テストユーザー2
メールアドレス：testuser2@example.com
パスワード：12345678
```

以上でセットアップは完了です。

## 使用技術
- PHP 7.4.9
- Laravel 8.83.27
- MySQL 15.1

## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/

## その他
- 商品購入時にStripeによるカード払いのシミュレーションに使用できるカード情報は以下です。

```bash
カード番号: 4242 4242 4242 4242
有効期限: 任意の未来日（例: 12/34）
CVC: 任意の3桁の数字（例: 123）
```

- 未実装（実装途中となってしまった）のPHP Unit テスト

```bash
Stripe支払い画面への遷移: PaymentTest.php
Stripe支払い画面での購入：PurchaseTest.php
```

