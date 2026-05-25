<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="login-logo">
            <div class="icon"><i class="fas fa-lock"></i></div>
            <h1>Welcome Back</h1>
            <p>Sign in to your admin dashboard</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-check" style="margin-bottom: 1.25rem;">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="font-size: 0.9rem;">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('home') }}" style="color: #6b7280; font-size: 0.85rem;"><i class="fas fa-arrow-left"></i> Back to website</a>
        </div>
    </div>
</body>
</html>
