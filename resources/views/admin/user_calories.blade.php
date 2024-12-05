<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }}'s Calories Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .table {
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
        th {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
        }
        td {
            background-color: #e8f5e9;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4caf50;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }
        #chartContainer {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <h1>{{ $user->name }}'s Calories Data</h1>
    <div class="container">
        <!-- Calories Data Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Calories Burned</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($calories as $cal)
                <tr>
                    <td>{{ $cal['date'] }}</td>
                    <td>{{ number_format($cal['calories'], 2) }} kcal</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Calories Chart -->
    <div id="chartContainer" class="container">
        <canvas id="caloriesChart"></canvas>
    </div>

    <script>
        // Calories Data from Backend (only last 7 days)
        const caloriesData = @json($calories);

        // Extract Dates and Calories from Data
        const dates = caloriesData.map(cal => cal.date);
        const values = caloriesData.map(cal => cal.calories);

        // Initialize Chart.js
        const ctx = document.getElementById('caloriesChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Calories Burned',
                    data: values,
                    borderColor: 'rgb(255, 159, 64)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(255, 99, 132)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(255, 99, 132)',
                    fill: true,
                    tension: 0.4 // Smooth line
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.raw.toFixed(2)} kcal`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                            font: {
                                size: 14
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Calories Burned (kcal)',
                            font: {
                                size: 14
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
