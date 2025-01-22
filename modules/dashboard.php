<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

$total = $pdo->query("SELECT COUNT(*) FROM airdrops_list")->fetchColumn();
$active = $pdo->query("SELECT COUNT(*) FROM airdrops_list WHERE status = 'active'")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM airdrops_list WHERE status = 'pending'")->fetchColumn();
$ended = $pdo->query("SELECT COUNT(*) FROM airdrops_list WHERE status = 'ended'")->fetchColumn();

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;
$total_pages = ceil($total / $limit);

// Update the main query
$stmt = $pdo->prepare("SELECT * FROM airdrops_list ORDER BY created_at DESC LIMIT :start, :limit");
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catatan Airdrop</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #0d6efd;
      --secondary-color: #6c757d;
      --success-color: #198754;
      --danger-color: #dc3545;
      --warning-color: #ffc107;
      --light-bg: #f8f9fa;
      --border-radius: 1rem;
      --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background-color: var(--light-bg);
      color: #333;
      overflow-x: hidden;
      /* Prevent horizontal scroll when side nav is open */
    }

    /* Side Navigation Styling */
    .side-nav {
      position: fixed;
      top: 0;
      right: -300px;
      width: 300px;
      height: 100100%;
      background: linear-gradient(145deg, var(--primary-color), #1a75ff);
      z-index: 1050;
      transition: 0.3s ease-in-out;
      padding: 2rem 1.5rem;
      overflow-y: auto;
    }

    .side-nav.active {
      right: 0;
    }

    .nav-overlay {
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.3);
      /* Latar belakang semi-transparan */
      z-index: 1040;
      opacity: 0;
      visibility: hidden;
      backdrop-filter: blur(10px);
      /* Efek blur lebih kuat */
      -webkit-backdrop-filter: blur(10px);
      /* Dukungan Safari */
      transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
    }

    .nav-overlay.active {
      opacity: 1;
      /* Pastikan overlay terlihat */
      visibility: visible;
      backdrop-filter: blur(10px);
      /* Efek blur */
      -webkit-backdrop-filter: blur(10px);
      /* Dukungan untuk browser WebKit (Safari) */
      transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
    }

    .nav-overlay.active {
      opacity: 1;
      /* Pastikan overlay terlihat */
      visibility: visible;
      backdrop-filter: blur(10px);
      /* Efek blur */
      -webkit-backdrop-filter: blur(10px);
      /* Dukungan untuk browser WebKit (Safari) */
      transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
    }

    .side-nav-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .side-nav-header h5 {
      color: white;
      margin: 0;
      font-weight: 600;
    }

    .close-nav {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      padding: 0.5rem;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .close-nav:hover {
      opacity: 0.8;
      transform: scale(1.1);
    }

    .side-nav .nav-link {
      color: white;
      padding: 1rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
      transition: 0.3s ease;
    }

    .side-nav .nav-link i {
      width: 24px;
      margin-right: 1rem;
      text-align: center;
    }

    .side-nav .nav-link:hover,
    .side-nav .nav-link.active {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }

    .toggle-nav {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      padding: 0.5rem;
      cursor: pointer;
      transition: 0.3s ease;
      position: absolute;
      right: 1rem;
      top: 1.5rem;
      z-index: 1030;
    }

    .toggle-nav:hover {
      transform: scale(1.1);
    }

    /* Navbar Styling */
    .navbar {
      padding: 1rem 0;
      background: transparent !important;
    }

    .navbar-toggler {
      color: white;
      border-color: rgba(255, 255, 255, 0.5);
      padding: 0.5rem;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .navbar-collapse {
      background: transparent;
    }

    .navbar-nav .nav-link {
      color: white !important;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      padding: 0.75rem 1rem;
      margin: 0.25rem 0;
      text-align: center;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      background: rgba(255, 255, 255, 0.1);
    }

    .navbar-nav .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    /* Header Styling */
    .page-header {
      background: linear-gradient(145deg, var(--primary-color), #1a75ff);
      color: white;
      /* padding: 20px 0; */
      margin-bottom: 2rem;
    }

    .header-content {
      flex: 1;
    }

    .page-header h1 {
      font-weight: 800;
      margin-bottom: 0.5rem;
    }

    .page-header .lead {
      opacity: 0.9;
    }

    /* Card Styling */
    .card {
      border: none;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      transition: transform 0.3s ease;
    }

    .add-airdrop-card {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: var(--box-shadow);
    }

    .stats-card {
      margin: 0;
      height: auto;
      padding: 1.25rem;
    }

    .stats-card:hover {
      transform: translateY(-5px);
    }

    .stats-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 1.5rem;
    }

    /* Form Styling */
    .form-floating>.form-control {
      border-radius: 0.75rem;
      border: 1px solid #dee2e6;
    }

    .form-floating>.form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .form-floating>label {
      padding-left: 1rem;
      color: #6c757d;
    }

    /* Table Styling */
    .airdrop-table {
      background: white;
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--box-shadow);
    }

    .table thead th {
      background: var(--primary-color);
      color: white;
      font-weight: 600;
      border: none;
      padding: 1rem;
    }

    .table tbody td {
      padding: 1rem;
      vertical-align: middle;
    }

    /* Status Badge Styling */
    .status-badge {
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-block;
    }

    .status-active {
      background: rgba(25, 135, 84, 0.1);
      color: var(--success-color);
    }

    .status-pending {
      background: rgba(255, 193, 7, 0.1);
      color: var(--warning-color);
    }

    .status-ended {
      background: rgba(220, 53, 69, 0.1);
      color: var(--danger-color);
    }

    /* Account Details Styling */
    .account-details {
      font-size: 0.875rem;
      color: #666;
      line-height: 1.8;
    }

    .account-details i {
      width: 20px;
      margin-right: 0.5rem;
      color: var(--primary-color);
    }

    /* Button Styling */
    .btn {
      padding: 0.5rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 500;
    }

    .btn-group .btn {
      border-radius: 0.75rem;
    }

    .action-buttons .btn {
      width: 36px;
      height: 36px;
      padding: 0.25rem 0.5rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 0.25rem;
    }

    /* Pagination Styling */
    .pagination {
      margin: 1rem 0;
    }

    .pagination .page-link {
      padding: 0.375rem 0.75rem;
    }

    .page-link {
      border-radius: 0.5rem;
      margin: 0 0.25rem;
      border: none;
      color: var(--primary-color);
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .page-item.active .page-link {
      background-color: var(--primary-color);
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
      .navbar-collapse {
        background: rgba(13, 110, 253, 0.95);
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-top: 1rem;
      }
    }

    @media (max-width: 768px) {
      .table-responsive {
        margin: 0;
        padding: 0;
        border-radius: var(--border-radius);
        overflow-x: auto;
      }

      .table {
        min-width: 800px;
        /* Ensure horizontal scroll on mobile */
      }

      .table td,
      .table th {
        white-space: nowrap;
        padding: 0.75rem;
        font-size: 0.875rem;
      }

      .page-header {
        padding: 2rem 0 1rem;
      }

      .stats-wrapper {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin: 0 0 1.5rem;
        /* padding: 1rem 0; */
      }

      .add-airdrop-card {
        padding: 1.25rem;
        margin-bottom: 1.5rem;
      }

      .stats-card {
        margin: 0;
        padding: 1rem;
        margin-bottom: 1rem;
      }


      .filter-search-container {
        margin-bottom: 1.5rem;
      }

      .filter-search-container .col-md-8,
      .filter-search-container .col-md-4 {
        padding: 0;
      }

      .search-container {
        display: flex;
        gap: 0.5rem;
      }

      .search-btn {
        flex-shrink: 0;
      }

      .table-responsive {
        margin: 0 -1rem;
        padding: 0 1rem;
        border-radius: 0;
      }

      /* .table-responsive {
        border-radius: var(--border-radius);
      } */
      .table th,
      .table td {
        padding: 0.75rem;
        font-size: 0.875rem;
      }

      .action-buttons .btn {
        width: 32px;
        height: 32px;
      }

      .navbar-nav .nav-link:hover,
      .navbar-nav .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
      }
    }

    @media (max-width: 768px) {
      .stats-card {
        margin-bottom: 1rem;
      }

      .row.mb-4 {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        margin: 0 -15px;
        padding-bottom: 1rem;
      }

      .col-md-3 {
        flex: 0 0 auto;
        width: auto;
        min-width: 200px;
        padding: 0 15px;
      }

      /* Table Responsiveness */
      .table td {
        white-space: normal;
        min-width: 120px;
      }

      .table td:nth-child(5),
      .table td:nth-child(6) {
        min-width: 200px;
      }

      .account-details {
        font-size: 0.75rem;
        line-height: 1.4;
      }

      .action-buttons {
        display: flex;
        gap: 0.5rem;
      }
    }
  </style>

<body>
  <!-- Side Navigation -->
  <div class="nav-overlay"></div>
  <div class="side-nav">
    <div class="side-nav-header">
      <h5>Menu Navigasi</h5>
      <button class="close-nav">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="nav-links">
      <a class="nav-link active" href="../index.php">
        <i class="fas fa-home"></i>
        <span>Beranda</span>
      </a>
      <a class="nav-link" href="list-admin.php">
        <i class="fas fa-list"></i>
        <span>List Airdrop</span>
      </a>
      <a class="nav-link" href="../auth/register.php">
        <i class="fas fa-user-plus"></i>
        <span>Register</span>
      </a>
      <a class="nav-link" href="../auth/logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>

  <!-- Page Header -->
  <div class="page-header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="header-content">
          <h1 class="display-5 fw-bold">Catatan Airdrop Anda</h1>
          <p class="lead">Kelola dan pantau progress airdrop Anda di satu tempat</p>
        </div>
        <button class="toggle-nav">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </div>

  <div class="container">
    <!-- Add New Airdrop Form -->
    <div class="add-airdrop-card">
      <h3 class="mb-4">Tambah Airdrop Baru</h3>
      <form action="../modules/add-airdrops.php" method="POST">
        <div class="row g-3">
          <!-- Basic Info -->
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="projectName" name="name" placeholder="Nama Project" required>
              <label for="projectName">Nama Project</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="tokenName" name="token" placeholder="Nama Token" required>
              <label for="tokenName">Nama Token</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating mb-3">
              <input type="url" class="form-control" id="projectLink" name="link" placeholder="Link Project" required>
              <label for="projectLink">Link Project</label>
            </div>
          </div>
          <!-- Wallet Details -->
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="wallet" name="wallet" placeholder="Wallet Type (e.g., Metamask, Trust Wallet)" required>
              <label for="wallet">Wallet Type</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="address" name="address" placeholder="Wallet Address" required>
              <label for="address">Wallet Address</label>
            </div>
          </div>
          <!-- Dates -->
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="date" class="form-control" id="startDate" name="start_date" required>
              <label for="startDate">Tanggal Mulai</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating mb-3">
              <input type="date" class="form-control" id="endDate" name="end_date" required>
              <label for="endDate">Tanggal Berakhir</label>
            </div>
          </div>

          <!-- Account Details -->
          <div class="col-md-4">
            <div class="form-floating mb-3">
              <input type="email" class="form-control" id="googleAccount" name="account_google" placeholder="Akun Google">
              <label for="googleAccount">Akun Google</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="discordAccount" name="account_discord" placeholder="Username Discord">
              <label for="discordAccount">Username Discord</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="twitterAccount" name="account_twitter" placeholder="Username Twitter">
              <label for="twitterAccount">Username Twitter</label>
            </div>
          </div>

          <!-- Status -->
          <div class="col-12">
            <div class="form-floating mb-3">
              <select class="form-select" id="status" name="status" required>
                <option value="active">Aktif</option>
                <option value="pending">Pending</option>
                <option value="ended">Selesai</option>
              </select>
              <label for="status">Status</label>
            </div>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary">Tambah Airdrop</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 stats-wrapper">
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-rocket mb-3 text-primary" style="font-size: 2rem;"></i>
          <h3 class="fw-bold"><?= $total ?></h3>
          <p class="text-muted mb-0">Total Airdrop</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-check-circle mb-3 text-success" style="font-size: 2rem;"></i>
          <h3 class="fw-bold"><?= $active ?></h3>
          <p class="text-muted mb-0">Aktif</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-clock mb-3 text-warning" style="font-size: 2rem;"></i>
          <h3 class="fw-bold"><?= $pending ?></h3>
          <p class="text-muted mb-0">Pending</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-flag-checkered mb-3 text-danger" style="font-size: 2rem;"></i>
          <h3 class="fw-bold"><?= $ended ?></h3>
          <p class="text-muted mb-0">Selesai</p>
        </div>
      </div>
    </div>


    <!-- Filter and Search -->
    <div class="row mb-4 filter-search-container">
      <div class="col-md-8 mb-3 mb-md-0">
        <select class="form-select" id="statusFilter">
          <option value="all">Semua</option>
          <option value="active">Aktif</option>
          <option value="pending">Pending</option>
          <option value="ended">Selesai</option>
        </select>
      </div>
      <div class="col-md-4">
        <div class="search-container">
          <input type="text" class="form-control" placeholder="Cari airdrop...">
          <button class="btn btn-primary search-btn"><i class="fas fa-search"></i></button>
        </div>
      </div>
    </div>

    <!-- Airdrop Table -->
    <div class="table-responsive airdrop-table">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Project</th>
            <th>Token</th>
            <th>Periode</th>
            <th>Status</th>
            <th>Akun Terdaftar</th>
            <th>Wallet Info</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->query("SELECT * FROM airdrops_list ORDER BY created_at DESC");
          while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['token']}</td>";
            echo "<td>{$row['start_date']} - {$row['end_date']}</td>";
            echo "<td><span class='status-badge status-{$row['status']}'>{$row['status']}</span></td>";
            echo "<td>
          <div class='account-details'>
              <i class='fab fa-google'></i> {$row['account_google']}<br>
              <i class='fab fa-discord'></i> {$row['account_discord']}<br>
              <i class='fab fa-twitter'></i> {$row['account_twitter']}
          </div>
        </td>";
            echo "<td>
          <div class='account-details'>
              <i class='fas fa-wallet'></i> {$row['wallet']}<br>
              <i class='fas fa-key'></i> " . substr($row['address'], 0, 8) . "..." . substr($row['address'], -6) . "
          </div>
        </td>";
            echo "<td class='action-buttons'>
            <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-primary'><i class='fas fa-edit'></i></a>
            <a href='hapus.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'><i class='fas fa-trash'></i></a>
          </td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <!-- Update pagination section with: -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
      <div class="text-muted">
        Menampilkan <?= $start + 1 ?>-<?= min($start + $limit, $total) ?> dari <?= $total ?>
      </div>
      <nav>
        <ul class="pagination">
          <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>"><i class="fas fa-chevron-left"></i></a>
          </li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>"><i class="fas fa-chevron-right"></i></a>
          </li>
        </ul>
      </nav>
    </div>

    <footer class="py-4 text-center" style="background: transparent; color: black;">
      <div class="container">
        <p>&copy; <span id="year"></span> Mad-jr. All Rights Reserved.</p>
      </div>
    </footer>

    <script>
      // Set tahun saat ini secara otomatis
      document.getElementById('year').textContent = new Date().getFullYear();
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const toggleNav = document.querySelector('.toggle-nav');
        const closeNav = document.querySelector('.close-nav');
        const sideNav = document.querySelector('.side-nav');
        const overlay = document.querySelector('.nav-overlay');

        function openNav() {
          sideNav.classList.add('active');
          overlay.classList.add('active');
          document.body.style.overflow = 'hidden';
        }

        function closeNavigation() {
          sideNav.classList.remove('active');
          overlay.classList.remove('active');
          document.body.style.overflow = '';
        }

        toggleNav.addEventListener('click', openNav);
        closeNav.addEventListener('click', closeNavigation);
        overlay.addEventListener('click', closeNavigation);

        // Close nav when pressing Escape key
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape' && sideNav.classList.contains('active')) {
            closeNavigation();
          }
        });
      });
    </script>
</body>

</html>