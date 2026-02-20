<?php 
require_once '../includes/DataGenerator.php'; 

// "Real" Logic: Monte Carlo Math Simulation
// In a real app, these values would come from an XGBoost or Monte Carlo PHP library.
$riskLabels = ['-$10k', '-$5k', 'Break Even', '+$5k', '+$10k', '+$15k', '+$20k'];
// Bell curve simulation (Normal Distribution)
$simulationResults = [5, 12, 28, 42, 25, 10, 3]; 
$profitabilityChance = "78.4%";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Risk Simulator | AgroNexus Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.05); }
        .terminal-grid { background-image: radial-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 0); background-size: 24px 24px; }
    </style>
</head>
<body class="bg-[#05070a] text-slate-300 antialiased flex overflow-hidden terminal-grid">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen relative">
        <div class="h-16 border-b border-slate-800/50 flex items-center justify-between px-8 glass sticky top-0 z-50">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest text-blue-500 mono">SIM_ENGINE_v4.2</span>
                </div>
                <div class="text-[10px] text-slate-500 font-bold uppercase mono tracking-tighter">
                    Precision: <span class="text-slate-300">10,000 ITERATIONS/SEC</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-[10px] text-slate-500 font-bold">MODE: STOCHASTIC_VOLATILITY</span>
            </div>
        </div>

        <div class="flex-1 p-8 overflow-y-auto">
            <header class="mb-10">
                <h2 class="text-3xl font-extrabold text-white tracking-tighter">Financial Resilience Simulator</h2>
                <p class="text-slate-400 mt-2 text-sm max-w-2xl">
                    Probabilistic modeling of farm-level solvency using climate-shock variables and input-cost variance.
                </p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <div class="lg:col-span-3 glass p-8 rounded-3xl shadow-2xl relative">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h4 class="text-lg font-bold text-white">Probability Density Function (PDF)</h4>
                            <p class="text-[10px] text-slate-500 mono uppercase mt-1">Outcome Distribution: Profit/Loss vs Probability</p>
                        </div>
                        <div class="p-2 bg-blue-500/10 rounded-lg border border-blue-500/20">
                            <span class="text-blue-400 text-xs font-bold mono">CI: 95.0%</span>
                        </div>
                    </div>
                    
                    <div class="h-[350px]">
                        <canvas id="riskCurveChart"></canvas>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <div class="flex-1 p-4 bg-white/5 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-slate-500 font-bold uppercase block mb-1">Climate Stressor</span>
                            <p class="text-xs text-slate-300">Rainfall Variance: <span class="text-rose-400">-60.0%</span></p>
                        </div>
                        <div class="flex-1 p-4 bg-white/5 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-slate-500 font-bold uppercase block mb-1">Economic Shock</span>
                            <p class="text-xs text-slate-300">Fertilizer Inflation: <span class="text-rose-400">+20.0%</span></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="glass p-6 rounded-2xl border-l-4 border-l-emerald-500">
                        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Insolvency Risk Score</p>
                        <span class="text-4xl font-black text-white tracking-tighter"><?php echo $profitabilityChance; ?></span>
                        <p class="text-[10px] text-emerald-500 mt-2 font-mono">CHANCE_OF_PROFIT</p>
                    </div>

                    <div class="glass p-6 rounded-2xl">
                        <h4 class="text-xs font-bold text-white uppercase mb-6 tracking-widest">Variable Injection</h4>
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <label class="text-[10px] text-slate-500 font-bold uppercase">Precipitation</label>
                                    <span class="text-[10px] text-white mono" id="valRain">-60%</span>
                                </div>
                                <input type="range" class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-blue-500" min="-100" max="100" value="-60" oninput="document.getElementById('valRain').innerText = this.value + '%'">
                            </div>
                            <div>
                                <div class="flex justify-between mb-2">
                                    <label class="text-[10px] text-slate-500 font-bold uppercase">Input Costs</label>
                                    <span class="text-[10px] text-white mono" id="valCost">+20%</span>
                                </div>
                                <input type="range" class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-rose-500" min="0" max="100" value="20" oninput="document.getElementById('valCost').innerText = '+' + this.value + '%'">
                            </div>
                            <button onclick="location.reload()" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20">
                                Run Monte Carlo
                            </button>
                        </div>
                    </div>

                    <div class="p-6 bg-blue-500/5 rounded-2xl border border-blue-500/10">
                        <h4 class="text-blue-400 text-[10px] font-bold uppercase mb-2">Model Architecture</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed italic">
                            By simulating the "Basis Risk" between satellite-derived rainfall and actual yield, we define the parameters for parametric insurance triggers.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('riskCurveChart').getContext('2d');
        
        const gradBlue = ctx.createLinearGradient(0, 0, 0, 400);
        gradBlue.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
        gradBlue.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($riskLabels); ?>,
                datasets: [{
                    label: 'Probability Density',
                    data: <?php echo json_encode($simulationResults); ?>,
                    borderColor: '#3b82f6',
                    borderWidth: 4,
                    backgroundColor: gradBlue,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: 'rgba(255,255,255,0.02)', drawBorder: false },
                        ticks: { display: false } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { color: '#475569', font: { family: 'JetBrains Mono', size: 10 } } 
                    }
                }
            }
        });
    </script>
</body>
</html>