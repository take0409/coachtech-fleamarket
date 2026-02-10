<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;

class ItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 不要なテストデータの削除（現在のデータを整理）
        // 「テスト商品」や「あ」という名前のアイテムをDBから削除します
        Item::whereIn('name', ['テスト商品', 'あ'])->delete();

        // 2. 出品者ユーザーの確保
        $user = User::where('email', 'test@example.com')->first() ?: User::create([
            'name' => 'Takeru',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 3. 全10カテゴリーのマスター作成
        $categoriesList = [
            'ファッション', '家電', 'インテリア', 'レディース', 'メンズ',
            'コスメ', '本', 'ゲーム', 'おもちゃ', 'その他'
        ];

        $catModels = [];
        foreach ($categoriesList as $name) {
            $catModels[$name] = Category::firstOrCreate(['name' => $name]);
        }

        // 4. 本物の10品データ
        $items = [
            ['name' => '腕時計', 'price' => 15000, 'brand' => 'Rolax', 'description' => 'メンズ腕時計', 'condition' => '良好', 'img_url' => 'img/watch.jpg', 'cats' => ['ファッション', 'メンズ']],
            ['name' => 'HDD', 'price' => 5000, 'brand' => '西芝', 'description' => '高速ハードディスク', 'condition' => '目立った傷や汚れなし', 'img_url' => 'img/hdd.jpg', 'cats' => ['家電']],
            ['name' => '玉ねぎ3束', 'price' => 300, 'brand' => 'なし', 'description' => '新鮮な玉ねぎ', 'condition' => 'やや傷や汚れあり', 'img_url' => 'img/onion.jpg', 'cats' => ['その他']],
            ['name' => '革靴', 'price' => 4000, 'brand' => null, 'description' => 'クラシックな革靴', 'condition' => '状態が悪い', 'img_url' => 'img/shoes.jpg', 'cats' => ['ファッション', 'メンズ']],
            ['name' => 'ノートPC', 'price' => 45000, 'brand' => null, 'description' => '高性能ノートPC', 'condition' => '良好', 'img_url' => 'img/pc.jpg', 'cats' => ['家電']],
            ['name' => 'マイク', 'price' => 8000, 'brand' => 'なし', 'description' => 'レコーディング用マイク', 'condition' => '目立った傷や汚れなし', 'img_url' => 'img/mic.jpg', 'cats' => ['家電', 'その他']],
            ['name' => 'ショルダーバッグ', 'price' => 3500, 'brand' => null, 'description' => 'おしゃれなバッグ', 'condition' => 'やや傷や汚れあり', 'img_url' => 'img/bag.jpg', 'cats' => ['ファッション', 'レディース']],
            ['name' => 'タンブラー', 'price' => 500, 'brand' => 'なし', 'description' => '使いやすいタンブラー', 'condition' => '状態が悪い', 'img_url' => 'img/tumbler.jpg', 'cats' => ['インテリア']],
            ['name' => 'コーヒーミル', 'price' => 4000, 'brand' => 'Starbacks', 'description' => '手動のコーヒーミル', 'condition' => '良好', 'img_url' => 'img/coffee.jpg', 'cats' => ['インテリア']],
            ['name' => 'メイクセット', 'price' => 2500, 'brand' => null, 'description' => '便利なメイクセット', 'condition' => '目立った傷や汚れなし', 'img_url' => 'img/makeup.jpg', 'cats' => ['コスメ', 'レディース']],
        ];

        // 5. 保存と紐付け
        foreach ($items as $data) {
            $targetCats = $data['cats'];
            unset($data['cats']);

            $item = Item::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['user_id' => $user->id])
            );

            $syncIds = [];
            foreach ($targetCats as $catName) {
                $syncIds[] = $catModels[$catName]->id;
            }
            $item->categories()->syncWithoutDetaching($syncIds);
        }

        // 6. 「腕時計」を購入済み（SOLD）にする
        $watch = Item::where('name', '腕時計')->first();
        if ($watch) {
            OrderItem::firstOrCreate([
                'user_id' => $user->id,
                'item_id' => $watch->id,
            ]);
        }
    }
}