<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    /**
     * 一括書き込みを許可する属性
     * img_urlを追加し、プロフィール画像パスを保存可能にしています
     */
    protected $fillable = [
        'user_id',
        'img_url',
        'postal_code',
        'address',
        'building',
    ];

    /**
     * ユーザーとのリレーション（多対1）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}