<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

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
    }

    /* Navbar Styling */
    .navbar {
      padding: 1rem 0;
      background: transparent !important;
    }

    .navbar-nav .nav-link {
      color: white !important;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    /* Header Styling */
    .page-header {
      background: linear-gradient(145deg, var(--primary-color), #1a75ff);
      color: white;
      padding: 20px 0;
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
      padding: 1.5rem;
      text-align: center;
      height: 100%;
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
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 0.25rem;
    }

    /* Pagination Styling */
    .pagination {
      margin-bottom: 0;
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
    @media (max-width: 768px) {
      .page-header {
        padding: 2rem 0 1rem;
      }

      .add-airdrop-card {
        padding: 1.5rem;
      }

      .stats-card {
        margin-bottom: 1rem;
      }

      .table-responsive {
        border-radius: var(--border-radius);
      }

      .action-buttons .btn {
        width: 32px;
        height: 32px;
      }
    }
  </style>
</head>

<body>
  <div class="page-header">
    <div class="container">
      <div class="header-content">
        <h1 class="display-5 fw-bold">Catatan Airdrop Anda</h1>
        <p class="lead">Kelola dan pantau progress airdrop Anda di satu tempat</p>
      </div>
      <nav class="navbar navbar-expand-lg">
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link active" href="../index.php">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="list-admin.php">List Airdrop</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../auth/register.php">Register</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../auth/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </nav>
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
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-rocket mb-3 text-primary" style="font-size: 2rem;"></i>
          <h3 class="fw-bold">12</h3>
          <p class="text-muted mb-0">Total Airdrop</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-check-circle mb-3 text-success" style="font-size: 2rem;"></i>
          <h3 class="fw-bold">8</h3>
          <p class="text-muted mb-0">Aktif</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-clock mb-3 text-warning" style="font-size: 2rem;"></i>
          <h3 class="fw-bold">3</h3>
          <p class="text-muted mb-0">Pending</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card text-center">
          <i class="fas fa-flag-checkered mb-3 text-danger" style="font-size: 2rem;"></i>
          <h3 class="fw-bold">1</h3>
          <p class="text-muted mb-0">Selesai</p>
        </div>
      </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
      <div class="col-md-8">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-outline-primary">Semua</button>
          <button type="button" class="btn btn-outline-primary">Aktif</button>
          <button type="button" class="btn btn-outline-primary">Pending</button>
          <button type="button" class="btn btn-outline-primary">Selesai</button>
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Cari airdrop...">
          <button class="btn btn-primary"><i class="fas fa-search"></i></button>
        </div>
      </div>
    </div>

    <!-- Airdrop Table -->
    <div class="table-responsive airdrop-table">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Nama Project</th>
            <th>Token</th>
            <th>Periode</th>
            <th>Status</th>
            <th>Akun Terdaftar</th>
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
    <div class="d-flex justify-content-between align-items-center mt-4">
      <div class="text-muted">
        Menampilkan 1-10 dari 12 airdrop
      </div>
      <nav>
        <ul class="pagination">
          <li class="page-item disabled">
            <a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a>
          </li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item">
            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
          </li>
        </ul>
      </nav>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>