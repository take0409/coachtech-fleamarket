# 商品出品・購入プラットフォーム「COACHTECH フリマ」

本プロジェクトは、特定のユーザー間で商品を売買できる、Figmaのデザイン案に完全準拠したフリーマーケット形式のWebアプリケーションです。

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
* **Language**: PHP 8.2.30
* **Framework**: Laravel 10.x / 11.x
* **Database**: MySQL 8.x
* **Infrastructure**: Docker / Docker Compose

## 4. データベース設計（ER図）
システムのデータ構造とリレーションシップを可視化した図解です。

```mermaid
erDiagram
    users ||--o| profiles : "プロフィール保持"
    users ||--o{ items : "商品を出品"
    users ||--o{ order_items : "商品を注文"
    users ||--o{ favorites : "お気に入り登録"
    users ||--o{ comments : "コメント投稿"
    
    items ||--o| order_items : "注文確定(SOLD)"
    items ||--o{ item_category : "カテゴリ紐付け"
    categories ||--o{ item_category : "カテゴリ紐付け"
    items ||--o{ favorites : "被お気に入り"
    items ||--o{ comments : "被コメント"

    users {
        bigint id PK
        string name "ユーザー名"
        string email "メールアドレス"
        string password "パスワード"
        timestamp email_verified_at "メール認証日時"
    }
    profiles {
        bigint id PK
        bigint user_id FK "ユーザーID"
        string post_code "郵便番号"
        string address "住所"
        string building "建物名"
        string img_url "プロフィール画像URL"
    }
    items {
        bigint id PK
        bigint user_id FK "出品者ID"
        string name "商品名"
        string brand "ブランド名"
        integer price "価格"
        text description "商品説明"
        string condition "商品の状態"
        string img_url "商品画像URL"
    }
    categories {
        bigint id PK
        string name "カテゴリ名"
    }
    order_items {
        bigint id PK
        bigint user_id FK "購入者ID"
        bigint item_id FK "商品ID"
        string payment_method "支払い方法"
    }
    favorites {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
    }
    comments {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        text content "コメント内容"
    }