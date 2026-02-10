<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録 - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; }
        header { background-color: #000; padding: 15px 40px; display: flex; align-items: center; }
        .header-logo { height: 35px; width: auto; }
        .register-container { width: 400px; margin: 60px auto; background: #fff; padding: 40px; }
        h2 { text-align: center; margin-bottom: 30px; font-size: 24px; font-weight: bold; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 14px; }
        input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; }
        /* バリデーションエラー：指示通りの赤い文字設定 */
        .error-message { color: #ff4b00; font-size: 14px; font-weight: bold; margin-top: 5px; }
        .submit-btn { width: 100%; padding: 12px; background-color: #ff4b00; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .login-link { text-align: center; margin-top: 20px; font-size: 14px; }
        .login-link a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
    </header>

    <div class="register-container">
        <h2>会員登録</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
            </div>

            <button type="submit" class="submit-btn">登録する</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
</body>
</html>