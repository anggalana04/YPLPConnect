<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/Login/login.css') }}">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="logo-section">
            <img src="{{ asset('image/logoYPLP/logo.svg') }}" alt="Logo PGRI" class="logo">
        </div>
        <div class="login-box">
            <h1>LOGIN</h1>
            <form action="proses_login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
