<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Patrick Hand', cursive;
            background-color: #F5F3E7;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #DEB887;
            background: #FFF8DC;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #3B5323;
            margin-bottom: 30px;
        }

        table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead {
            background-color: #A8D8B9;
            color: #3B5323;
        }

        .table tbody tr:hover {
            background-color: #F5DEB3;
        }

        .table td {
            color: #6B4226;
            font-weight: bold;
        }

        .action-link {
            color: #007BFF;
            font-weight: bold;
            text-decoration: none;
        }

        .action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Fitbit User Data</h1>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Fitbit User ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Height (cm)</th>
                    <th>Weight (kg)</th>
                    <th>Steps</th>
                    <th>Heart Rate</th>
                    <th>Sleep</th>
                    <th>Calories</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->fitbit_user_id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->age }}</td>
                        <td>{{ $user->gender }}</td>
                        <td>{{ $user->height }}</td>
                        <td>{{ $user->weight }}</td>
                        <td><a href="{{ route('admin.user.steps', $user->id) }}">歩数データを表示</a></td>
                        <td><a href="{{ route('admin.user.heartRates', $user->id) }}">心拍データを表示</a></td>
                        <td><a href="{{ route('admin.user.sleeps', $user->id) }}">睡眠データを表示</a></td>
                        <td><a href="{{ route('admin.user.calories', $user->id) }}">カロリーデータを表示</a></td>
                        <td>
                        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
</form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
