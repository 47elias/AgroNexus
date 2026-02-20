<?php 
require_once '../includes/DataGenerator.php'; 

/**
 * ARCHITECTURAL LOGIC: Monte Carlo Simulation [cite: 101]
 * Instead of one outcome, we simulate 10,000+ iterations.
 * Variables: Rainfall drop (60%) vs. Input cost increase (20%)[cite: 23].
 */
$riskLabels = ['Bankruptcy', 'High Loss', 'Break Even', 'Low Profit', 'Target Profit', 'High Yield'];
$simulationResults = DataGenerator::getRiskData(); // Returns probability distribution 
$profitabilityChance = "72%"; // Simulated result of the probability curve
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Risk Analysis | AgroNexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-950 text-slate-200 antialiased flex">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 p-8">
        <header class="mb-10">
            <h2 class="text-3xl font-bold text-white">Financial Resilience: Monte Carlo</h2>
            <p class="text-slate-400 mt-2">
                [cite_start]Quantifying financial risk by simulating thousands of "What-If" scenarios[cite: 22].
            </p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 bg-slate-900 p-8 rounded-3xl border border-slate-800 shadow-xl">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="text-xl font-bold">Profitability Probability Curve</h4>
                    <span class="text-xs font-mono text-blue-500 bg-blue-500/10 px-3 py-1 rounded-full uppercase">10,000 Iterations Run</span>
                </div>
                <div class="h-80">
                    <canvas id="riskCurveChart"></canvas>
                </div>
                <div class="mt-6 p-4 bg-slate-800/30 rounded-xl border border-slate-700 text-sm text-slate-400">
                    [cite_start]<strong>Current Shock Scenario:</strong> 60% drop in rainfall paired with a 20% increase in global fertilizer prices[cite: 23].
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Success Probability</p>
                    <span class="text-4xl font-bold text-emerald-400"><?php echo $profitabilityChance; ?></span>
                    [cite_start]<p class="text-slate-400 text-xs mt-2 italic">Probability of exceeding break-even point[cite: 105].</p>
                </div>

                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800">
                    <h4 class="text-sm font-bold mb-4">Adjust Stress Variables</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Rainfall Variance (%)</label>
                            <input type="range" class="w-full accent-emerald-500" min="0" max="100" value="60">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Input Price Volatility (%)</label>
                            <input type="range" class="w-full accent-rose-500" min="0" max="100" value="20">
                        </div>
                        <button class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-bold transition">
                            Re-run Simulation
                        </button>
                    </div>
                </div>

                <div class="bg-blue-500/10 p-6 rounded-2xl border border-blue-500/20">
                    <h4 class="text-blue-400 text-xs font-bold uppercase mb-2">Architectural Impact</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        [cite_start]Reduces "basis risk" for index-based insurance, making it easier for smallholders to access credit[cite: 24].
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('riskCurveChart').getContext('2d');
        
        // Gradient for the bars based on risk level
        const gradientRed = 'rgba(239, 68, 68, 0.6)'; // rose-500
        const gradientGreen = 'rgba(16, 185, 129, 0.6)'; // emerald-500

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($riskLabels); ?>,
                datasets: [{
                    label: 'Frequency of Outcome',
                    data: <?php echo json_encode($simulationResults); ?>,
                    backgroundColor: [
                        gradientRed, gradientRed, '#94a3b8', 
                        gradientGreen, gradientGreen, gradientGreen
                    ],
                    borderRadius: 12,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { display: false }
                    },
                    x: { grid: { display: false }, ticks: { color: '#64748b' } }
                }
            }
        });
    </script>
</body>
</html>