<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Login/login.css') }}">
    <title>Login</title>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="logo-section">
            <img src="{{ asset('image/logoYPLP/logo.svg') }}" alt="Logo PGRI" class="logo">
        </div>
        <div class="login-box">
            <h1>LOGIN</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif

                <input type="password" name="password" placeholder="Password" required>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
""
