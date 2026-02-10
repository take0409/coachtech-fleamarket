<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>住所の変更</title>
    <style>
        body { font-family: sans-serif; padding: 40px; max-width: 600px; margin: auto; }
        h1 { text-align: center; margin-bottom: 40px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .error-message { color: #ff4b00; font-size: 14px; font-weight: bold; margin-top: 5px; }
        .submit-btn { width: 100%; padding: 15px; background: #ff4b00; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>住所の変更</h1>
    <form action="{{ route('address.update', $item_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" name="postal_code" placeholder="123-4567" value="{{ old('postal_code') }}">
            @error('postal_code') <div class="error-message">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address" placeholder="東京都渋谷区..." value="{{ old('address') }}">
            @error('address') <div class="error-message">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>建物名</label>
            <input type="text" name="building" placeholder="マンション名など" value="{{ old('building') }}">
            @error('building') <div class="error-message">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="submit-btn">更新する</button>
    </form>
</body>
</html>