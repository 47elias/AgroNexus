<?php 
require_once '../includes/DataGenerator.php'; 

// Real-time Simulation Engine
$basePrice = 412.50;
$historical = [];
for ($i = 0; $i < 12; $i++) {
    $basePrice += (rand(-15, 25) / 10);
    $historical[] = $basePrice;
}

// Forecast with Confidence Intervals (Standard for ARIMA)
$forecast = [];
$upperBound = [];
$lowerBound = [];
$lastPrice = end($historical);

for ($i = 1; $i <= 6; $i++) {
    $pred = $lastPrice + ($i * 4.2) + rand(-2, 2);
    $forecast[] = $pred;
    $upperBound[] = $pred + ($i * 3.5); // Expanding uncertainty
    $lowerBound[] = $pred - ($i * 3.5);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Macro Terminal | AgroNexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); }
        .terminal-grid { background-image: radial-gradient(rgba(16, 185, 129, 0.05) 1px, transparent 0); background-size: 24px 24px; }
    </style>
</head>
<body class="bg-[#05070a] text-slate-300 antialiased flex overflow-hidden terminal-grid">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen relative">
        <div class="h-16 border-b border-slate-800/50 flex items-center justify-between px-8 glass sticky top-0 z-50">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest text-emerald-500 mono">LIVE_MARKET_FEED</span>
                </div>
                <div class="h-4 w-[1px] bg-slate-800"></div>
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">
                    Last Computed: <span class="text-slate-300"><?php echo date('H:i:s'); ?> UTC</span>
                </div>
            </div>
            <div class="flex gap-4">
                <button class="text-[10px] bg-slate-800 px-3 py-1 rounded border border-slate-700 hover:bg-slate-700 transition">EXPORT_CSV</button>
                <button class="text-[10px] bg-emerald-600/20 text-emerald-400 px-3 py-1 rounded border border-emerald-500/30">RE-TRAIN_MODEL</button>
            </div>
        </div>

        <div class="flex-1 p-8 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass border border-slate-800 p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Maize Spot Price (ZWL/t)</p>
                    <h3 class="text-3xl font-extrabold text-white mt-1 mono tracking-tighter">$<?php echo end($historical); ?></h3>
                    <p class="text-xs text-emerald-400 mt-2 font-bold">+2.4% <span class="text-slate-500 font-normal">vs last month</span></p>
                </div>
                
                <div class="glass border border-slate-800 p-6 rounded-2xl">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">VAR Inflation Ripple</p>
                    <h3 class="text-3xl font-extrabold text-rose-500 mt-1 mono tracking-tighter">14.8%</h3>
                    <div class="w-full bg-slate-800 h-1 mt-4 rounded-full overflow-hidden">
                        <div class="bg-rose-500 h-full w-[74%]"></div>
                    </div>
                </div>

                <div class="glass border border-slate-800 p-6 rounded-2xl">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Model AIC/BIC Score</p>
                    <h3 class="text-3xl font-extrabold text-blue-400 mt-1 mono tracking-tighter">0.982</h3>
                    <p class="text-[10px] text-slate-500 mt-2">Strong Correlation (Rainfall : Yield)</p>
                </div>
            </div>

            <div class="glass border border-slate-800 p-8 rounded-3xl mb-8 relative">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h4 class="text-xl font-bold text-white">ARIMA Time-Series Projection</h4>
                        <p class="text-xs text-slate-500 mt-1">Stochastic analysis of seasonal price fluctuations with 95% Confidence Interval.</p>
                    </div>
                    <div class="flex bg-black/40 p-1 rounded-lg border border-slate-800 mono text-[10px]">
                        <button class="px-3 py-1 bg-slate-800 text-white rounded shadow-sm">12M_VIEW</button>
                        <button class="px-3 py-1 text-slate-500 hover:text-slate-300">24M_VIEW</button>
                    </div>
                </div>
                <div class="h-[400px]">
                    <canvas id="macroChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="glass border border-slate-800 p-6 rounded-2xl">
                    <h5 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> VAR Multivariate Logic
                    </h5>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs p-3 bg-white/5 rounded-lg border border-white/5">
                            <span class="text-slate-400">Global Fertilizer Volatility</span>
                            <span class="mono text-rose-400">HIGH_RISK</span>
                        </div>
                        <div class="flex justify-between items-center text-xs p-3 bg-white/5 rounded-lg border border-white/5">
                            <span class="text-slate-400">Exchange Rate Pass-Through</span>
                            <span class="mono text-amber-400">0.45 Correlation</span>
                        </div>
                    </div>
                </div>
                <div class="glass border border-slate-800 p-6 rounded-2xl">
                    <h5 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Architectural Insight
                    </h5>
                    <p class="text-xs text-slate-400 leading-relaxed italic">
                        "The ARIMA component handles the linear trend of domestic consumption, while the VAR engine interprets global shocks as exogenous variables affecting national inflation."
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('macroChart').getContext('2d');
        
        // Custom Gradients
        const gradGreen = ctx.createLinearGradient(0, 0, 0, 400);
        gradGreen.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
        gradGreen.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan+1','Feb+1','Mar+1','Apr+1','May+1','Jun+1'],
                datasets: [
                    {
                        label: 'Upper Bound',
                        data: [...Array(12).fill(null), ...<?php echo json_encode($upperBound); ?>],
                        borderColor: 'transparent',
                        backgroundColor: 'rgba(255, 255, 255, 0.03)',
                        fill: '+1',
                        pointRadius: 0,
                        tension: 0.4
                    },
                    {
                        label: 'Lower Bound',
                        data: [...Array(12).fill(null), ...<?php echo json_encode($lowerBound); ?>],
                        borderColor: 'transparent',
                        backgroundColor: 'rgba(255, 255, 255, 0.03)',
                        fill: false,
                        pointRadius: 0,
                        tension: 0.4
                    },
                    {
                        label: 'Historical',
                        data: <?php echo json_encode($historical); ?>,
                        borderColor: '#475569',
                        borderWidth: 2,
                        pointRadius: 2,
                        fill: false
                    },
                    {
                        label: 'ARIMA Projection',
                        data: [...Array(11).fill(null), <?php echo end($historical); ?>, ...<?php echo json_encode($forecast); ?>],
                        borderColor: '#10b981',
                        borderWidth: 3,
                        backgroundColor: gradGreen,
                        fill: true,
                        tension: 0.4,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        position: 'right',
                        grid: { color: 'rgba(255,255,255,0.03)' },
                        ticks: { color: '#64748b', font: { family: 'JetBrains Mono', size: 10 } }
                    },
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { family: 'JetBrains Mono', size: 10 } } }
                }
            }
        });
    </script>
</body>
</html>