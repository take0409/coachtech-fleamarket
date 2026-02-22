# 1. README.md を模範解答の形式で一気に上書き作成
cat << 'EOF' > README.md
# COACHTECH フリマ

## 環境構築
**Dockerビルド**
1. \`git clone https://github.com/take0409/coachtech-fleamarket.git\`
2. DockerDesktopアプリを立ち上げる
3. \`docker-compose up -d --build\`

> *MacのM1・M2チップのPCの場合、\`no matching manifest for linux/arm64/v8 in the manifest list entries\`のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「db」内に「platform」の項目を追加で記載してください*
\`\`\` bash
db:
    platform: linux/x86_64
    image: mysql:8.0.26
    environment:
\`\`\`

**Laravel環境構築**
1. \`docker-compose exec app bash\`
2. \`composer install\`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
\`\`\` text
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=phper
DB_PASSWORD=password

# Stripe決済設定
STRIPE_KEY=pk_test_...（ご自身の公開鍵）
STRIPE_SECRET=sk_test_...（ご自身のシークレットキー）
\`\`\`
5. アプリケーションキーの作成
\`\`\` bash
php artisan key:generate
\`\`\`

6. ストレージリンクの作成
\`\`\` bash
php artisan storage:link
\`\`\`

7. マイグレーションの実行
\`\`\` bash
php artisan migrate
\`\`\`

8. シーディングの実行
\`\`\` bash
php artisan db:seed
\`\`\`

9. フロントエンドのビルド
\`\`\` bash
npm install
npm run build
\`\`\`

## 使用技術(実行環境)
- PHP 8.2.x
- Laravel 11.x
- MySQL 8.x
- Docker / Docker Compose
- Stripe API (決済)

## ER図
\`\`\`mermaid
erDiagram
    users ||--|| profiles : "1:1"
    users ||--o{ items : "1:N (出品)"
    users ||--o{ order_items : "1:N (購入)"
    items ||--o{ order_items : "1:N (SOLD判定)"
    items }o--o{ categories : "N:M"
    users ||--o{ favorites : "1:N"
    users ||--o{ comments : "1:N"
\`\`\`

## URL
- 開発環境：http://localhost:8080/
- Mailpit：http://localhost:8025/
EOF

# 2. GitHubへ変更を送信
git add README.md
git commit -m "Update README to follow the official format"
git push origin main