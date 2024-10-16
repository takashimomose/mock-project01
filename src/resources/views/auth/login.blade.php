<div class="container">
    <h2>ログイン</h2>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Login Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                ログインする
            </button>
        </div>

        <!-- Register Link -->
        <div class="form-group">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </form>
</div>