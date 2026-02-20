<?php 
require_once '../includes/DataGenerator.php'; 
$metrics = DataGenerator::getHealthMetrics(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>High-Precision Sensor Uplink | AgroNexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    
    <style>
        /* High-Precision Pulsing Blue Dot */
        .sensor-dot {
            width: 14px;
            height: 14px;
            background-color: #3b82f6;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.8);
            position: relative;
        }
        .sensor-dot::after {
            content: '';
            position: absolute;
            top: -2px; left: -2px;
            width: 14px; height: 14px;
            border-radius: 50%;
            border: 2px solid #3b82f6;
            animation: ripple 1.5s infinite;
        }
        @keyframes ripple {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(4); opacity: 0; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased flex overflow-hidden">

    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 flex flex-col h-screen">
        <header class="p-6 bg-slate-900/50 border-b border-slate-800 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Sensor-Driven Remote Sensing</h2>
                <div class="flex items-center gap-3 mt-1">
                    <div id="signalLight" class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                    <span id="signalText" class="text-xs font-mono text-slate-500 uppercase tracking-widest">Searching for GPS Hardware...</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <div class="bg-slate-950 px-4 py-2 rounded-lg border border-slate-800 flex flex-col items-center">
                    <span class="text-[10px] text-slate-500 font-bold uppercase">Accuracy</span>
                    <span id="accDisplay" class="text-emerald-400 font-mono text-sm">-- m</span>
                </div>
                <div class="bg-slate-950 px-4 py-2 rounded-lg border border-slate-800 flex flex-col items-center">
                    <span class="text-[10px] text-slate-500 font-bold uppercase">Satellites</span>
                    <span class="text-blue-400 font-mono text-sm">Active</span>
                </div>
            </div>
        </header>

        <div class="flex-1 flex flex-col lg:flex-row">
            <div id="map" class="flex-1 z-10 relative bg-slate-900">
                <div class="absolute bottom-8 left-8 z-[1000] bg-slate-900/90 p-4 rounded-xl border border-slate-700 backdrop-blur-md">
                    <p class="text-[10px] font-bold text-slate-500 uppercase mb-2">Spectral Index Legend</p>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div> <span class="text-[10px]">High Stress</span>
                    </div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full"></div> <span class="text-[10px]">Moderate</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-lime-500 rounded-full"></div> <span class="text-[10px]">Optimal Growth</span>
                    </div>
                </div>
            </div>

            <aside class="w-full lg:w-80 bg-slate-950 border-l border-slate-800 p-6 space-y-6 overflow-y-auto">
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Live Coordinates</h4>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="bg-slate-900 p-3 rounded-lg border border-slate-800">
                            <label class="text-[10px] text-slate-500 uppercase">Latitude</label>
                            <p id="latDisplay" class="font-mono text-white text-sm">Waiting...</p>
                        </div>
                        <div class="bg-slate-900 p-3 rounded-lg border border-slate-800">
                            <label class="text-[10px] text-slate-500 uppercase">Longitude</label>
                            <p id="lngDisplay" class="font-mono text-white text-sm">Waiting...</p>
                        </div>
                    </div>
                </div>

                <div class="bg-emerald-500/5 border border-emerald-500/20 p-4 rounded-xl">
                    <h4 class="text-emerald-400 text-xs font-bold uppercase mb-2">Agronomic Context</h4>
                    <p class="text-[11px] text-slate-400 leading-relaxed italic">
                        Current data is cross-referenced with your precise GPS location to simulate <strong>NDVI</strong> (Normalised Difference Vegetation Index) for this specific soil profile.
                    </p>
                </div>

                <div class="space-y-2">
                    <button id="recenterBtn" class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        RE-CENTER SENSOR
                    </button>
                    <button onclick="window.location.reload()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition-all">
                        RE-SYNC SATELLITE
                    </button>
                </div>
            </aside>
        </div>
    </main>

    <script>
        // 1. Setup High-Definition Satellite Map
        const map = L.map('map', { zoomControl: false }).setView([0, 0], 2);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'AgroNexus Hardware Interfacing'
        }).addTo(map);

        let sensorMarker, accuracyRadius, heatLayer;
        
        const sensorIcon = L.divIcon({
            className: 'custom-sensor-icon',
            html: "<div class='sensor-dot'></div>",
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });

        // 2. High-Precision Tracking Options
        const sensorOptions = {
            enableHighAccuracy: true, // Forces Hardware GPS
            timeout: 10000,           // 10 second timeout
            maximumAge: 0             // No cached locations
        };

        function onSensorUpdate(position) {
            const { latitude, longitude, accuracy } = position.coords;
            const currentPos = [latitude, longitude];

            // Update UI
            document.getElementById('latDisplay').innerText = latitude.toFixed(6);
            document.getElementById('lngDisplay').innerText = longitude.toFixed(6);
            document.getElementById('accDisplay').innerText = `${accuracy.toFixed(1)} m`;
            
            // Visual feedback on signal quality
            const signalLight = document.getElementById('signalLight');
            const signalText = document.getElementById('signalText');
            if(accuracy < 20) {
                signalLight.className = "w-2 h-2 bg-emerald-500 rounded-full";
                signalText.innerText = "High Precision Lock (GPS Hardware)";
            } else {
                signalLight.className = "w-2 h-2 bg-amber-500 rounded-full";
                signalText.innerText = "Low Precision (Triangulating...)";
            }

            // Update Marker and Accuracy Radius
            if (!sensorMarker) {
                sensorMarker = L.marker(currentPos, { icon: sensorIcon }).addTo(map);
                accuracyRadius = L.circle(currentPos, { 
                    radius: accuracy, 
                    color: '#3b82f6', 
                    fillColor: '#3b82f6', 
                    fillOpacity: 0.1, 
                    weight: 1 
                }).addTo(map);
                map.flyTo(currentPos, 18);
                generateHeatmap(latitude, longitude);
            } else {
                sensorMarker.setLatLng(currentPos);
                accuracyRadius.setLatLng(currentPos).setRadius(accuracy);
            }
        }

        function generateHeatmap(lat, lng) {
            // Generates health data exactly where the sensor is
            const dataPoints = [];
            for (let i = 0; i < 25; i++) {
                dataPoints.push([
                    lat + (Math.random() - 0.5) * 0.005, 
                    lng + (Math.random() - 0.5) * 0.005, 
                    Math.random()
                ]);
            }
            if (heatLayer) map.removeLayer(heatLayer);
            heatLayer = L.heatLayer(dataPoints, {
                radius: 30, 
                blur: 20, 
                gradient: {0.2: 'red', 0.5: 'yellow', 1: 'lime'}
            }).addTo(map);
        }

        document.getElementById('recenterBtn').onclick = () => {
            if (sensorMarker) map.flyTo(sensorMarker.getLatLng(), 18);
        };

        // 3. Initiate the Sensor Uplink
        if ("geolocation" in navigator) {
            navigator.geolocation.watchPosition(onSensorUpdate, (err) => {
                console.error(err);
                document.getElementById('signalText').innerText = "Hardware Fault: Check GPS Permissions";
            }, sensorOptions);
        } else {
            alert("Sensor Hardware Not Detected.");
        }
    </script>
</body>
</html>