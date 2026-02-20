<?php 
require_once '../includes/DataGenerator.php'; 
// This file now acts as both the UI and the API endpoint for analysis
if (isset($_GET['api_analyze'])) {
    header('Content-Type: application/json');
    sleep(2); // Simulate neural network processing latency

    $pests = [
        ['name' => 'Fall Armyworm', 'scientific' => 'Spodoptera frugiperda', 'conf' => rand(88, 99), 'severity' => 'Critical', 'color' => 'rose'],
        ['name' => 'Maize Lethal Necrosis', 'scientific' => 'MLN Virus', 'conf' => rand(70, 85), 'severity' => 'High', 'color' => 'amber'],
        ['name' => 'Grey Leaf Spot', 'scientific' => 'Cercospora zeae-maydis', 'conf' => rand(90, 96), 'severity' => 'Moderate', 'color' => 'emerald'],
        ['name' => 'Healthy Leaf', 'scientific' => 'Zea mays', 'conf' => rand(98, 100), 'severity' => 'None', 'color' => 'blue']
    ];
    
    echo json_encode($pests[array_rand($pests)]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AgroNexus Vision | Edge Intelligence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Inter:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #020408; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.08); }
        
        /* Tactical Viewfinder */
        .reticle {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 260px; height: 260px;
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 40px; pointer-events: none;
        }
        .reticle::before {
            content: ''; position: absolute; inset: -10px;
            border: 2px solid #10b981; border-radius: 45px;
            clip-path: polygon(0 0, 30% 0, 30% 10%, 0 10%, 0 30%, 10% 30%, 10% 0, 0 0, 100% 0, 100% 30%, 90% 30%, 90% 10%, 70% 10%, 70% 0, 100% 0, 100% 100%, 70% 100%, 70% 90%, 90% 90%, 90% 70%, 100% 70%, 100% 100%, 0 100%, 0 70%, 10% 70%, 10% 90%, 30% 90%, 30% 100%, 0 100%);
        }

        /* Processing Loader */
        .ai-loader {
            display: none; position: absolute; inset: 0;
            background: rgba(0,0,0,0.7); z-index: 60;
            flex-direction: column; align-items: center; justify-content: center;
        }

        .shutter-flash { animation: flash 0.4s ease-out; }
        @keyframes flash { 0% { background: white; } 100% { background: transparent; } }
        
        #camera-feed.mirror { transform: scaleX(-1); }
    </style>
</head>
<body class="text-slate-300 antialiased flex h-screen overflow-hidden">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 flex flex-col relative overflow-hidden">
        <header class="h-14 border-b border-white/5 flex items-center justify-between px-6 glass z-50">
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_#10b981]"></span>
                <h1 class="text-[10px] font-black mono tracking-[0.3em] text-white">SYSTEM_EYE_v4.0</h1>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="toggleLens()" class="p-2 hover:bg-white/5 rounded-full transition text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden lg:flex-row flex-col">
            
            <div class="relative flex-1 bg-black flex items-center justify-center overflow-hidden">
                <video id="camera-feed" autoplay playsinline class="w-full h-full object-cover transition-opacity duration-500"></video>
                <div class="reticle"></div>
                
                <div id="flash" class="absolute inset-0 pointer-events-none z-50"></div>

                <div id="loader" class="ai-loader">
                    <div class="w-12 h-12 border-4 border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin"></div>
                    <p class="mt-4 text-[10px] mono font-bold text-emerald-500 tracking-widest">RUNNING_INFERENCE...</p>
                </div>

                <div class="absolute bottom-8 left-0 right-0 flex justify-center items-center gap-8 z-40">
                    <div class="text-center hidden lg:block">
                        <p class="text-[9px] font-bold text-slate-500 uppercase">Resolution</p>
                        <p class="text-xs text-white mono" id="res-val">1080p</p>
                    </div>
                    <button id="capture-btn" onclick="performAnalysis()" class="w-20 h-20 rounded-full border-4 border-white/20 p-1 bg-white/5 hover:scale-105 active:scale-95 transition-all">
                        <div class="w-full h-full bg-white rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                            <div class="w-6 h-6 border-2 border-black rounded-sm"></div>
                        </div>
                    </button>
                    <div class="text-center hidden lg:block">
                        <p class="text-[9px] font-bold text-slate-500 uppercase">Latency</p>
                        <p class="text-xs text-emerald-400 mono">12ms</p>
                    </div>
                </div>
            </div>

            <aside class="w-full lg:w-[420px] bg-[#05070a] border-l border-white/5 flex flex-col z-50">
                <div class="p-6 overflow-y-auto flex-1 space-y-8" id="results-panel">
                    
                    <div id="idle-state" class="h-full flex flex-col items-center justify-center text-center space-y-6 opacity-60">
                        <div class="w-20 h-20 bg-white/5 rounded-[2.5rem] flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-lg">Awaiting Capture</h3>
                            <p class="text-sm text-slate-500 mt-2 px-8">Point camera at crop foliage. Ensure high contrast and steady focus for CNN analysis.</p>
                        </div>
                    </div>

                    <div id="result-state" class="hidden space-y-6 animate-in fade-in slide-in-from-right duration-500">
                        <div class="relative group rounded-3xl overflow-hidden border border-white/10">
                            <img id="capture-preview" class="w-full aspect-video object-cover" src="">
                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-60"></div>
                            <span id="severity-badge" class="absolute bottom-4 left-4 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">CRITICAL</span>
                        </div>

                        <div class="space-y-2">
                            <p class="text-xs mono text-emerald-500 font-bold tracking-widest uppercase">Classification_Found</p>
                            <h2 id="pest-name" class="text-4xl font-black text-white tracking-tighter uppercase">---</h2>
                            <p id="pest-sci" class="text-sm italic text-slate-500">---</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                                <p class="text-[9px] font-bold text-slate-500 uppercase mb-1">Confidence</p>
                                <p id="pest-conf" class="text-2xl font-black text-white mono">0%</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                                <p class="text-[9px] font-bold text-slate-500 uppercase mb-1">Model Score</p>
                                <p class="text-2xl font-black text-blue-400 mono">0.98</p>
                            </div>
                        </div>

                        <div class="p-6 rounded-3xl glass space-y-4">
                            <h4 class="text-xs font-bold text-white uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Recommended Action
                            </h4>
                            <p class="text-sm text-slate-300 leading-relaxed">Based on current morphology, localized treatment with organic pest repellents is advised. Isolate the affected row immediately to prevent spore migration.</p>
                            <button class="w-full py-4 bg-white text-black text-xs font-black uppercase rounded-xl hover:bg-emerald-500 transition-colors">Broadcast Alert to Farm</button>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <script>
        const video = document.getElementById('camera-feed');
        const loader = document.getElementById('loader');
        const flash = document.getElementById('flash');
        const idleState = document.getElementById('idle-state');
        const resultState = document.getElementById('result-state');
        
        let stream = null;
        let facingMode = "environment";

        async function initCamera() {
            if (stream) stream.getTracks().forEach(t => t.stop());
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
                });
                video.srcObject = stream;
                video.classList.toggle('mirror', facingMode === 'user');
                const settings = stream.getVideoTracks()[0].getSettings();
                document.getElementById('res-val').innerText = `${settings.width}x${settings.height}`;
            } catch (err) {
                console.error("Camera error:", err);
                alert("Please enable camera access for AI diagnosis.");
            }
        }

        function toggleLens() {
            facingMode = (facingMode === "user") ? "environment" : "user";
            initCamera();
        }

        async function performAnalysis() {
            // UI Feedback
            flash.classList.add('shutter-flash');
            setTimeout(() => flash.classList.remove('shutter-flash'), 400);
            
            // Capture image to canvas to preview
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            document.getElementById('capture-preview').src = canvas.toDataURL('image/jpeg');

            // Show Loading
            loader.style.display = 'flex';
            
            try {
                // Fully Functional Random Backend Call
                const response = await fetch('?api_analyze=true');
                const data = await response.json();
                
                // Update UI
                idleState.classList.add('hidden');
                resultState.classList.remove('hidden');
                
                document.getElementById('pest-name').innerText = data.name;
                document.getElementById('pest-sci').innerText = data.scientific;
                document.getElementById('pest-conf').innerText = data.conf + '%';
                
                const badge = document.getElementById('severity-badge');
                badge.innerText = data.severity;
                badge.className = `absolute bottom-4 left-4 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter bg-${data.color}-500 text-white`;

            } catch (err) {
                console.error("Inference Error:", err);
            } finally {
                loader.style.display = 'none';
            }
        }

        window.onload = initCamera;
    </script>
</body>
</html>