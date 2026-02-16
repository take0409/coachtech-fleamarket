<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入 - COACHTECH</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333; }
        header { background-color: #000; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        .header-logo { height: 30px; }
        .search-bar { flex-grow: 1; margin: 0 40px; max-width: 600px; padding: 8px; border-radius: 4px; border: none; outline: none; }
        nav a { color: #fff; text-decoration: none; margin-left: 20px; font-weight: bold; font-size: 14px; }
        .sell-btn { background-color: #fff; color: #000; padding: 8px 16px; border-radius: 4px; }

        .main-container { max-width: 1000px; margin: 60px auto; display: flex; gap: 60px; padding: 0 20px; }
        .left-content { flex: 1.5; }
        .right-content { flex: 1; border: 1px solid #ccc; padding: 30px; border-radius: 4px; height: fit-content; }

        .item-info { display: flex; gap: 30px; margin-bottom: 40px; }
        .item-image { width: 120px; height: 120px; background-color: #ddd; object-fit: cover; border-radius: 2px; }
        .item-detail h2 { margin: 0 0 15px 0; font-size: 22px; font-weight: bold; }
        .item-price { font-size: 20px; font-weight: bold; margin: 0; }

        .section-title { font-weight: bold; border-bottom: 1px solid #333; padding-bottom: 10px; margin-top: 40px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; font-size: 18px; }
        .change-link { color: #007bff; text-decoration: none; font-size: 14px; }

        .payment-select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; margin-bottom: 20px; background-color: #fff; }
        .address-display { font-size: 16px; line-height: 1.6; }
        
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .summary-table th, .summary-table td { padding: 20px 0; border-bottom: 1px solid #eee; font-size: 16px; }
        .summary-table th { font-weight: normal; text-align: left; color: #333; width: 50%; }
        .summary-table td { text-align: right; font-weight: bold; }

        .purchase-btn { width: 100%; padding: 15px; background-color: #ff4b00; color: #fff; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .purchase-btn:hover { background-color: #e64400; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
        <input type="text" class="search-bar" placeholder="なにをお探しですか？">
        <nav>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <a href="#" onclick="this.parentNode.submit()">ログアウト</a>
            </form>
            <a href="{{ route('mypage.index') }}">マイページ</a>
            <a href="{{ route('item.create') }}" class="sell-btn">出品</a>
        </nav>
    </header>

    <main class="main-container">
        <div class="left-content">
            <div class="item-info">
                <img src="{{ asset($item->img_url) }}" alt="{{ $item->name }}" class="item-image">
                <div class="item-detail">
                    <h2>{{ $item->name }}</h2>
                    <p class="item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            <form id="purchase-form" action="{{ route('item.purchase.store', $item->id) }}" method="POST">
                @csrf
                <div class="section-title">支払い方法</div>
                <select name="payment_method" class="payment-select" onchange="updateSummary()">
                    <option value="" disabled selected>選択してください</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>

                <div class="section-title">
                    配送先
                    <a href="{{ route('address.edit', ['item_id' => $item->id]) }}" class="change-link">変更する</a>
                </div>
                <div class="address-display">
                    <p>〒 {{ $address['postal_code'] ?? '' }}</p>
                    <p>{{ $address['address'] ?? '住所を登録してください' }}{{ $address['building'] ?? '' }}</p>
                </div>
            </form>
        </div>

        <div class="right-content">
            <table class="summary-table">
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td id="payment-display">未選択</td>
                </tr>
            </table>
            <button type="button" class="purchase-btn" onclick="submitPurchaseForm()">購入する</button>
        </div>
    </main>

    <script>
        function updateSummary() {
            const select = document.querySelector('select[name="payment_method"]');
            const display = document.getElementById('payment-display');
            display.innerText = select.value;
        }

        function submitPurchaseForm() {
            const select = document.querySelector('select[name="payment_method"]');
            if (!select.value) {
                alert('支払い方法を選択してください');
                return;
            }
            document.getElementById('purchase-form').submit();
        }
    </script>
</body>
</html>