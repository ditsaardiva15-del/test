<?php
// File konfigurasi database dan fungsi-fungsi helper
// Data disimpan dalam file JSON untuk kemudahan (tanpa database SQL)

define('DATA_PATH', __DIR__ . '/../data/');

// Buat folder data jika belum ada
if(!file_exists(DATA_PATH)) {
    mkdir(DATA_PATH, 0777, true);
}

// Inisialisasi file data jika belum ada
function initDataFile($filename, $defaultData) {
    $filepath = DATA_PATH . $filename . '.json';
    if(!file_exists($filepath)) {
        file_put_contents($filepath, json_encode($defaultData, JSON_PRETTY_PRINT));
    }
    return json_decode(file_get_contents($filepath), true);
}

function saveDataFile($filename, $data) {
    $filepath = DATA_PATH . $filename . '.json';
    file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT));
}

// Default Data
$defaultMenu = [
    ['id' => 1, 'name' => 'Espresso Gayo', 'category' => 'kopi', 'price' => 28000, 'description' => 'Single origin Aceh Gayo, bold dan fruity', 'emoji' => '☕', 'badge' => 'popular', 'status' => 'active'],
    ['id' => 2, 'name' => 'Kopi Toraja', 'category' => 'kopi', 'price' => 35000, 'description' => 'Arabika Toraja dengan nuansa cokelat', 'emoji' => '🫘', 'badge' => 'hot', 'status' => 'active'],
    ['id' => 3, 'name' => 'Cappuccino Classico', 'category' => 'kopi', 'price' => 38000, 'description' => 'Espresso double shot dengan milk foam', 'emoji' => '☕', 'badge' => 'popular', 'status' => 'active'],
    ['id' => 4, 'name' => 'Cold Brew Flores', 'category' => 'kopi', 'price' => 42000, 'description' => 'Kopi Flores direndam 18 jam', 'emoji' => '🧊', 'badge' => 'new', 'status' => 'active'],
    ['id' => 5, 'name' => 'Latte Kintamani', 'category' => 'kopi', 'price' => 40000, 'description' => 'Single origin Bali Kintamani', 'emoji' => '🥛', 'badge' => '', 'status' => 'active'],
    ['id' => 6, 'name' => 'Teh Tarik Premium', 'category' => 'non', 'price' => 22000, 'description' => 'Teh hitam Ceylon dengan susu', 'emoji' => '🍵', 'badge' => '', 'status' => 'active'],
    ['id' => 7, 'name' => 'Es Matcha Latte', 'category' => 'non', 'price' => 35000, 'description' => 'Matcha grade A dengan oat milk', 'emoji' => '🍵', 'badge' => 'new', 'status' => 'active'],
    ['id' => 8, 'name' => 'Croissant Butter', 'category' => 'makanan', 'price' => 32000, 'description' => 'Croissant renyah mentega pilihan', 'emoji' => '🥐', 'badge' => 'popular', 'status' => 'active'],
];

$defaultTestimonials = [
    ['id' => 1, 'name' => 'Andika Pratama', 'role' => 'Coffee Blogger', 'text' => 'Kopi Gayo-nya luar biasa! Atmosphere nyaman banget.', 'rating' => 5],
    ['id' => 2, 'name' => 'Sari Indah', 'role' => 'Arsitek', 'text' => 'Tempat favorit kerja remote. Cold brew Flores-nya addictive!', 'rating' => 5],
    ['id' => 3, 'name' => 'Rizky Mahendra', 'role' => 'Fotografer', 'text' => 'Instagramable banget! Recommended banget!', 'rating' => 5],
];

$defaultOrders = [
    ['id' => 'KN-001', 'customer' => 'Andika P.', 'items' => 'Espresso Gayo x2', 'total' => 56000, 'time' => '09:15', 'status' => 'done'],
    ['id' => 'KN-002', 'customer' => 'Sari I.', 'items' => 'Cold Brew Flores', 'total' => 42000, 'time' => '09:45', 'status' => 'active'],
    ['id' => 'KN-003', 'customer' => 'Rizky M.', 'items' => 'Cappuccino x3', 'total' => 114000, 'time' => '10:20', 'status' => 'pending'],
];

$defaultReservasis = [
    ['id' => 1, 'name' => 'Budi Santoso', 'datetime' => '2025-01-20 19:00', 'meja' => 'Meja 5', 'tamu' => 4, 'status' => 'active'],
    ['id' => 2, 'name' => 'PT. Karya Maju', 'datetime' => '2025-01-21 13:00', 'meja' => 'Ruang Meeting', 'tamu' => 8, 'status' => 'pending'],
];

$defaultPromos = [
    ['id' => 1, 'name' => 'Happy Hour', 'disc' => 20, 'start_date' => '2025-01-01', 'end_date' => '2025-03-31', 'code' => 'HAPPY20', 'status' => 'active'],
];

$defaultContact = [
    'alamat' => 'Jl. Pemuda No. 88, Semarang Tengah, Jawa Tengah 50132',
    'jam_buka' => "Senin – Jumat: 07.00 – 22.00\nSabtu – Minggu: 08.00 – 23.00",
    'telepon' => '+62 24 8888 7777',
    'email' => 'hello@kopinusantara.id'
];

$defaultStats = [
    'jenis_kopi' => 45,
    'pelanggan' => 5,
    'tahun' => 2015
];

$defaultSettings = [
    'site_name' => 'Kopi Nusantara',
    'footer_text' => '© 2025 Kopi Nusantara. Semua hak dilindungi.'
];

// Getter functions
function getMenuItems() { return initDataFile('menu', $GLOBALS['defaultMenu']); }
function saveMenuItems($data) { saveDataFile('menu', $data); }

function getTestimonials() { return initDataFile('testimonials', $GLOBALS['defaultTestimonials']); }
function saveTestimonials($data) { saveDataFile('testimonials', $data); }

function getOrders() { return initDataFile('orders', $GLOBALS['defaultOrders']); }
function saveOrders($data) { saveDataFile('orders', $data); }

function getReservations() { return initDataFile('reservasis', $GLOBALS['defaultReservasis']); }
function saveReservations($data) { saveDataFile('reservasis', $data); }

function getPromos() { return initDataFile('promos', $GLOBALS['defaultPromos']); }
function savePromos($data) { saveDataFile('promos', $data); }

function getContactInfo() { return initDataFile('contact', $GLOBALS['defaultContact']); }
function saveContactInfo($data) { saveDataFile('contact', $data); }

function getStats() { return initDataFile('stats', $GLOBALS['defaultStats']); }
function saveStats($data) { saveDataFile('stats', $data); }

function getSettings() { return initDataFile('settings', $GLOBALS['defaultSettings']); }
function saveSettings($data) { saveDataFile('settings', $data); }
?>
