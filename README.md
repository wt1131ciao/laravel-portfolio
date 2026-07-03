# Laravel Portfolio

PHP 8.3 + Laravel 13 で構築したポートフォリオアプリケーションです。

## 技術スタック

| 種別 | 技術 |
|---|---|
| バックエンド | PHP 8.3 / Laravel 13 |
| データベース | MySQL 8.0 |
| キャッシュ / キュー | Redis 6 |
| Web サーバー | Nginx |
| コンテナ | Docker / Docker Compose |

## 必要な環境

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)

## 初期構築手順 

### 1. リポジトリをクローン

```bash
git clone https://github.com/takahashi/laravel-portfolio.git
cd laravel-portfolio
```

### 2. 環境変数ファイルを作成

```bash
cp .env.example .env
```

`.env` の DB / Redis 設定が以下になっていることを確認してください（Docker サービス名を使います）：

```env
DB_HOST=db
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

REDIS_HOST=redis
```

### 3. コンテナを起動

```bash
docker compose up -d
```

### 4. Composer パッケージをインストール

```bash
docker compose exec app composer install
```

### 5. アプリケーションキーを生成

```bash
docker compose exec app php artisan key:generate
```

### 6. マイグレーションを実行

```bash
docker compose exec app php artisan migrate
```

### 7. ブラウザで確認

[http://localhost](http://localhost) にアクセス

---

## 開発中のよく使うコマンド

```bash
# コンテナ起動
docker compose up -d

# コンテナ停止
docker compose down

# マイグレーション
docker compose exec app php artisan migrate

# シーダー実行
docker compose exec app php artisan db:seed

# キャッシュクリア
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# テスト実行
docker compose exec app php artisan test

# Tinker（対話型 REPL）
docker compose exec app php artisan tinker
```

## コンテナ構成

| サービス名 | 役割 | ポート |
|---|---|---|
| `app` | PHP-FPM | - |
| `web` | Nginx | 80 |
| `db` | MySQL 8.0 | 3306 |
| `redis` | Redis 6 | 6379 |



# Stripe

## 用意されているテストカード一覧
https://docs.stripe.com/testing?locale=ja-JP

| カード番号 | 数字 | 
|---|---|
|Visa	|4242424242424242 |
|Visa (デビット)	|4000056655665556 |
|Mastercard	|5555555555554444 |