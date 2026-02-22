<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'price' => fake()->numberBetween(100, 10000),
            'description' => fake()->realText(50),
            'condition' => '良好',
            // image は items テーブルに無いようなので削除し、必要そうな img_url に変更
            'img_url' => 'default.jpg',
            // brand など、他にも必須カラムがあればここに追加しますが、まずはこれで試します
        ];
    }
}