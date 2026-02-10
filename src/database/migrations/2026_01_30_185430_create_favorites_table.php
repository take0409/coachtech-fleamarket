<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 既存のデータを壊さないよう、存在チェックを入れてから実行します。
     */
    public function up(): void
    {
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                // どのユーザーが「いいね」したか
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                // どの商品を「いいね」したか
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * 誤ってデータを消さないよう、デフォルトでは削除命令を無効化（コメントアウト）しています。
     */
    public function down(): void
    {
        // 開発中にリセットが必要な場合のみ、自己責任で以下のコメントを外してください。
        // Schema::dropIfExists('favorites');
    }
};