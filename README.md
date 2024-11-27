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
$ cd exam01
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

### 6. .envファイルの編集
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

### 7. データベースの確認
docker-compose.ymlで設定したphpMyAdminにアクセスし、データベースが存在しているかを確認します。

phpMyAdmin URL: http://localhost:8080/

### 8. アプリケーションへのアクセス
アプリケーションにアクセスするには、以下のURLにアクセスします。

アプリケーション URL: http://localhost/
もしアプリケーションにアクセスできない場合、以下のコマンドを実行してパーミッションを修正してください。

```bash
$ sudo chmod -R 777 src/*
```

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

### 12. ストレージのシンボリックリンクを設定
プロフィール画像、商品画像が格納されているstorage/app/public内のファイルを公開するためにpublic/storageディレクトリにシンボリックリンクを設定してください。

```bash
$ php artisan storage:link
```

### 13. 動作確認
最後に、ブラウザで以下にアクセスし、フリマアプリのトップ画面（商品一覧画面）が正しく表示されていることを確認してください。
http://localhost/

以上でセットアップは完了です。

## 使用技術
- PHP 7.4.9
- Laravel 8.83.27
- MySQL 15.1

## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/