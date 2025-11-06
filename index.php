<?php
// index.php

// Memulai Session untuk State Management 
session_start();

// Memuat file konfigurasi dan model dasar
require_once 'config.php';
require_once 'model/Database.php';
require_once 'model/User.php';

// Memuat semua controller
require_once 'controller/HomeController.php';
require_once 'controller/AuthController.php';

// --- Proses Routing ---
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

// Menentukan Controller
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'HomeController';
// Menentukan Method
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';

// Menghapus controller dan method dari array untuk mendapatkan parameter
unset($urlParts[0], $urlParts[1]);
$params = array_values($urlParts);

// Cek jika class controller ada, lalu buat object-nya
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    
    // Cek jika method ada di dalam controller
    if (method_exists($controller, $methodName)) {
        // Panggil method dan kirimkan parameter
        call_user_func_array([$controller, $methodName], $params);
    } else {
        echo "Error: Method $methodName not found in $controllerName";
    }
} else {
    echo "Error: Controller $controllerName not found";
}