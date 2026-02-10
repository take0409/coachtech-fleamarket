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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            // ユーザーと紐付けるための外部キー（ユーザーが削除されたらプロフィールも消える設定）
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // プロフィール画像（Figmaに画像設定があるため）
            $table->string('img_url')->nullable();
            
            // 配送先情報
            $table->string('postal_code')->nullable(); // 郵便番号
            $table->string('address')->nullable();     // 住所
            $table->string('building')->nullable();    // 建物名
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};