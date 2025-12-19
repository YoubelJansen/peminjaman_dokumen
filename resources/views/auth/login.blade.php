<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LendCore - Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #F4F6F8; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05); width: 100%; max-width: 400px; text-align: center; }
        .brand-title { color: #5D4037; font-weight: bold; font-size: 24px; margin-bottom: 5px; }
        .sub-title { color: #6c757d; font-size: 14px; margin-bottom: 30px; }
        .form-control { background-color: #F3F4F6; border: 1px solid #E5E7EB; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
        .btn-signin { background-color: #6D4C41; color: white; width: 100%; padding: 12px; border-radius: 6px; font-weight: 600; border: none; }
        .btn-signin:hover { background-color: #5D4037; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-title">LendCore</div>
        
        <div class="fw-bold" style="color: #6D4C41; font-size: 16px;">Hi, Welcome Back</div>
        <p class="sub-title">Enter your credential to continue</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf <div class="text-start mb-1"><small class="text-muted"></small></div>
            <input type="email" name="email" class="form-control" placeholder="Username" required autofocus>
            
            <div class="text-start mb-1"><small class="text-muted"></small></div>
            <input type="password" name="password" class="form-control" placeholder="Password" required>

            <button type="submit" class="btn-signin">Sign In</button>
        </form>
    </div>

</body>
</html>