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
    users ||--o| profiles : "1:1 user_id"
    users ||--o{ items : "1:N user_id"
    users ||--o{ order_items : "1:N user_id"
    items ||--o| order_items : "1:1 item_id"
    items ||--o{ item_category : "1:N item_id"
    categories ||--o{ item_category : "1:N category_id"
    users ||--o{ favorites : "1:N user_id"
    items ||--o{ favorites : "1:N item_id"
    users ||--o{ comments : "1:N user_id"
    items ||--o{ comments : "1:N item_id"

    users {
        bigint id PK
        string name
        string email
        string password
    }
    items {
        bigint id PK
        bigint user_id FK
        string name
        integer price
        text description
        string img_url
        string condition
    }
    profiles {
        bigint id PK
        bigint user_id FK
        string post_code
        string address
        string img_url
    }
    favorites {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
    }
    order_items {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        string payment_method
    }