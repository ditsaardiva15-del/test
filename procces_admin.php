<?php
session_start();
require_once 'config/database.php';

$response = ['success' => false, 'message' => ''];

// Handle Login
if($_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Default credentials (bisa diubah via admin panel nanti)
    $validUsername = 'admin';
    $validPassword = 'kopi123';
    
    if($username === $validUsername && $password === $validPassword) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        header('Location: admin_login.php?error=1');
        exit;
    }
}

// Check if logged in for other actions
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$action = $_POST['action'] ?? '';

// MENU ACTIONS
if($action === 'add_menu') {
    $menu = getMenuItems();
    $newId = count($menu) > 0 ? max(array_column($menu, 'id')) + 1 : 1;
    $menu[] = [
        'id' => $newId,
        'name' => $_POST['name'],
        'category' => $_POST['category'],
        'price' => (int)$_POST['price'],
        'description' => $_POST['description'] ?? '',
        'emoji' => $_POST['emoji'] ?? '☕',
        'badge' => $_POST['badge'] ?? '',
        'status' => $_POST['status'] ?? 'active'
    ];
    saveMenuItems($menu);
    header('Location: admin.php?success=Menu berhasil ditambahkan');
    exit;
}

if($action === 'delete_menu') {
    $menu = getMenuItems();
    $menu = array_filter($menu, fn($m) => $m['id'] != $_POST['id']);
    saveMenuItems(array_values($menu));
    echo json_encode(['success' => true]);
    exit;
}

if($action === 'toggle_menu') {
    $menu = getMenuItems();
    foreach($menu as &$m) {
        if($m['id'] == $_POST['id']) {
            $m['status'] = $_POST['status'];
            break;
        }
    }
    saveMenuItems($menu);
    echo json_encode(['success' => true]);
    exit;
}

// ORDERS ACTIONS
if($action === 'delete_order') {
    $orders = getOrders();
    $orders = array_filter($orders, fn($o) => $o['id'] != $_POST['id']);
    saveOrders(array_values($orders));
    echo json_encode(['success' => true]);
    exit;
}

if($action === 'update_order') {
    $orders = getOrders();
    $statusCycle = ['pending', 'active', 'done'];
    foreach($orders as &$o) {
        if($o['id'] == $_POST['id']) {
            $currentIndex = array_search($o['status'], $statusCycle);
            $o['status'] = $statusCycle[($currentIndex + 1) % 3];
            break;
        }
    }
    saveOrders($orders);
    echo json_encode(['success' => true]);
    exit;
}

if($action === 'add_sample_order') {
    $orders = getOrders();
    $activeMenu = array_filter(getMenuItems(), fn($m) => $m['status'] === 'active');
    $randomMenu = $activeMenu[array_rand($activeMenu)];
    $newId = 'KN-' . str_pad(count($orders) + 1, 3, '0', STR_PAD_LEFT);
    $orders[] = [
        'id' => $newId,
        'customer' => 'Pelanggan Baru',
        'items' => $randomMenu['name'] . ' x1',
        'total' => $randomMenu['price'],
        'time' => date('H:i'),
        'status' => 'pending'
    ];
    saveOrders($orders);
    echo json_encode(['success' => true]);
    exit;
}

// RESERVASI ACTIONS
if($action === 'delete_reservasi') {
    $reservasis = getReservations();
    $reservasis = array_filter($reservasis, fn($r) => $r['id'] != $_POST['id']);
    saveReservations(array_values($reservasis));
    echo json_encode(['success' => true]);
    exit;
}

if($action === 'update_reservasi') {
    $reservasis = getReservations();
    $statusCycle = ['pending', 'active', 'done'];
    foreach($reservasis as &$r) {
        if($r['id'] == $_POST['id']) {
            $currentIndex = array_search($r['status'], $statusCycle);
            $r['status'] = $statusCycle[($currentIndex + 1) % 3];
            break;
        }
    }
    saveReservations($reservasis);
    echo json_encode(['success' => true]);
    exit;
}

if($action === 'add_sample_reservasi') {
    $reservasis = getReservations();
    $newId = count($reservasis) + 10;
    $reservasis[] = [
        'id' => $newId,
        'name' => 'Pelanggan Baru',
        'datetime' => date('Y-m-d') . ' 18:00',
        'meja' => 'Meja ' . rand(1, 10),
        'tamu' => rand(2, 6),
        'status' => 'pending'
    ];
    saveReservations($reservasis);
    echo json_encode(['success' => true]);
    exit;
}

// TESTIMONI ACTIONS
if($action === 'add_testimoni') {
    $testimonials = getTestimonials();
    $newId = count($testimonials) > 0 ? max(array_column($testimonials, 'id')) + 1 : 1;
    $testimonials[] = [
        'id' => $newId,
        'name' => $_POST['name'],
        'role' => $_POST['role'],
        'text' => $_POST['text'],
        'rating' => (int)$_POST['rating']
    ];
    saveTestimonials($testimonials);
    header('Location: admin.php?success=Testimoni berhasil ditambahkan');
    exit;
}

if($action === 'delete_testimoni') {
    $testimonials = getTestimonials();
    $testimonials = array_filter($testimonials, fn($t) => $t['id'] != $_POST['id']);
    saveTestimonials(array_values($testimonials));
    echo json_encode(['success' => true]);
    exit;
}

// PROMO ACTIONS
if($action === 'add_promo') {
    $promos = getPromos();
    $newId = count($promos) > 0 ? max(array_column($promos, 'id')) + 1 : 1;
    $promos[] = [
        'id' => $newId,
        'name' => $_POST['name'],
        'disc' => (int)$_POST['disc'],
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'code' => strtoupper($_POST['code']),
        'status' => 'active'
    ];
    savePromos($promos);
    header('Location: admin.php?success=Promo berhasil ditambahkan');
    exit;
}

if($action === 'delete_promo') {
    $promos = getPromos();
    $promos = array_filter($promos, fn($p) => $p['id'] != $_POST['id']);
    savePromos(array_values($promos));
    echo json_encode(['success' => true]);
    exit;
}

// CONTACT ACTIONS
if($action === 'update_contact') {
    $contact = [
        'alamat' => $_POST['alamat'],
        'jam_buka' => $_POST['jam_buka'],
        'telepon' => $_POST['telepon'],
        'email' => $_POST['email']
    ];
    saveContactInfo($contact);
    header('Location: admin.php?success=Info kontak berhasil diperbarui');
    exit;
}

if($action === 'update_stats') {
    $stats = [
        'jenis_kopi' => (int)$_POST['jenis_kopi'],
        'pelanggan' => (int)$_POST['pelanggan'],
        'tahun' => (int)$_POST['tahun']
    ];
    saveStats($stats);
    header('Location: admin.php?success=Statistik berhasil diperbarui');
    exit;
}

// SETTINGS ACTIONS
if($action === 'update_settings') {
    $settings = [
        'site_name' => $_POST['site_name'],
        'footer_text' => $_POST['footer_text']
    ];
    saveSettings($settings);
    header('Location: admin.php?success=Pengaturan berhasil disimpan');
    exit;
}

if($action === 'change_password') {
    $newPass = $_POST['new_password'];
    $confirmPass = $_POST['confirm_password'];
    
    if($newPass === $confirmPass && strlen($newPass) >= 6) {
        // Untuk demo, password disimpan dalam session dan file
        // Dalam production, sebaiknya simpan di file terenkripsi
        file_put_contents(DATA_PATH . 'admin_pass.txt', password_hash($newPass, PASSWORD_DEFAULT));
        $_SESSION['admin_password'] = $newPass;
        header('Location: admin.php?success=Password berhasil diubah');
    } else {
        header('Location: admin.php?success=Gagal: Password tidak cocok atau terlalu pendek');
    }
    exit;
}

// Default redirect
header('Location: admin.php');
exit;
?>
