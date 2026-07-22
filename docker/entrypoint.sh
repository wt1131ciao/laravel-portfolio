#!/bin/sh
set -e

# Cloud Run は PORT 環境変数でリッスンポートを指定してくる
PORT="${PORT:-8080}"
sed -ri "s/Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-enabled/000-default.conf

php artisan config:clear

# SQLite はコンテナのローカルディスク上にあり、インスタンスが再起動するたびに
# イメージ内の空ファイルに戻る。起動のたびにマイグレーション＋シードを実行し、
# 常にスキーマとデモデータが揃った状態にする。
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force

chown -R www-data:www-data storage bootstrap/cache database

exec "$@"
