<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to bottom right, #f7ede2, #f5c6aa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            max-width: 400px;
            padding: 30px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-container h3 {
            margin-bottom: 15px;
            font-size: 1.8rem;
            color: #d17842;
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 20px;
            height: 45px;
            border-radius: 12px;
            border: 1px solid #e5a58a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-control:focus {
            border-color: #d17842;
            box-shadow: 0px 0px 8px rgba(209, 120, 66, 0.4);
        }
        .btn-primary {
            background: linear-gradient(to right, #d17842, #f0a07a);
            border: none;
            border-radius: 12px;
            height: 45px;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #f0a07a, #d17842);
            box-shadow: 0px 4px 10px rgba(209, 120, 66, 0.3);
        }
        .alert-danger {
            font-size: 0.9rem;
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 10px;
            background-color: #fde8e4;
            color: #d17842;
            border: 1px solid #d17842;
        }
        .login-note {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #666;
        }
        .login-note span {
            font-weight: bold;
            color: #d17842;
        }
        @media (max-width: 768px) {
            .login-container {
                width: 90%;
                padding: 20px;
            }
            .login-container h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3>ログイン</h3>
        <p class="login-note">ログインには <span>Fitbit User ID</span> を使用してください。</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('user.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" id="fitbit_user_id" name="fitbit_user_id" class="form-control" placeholder="Fitbit User ID" required>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="パスワード" required>
            </div>
            <button type="submit" class="btn btn-primary">ログインする</button>
        </form>
    </div>
</body>
</html>
