<?php require_once 'includes/DataGenerator.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-950 text-slate-200">
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>

        <main class="flex-1 p-8">
            <header class="mb-10 flex justify-between items-end">
                <div>
                    <h2 class="text-3xl font-bold">Predictive Intelligence Overview</h2>
                    <p class="text-slate-400">Integrated national agricultural monitoring</p>
                </div>
                <div class="px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-emerald-400 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                    Live Simulation Active
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <?php $metrics = DataGenerator::getHealthMetrics(); ?>
                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800">
                    <h4 class="text-slate-500 text-sm mb-1">National NDVI Avg.</h4>
                    <p class="text-3xl font-bold"><?php echo $metrics['ndvi']; ?></p>
                </div>
                </div>

            <div class="bg-slate-900 p-8 rounded-3xl border border-slate-800">
                <h3 class="text-xl font-semibold mb-6">Macroeconomic Price Forecast (ARIMA/LSTM)</h3>
                <canvas id="mainForecast" class="h-64 w-full"></canvas>
            </div>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('mainForecast').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Predicted Price',
                    data: <?php echo json_encode(DataGenerator::getPriceForecast()); ?>,
                    borderColor: '#10b981',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)'
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    </script>
</body>
</html>