<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// Cek jika sudah login
if (!isset($_SESSION['login'])) {
    header('Location: /airdrops/auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Airdrop</title>
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

        /* Alert Styling */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: var(--success-color);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .alert-info {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
        }

        .btn-close {
            padding: 1.25rem;
            opacity: 0.75;
        }

        /* Table Container Styling */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }

        .navbar-nav .nav-link {
            color: white;
            /* Mengatur warna teks menjadi putih */
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.02);
        }

        /* Link Styling */
        .table a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }

        .table a:hover {
            background: rgba(13, 110, 253, 0.1);
        }

        /* Status Badge */
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
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

        /* Account Column Styling */
        .account-cell {
            font-size: 0.875rem;
            color: #666;
        }

        .account-cell i {
            width: 20px;
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        /* Date Column Styling */
        .date-cell {
            font-size: 0.875rem;
            color: #666;
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">List Airdrops</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Add</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../auth/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div>
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div>
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Table Section -->
        <div class="table-container">
            <div class="table-responsive">
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM airdrops_list ORDER BY created_at DESC");

                    if ($stmt->rowCount() > 0) {
                        echo "<table class='table'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Nama Project</th>";
                        echo "<th>Token</th>";
                        echo "<th>Link</th>";
                        echo "<th>Periode</th>";
                        echo "<th>Status</th>";
                        echo "<th>Akun Terdaftar</th>";
                        echo "<th>Tanggal Dibuat</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['token']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row['link']) . "' target='_blank'><i class='fas fa-external-link-alt me-1'></i>Link</a></td>";
                            echo "<td class='date-cell'>" . htmlspecialchars($row['start_date']) . " - " . htmlspecialchars($row['end_date']) . "</td>";
                            echo "<td><span class='status-badge status-" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                            echo "<td class='account-cell'>
                            <div><i class='fab fa-google'></i>" . htmlspecialchars($row['account_google']) . "</div>
                            <div><i class='fab fa-discord'></i>" . htmlspecialchars($row['account_discord']) . "</div>
                            <div><i class='fab fa-twitter'></i>" . htmlspecialchars($row['account_twitter']) . "</div>
                          </td>";
                            echo "<td class='date-cell'>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-info m-3'>
                        <i class='fas fa-info-circle me-2'></i>
                        Belum ada data airdrop yang tersedia.
                      </div>";
                    }
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger m-3'>
                    <i class='fas fa-exclamation-triangle me-2'></i>
                    Error: " . $e->getMessage() . "
                  </div>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>