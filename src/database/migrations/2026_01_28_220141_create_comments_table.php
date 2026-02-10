<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // すでにテーブルが存在する場合は作成しない（二重作成防止）
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                // 外部キー：誰が投稿したか（ユーザーが削除されたらコメントも消える）
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                // 外部キー：どの商品への投稿か（商品が削除されたらコメントも消える）
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                // コメント本文
                $table->text('comment');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};