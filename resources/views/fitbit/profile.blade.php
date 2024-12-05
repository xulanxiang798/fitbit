<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitbit User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #fef9e7, #fff);
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        }
        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid #ffd966;
        }
        .welcome-message {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #28a745;
            font-weight: bold;
        }
        .profile-info {
            font-size: 1.1em;
            color: #333;
        }
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fffbf0;
            border-radius: 20px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
            text-align: left;
        }
        .form-container h2 {
            font-size: 1.8rem;
            color: #ff6f61;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container p {
            font-size: 1rem;
            color: #555;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 0.9rem;
            color: #777;
        }
        .form-control {
            height: 45px;
            border-radius: 12px;
            margin-bottom: 15px;
            border: 1px solid #ffd966;
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-control:focus {
            border-color: #ff6f61;
            box-shadow: 0px 0px 10px rgba(255, 111, 97, 0.4);
        }
        .btn-primary {
            background: linear-gradient(to right, #ff6f61, #ffa07a);
            border: none;
            border-radius: 12px;
            height: 45px;
            font-size: 1rem;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #ffa07a, #ff6f61);
            box-shadow: 0px 4px 10px rgba(255, 111, 97, 0.3);
        }
        .form-note {
            font-size: 0.9rem;
            text-align: center;
            margin-top: 20px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Fitbit User Profile</h1>
        <img src="{{ $userAvatar }}" alt="User Avatar" class="avatar">
        <div class="welcome-message">
            認証に成功しました！<br>
            Authentication Successful! Welcome!
        </div>
        <div class="profile-info">
            <p><strong>User ID:</strong> {{ $userId }}</p>
            <p><strong>User Name:</strong> {{ $userName }}</p>
        </div>
    </div>

    <div class="form-container">
        <h2>パスワードを設定する</h2>
        <p>ログインに使用するアカウントIDは「<span style="color: #ff6f61; font-weight: bold;">Fitbit User ID</span>」です。<br>必ず記録しておいてください！</p>
        <form action="{{ route('user.register.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="fitbit_user_id" value="{{ $userId }}">
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="新しいパスワードを入力" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">パスワードの確認</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="もう一度パスワードを入力" required>
            </div>
            <button type="submit" class="btn btn-primary">パスワードを設定する</button>
        </form>
        <p class="form-note">アカウントIDはログイン時に必要です。忘れないようにしてください。</p>
    </div>
</body>
</html>
