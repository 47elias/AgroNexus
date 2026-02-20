<?php 
require_once '../includes/DataGenerator.php'; 

// Fetching mock data from our central generator
$maizeForecast = [380, 395, 410, 405, 425, 445, 460]; // ARIMA Price Trend
$actualPrices = [375, 390, 405, 400, 415];           // Historical baseline
$inflationData = [5, 8, 12, 15, 18, 22];             // VAR Ripple effect simulation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macro Forecasting | AgroNexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-950 text-slate-200 antialiased flex">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 p-8">
        <header class="mb-10">
            <h2 class="text-3xl font-bold text-white">Macroeconomic Forecasting</h2>
            <p class="text-slate-400 mt-2">
                [cite_start]Utilizing ARIMA & VAR models to predict national financial stability based on agricultural output[cite: 6].
            </p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-slate-900/50 p-6 rounded-2xl border border-slate-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-500/20 text-emerald-400 rounded-lg flex items-center justify-center font-bold">A</div>
                    <h3 class="text-lg font-semibold">ARIMA Model</h3>
                </div>
                <p class="text-sm text-slate-400">
                    [cite_start]Univariate model analyzing linear trends in historical yields to forecast short-term price stability[cite: 7, 80].
                </p>
            </div>
            <div class="bg-slate-900/50 p-6 rounded-2xl border border-slate-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center font-bold">V</div>
                    <h3 class="text-lg font-semibold">VAR Model</h3>
                </div>
                <p class="text-sm text-slate-400">
                    [cite_start]Multivariate system evaluating how variables like fertilizer prices and rainfall ripple through to inflation[cite: 8, 82].
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 bg-slate-900 p-8 rounded-3xl border border-slate-800 shadow-xl">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="text-xl font-bold">Maize Price Stability Forecast</h4>
                    <span class="text-xs font-mono text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-full uppercase">Univariate Analysis</span>
                </div>
                <div class="h-80">
                    <canvas id="arimaChart"></canvas>
                </div>
            </div>

            <div class="bg-slate-900 p-8 rounded-3xl border border-slate-800 flex flex-col">
                <h4 class="text-xl font-bold mb-6">VAR System Ripple</h4>
                <div class="space-y-6 flex-1">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-400">Fertilizer Spike Impact</span>
                            <span class="text-rose-400">+12.4%</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-2">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-400">Rainfall Correlation</span>
                            <span class="text-emerald-400">-0.82</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-400">Food Inflation Forecast</span>
                            <span class="text-amber-400">High Risk</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-800 text-xs text-slate-500">
                    [cite_start]System analyzing interaction between 4 global and 3 local financial variables[cite: 84].
                </div>
            </div>
        </div>
    </main>

    <script>
        // ARIMA Visualization
        const ctx = document.getElementById('arimaChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [
                    {
                        label: 'Actual Market Price',
                        data: <?php echo json_encode($actualPrices); ?>,
                        borderColor: '#94a3b8',
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'ARIMA Predicted Path',
                        data: <?php echo json_encode($maizeForecast); ?>,
                        borderColor: '#10b981',
                        borderWidth: 3,
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#94a3b8', usePointStyle: true } }
                },
                scales: {
                    y: { 
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#64748b', callback: (v) => '$' + v }
                    },
                    x: { grid: { display: false }, ticks: { color: '#64748b' } }
                }
            }
        });
    </script>
</body>
</html>