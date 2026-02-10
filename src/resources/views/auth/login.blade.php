<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; }
        header { background-color: #000; padding: 15px 40px; display: flex; align-items: center; }
        .header-logo { height: 35px; width: auto; }
        .login-container { width: 400px; margin: 60px auto; background: #fff; padding: 40px; }
        h2 { text-align: center; margin-bottom: 30px; font-size: 24px; font-weight: bold; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 14px; }
        input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; }
        /* バリデーションエラー：赤い文字表示 */
        .error-message { color: #ff4b00; font-size: 14px; font-weight: bold; margin-top: 5px; }
        .submit-btn { width: 100%; padding: 12px; background-color: #ff4b00; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .register-link { text-align: center; margin-top: 20px; font-size: 14px; }
        .register-link a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
    </header>

    <div class="login-container">
        <h2>ログイン</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
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

            <button type="submit" class="submit-btn">ログインする</button>
        </form>

        <div class="register-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </div>
</body>
</html>