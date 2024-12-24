<?php

define('ENVIRONMENT', 'testing'); // Atur lingkungan sesuai kebutuhan Anda

// Tentukan BASEPATH menggunakan path absolut tanpa realpath untuk sementara
// $system_path = str_replace('\\', '/', 'C:/Projek PHP/barang/system');
// define('BASEPATH', rtrim($system_path, '/') . '/'); // Gunakan forward slash untuk konsistensi

$system_path = realpath('C:/Projek PHP/barang/system') . '/';
define('BASEPATH', str_replace('\\', '/', $system_path));

// Debug untuk memastikan BASEPATH benar
var_dump(BASEPATH);

// Pastikan file Common.php ada
$commonPath = BASEPATH . 'core/Common.php';
if (file_exists($commonPath)) {
    require_once $commonPath;
    var_dump($commonPath); // Debug path untuk memastikan path Common.php
} else {
    die('File Common.php tidak ditemukan di path: ' . $commonPath);
}

// Tentukan APPPATH dan VIEWPATH untuk CodeIgniter
if (!defined('APPPATH')) {
    define('APPPATH', rtrim(str_replace('\\', '/', 'C:/Projek PHP/barang/application'), '/') . '/');
}

if (!defined('VIEWPATH')) {
    define('VIEWPATH', APPPATH . 'views' . '/');
}

// Set default HTTP_HOST jika belum ada
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}

// Debug untuk memastikan path yang benar
var_dump(APPPATH);  // Debug path application
var_dump(VIEWPATH);  // Debug path view

// Pastikan sistem dan aplikasi ada
if (!file_exists($system_path) || !file_exists(APPPATH)) {
    die('Sistem atau aplikasi path tidak ditemukan!');
}

// Menambahkan include_path untuk sistem dan aplikasi
set_include_path(get_include_path() . PATH_SEPARATOR . BASEPATH . PATH_SEPARATOR . APPPATH);

// Load file autoloader Composer dari folder unittest
$autoload_path = str_replace('\\', '/', __DIR__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) {
    require_once $autoload_path;
    var_dump($autoload_path); // Debug untuk memastikan path autoload benar
} else {
    die('File vendor/autoload.php tidak ditemukan di path: ' . $autoload_path);
}

// Memuat file CodeIgniter setelah autoloader Composer
$codeIgniterPath = BASEPATH . 'core/CodeIgniter.php';
if (file_exists($codeIgniterPath)) {
    require_once $codeIgniterPath;
    echo BASEPATH . 'core/CodeIgniter.php'; // Tampilkan jalur yang akan dimuat
    var_dump($codeIgniterPath); // Debug untuk memastikan path CodeIgniter benar
} else {
    die('File CodeIgniter.php tidak ditemukan di path: ' . $codeIgniterPath);
}
// $codeIgniterPath = BASEPATH . 'core/CodeIgniter.php';
// if (!file_exists($codeIgniterPath)) {
//     die('File CodeIgniter.php tidak ditemukan di path: ' . $codeIgniterPath);
// }
// require_once $codeIgniterPath;

// Memuat konfigurasi constants CodeIgniter
require_once APPPATH . 'config/constants.php';

// Initialize CodeIgniter instance 
new CI_Controller();
