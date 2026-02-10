<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 既存のテーブルにカラムを追加します
     */
    public function up(): void
    {
        // データの安全性を高めるため、dropIfExistsなどは絶対に書かない
        Schema::table('items', function (Blueprint $table) {
            // 例：まだテーブルに存在しないカラムを追加する場合
            // すでにデータが入っていることを考慮し、->nullable() をつけるのが安全です
            if (!Schema::hasColumn('items', 'brand')) {
                $table->string('brand')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     * 追加したカラムを削除します（ロールバック用）
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // 追加したカラムのみを削除するように指定
            if (Schema::hasColumn('items', 'brand')) {
                $table->dropColumn('brand');
            }
        });
    }
};