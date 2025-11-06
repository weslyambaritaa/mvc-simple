<?php
// router.php

// Ambil path URL yang diminta, misal: /auth/showLogin
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Cek apakah yang diminta adalah file yang benar-benar ada (misal: gambar, css, js)
// Kita gunakan 'ltrim' untuk menghapus '/' di awal path agar 'file_exists' berfungsi
if (file_exists(__DIR__ . $urlPath) && is_file(__DIR__ . $urlPath)) {
    // Jika itu adalah file, biarkan server PHP menanganinya secara normal.
    return false; 
}

// --- Ini adalah bagian penting ---
// Jika bukan file, berarti itu adalah "route" (URL cantik).
// Kita akan atur variabel $_GET['url'] secara manual,
// persis seperti yang seharusnya dilakukan .htaccess.
$_GET['url'] = ltrim($urlPath, '/');

// Setelah $_GET['url'] diatur, kita jalankan file index.php
// yang berisi logika front-controller kita.
require_once 'index.php';