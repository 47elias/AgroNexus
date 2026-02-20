<?php 
require_once '../includes/DataGenerator.php'; 

// Simulated AI Diagnosis Data
$pestDatabase = [
    'fall_armyworm' => [
        'name' => 'Fall Armyworm (Spodoptera frugiperda)',
        'confidence' => '98.4%',
        'severity' => 'Critical',
        'advice' => 'Apply targeted insecticide immediately. Focus on the whorl of the plant where larvae feed.'
    ]
];

// Logic to simulate an upload result
$diagnosis = null;
if (isset($_POST['analyze'])) {
    // Simulating the "Convolution & Pooling" process [cite: 109, 110]
    sleep(1); 
    $diagnosis = $pestDatabase['fall_armyworm'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Field Diagnostics | AgroNexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .scan-line {
            width: 100%;
            height: 2px;
            background: #10b981;
            position: absolute;
            top: 0;
            left: 0;
            box-shadow: 0 0 15px #10b981;
            animation: scan 2s linear infinite;
            display: none;
        }
        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased flex">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 p-8">
        <header class="mb-10">
            <h2 class="text-3xl font-bold">Field Diagnostics: AI Pathologist</h2>
            [cite_start]<p class="text-slate-400 mt-2">Mobile "edge" tool for immediate pest and disease identification[cite: 26].</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-slate-900 p-8 rounded-3xl border border-slate-800 shadow-xl">
                <h4 class="text-xl font-bold mb-6">Visual Analysis Engine</h4>
                
                <form method="POST" onsubmit="document.querySelector('.scan-line').style.display='block';">
                    <div class="relative w-full h-64 bg-slate-800 rounded-2xl border-2 border-dashed border-slate-700 flex flex-col items-center justify-center overflow-hidden">
                        <div class="scan-line"></div>
                        
                        <?php if (!$diagnosis): ?>
                            <svg class="w-12 h-12 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            [cite_start]<p class="text-slate-400 text-sm">Upload leaf photo for CNN analysis [cite: 108]</p>
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1597338834069-4bc27845348c?auto=format&fit=crop&q=80&w=400" class="object-cover w-full h-full opacity-60">
                        <?php endif; ?>
                    </div>
                    
                    <button name="analyze" class="w-full mt-6 py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold transition flex items-center justify-center gap-2">
                        <span>Run AI Diagnostic</span>
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                <?php if ($diagnosis): ?>
                    <div class="bg-slate-900 p-8 rounded-3xl border border-emerald-500/30 animate-fade-in">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-widest">Diagnosis Found</span>
                            <span class="text-slate-500 text-xs font-mono">Conf: <?php echo $diagnosis['confidence']; ?></span>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-white mb-2"><?php echo $diagnosis['name']; ?></h3>
                        <div class="inline-block px-3 py-1 bg-rose-500/20 text-rose-400 text-xs rounded mb-6">Severity: <?php echo $diagnosis['severity']; ?></div>
                        
                        <div class="p-4 bg-slate-800/50 rounded-xl border border-slate-700">
                            [cite_start]<h5 class="text-sm font-bold text-emerald-400 mb-2 uppercase tracking-wide">Actionable Advice [cite: 28]</h5>
                            <p class="text-slate-300 text-sm leading-relaxed"><?php echo $diagnosis['advice']; ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-slate-900/50 p-8 rounded-3xl border border-slate-800 flex items-center justify-center h-full">
                        [cite_start]<p class="text-slate-500 italic text-center">Waiting for visual input to compare against PlantVillage database[cite: 111]...</p>
                    </div>
                <?php endif; ?>

                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-4">Architectural Logic</h4>
                    <ul class="text-xs text-slate-400 space-y-2">
                        [cite_start]<li>• <strong>Convolution:</strong> Scans for holes/yellowing[cite: 109].</li>
                        [cite_start]<li>• <strong>Pooling:</strong> Focuses on specific lesions[cite: 110].</li>
                        [cite_start]<li>• <strong>Classification:</strong> MobileNet comparison[cite: 108].</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>
</html>