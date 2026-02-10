<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証 - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; color: #333; }
        
        /* 共通固定ヘッダー */
        header { background-color: #000; padding: 15px 40px; display: flex; align-items: center; position: sticky; top: 0; z-index: 1000; width: 100%; box-sizing: border-box; }
        .header-logo { height: 35px; }

        .container { max-width: 600px; margin: 80px auto; padding: 0 20px; text-align: center; }
        .card { padding: 40px; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        
        h1 { font-size: 24px; font-weight: bold; margin-bottom: 30px; }
        .instruction-text { line-height: 1.8; color: #555; margin-bottom: 30px; text-align: left; }

        .resend-btn { width: 100%; padding: 16px; background-color: #ff4b00; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; text-decoration: none; display: block; margin-bottom: 20px; }
        
        .logout-btn { background: none; border: none; color: #888; text-decoration: underline; cursor: pointer; font-size: 14px; }
        
        .status-message { background-color: #e6fffa; color: #2d3748; padding: 15px; border-radius: 4px; border: 1px solid #38b2ac; margin-bottom: 25px; font-size: 14px; text-align: left; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
    </header>

    <div class="container">
        <div class="card">
            <h1>メール認証のお願い</h1>

            {{-- セッションにステータスがある場合（再送後など）に表示 --}}
            @if (session('status') == 'verification-link-sent')
                <div class="status-message">
                    ご登録いただいたメールアドレスに、新しい認証リンクを送信しました。
                </div>
            @endif

            <div class="instruction-text">
                ご登録ありがとうございます！<br>
                まずは、お届けしたメール内のリンクをクリックして、メールアドレスの認証を完了させてください。<br>
                もしメールが届いていない場合は、以下のボタンから再送することができます。
            </div>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="resend-btn">
                    認証メールを再送する
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    ログアウト
                </button>
            </form>
        </div>
    </div>
</body>
</html>