<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->name }} - 詳細</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; color: #333; }
        
        header { background-color: #000; padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; width: 100%; box-sizing: border-box; }
        .header-logo { height: 35px; }
        .search-bar { flex-grow: 1; margin: 0 40px; }
        .search-bar input { width: 100%; padding: 8px 15px; border-radius: 4px; border: none; outline: none; }
        .nav-links { display: flex; align-items: center; }
        .nav-links a, .nav-links button { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; background: none; border: none; cursor: pointer; }
        .sell-btn { background-color: #fff !important; color: #000 !important; padding: 8px 20px !important; border-radius: 4px !important; font-weight: bold; }

        .container { display: flex; max-width: 1000px; margin: 60px auto; padding: 0 40px; gap: 60px; }
        .item-image-box { flex: 1; text-align: center; }
        .item-image-box img { width: 100%; max-width: 450px; border-radius: 4px; object-fit: cover; aspect-ratio: 1/1; background-color: #eee; }
        
        .item-details { flex: 1; }
        .item-name { font-size: 32px; font-weight: bold; margin: 0 0 5px 0; }
        .item-brand { font-size: 18px; color: #333; margin-bottom: 15px; font-weight: 500; }
        .item-price { font-size: 28px; font-weight: bold; margin-bottom: 20px; }
        .item-price span { font-size: 18px; margin-left: 5px; }

        .action-section { display: flex; gap: 30px; margin-bottom: 25px; align-items: flex-start; }
        .action-item { text-align: center; background: none; border: none; padding: 0; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-decoration: none; color: inherit; }
        .action-icon { width: 32px; height: 32px; object-fit: contain; margin-bottom: 5px; }
        .action-count { font-size: 12px; color: #333; }
        
        .buy-btn { width: 100%; padding: 14px; background-color: #ff4b00; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-bottom: 40px; text-decoration: none; display: block; text-align: center; }
        .buy-btn:disabled { background-color: #888; cursor: not-allowed; }
        
        .section-title { font-size: 22px; font-weight: bold; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px; margin-top: 40px; }
        .description { line-height: 1.6; color: #333; white-space: pre-wrap; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info-table th { text-align: left; width: 120px; padding: 12px 0; vertical-align: top; border-bottom: 1px solid #f5f5f5; }
        .info-table td { padding: 12px 0; border-bottom: 1px solid #f5f5f5; }

        .category-tag { display: inline-block; background-color: #f5f5f5; color: #333; padding: 5px 15px; border-radius: 20px; font-size: 13px; margin-right: 8px; margin-bottom: 8px; }

        .comment-list { list-style: none; padding: 0; margin-bottom: 30px; }
        .comment-item { margin-bottom: 25px; }
        .comment-user { font-weight: bold; display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
        .user-icon { width: 36px; height: 36px; border-radius: 50%; background-color: #ddd; object-fit: cover; }
        .comment-text { background-color: #f5f5f5; padding: 15px; border-radius: 8px; line-height: 1.6; font-size: 14px; }
        .comment-textarea { width: 100%; height: 120px; padding: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: none; margin-bottom: 15px; font-size: 16px; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
        <div class="search-bar">
            <form action="{{ route('item.index') }}" method="GET">
                <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>
        </div>
        <nav class="nav-links">
            @auth
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf<button type="submit">ログアウト</button></form>
                <a href="{{ route('mypage.index') }}">マイページ</a>
                <a href="{{ route('item.create') }}" class="sell-btn">出品</a>
            @else
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('register') }}">会員登録</a>
                <a href="{{ route('login') }}" class="sell-btn">出品</a>
            @endauth
        </nav>
    </header>

    <div class="container">
        <div class="item-image-box">
            <img src="{{ asset($item->img_url) }}" onerror="this.src='{{ asset('img/coffee.jpg') }}'">
        </div>

        <div class="item-details">
            <h1 class="item-name">{{ $item->name }}</h1>
            
            {{-- ブランド名の表示（メイン） --}}
            @if($item->brand)
                <div class="item-brand">{{ $item->brand }}</div>
            @endif

            <div class="item-price">¥{{ number_format($item->price) }}<span>（税込）</span></div>

            <div class="action-section">
                @auth
                    @if($item->isFavoritedBy(Auth::user()))
                        <form action="{{ route('favorite.destroy', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-item">
                                <img src="{{ asset('heart_pink.png') }}" class="action-icon">
                                <span class="action-count">{{ $item->favorites->count() }}</span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('favorite.store', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="action-item">
                                <img src="{{ asset('heart_gray.png') }}" class="action-icon">
                                <span class="action-count">{{ $item->favorites->count() }}</span>
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="action-item">
                        <img src="{{ asset('heart_gray.png') }}" class="action-icon">
                        <span class="action-count">{{ $item->favorites->count() }}</span>
                    </a>
                @endauth
                
                <div class="action-item">
                    <img src="{{ asset('comment_icon.png') }}" class="action-icon">
                    <span class="action-count">{{ $item->comments->count() }}</span>
                </div>
            </div>

            @if($item->isSold())
                <button class="buy-btn" disabled>売り切れました</button>
            @else
                <a href="{{ route('item.purchase.show', $item->id) }}" class="buy-btn">購入手続きへ</a>
            @endif

            <h2 class="section-title">商品説明</h2>
            <div class="description">{{ $item->description }}</div>

            <h2 class="section-title">商品の情報</h2>
            <table class="info-table">
                <tr>
                    <th>カテゴリー</th>
                    <td>
                        @foreach($item->categories as $category)
                            <span class="category-tag">{{ $category->name }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>商品の状態</th>
                    <td>{{ $item->condition }}</td>
                </tr>
                {{-- ブランド名の表示（詳細テーブル内） --}}
                @if($item->brand)
                <tr>
                    <th>ブランド</th>
                    <td>{{ $item->brand }}</td>
                </tr>
                @endif
            </table>

            <div class="comment-section">
                <h2 class="section-title">コメント ({{ $item->comments->count() }})</h2>
                <ul class="comment-list">
                    @foreach($item->comments as $comment)
                        <li class="comment-item">
                            <div class="comment-user">
                                <img src="{{ asset($comment->user->profile->img_url ?? 'img/user_default.png') }}" class="user-icon">
                                <span>{{ $comment->user->name }}</span>
                            </div>
                            <div class="comment-text">{{ $comment->comment }}</div>
                        </li>
                    @endforeach
                </ul>

                <div class="comment-form">
                    <p style="font-weight: bold; margin-bottom: 10px;">商品へのコメント</p>
                    <form action="{{ route('comment.store', $item->id) }}" method="POST">
                        @csrf
                        <textarea name="comment" class="comment-textarea" placeholder="コメントを入力してください" required></textarea>
                        @auth
                            <button type="submit" class="buy-btn">コメントを送信する</button>
                        @else
                            <a href="{{ route('login') }}" class="buy-btn">ログインしてコメントする</a>
                        @endauth
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>