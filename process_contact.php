<?php
// Simpan pesan kontak
$name = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$keperluan = $_POST['keperluan'] ?? '';
$message = $_POST['pesan'] ?? '';

// Validasi sederhana
if(empty($name) || empty($email) || empty($message)) {
    header('Location: index.php?error=1');
    exit;
}

// Simpan ke file log (untuk demo)
$dataDir = __DIR__ . '/data/';
if(!file_exists($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$contactLog = $dataDir . 'contact_messages.json';
$messages = file_exists($contactLog) ? json_decode(file_get_contents($contactLog), true) : [];
$messages[] = [
    'id' => count($messages) + 1,
    'name' => $name,
    'email' => $email,
    'keperluan' => $keperluan,
    'message' => $message,
    'date' => date('Y-m-d H:i:s')
];
file_put_contents($contactLog, json_encode($messages, JSON_PRETTY_PRINT));

header('Location: index.php?success=1');
exit;
?>
