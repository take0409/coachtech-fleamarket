<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 中間テーブル（商品とカテゴリーの紐付け）を作成
     */
    public function up(): void
    {
        // 重複エラーを避けるため、既存のテーブルがあれば削除してから作成
        Schema::dropIfExists('category_item');

        Schema::create('category_item', function (Blueprint $table) {
            $table->id();
            // 商品ID（親の商品が削除されたら、この紐付けレコードも自動削除される設定）
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            // カテゴリーID（親のカテゴリーが削除されたら、この紐付けレコードも自動削除される設定）
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * テーブルの削除
     */
    public function down(): void
    {
        Schema::dropIfExists('category_item');
    }
};