<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <style>
        html, body { margin: 0; min-height: 100%; }
        body { font-family: sans-serif; background-color: #f9f9f9; opacity: 1 !important; filter: none !important; pointer-events: auto !important; }
        header { background: #000; padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; }
        .tab-menu { padding: 20px 40px 0; border-bottom: 1px solid #ddd; background: #fff; }
        .tab-menu a { text-decoration: none; color: #888; margin-right: 20px; padding-bottom: 5px; display: inline-block; }
        .tab-menu a.active { color: #ff4d4d; border-bottom: 2px solid #ff4d4d; font-weight: bold; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 40px; }
        .item-card { text-decoration: none; color: inherit; background: #fff; border: 1px solid #eee; padding: 10px; position: relative; display: block; }
        .item-image { width: 100%; aspect-ratio: 1/1; object-fit: cover; }
        .sold-label { position: absolute; top: 10px; left: 10px; background: rgba(255, 0, 0, 0.8); color: #fff; padding: 2px 8px; font-size: 12px; font-weight: bold; z-index: 10; }
        .app-shell { position: relative; z-index: 1; pointer-events: auto; }
    </style>
</head>
<body>
    <div class="app-shell">
        <header>
            <a href="/"><img src="{{ asset('logo.png') }}" style="height:35px;"></a>
            <form action="{{ route('item.index') }}" method="GET">
                <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}" style="width: 300px; padding: 5px;">
            </form>
            <nav>
                @auth
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:#fff; margin-right:15px;">ログアウト</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    <a href="{{ route('mypage.index') }}" style="color:#fff;">マイページ</a>
                    <a href="{{ route('item.create') }}" style="background:#fff; color:#000; padding:5px 15px; text-decoration:none; margin-left:15px; border-radius:4px;">出品</a>
                @else
                    <a href="{{ route('login') }}" style="color:#fff;">ログイン</a>
                    <a href="{{ route('register') }}" style="color:#fff; margin-left:15px;">会員登録</a>
                @endauth
            </nav>
        </header>

        <div class="tab-menu">
            <a href="{{ route('item.index', ['tab' => 'all']) }}" class="{{ $tab === 'all' ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('item.index', ['tab' => 'fav']) }}" class="{{ $tab === 'fav' ? 'active' : '' }}">マイリスト</a>
        </div>

        <div class="grid">
            @forelse($items as $item)
                <a href="{{ route('item.show', $item->id) }}" class="item-card">
                    @if($item->isSold())
                        <span class="sold-label">SOLD</span>
                    @endif
                    <img src="{{ asset($item->img_url) }}" class="item-image">
                    <p style="margin: 10px 0 5px; font-weight: bold;">{{ $item->name }}</p>
                    <p style="margin: 0; color: #555;">¥{{ number_format($item->price) }}</p>
                </a>
            @empty
                <p style="padding: 40px; color: #888;">表示する商品がありません。</p>
            @endforelse
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            document.documentElement.style.pointerEvents = 'auto';
            document.body.style.pointerEvents = 'auto';
            document.body.style.opacity = '1';
            document.body.style.filter = 'none';

            document.querySelectorAll('[style*="position: fixed"], [style*="position:fixed"]').forEach((element) => {
                const styles = window.getComputedStyle(element);

                if (
                    styles.inset === '0px' ||
                    (styles.top === '0px' && styles.left === '0px' && styles.width === `${window.innerWidth}px` && styles.height === `${window.innerHeight}px`)
                ) {
                    element.style.pointerEvents = 'none';
                    element.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
