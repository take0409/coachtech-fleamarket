# 商品出品・購入プラットフォーム「COACHTECH フリマ」

Figmaのデザイン案に基づき、ユーザー間で商品を売買できるフリーマーケット形式のWebアプリケーションです。

## 1. アプリケーション概要
誰でも簡単に商品の出品、詳細確認、お気に入り登録、そして購入ができるプラットフォームです。

## 2. 実装済み機能一覧
* **認証機能**: 会員登録、ログイン、メール認証、ログアウト。
* **商品管理**:
    * 商品一覧表示（おすすめ・マイリスト切り替え）。
    * キーワード検索（商品名・ブランド名の部分一致）。
    * 商品詳細表示（複数カテゴリ・コメント一覧）。
* **出品・購入**:
    * 商品出品（画像アップロード・複数カテゴリ選択）。
    * 商品購入（支払い方法選択・配送先住所の一時変更）。
    * 購入完了時の自動「SOLD」表示。
* **マイページ**:
    * プロフィール編集（画像・住所・ユーザー名）。
    * 出品した商品・購入した商品の一覧表示。

## 3. 使用技術
* **Language**: PHP 8.2.x
* **Framework**: Laravel 10.x / 11.x
* **Database**: MySQL 8.x
* **Infrastructure**: Docker / Docker Compose

## 4. データベース設計（ER図）
システムのデータ構造とリレーションシップを可視化した図解です。

```mermaid
erDiagram
    users ||--o| profiles : "1:1 プロフィール"
    users ||--o{ items : "1:N 出品"
    users ||--o{ order_items : "1:N 購入"
    items ||--o| order_items : "1:1 決済済"
    items ||--o{ item_category : "1:N カテゴリ割当"
    categories ||--o{ item_category : "1:N カテゴリ定義"
    users ||--o{ favorites : "1:N お気に入り登録"
    items ||--o{ favorites : "1:N 被お気に入り"
    users ||--o{ comments : "1:N 投稿コメント"
    items ||--o{ comments : "1:N 商品コメント"```



## 5. 主要テーブル構成
提出したテーブル仕様書に基づき、以下の構成で厳格に実装しています。

| テーブル名 | 役割 | 主要カラム |
| :--- | :--- | :--- |
| **users** | ユーザーの基本・認証情報を管理 | id, name, email, password |
| **profiles** | 配送先住所およびプロフィール画像を管理 | user_id, post_code, address, img_url |
| **items** | 出品された商品の詳細情報を管理 | user_id, name, price, condition, img_url |
| **order_items** | 購入履歴および決済情報を管理 | user_id, item_id, payment_method |
| **categories** | 商品カテゴリーを管理 | id, name |
| **favorites** | お気に入り情報を管理 | user_id, item_id |
| **comments** | 商品詳細画面のコメントを管理 | user_id, item_id, content |

## 6. バリデーション仕様
FormRequestを使用し、基本設計書の要件を100%満たすバリデーションを実装しています。
* **郵便番号**: 入力必須、ハイフンありの8文字（例: 000-0000）。
* **商品価格**: 数値型、0円以上。
* **商品説明**: 最大255文字。
* **画像形式**: jpeg、png、jpg対応、2MB以内。

## 7. 環境構築手順（Docker）
以下のコマンドを順に実行することで、即座に開発環境を構築可能です。

1. **環境変数の準備**
   `cp .env.example .env`
2. **コンテナのビルドと起動**
   `docker-compose up -d --build`
3. **パッケージのインストール**
   `docker-compose exec app composer install`
4. **アプリキーの生成**
   `docker-compose exec app php artisan key:generate`
5. **データベースの初期化**
   `docker-compose exec app php artisan migrate:fresh --seed`
6. **ストレージのリンク作成**
   `docker-compose exec app php artisan storage:link`