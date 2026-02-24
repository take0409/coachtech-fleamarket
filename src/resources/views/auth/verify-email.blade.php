<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証 - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; color: #000; }
        
        /* ヘッダー：黒背景にロゴを左寄せ */
        header { background-color: #000; padding: 15px 40px; display: flex; align-items: center; width: 100%; box-sizing: border-box; }
        .header-logo { height: 30px; }

        /* コンテンツ中央配置 */
        .container { 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            min-height: 80vh; 
            text-align: center; 
            padding: 0 20px; 
        }
        
        /* 案内文：太字でデザイン案の通り */
        .message-text { 
            font-size: 18px; 
            font-weight: bold; 
            line-height: 1.6; 
            margin-bottom: 40px; 
            color: #000;
        }

        /* 認証誘導ボタン：グレー背景 */
        .verify-trigger-btn { 
            display: inline-block;
            background-color: #e5e7eb;
            color: #000;
            padding: 12px 40px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #d1d5db;
            margin-bottom: 30px;
            transition: background-color 0.2s;
        }
        .verify-trigger-btn:hover { background-color: #d1d5db; }

        /* 再送リンク：青色 */
        .resend-link { 
            background: none; 
            border: none; 
            color: #3b82f6; 
            text-decoration: none; 
            cursor: pointer; 
            font-size: 14px; 
            padding: 0;
        }
        .resend-link:hover { text-decoration: underline; }

        /* ステータスメッセージ */
        .status-message { 
            color: #059669; 
            margin-bottom: 20px; 
            font-size: 14px; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}">
            <img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo">
        </a>
    </header>

    <div class="container">
        <div class="message-text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </div>

        {{-- セッションメッセージ（再送時のみ表示） --}}
        @if (session('status') == 'verification-link-sent')
            <div class="status-message">
                ※新しい認証リンクを送信しました。
            </div>
        @endif

        <a href="http://localhost:8025" target="_blank" class="verify-trigger-btn">
            認証はこちらから
        </a>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="resend-link">
                認証メールを再送する
            </button>
        </form>
    </div>
</body>
</html>