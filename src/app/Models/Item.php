<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth; // 追加

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'price',
        'description',
        'img_url',
        'condition',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 注文データとのリレーション
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItem(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isFavoritedBy($user): bool
    {
        if (!$user) return false;
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    /**
     * 【最適化版】売り切れ判定
     * ログインしているユーザー自身が購入した商品のみ「SOLD」とする設定。
     * これにより、新しいメアド（新規ユーザー）でログインした際は
     * 過去の他人の購入履歴が無視され、全ての商品がフラットな状態になります。
     */
    public function isSold(): bool
    {
        $user = Auth::user();
        
        // ログインしていない場合はSOLDを表示しない
        if (!$user) {
            return false;
        }

        // ログイン中のユーザーIDに紐づく注文データが存在するかチェック
        return $this->orderItems()->where('user_id', $user->id)->exists();
    }
}