<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フィットビット週次サマリー</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #fefbd8, #ffffff);
            font-family: "ヒラギノ丸ゴ Pro W4", sans-serif;
            color: #444;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border: 4px solid #ffd966;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2.4rem;
            font-weight: bold;
            color: #ff9800;
        }
        .header p {
            font-size: 1rem;
            color: #888;
        }
        .comparison-summary {
            padding: 15px;
            background: #ffecb3;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .comparison-summary h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #ff6f00;
        }
        .progress-bar-container {
            margin-bottom: 20px;
        }
        .progress-bar {
            height: 25px;
            border-radius: 12px;
            background-color: #e8eaf6;
            overflow: hidden;
            position: relative;
        }
        .progress-bar span {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, #3f51b5, #7986cb);
            width: 0%; /* Default empty state */
            animation: growBar 1.5s ease-out forwards;
        }
        @keyframes growBar {
            from { width: 0%; }
            to { width: var(--bar-width); }
        }
        .progress-bar-percentage {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9rem;
            color: #333;
        }
        .difference-text {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        .chart-container {
            margin-top: 30px;
        }
        .chart-container h4 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 1rem;
            color: #444;
            font-weight: bold;
        }
        canvas {
            max-width: 100%;
        }
        .user-message {
            background: #e3f2fd;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 5px solid #42a5f5;
            border-radius: 8px;
            animation: slideIn 1s ease-in-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 0.8rem;
        }
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }
            .header h1 {
                font-size: 1.8rem;
            }
            .comparison-summary h4, .chart-container h4 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1>こんにちは、{{ $userName }}さん！</h1>
            <p>あなたの週次データサマリー</p>
        </div>

        <!-- Personalized Message -->
        <div class="user-message">
            {{ $currentWeekSteps > $previousWeekSteps ? "素晴らしい！今週は先週を超えるペースで進んでいます！" : "少しペースを上げて、先週を超えましょう！" }}
        </div>

        <!-- Comparison Summary -->
        <div class="comparison-summary">
            <h4>今週の概要</h4>

            <!-- Steps Progress -->
            <div class="progress-bar-container">
                <p>歩数：今週 {{ $currentWeekSteps ?? "データなし" }} 歩、先週 {{ $previousWeekSteps ?? "データなし" }} 歩</p>
                <div class="progress-bar" style="--bar-width: {{ $previousWeekSteps ? ($currentWeekSteps / $previousWeekSteps) * 100 : 0 }}%;">
                    <span></span>
                    <div class="progress-bar-percentage">
                        {{ $previousWeekSteps ? round(($currentWeekSteps / $previousWeekSteps) * 100) : 0 }}%
                    </div>
                </div>
                <p class="difference-text">
                    {{ $previousWeekSteps ? "上週との差: " . ($previousWeekSteps - $currentWeekSteps) . " 歩" : "データなし" }}
                </p>
            </div>

            <!-- Calories Progress -->
            <div class="progress-bar-container">
                <p>消費カロリー：今週 {{ $currentWeekCalories ?? "データなし" }} kcal、先週 {{ $previousWeekCalories ?? "データなし" }} kcal</p>
                <div class="progress-bar" style="--bar-width: {{ $previousWeekCalories ? ($currentWeekCalories / $previousWeekCalories) * 100 : 0 }}%;">
                    <span></span>
                    <div class="progress-bar-percentage">
                        {{ $previousWeekCalories ? round(($currentWeekCalories / $previousWeekCalories) * 100) : 0 }}%
                    </div>
                </div>
                <p class="difference-text">
                    {{ $previousWeekCalories ? "上週との差: " . ($previousWeekCalories - $currentWeekCalories) . " kcal" : "データなし" }}
                </p>
            </div>

            <!-- Sleep Progress -->
            <div class="progress-bar-container">
                <p>睡眠時間：今週 {{ $currentWeekSleep ?? "データなし" }} 時間、先週 {{ $previousWeekSleep ?? "データなし" }} 時間</p>
                <div class="progress-bar" style="--bar-width: {{ $previousWeekSleep ? ($currentWeekSleep / $previousWeekSleep) * 100 : 0 }}%;">
                    <span></span>
                    <div class="progress-bar-percentage">
                        {{ $previousWeekSleep ? round(($currentWeekSleep / $previousWeekSleep) * 100) : 0 }}%
                    </div>
                </div>
                <p class="difference-text">
                    {{ $previousWeekSleep ? "上週との差: " . ($previousWeekSleep - $currentWeekSleep) . " 時間" : "データなし" }}
                </p>
            </div>
        </div>

        <!-- Charts -->
        <div class="chart-container">
            <h4>歩数の推移</h4>
            <canvas id="stepsChart"></canvas>
        </div>
        <div class="chart-container">
            <h4>睡眠時間の推移</h4>
            <canvas id="sleepChart"></canvas>
        </div>
        <div class="chart-container">
            <h4>消費カロリーの推移</h4>
            <canvas id="caloriesChart"></canvas>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 フィットビット トラッカー</p>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        const stepsData = @json($stepsData);
        const sleepData = @json($sleepData);
        const caloriesData = @json($caloriesData);

        function createChart(ctx, label, labels, data, color) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true }
                    },
                    scales: {
                        x: { title: { display: true, text: '日付' }},
                        y: { title: { display: true, text: label }, beginAtZero: true }
                    }
                }
            });
        }

        createChart(document.getElementById('stepsChart'), '歩数', stepsData.map(d => d.date), stepsData.map(d => d.steps), '#3f51b5');
        createChart(document.getElementById('sleepChart'), '睡眠時間(分)', sleepData.map(d => d.date), sleepData.map(d => d.duration), '#ff6f00');
        createChart(document.getElementById('caloriesChart'), 'カロリー消費(kcal)', caloriesData.map(d => d.date), caloriesData.map(d => d.calories), '#ff5722');
    </script>
</body>
</html>
