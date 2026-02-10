<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール設定 - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; color: #333; }
        header { background-color: #000; padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; width: 100%; box-sizing: border-box; }
        .header-logo { height: 35px; }
        .search-bar { flex-grow: 1; margin: 0 40px; }
        .search-bar input { width: 100%; padding: 8px 15px; border-radius: 4px; border: none; outline: none; }
        .nav-links { display: flex; align-items: center; }
        .nav-links a, .nav-links button { color: white; text-decoration: none; margin-left: 20px; font-size: 14px; background: none; border: none; cursor: pointer; }
        .sell-btn { background-color: #fff !important; color: #000 !important; padding: 8px 20px !important; border-radius: 4px !important; font-weight: bold; }
        .container { max-width: 600px; margin: 0 auto; padding: 60px 20px; }
        h1 { text-align: center; font-size: 24px; margin-bottom: 40px; }
        .avatar-section { display: flex; align-items: center; gap: 30px; margin-bottom: 40px; }
        .current-avatar { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; background-color: #ddd; }
        .file-label { padding: 8px 16px; border: 2px solid #ff4b00; color: #ff4b00; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; }
        input[type="file"] { display: none; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; }
        .error-message { color: #ff4b00; font-size: 14px; font-weight: bold; margin-top: 5px; }
        .update-btn { width: 100%; padding: 15px; background-color: #ff4b00; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
        <div class="search-bar">
            <form action="{{ route('item.index') }}" method="GET">
                <input type="text" name="keyword" placeholder="なにをお探しですか？">
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
        <h1>プロフィール設定</h1>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="avatar-section">
                <img src="{{ asset($user->profile->img_url ?? 'img/user_default.png') }}" class="current-avatar" id="avatar-preview">
                <label class="file-label">
                    画像を選択する
                    <input type="file" name="img_url" accept="image/*" onchange="previewImage(this);">
                </label>
                @error('img_url') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>ユーザー名</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}">
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>郵便番号</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
                @error('postal_code') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>住所</label>
                <input type="text" name="address" value="{{ old('address', $user->profile->address ?? '') }}">
                @error('address') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>建物名</label>
                <input type="text" name="building" value="{{ old('building', $user->profile->building ?? '') }}">
                @error('building') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="update-btn">更新する</button>
        </form>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { document.getElementById('avatar-preview').src = e.target.result; }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>