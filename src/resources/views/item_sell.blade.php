<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品の出品 - coachtech-fleamarket</title>
    <style>
        body { margin: 0; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #fff; color: #333; }
        header { background-color: #000; padding: 15px 40px; display: flex; align-items: center; }
        .header-logo { height: 35px; }
        .container { max-width: 600px; margin: 60px auto; padding: 0 20px; }
        h2 { text-align: center; margin-bottom: 40px; font-size: 24px; font-weight: bold; }
        .section-title { font-size: 18px; font-weight: bold; border-bottom: 2px solid #eee; padding-bottom: 10px; margin: 40px 0 20px; color: #555; }
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 14px; }
        input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; }

        .image-upload-box { border: 2px dashed #ccc; padding: 20px; text-align: center; border-radius: 4px; margin-bottom: 10px; min-height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; }
        #image-preview { max-width: 100%; max-height: 200px; display: none; margin-bottom: 10px; }
        .image-upload-btn { border: 2px solid #ff4b00; color: #ff4b00; padding: 8px 20px; border-radius: 4px; background: none; font-weight: bold; cursor: pointer; }
        #file-input { display: none; }

        .category-group { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 10px; }
        .category-checkbox { display: none; }
        .category-label { padding: 6px 18px; border: 2px solid #ff4b00; border-radius: 20px; font-size: 14px; font-weight: bold; color: #ff4b00; cursor: pointer; transition: all 0.2s; }
        .category-checkbox:checked + .category-label { background-color: #ff4b00; color: #fff; }

        .error-message { color: #ff4b00; font-size: 14px; font-weight: bold; margin-top: 5px; }
        .submit-btn { width: 100%; padding: 18px; background-color: #ff4b00; color: white; border: none; border-radius: 4px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 40px; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('item.index') }}"><img src="{{ asset('logo.png') }}" alt="COACHTECH" class="header-logo"></a>
    </header>

    <div class="container">
        <h2>商品の出品</h2>

        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>商品画像</label>
                <div class="image-upload-box">
                    <img id="image-preview" src="" alt="プレビュー">
                    <button type="button" class="image-upload-btn" onclick="document.getElementById('file-input').click();">画像を選択する</button>
                    <input type="file" name="img_url" id="file-input" accept="image/*">
                </div>
                @error('img_url') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <h3 class="section-title">商品の詳細</h3>
            
            <div class="form-group">
                <label>カテゴリー（複数選択可）</label>
                <div class="category-group">
                    @foreach($categories as $category)
                        <div class="category-item">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat-{{ $category->id }}" class="category-checkbox" {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                            <label for="cat-{{ $category->id }}" class="category-label">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('categories') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>商品の状態</label>
                <select name="condition">
                    <option value="">選択してください</option>
                    @foreach(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'] as $cond)
                        <option value="{{ $cond }}" {{ old('condition') == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                    @endforeach
                </select>
                @error('condition') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <h3 class="section-title">商品名と説明</h3>
            <div class="form-group">
                <label>商品名</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>ブランド名</label>
                <input type="text" name="brand" value="{{ old('brand') }}">
                @error('brand') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>商品の説明</label>
                <textarea name="description" rows="5">{{ old('description') }}</textarea>
                @error('description') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>販売価格</label>
                <input type="number" name="price" placeholder="¥" value="{{ old('price') }}">
                @error('price') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="submit-btn">出品する</button>
        </form>
    </div>

    <script>
        document.getElementById('file-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('image-preview');
                reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>