# Cloud Run デプロイメントガイド

このドキュメントでは、laravel-portfolio を Google Cloud Run にデプロイする手順を説明します。
`koharu-blog` と同じ「GitHub Actions → Artifact Registry → Cloud Run」の構成を、Laravel向けに調整したものです。

## 構成上の判断

| 項目 | 内容 |
|---|---|
| Webサーバー | `php:8.3-apache`(mod_php)に1本化。ローカルのnginx+php-fpm 2コンテナ構成とは別物 |
| DB | SQLite。コストゼロで動かすためのトレードオフ(下記「既知の制約」を参照) |
| 台数 | `min-instances=0` / `max-instances=1` 固定 |

## 既知の制約(SQLiteを選んだことによるもの)

- Cloud Runのコンテナローカルディスクは**インスタンスが再起動すると消える**。デプロイのたびに、また無通信でインスタンスがゼロにスケールして次のリクエストで起動し直すたびに、DBはイメージに焼き込まれた空の状態＋起動時の`migrate`+`db:seed`でリセットされる。**注文データや在庫の変更は永続しない**、という前提のデモ環境。
- そのため`max-instances`は**必ず1**に固定している。2台以上に同時スケールすると、インスタンスごとに別々のSQLiteファイルを持つことになり、同じ注文が別インスタンドでは見えない、といった不整合が起きる。
- 本格的に本番運用するなら、Cloud SQL(MySQL)+ Unixソケット接続に切り替えるのが筋(月$7〜10程度の固定費が発生)。

## 初回セットアップ

### 1. Google Cloud プロジェクトの準備

```bash
gcloud auth login
gcloud config set project <YOUR_PROJECT_ID>

gcloud services enable run.googleapis.com
gcloud services enable artifactregistry.googleapis.com
```

### 2. Artifact Registry リポジトリの作成

```bash
gcloud artifacts repositories create laravel-portfolio \
    --repository-format=docker \
    --location=asia-northeast1 \
    --description="laravel-portfolio container images"
```

### 3. サービスアカウントの作成

```bash
gcloud iam service-accounts create github-actions \
    --display-name="GitHub Actions"

gcloud projects add-iam-policy-binding <YOUR_PROJECT_ID> \
    --member="serviceAccount:github-actions@<YOUR_PROJECT_ID>.iam.gserviceaccount.com" \
    --role="roles/run.admin"

gcloud projects add-iam-policy-binding <YOUR_PROJECT_ID> \
    --member="serviceAccount:github-actions@<YOUR_PROJECT_ID>.iam.gserviceaccount.com" \
    --role="roles/artifactregistry.writer"

gcloud projects add-iam-policy-binding <YOUR_PROJECT_ID> \
    --member="serviceAccount:github-actions@<YOUR_PROJECT_ID>.iam.gserviceaccount.com" \
    --role="roles/iam.serviceAccountUser"

# サービスアカウントキーを作成(JSON形式)
gcloud iam service-accounts keys create key.json \
    --iam-account=github-actions@<YOUR_PROJECT_ID>.iam.gserviceaccount.com
```

`key.json` はローカルに保存したらすぐGitHub Secretsに登録し、その後はコミットしないこと(`.gitignore`に`key.json`を追加済み)。

### 4. APP_KEY を発行する

本番用の`APP_KEY`は、CI環境ではなく手元で一度だけ生成し、固定値としてSecretsに保存する(デプロイのたびに変わると、実行中インスタンスの暗号化Cookie/セッションが壊れるため)。

```bash
php artisan key:generate --show
```

出力された`base64:...`をそのまま次のSecretsに登録する。

### 5. GitHub Secretsの設定

GitHubリポジトリの Settings → Secrets and variables → Actions で以下を登録:

| Secret名 | 内容 |
|---|---|
| `GCP_PROJECT_ID` | Google CloudプロジェクトID |
| `GCP_SA_KEY` | 手順3で作成した`key.json`の中身をそのまま貼り付け |
| `APP_KEY` | 手順4で発行した`base64:...`の値 |
| `STRIPE_KEY` | Stripeの公開可能キー(`pk_...`) |
| `STRIPE_SECRET` | Stripeのシークレットキー(`sk_...`) |
| `STRIPE_WEBHOOK_SECRET` | Cloud Run側のWebhookエンドポイントに対応する`whsec_...`(手順7参照) |

### 6. デプロイ

`main`ブランチにpushすると、テスト→ビルド→デプロイが自動実行される。

```bash
git add .
git commit -m "chore: Cloud Runへのデプロイ設定を追加"
git push origin main
```

### 7. StripeのWebhook設定を本番URLに合わせる

初回デプロイ後、サービスURLを確認する。

```bash
gcloud run services describe laravel-portfolio \
    --region=asia-northeast1 \
    --format='value(status.url)'
```

Stripeダッシュボード → 開発者 → Webhook で、上記URLの`/webhook/stripe`を新しいエンドポイントとして登録し、発行された署名シークレット(`whsec_...`)を`STRIPE_WEBHOOK_SECRET`としてGitHub Secretsに設定し直す。値を更新したら、再度`main`へpush(または空コミット)して反映する。

## デプロイ後の確認・デモ用アカウント

`DatabaseSeeder`が起動のたびに以下のユーザーを再作成する(パスワードはどちらも`password`)。

| メールアドレス | 権限 |
|---|---|
| `admin@example.com` | 管理者 |
| `test@example.com` | 一般ユーザー |

決済にはStripeのテストカード(`4242 4242 4242 4242`など、README参照)が使える。

## ローカルでのDockerビルドテスト

```bash
docker build -t laravel-portfolio .
docker run -p 8080:8080 \
    -e APP_KEY="$(php artisan key:generate --show)" \
    -e APP_ENV=production \
    -e STRIPE_KEY=pk_test_xxx \
    -e STRIPE_SECRET=sk_test_xxx \
    -e STRIPE_WEBHOOK_SECRET=whsec_xxx \
    laravel-portfolio

# http://localhost:8080 にアクセス
```

## トラブルシューティング

```bash
# Cloud Runのログを確認
gcloud run services logs read laravel-portfolio --region=asia-northeast1

# Artifact Registry のイメージ一覧
gcloud artifacts docker images list asia-northeast1-docker.pkg.dev/<YOUR_PROJECT_ID>/laravel-portfolio
```

## 参考リンク

- [Cloud Run ドキュメント](https://cloud.google.com/run/docs)
- [Cloud Run 料金](https://cloud.google.com/run/pricing)
- [Laravel デプロイメント](https://laravel.com/docs/deployment)
