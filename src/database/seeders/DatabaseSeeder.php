<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * 1. 商品データと関連ユーザーを登録
         * ItemsTableSeederの中で 'test@example.com' ユーザーも作成されるため、
         * ここでは重複エラーを避けるために個別のUser作成（User::factory）は行いません。
         */
        $this->call([
            ItemsTableSeeder::class,
        ]);
    }
}