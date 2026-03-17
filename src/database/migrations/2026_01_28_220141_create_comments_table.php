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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // 外部キー：誰が投稿したか
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // 外部キー：どの商品への投稿か
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            
            // コメント本文（Controller/Requestと合わせて content に統一）
            $table->text('content'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};