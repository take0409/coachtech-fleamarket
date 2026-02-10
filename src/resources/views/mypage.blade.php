<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; color: #333; }
        header { background-color: #000; padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; width: 100%; box-sizing: border-box; }
        .header-logo { height: 35px; }
        .search-bar { flex-grow: 1; margin: 0 40px; }
        .search-bar input { width: 100%; padding: 8px 15px; border-radius: 4px; border: none; outline: none; }
        .nav-links { display: flex; align-items: center; }
        .nav-links a, .nav-links button { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; background: none; border: none; cursor: pointer; }
        .sell-btn { background-color: #fff !important; color: #000 !important; padding: 8px 20px !important; border-radius: 4px !important; font-weight: bold; }
        .container { max-width: 1000px; margin: 0 auto; padding: 60px 20px; }
        .user-info { display: flex; align-items: center; justify-content: center; gap: 40px; margin-bottom: 60px; }
        .user-avatar { width: 120px; height: 120px; border-radius: 50%; background-color: #ddd; object-fit: cover; border: 1px solid #eee; }
        .user-name { font-size: 28px; font-weight: bold; margin: 0; }
        .edit-profile-btn { padding: 8px 24px; border: 2px solid #ff4b00; color: #ff4b00; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .tabs { display: flex; gap: 40px; border-bottom: 1px solid #ddd; margin-bottom: 30px; }
        .tab-item { padding-bottom: 10px; text-decoration: none; color: #333; font-weight: bold; font-size: 16px; position: relative; }
        .tab-item.active { color: #ff4b00; }
        .tab-item.active::after { content: ""; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background-color: #ff4b00; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; }
        .item-card { text-decoration: none; color: inherit; position: relative; display: block; }
        .item-image-wrapper { position: relative; width: 100%; aspect-ratio: 1/1; }
        .item-image { width: 100%; height: 100%; object-fit: cover; background-color: #eee; border-radius: 4px; }
        .sold-label { position: absolute; top: 0; left: 0; background-color: rgba(255, 75, 0, 0.9); color: white; padding: 4px 12px; font-weight: bold; font-size: 13px; z-index: 10; border-bottom-right-radius: 4px; }
        .item-info-text { padding: 12px 0; font-weight: bold; font-size: 15px; }
        .no-items { grid-column: 1 / -1; text-align: center; color: #888; padding: 80px 0; font-size: 16px; }
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
            @endauth
        </nav>
    </header>

    <div class="container">
        <div class="user-info">
            <img src="{{ ($user->profile && $user->profile->img_url) ? asset($user->profile->img_url) : asset('img/user_default.png') }}" 
                 class="user-avatar" 
                 alt="ユーザーアバター"
                 onerror="this.src='{{ asset('img/user_default.png') }}'">
            <h2 class="user-name">{{ $user->name }}</h2>
            <a href="{{ route('profile.edit') }}" class="edit-profile-btn">プロフィールを編集</a>
        </div>

        <div class="tabs">
            <a href="{{ route('mypage.index', ['tab' => 'sell']) }}" class="tab-item {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage.index', ['tab' => 'buy']) }}" class="tab-item {{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>

        <div class="grid">
            @forelse($items as $item)
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card">
                    <div class="item-image-wrapper">
                        @if($item->isSold())
                            <div class="sold-label">SOLD</div>
                        @endif
                        <img src="{{ asset($item->img_url) }}" class="item-image" onerror="this.src='{{ asset('img/coffee.jpg') }}'">
                    </div>
                    <div class="item-info-text">{{ $item->name }}</div>
                </a>
            @empty
                <div class="no-items">
                    <p>{{ $tab === 'buy' ? '購入した商品はまだありません' : '出品した商品はまだありません' }}</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>