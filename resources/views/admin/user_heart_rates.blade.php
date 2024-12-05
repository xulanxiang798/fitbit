<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Heart Rate Data - Detailed View</title>
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
            max-width: 900px;
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
<h1>{{ $user->name }}'s Heart Rate Data</h1>
    <div class="container">
        <h2>Realtime Heart Rates</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Heart Rate (bpm)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($realtimeHeartRates as $rate)
                <tr>
                    <td>{{ $rate->timestamp }}</td>
                    <td>{{ $rate->heart_rate }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="container">
        <h2>Heart Rate Zones</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Out of Range (min)</th>
                    <th>Fat Burn (min)</th>
                    <th>Cardio (min)</th>
                    <th>Peak (min)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($heartRateZones as $zone)
                <tr>
                    <td>{{ $zone->date }}</td>
                    <td>{{ $zone->out_of_range_minutes }}</td>
                    <td>{{ $zone->fat_burn_minutes }}</td>
                    <td>{{ $zone->cardio_minutes }}</td>
                    <td>{{ $zone->peak_minutes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="chartContainer" class="container">
        <h2>Heart Rate Data Chart</h2>
        <canvas id="heartRateChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('heartRateChart').getContext('2d');
        const realtimeHeartRateData = @json($realtimeHeartRates);
        const timestamps = realtimeHeartRateData.map(rate => rate.timestamp);
        const heartRates = realtimeHeartRateData.map(rate => rate.heart_rate);

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    label: 'Realtime Heart Rate',
                    data: heartRates,
                    fill: false,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Timestamp'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Heart Rate (bpm)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
