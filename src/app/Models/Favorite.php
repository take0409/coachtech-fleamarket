<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * 一括保存を許可する属性
     * これにより、Favorite::create(['user_id' => ..., 'item_id' => ...]) が可能になります。
     */
    protected $fillable = [
        'user_id',
        'item_id',
    ];

    /**
     * お気に入りをしたユーザーとのリレーション (1対多の逆)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 対象の商品とのリレーション (1対多の逆)
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}