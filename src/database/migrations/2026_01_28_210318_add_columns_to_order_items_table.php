<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. favoritesテーブルを作成
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('item_id');
                $table->timestamps();
            });
        }

        // 2. order_itemsに不足しているカラムを追加
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('order_items', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};