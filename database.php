<?php
// JSON tabanlı veritabanı sistemi

define('TARIFLER_FILE', __DIR__ . '/data/tarifler.json');

function initDatabase() {
    $dataDir = __DIR__ . '/data';
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    if (!file_exists(TARIFLER_FILE)) {
        file_put_contents(TARIFLER_FILE, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

function loadTarifler() {
    if (!file_exists(TARIFLER_FILE)) {
        return [];
    }
    $content = file_get_contents(TARIFLER_FILE);
    return json_decode($content, true) ?: [];
}

function saveTarifler($tarifler) {
    file_put_contents(TARIFLER_FILE, json_encode($tarifler, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Veritabanını başlat
initDatabase();
?>
