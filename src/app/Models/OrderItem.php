<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // この行を追加して、保存を許可します
    protected $fillable = ['user_id', 'item_id'];

    /**
     * 商品とのリレーション
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}