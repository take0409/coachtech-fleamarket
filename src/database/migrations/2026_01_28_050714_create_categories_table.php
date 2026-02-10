<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * カテゴリテーブルの作成
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // カテゴリ名（ファッション、家電、インテリア等）
            $table->timestamps();
        });
    }

    /**
     * テーブルの削除
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};