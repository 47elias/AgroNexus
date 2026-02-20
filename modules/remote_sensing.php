<?php 
require_once '../includes/DataGenerator.php'; 

// Fetching mock health metrics from our core generator
$metrics = DataGenerator::getHealthMetrics(); // returns ['ndvi' => 0.65, 'evi' => 0.52]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remote Sensing | AgroNexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
</head>
<body class="bg-slate-950 text-slate-200 antialiased flex">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 p-8">
        <header class="mb-8 flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-bold">Remote Sensing: Satellite Monitoring</h2>
                <p class="text-slate-400 mt-2">Using NDVI and EVI indices to detect agricultural stress patterns early.</p>
            </div>
            <div class="flex bg-slate-900 p-1 rounded-xl border border-slate-800">
                <button id="btnNDVI" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold transition">NDVI</button>
                <button id="btnEVI" class="px-4 py-2 hover:bg-slate-800 text-slate-400 rounded-lg text-sm transition">EVI</button>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[600px]">
            <div id="map" class="lg:col-span-3 bg-slate-900 rounded-3xl border border-slate-800 shadow-2xl relative overflow-hidden">
                </div>

            <div class="space-y-6">
                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-4">Current Indices</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">NDVI Value</span>
                            <span class="text-xl font-mono text-emerald-400 font-bold"><?php echo $metrics['ndvi']; ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">EVI Value</span>
                            <span class="text-xl font-mono text-blue-400 font-bold"><?php echo $metrics['evi']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-emerald-500/10 p-6 rounded-2xl border border-emerald-500/20">
                    <h4 class="text-emerald-400 text-sm font-bold mb-2">Architectural Logic</h4>
                    <p class="text-xs text-slate-400 leading-relaxed italic">
                        "By checking these weekly, a farmer can see a 'hotspot' of stress... before plants actually turn yellow to the naked eye."
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize Map (Simulating a farm location in Zimbabwe)
        const map = L.map('map').setView([-17.824, 31.053], 13);
        
        // Add Satellite Base Layer
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS'
        }).addTo(map);

        // Simulated NDVI Heatmap Data Points
        const ndviPoints = [
            [-17.820, 31.050, 0.8], [-17.822, 31.055, 0.6], 
            [-17.825, 31.053, 0.9], [-17.828, 31.057, 0.4]
        ];
        
        // Create Heatmap Layer
        const heatLayer = L.heatLayer(ndviPoints, {
            radius: 40,
            blur: 15,
            maxZoom: 17,
            gradient: {0.4: 'red', 0.65: 'yellow', 1: 'lime'}
        }).addTo(map);

        // Simple Switcher Logic
        document.getElementById('btnNDVI').onclick = () => {
            alert('Switching to NDVI Analysis: Measuring general biomass greenness.');
            // Logic to update heatmap data would go here
        };
        document.getElementById('btnEVI').onclick = () => {
            alert('Switching to EVI Analysis: Adjusting for atmospheric noise in dense canopies.');
        };
    </script>
</body>
</html>