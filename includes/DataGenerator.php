<?php
class DataGenerator {
    // 1. Macroeconomic (ARIMA/VAR) - Price Predictions
    public static function getPriceForecast() {
        return [380, 395, 410, 405, 420, 440]; // Mocked upward trend
    }

    // 2. Remote Sensing (NDVI/EVI) - Crop Health
    public static function getHealthMetrics() {
        return [
            'ndvi' => number_format(0.5 + (mt_rand() / mt_getrandmax() * 0.3), 2),
            'evi' => number_format(0.4 + (mt_rand() / mt_getrandmax() * 0.2), 2)
        ];
    }

    // 3. Monte Carlo - Risk Distribution
    public static function getRiskData() {
        return [5, 12, 25, 45, 10, 3]; // Bell curve distribution
    }
}