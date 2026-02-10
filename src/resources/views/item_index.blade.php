<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧 - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; color: #333; }
        
        /* 共通固定ヘッダー */
        header { background-color: #000; padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; width: 100%; box-sizing: border-box; }
        .header-logo { height: 35px; }
        .search-bar { flex-grow: 1; margin: 0 40px; }
        .search-bar input { width: 100%; padding: 8px 15px; border-radius: 4px; border: none; outline: none; }
        .nav-links { display: flex; align-items: center; }
        .nav-links a, .nav-links button { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; background: none; border: none; cursor: pointer; }
        .sell-btn { background-color: #fff !important; color: #000 !important; padding: 8px 20px !important; border-radius: 4px !important; font-weight: bold; }

        .container { padding: 40px; }
        .page-tabs { display: flex; gap: 30px; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .tab-item { padding-bottom: 10px; cursor: pointer; font-weight: bold; text-decoration: none; color: #333; transition: 0.2s; }
        .tab-item.active { color: #ff4b00; border-bottom: 2px solid #ff4b00; }

        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; }
        .item-card { text-decoration: none; color: inherit; position: relative; display: block; }
        .item-image-wrapper { position: relative; width: 100%; aspect-ratio: 1/1; }
        .item-image { width: 100%; height: 100%; object-fit: cover; background-color: #eee; border-radius: 4px; }
        
        .sold-label { position: absolute; top: 0; left: 0; background-color: rgba(255, 75, 0, 0.9); color: white; padding: 4px 12px; font-weight: bold; font-size: 13px; z-index: 10; border-bottom-right-radius: 4px; }
        
        .item-info { padding: 12px 0; }
        .item-brand { font-size: 12px; color: #888; margin-bottom: 2px; }
        .item-name { font-weight: bold; font-size: 15px; color: #333; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
        <div class="search-bar">
            <form action="{{ route('item.index') }}" method="GET">
                {{-- 検索時に現在のタブ(おすすめ/マイリスト)の状態を維持するための隠しパラメータ --}}
                <input type="hidden" name="tab" value="{{ $tab }}">
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
        <div class="page-tabs">
            {{-- タブ切り替え時も検索キーワード(keyword)を保持するように設定 --}}
            <a href="{{ route('item.index', ['tab' => 'all', 'keyword' => request('keyword')]) }}" class="tab-item {{ $tab !== 'fav' ? 'active' : '' }}">おすすめ</a>
            @auth
                <a href="{{ route('item.index', ['tab' => 'fav', 'keyword' => request('keyword')]) }}" class="tab-item {{ $tab === 'fav' ? 'active' : '' }}">マイリスト</a>
            @endauth
        </div>

        <div class="grid">
            @forelse($items as $item)
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card">
                    <div class="item-image-wrapper">
                        @if($item->isSold())
                            <div class="sold-label">SOLD</div>
                        @endif
                        <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="item-image" onerror="this.src='{{ asset('img/coffee.jpg') }}'">
                    </div>
                    <div class="item-info">
                        @if($item->brand)
                            <div class="item-brand">{{ $item->brand }}</div>
                        @endif
                        <div class="item-name">{{ $item->name }}</div>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 100px; color: #888;">該当する商品はありません</div>
            @endforelse
        </div>
    </div>
</body>
</html>