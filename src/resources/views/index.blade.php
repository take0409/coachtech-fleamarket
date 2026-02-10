<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <style>
        body { margin: 0; font-family: sans-serif; }
        header { background: #000; padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 40px; }
        .item-card { text-decoration: none; color: inherit; border: 1px solid #eee; padding: 10px; }
        .item-image { width: 100%; aspect-ratio: 1/1; object-fit: cover; }
    </style>
</head>
<body>
    <header>
        <a href="/"><img src="{{ asset('logo.png') }}" style="height:35px;"></a>
        <nav>
            @auth
                <a href="{{ route('mypage.index') }}" style="color:#fff;">マイページ</a>
            @else
                <a href="{{ route('login') }}" style="color:#fff;">ログイン</a>
            @endauth
        </nav>
    </header>
    <div class="grid">
        @foreach($items as $item)
            <a href="{{ route('item.show', $item->id) }}" class="item-card">
                <img src="{{ asset($item->img_url) }}" class="item-image">
                <p>{{ $item->name }}</p>
                <p>¥{{ number_format($item->price) }}</p>
            </a>
        @endforeach
    </div>
</body>
</html>