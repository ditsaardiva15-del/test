<?php
session_start();
require_once 'config/database.php';

// Check if logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$settings = getSettings();
$contact = getContactInfo();
$stats = getStats();
$menuItems = getMenuItems();
$testimonials = getTestimonials();
$orders = getOrders();
$reservasis = getReservations();
$promos = getPromos();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Kopi Nusantara</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --cream: #F5EDD8;
    --espresso: #2C1A0E;
    --caramel: #C07B3A;
    --latte: #D4A96A;
    --dark: #1A0F06;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--dark);
    color: var(--cream);
  }

  /* Admin Panel Styles */
  .admin-container {
    display: flex;
    min-height: 100vh;
  }

  /* Sidebar */
  .admin-sidebar {
    width: 260px;
    background: rgba(0,0,0,0.3);
    border-right: 1px solid rgba(192,123,58,0.15);
    padding: 2rem 0;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
  }

  .sidebar-header {
    padding: 0 1.5rem 1.5rem;
    border-bottom: 1px solid rgba(192,123,58,0.15);
    margin-bottom: 1.5rem;
  }

  .sidebar-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: var(--latte);
  }

  .sidebar-header p {
    font-size: 0.75rem;
    color: rgba(245,237,216,0.45);
    margin-top: 0.3rem;
  }

  .sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }

  .sidebar-nav a {
    padding: 0.75rem 1.5rem;
    color: rgba(245,237,216,0.65);
    text-decoration: none;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.2s;
  }

  .sidebar-nav a:hover, .sidebar-nav a.active {
    background: rgba(192,123,58,0.1);
    color: var(--latte);
    border-left: 3px solid var(--caramel);
  }

  /* Main Content */
  .admin-main {
    flex: 1;
    margin-left: 260px;
    padding: 2rem;
  }

  .admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(192,123,58,0.15);
  }

  .admin-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
  }

  .logout-btn {
    background: rgba(180,60,30,0.7);
    color: white;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
  }

  .logout-btn:hover { background: rgba(180,60,30,1); }

  /* Cards */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
  }

  .stat-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(245,237,216,0.08);
    border-radius: 12px;
    padding: 1.2rem;
  }

  .stat-label {
    font-size: 0.75rem;
    color: rgba(245,237,216,0.4);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.5rem;
  }

  .stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--latte);
  }

  /* Forms */
  .admin-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(245,237,216,0.08);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .admin-card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1.2rem;
    color: var(--latte);
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-label {
    display: block;
    font-size: 0.8rem;
    color: rgba(245,237,216,0.6);
    margin-bottom: 0.4rem;
    font-weight: 500;
  }

  .form-control {
    width: 100%;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(245,237,216,0.12);
    border-radius: 8px;
    padding: 0.7rem 0.9rem;
    color: var(--cream);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem;
    outline: none;
  }

  .form-control:focus { border-color: var(--caramel); }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .btn-primary {
    background: var(--caramel);
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .btn-danger {
    background: rgba(180,60,30,0.7);
    color: white;
    border: none;
    padding: 0.3rem 0.75rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.75rem;
  }

  .btn-sm {
    padding: 0.3rem 0.75rem;
    font-size: 0.75rem;
  }

  /* Tables */
  .admin-table {
    width: 100%;
    border-collapse: collapse;
  }

  .admin-table th {
    text-align: left;
    padding: 0.75rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(245,237,216,0.5);
    border-bottom: 1px solid rgba(245,237,216,0.08);
  }

  .admin-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.85rem;
    border-bottom: 1px solid rgba(245,237,216,0.05);
  }

  .status-badge {
    display: inline-block;
    padding: 0.2rem 0.7rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
  }

  .status-active { background: rgba(45,80,22,0.3); color: #7EC850; }
  .status-pending { background: rgba(180,130,0,0.2); color: #F0C040; }
  .status-done { background: rgba(30,60,130,0.2); color: #7BA8F0; }

  /* Tab Content */
  .tab-content {
    display: none;
  }

  .tab-content.active {
    display: block;
  }

  /* Toast */
  .toast {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: var(--caramel);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    z-index: 1000;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s;
  }

  .toast.show {
    transform: translateY(0);
    opacity: 1;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .admin-sidebar {
      width: 100%;
      position: relative;
      height: auto;
    }
    .admin-main {
      margin-left: 0;
    }
    .admin-container {
      flex-direction: column;
    }
    .form-row {
      grid-template-columns: 1fr;
    }
    .stats-grid {
      grid-template-columns: 1fr 1fr;
    }
  }
</style>
</head>
<body>
<div class="admin-container">
  <!-- Sidebar -->
  <div class="admin-sidebar">
    <div class="sidebar-header">
      <h2>☕ Kopi Nusantara</h2>
      <p>Admin Panel</p>
    </div>
    <div class="sidebar-nav">
      <a href="#" class="active" onclick="showTab('dashboard', this)">📊 Dashboard</a>
      <a href="#" onclick="showTab('menu', this)">☕ Menu</a>
      <a href="#" onclick="showTab('orders', this)">📋 Pesanan</a>
      <a href="#" onclick="showTab('reservasi', this)">📅 Reservasi</a>
      <a href="#" onclick="showTab('testimoni', this)">⭐ Testimoni</a>
      <a href="#" onclick="showTab('info', this)">📍 Info Kontak</a>
      <a href="#" onclick="showTab('promo', this)">🏷️ Promo</a>
      <a href="#" onclick="showTab('settings', this)">⚙️ Pengaturan</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="admin-main">
    <div class="admin-header">
      <h1>Panel Admin</h1>
      <a href="admin_logout.php" class="logout-btn">🚪 Keluar</a>
    </div>

    <!-- Dashboard Tab -->
    <div id="dashboard" class="tab-content active">
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-label">Total Menu Aktif</div>
          <div class="stat-value" id="stat-menu"><?= count(array_filter($menuItems, fn($m)=>$m['status']=='active')) ?></div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Pesanan Hari Ini</div>
          <div class="stat-value" id="stat-orders"><?= count($orders) ?></div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Reservasi Aktif</div>
          <div class="stat-value" id="stat-reserv"><?= count(array_filter($reservasis, fn($r)=>$r['status']=='active')) ?></div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Testimoni</div>
          <div class="stat-value" id="stat-testi"><?= count($testimonials) ?></div>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-title">📋 Pesanan Terbaru</div>
        <table class="admin-table">
          <thead>
            <tr><th>ID</th><th>Pelanggan</th><th>Menu</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
          </thead>
          <tbody id="dashboard-orders">
            <?php foreach(array_slice($orders, 0, 5) as $o): ?>
            <tr>
              <td><?= htmlspecialchars($o['id']) ?></td>
              <td><?= htmlspecialchars($o['customer']) ?></td>
              <td><?= htmlspecialchars($o['items']) ?></td>
              <td>Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
              <td><span class="status-badge status-<?= $o['status'] ?>"><?= $o['status'] ?></span></td>
              <td><button class="btn-danger btn-sm" onclick="deleteOrder('<?= $o['id'] ?>')">Hapus</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Menu Tab -->
    <div id="menu" class="tab-content">
      <div class="admin-card">
        <div class="admin-card-title">➕ Tambah Menu Baru</div>
        <form action="process_admin.php" method="POST">
          <input type="hidden" name="action" value="add_menu">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Nama Menu</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">Harga (Rp)</label>
              <input type="number" name="price" class="form-control" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Kategori</label>
              <select name="category" class="form-control">
                <option value="kopi">☕ Kopi</option>
                <option value="non">🍵 Non-Kopi</option>
                <option value="makanan">🥐 Makanan</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Badge</label>
              <select name="badge" class="form-control">
                <option value="">Tidak ada</option>
                <option value="popular">⭐ Popular</option>
                <option value="new">✨ New</option>
                <option value="hot">🔥 Hot</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Emoji</label>
              <input type="text" name="emoji" class="form-control" value="☕" maxlength="4">
            </div>
            <div class="form-group">
              <label class="form-label">Status</label>
              <select name="status" class="form-control">
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn-primary">+ Tambah Menu</button>
        </form>
      </div>

      <div class="admin-card">
        <div class="admin-card-title">📋 Daftar Menu</div>
        <table class="admin-table">
          <thead><tr><th>Item</th><th>Kategori</th><th>Harga</th><th>Badge</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody id="menu-list">
            <?php foreach($menuItems as $m): ?>
            <tr>
              <td><?= htmlspecialchars($m['name']) ?></td>
              <td><?= $m['category'] ?></td>
              <td>Rp <?= number_format($m['price'], 0, ',', '.') ?></td>
              <td><?= $m['badge'] ?></td>
              <td><span class="status-badge status-<?= $m['status'] ?>"><?= $m['status'] == 'active' ? 'Aktif' : 'Nonaktif' ?></span></td>
              <td>
                <button class="btn-danger btn-sm" onclick="deleteMenuItem(<?= $m['id'] ?>)">Hapus</button>
                <button class="btn-primary btn-sm" onclick="toggleMenuStatus(<?= $m['id'] ?>, '<?= $m['status'] ?>')">Toggle</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Orders Tab -->
    <div id="orders" class="tab-content">
      <div class="admin-card">
        <div class="admin-card-title">📋 Semua Pesanan</div>
        <table class="admin-table">
          <thead><tr><th>ID</th><th>Pelanggan</th><th>Menu</th><th>Total</th><th>Waktu</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody id="orders-list">
            <?php foreach($orders as $o): ?>
            <tr>
              <td><?= htmlspecialchars($o['id']) ?></td>
              <td><?= htmlspecialchars($o['customer']) ?></td>
              <td><?= htmlspecialchars($o['items']) ?></td>
              <td>Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
              <td><?= $o['time'] ?></td>
              <td><span class="status-badge status-<?= $o['status'] ?>"><?= $o['status'] ?></span></td>
              <td>
                <button class="btn-primary btn-sm" onclick="updateOrderStatus('<?= $o['id'] ?>')">Update</button>
                <button class="btn-danger btn-sm" onclick="deleteOrder('<?= $o['id'] ?>')">Hapus</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="margin-top:1rem;">
          <button class="btn-primary" onclick="addSampleOrder()">+ Tambah Pesanan Sample</button>
        </div>
      </div>
    </div>

    <!-- Reservasi Tab -->
    <div id="reservasi" class="tab-content">
      <div class="admin-card">
        <div class="admin-card-title">📅 Daftar Reservasi</div>
        <table class="admin-table">
          <thead><tr><th>Nama</th><th>Tgl & Waktu</th><th>Meja</th><th>Tamu</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody id="reservasi-list">
            <?php foreach($reservasis as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= $r['datetime'] ?></td>
              <td><?= $r['meja'] ?></td>
              <td><?= $r['tamu'] ?> org</td>
              <td><span class="status-badge status-<?= $r['status'] ?>"><?= $r['status'] ?></span></td>
              <td>
                <button class="btn-primary btn-sm" onclick="updateReservasiStatus(<?= $r['id'] ?>)">Update</button>
                <button class="btn-danger btn-sm" onclick="deleteReservasi(<?= $r['id'] ?>)">Hapus</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="margin-top:1rem;">
          <button class="btn-primary" onclick="addSampleReservasi()">+ Tambah Reservasi</button>
        </div>
      </div>
    </div>

    <!-- Testimoni Tab -->
    <div id="testimoni" class="tab-content">
      <div class="admin-card">
        <div class="admin-card-title">➕ Tambah Testimoni</div>
        <form action="process_admin.php" method="POST">
          <input type="hidden" name="action" value="add_testimoni">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Nama</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">Role</label>
              <input type="text" name="role" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Ulasan</label>
            <textarea name="text" class="form-control" rows="2" required></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-control">
              <option value="5">⭐⭐⭐⭐⭐ (5)</option>
              <option value="4">⭐⭐⭐⭐ (4)</option>
              <option value="3">⭐⭐⭐ (3)</option>
            </select>
          </div>
          <button type="submit" class="btn-primary">+ Tambah Testimoni</button>
        </form>
      </div>

      <div class="admin-card">
        <div class="admin-card-title">⭐ Daftar Testimoni</div>
        <div id="testimoni-list">
          <?php foreach($testimonials as $t): ?>
          <div style="padding:1rem 0; border-bottom:1px solid rgba(245,237,216,0.08); display:flex; justify-content:space-between;">
            <div>
              <strong><?= htmlspecialchars($t['name']) ?></strong> (<?= str_repeat('★', $t['rating']) ?>)
              <div style="font-size:0.8rem; color:rgba(245,237,216,0.6);"><?= htmlspecialchars($t['role']) ?></div>
              <div style="font-size:0.85rem; margin-top:0.3rem;">"<?= htmlspecialchars($t['text']) ?>"</div>
            </div>
            <button class="btn-danger btn-sm" onclick="deleteTestimoni(<?= $t['id'] ?>)">Hapus</button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Info Kontak Tab -->
    <div id="info" class="tab-content">
      <div class="admin-card">
        <div class="admin-card-title">📍 Informasi Kontak</div>
        <form action="process_admin.php" method="POST">
          <input type="hidden" name="action" value="update_contact">
          <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($contact['alamat']) ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Jam Buka</label>
            <textarea name="jam_buka" class="form-control" rows="2"><?= htmlspecialchars($contact['jam_buka']) ?></textarea>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Telepon</label>
              <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($contact['telepon']) ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($contact['email']) ?>">
            </div>
          </div>
          <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
        </form>
      </div>

      <div class="admin-card">
        <div class="admin-card-title">📊 Statistik Hero</div>
        <form action="process_admin.php" method="POST">
          <input type="hidden" name="action" value="update_stats">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Jenis Kopi</label>
              <input type="number" name="jenis_kopi" class="form-control" value="<?= $stats['jenis_kopi'] ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Pelanggan (K)</label>
              <input type="number" name="pelanggan" class="form-control" value="<?= $stats['pelanggan'] ?>">
            </div>
            <div class="form-group">
     
