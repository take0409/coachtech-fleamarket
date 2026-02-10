<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // 出品者との紐付け
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('name');             // 商品名
            $table->integer('price');           // 販売価格
            $table->text('description');        // 商品の説明
            $table->string('brand')->nullable(); // ブランド名（任意項目）
            $table->string('condition');        // 商品の状態（良好、目立った傷や汚れなし等）
            $table->string('img_url');          // 商品画像パス（storage内保存用）
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};