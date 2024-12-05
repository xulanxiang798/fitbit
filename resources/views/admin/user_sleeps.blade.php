<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name ?? 'User' }}'s Sleep Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f5f5dc;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .table {
            background-color: #f0f4c3;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
        th {
            background-color: #9acd32;
            color: #333;
            font-weight: bold;
        }
        td {
            background-color: #dcedc8;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #6b8e23;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fafad2;
            border-radius: 15px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }
        #chartContainer {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<h1>{{ $user->name ?? 'User' }}'s Sleep Data</h1>
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sleep Duration (minutes)</th>
                    <th>Bed Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sleeps as $sleep)
                <tr>
                    <td>{{ $sleep['date'] }}</td>
                    <td>{{ $sleep['duration_minutes'] }}</td>
                    <td>{{ $sleep['start_time'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="chartContainer" class="container">
        <canvas id="sleepDurationChart"></canvas>
    </div>

    <script>
        const sleepData = @json($sleeps);
        const dates = sleepData.map(sleep => sleep.date);
        const durations = sleepData.map(sleep => sleep.duration_minutes);

        const durationCtx = document.getElementById('sleepDurationChart').getContext('2d');
        new Chart(durationCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Daily Sleep Duration (minutes)',
                        data: durations,
                        fill: false,
                        borderColor: 'rgb(54, 162, 235)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Sleep Duration (minutes)'
                        },
                        beginAtZero: true,
                        suggestedMax: 600
                    }
                }
            }
        });
    </script>
</body>
</html>
