<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Step Data - Simplified Style</title>
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
<h1>{{ $user->name }}'s Step Data</h1>
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Steps</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($steps as $step)
                <tr>
                    <td>{{ $step['date'] }}</td>
                    <td>{{ $step['steps'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="chartContainer" class="container">
        <canvas id="stepsChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('stepsChart').getContext('2d');
        const stepsData = @json($steps);
        const dates = stepsData.map(step => step.date);
        const values = stepsData.map(step => step.steps);

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Steps',
                    data: values,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
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
                            text: 'Steps'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
