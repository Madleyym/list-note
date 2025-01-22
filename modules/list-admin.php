<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

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
            --primary-blue: #0d6efd;
            --primary-light: #e7f1ff;
            --dark-blue: #0a58ca;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --border-radius: 8px;
            --box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* Navbar Improvements */
        .navbar {
            background-color: var(--primary-blue) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .navbar-nav .nav-link {
            color: white;
            /* Mengatur warna teks menjadi putih */
        }

        .nav-link {
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: var(--border-radius);
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Container Spacing */
        .container {
            max-width: 1200px;
            padding: 0 1rem;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            padding: 0.75rem 1rem;
            margin: 1rem 0;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: all 0.2s;
        }

        .back-button:hover {
            background: var(--primary-light);
            color: var(--dark-blue);
            transform: translateX(-3px);
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin: 1rem 0 2rem;
            overflow: hidden;
        }

        /* Table Styling */
        .table {
            margin: 0;
        }

        .table thead th {
            background: var(--primary-blue);
            color: white;
            font-weight: 600;
            padding: 1rem;
            border: none;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: var(--primary-light);
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .status-active {
            background-color: rgba(25, 135, 84, 0.15);
            color: var(--success-color);
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: var(--warning-color);
        }

        .status-ended {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger-color);
        }

        /* Account Cell */
        .account-cell {
            color: #444;
        }

        .account-cell div {
            display: flex;
            align-items: center;
            padding: 0.25rem 0;
        }

        .account-cell i {
            width: 24px;
            margin-right: 0.75rem;
            color: var(--primary-blue);
            font-size: 1.1rem;
        }

        /* Links */
        .table a {
            color: var(--primary-blue);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table a:hover {
            background: var(--primary-light);
            color: var(--dark-blue);
        }

        /* Alerts */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            margin: 1rem 0;
            padding: 1rem;
        }

        @media (max-width: 768px) {

            /* Container adjustments */
            .container {
                padding: 1rem;
            }

            /* Add wallet info styling for mobile */
            td[data-label='Wallet Info'] {
                margin-top: 0.25rem;
            }

            td[data-label='Wallet Info'] div {
                display: flex;
                align-items: center;
                padding: 0.5rem 0;
                margin: 0;
                font-size: 0.875rem;
            }

            td[data-label='Wallet Info'] i {
                width: 1.25rem;
                margin-right: 0.75rem;
                color: #3b82f6;
            }
        }

        /* Table container */
        .table-container {
            margin: 0;
            background: transparent;
            box-shadow: none;
        }

        /* Hide table headers */
        .table thead {
            display: none;
        }

        /* Style each row as a card */
        .table tbody tr {
            display: block;
            background: white;
            margin-bottom: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 1.25rem;
        }

        /* Style each cell */
        .table td {
            display: flex;
            flex-direction: column;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eef2f6;
        }

        .table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .table td:first-child {
            padding-top: 0;
        }

        /* Data labels */
        .table td::before {
            content: attr(data-label);
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        /* Project name styling */
        td[data-label="Nama Project"] {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 0.5rem;
        }

        td[data-label="Nama Project"] strong {
            font-size: 1.125rem;
            color: #1e40af;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            width: auto;
            margin-top: 0.25rem;
        }

        /* Link styling */
        .table a {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            padding: 0.625rem 1rem;
            border-radius: 6px;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }

        .table a:hover {
            background: #e2e8f0;
        }

        /* Account cell styling */
        .account-cell {
            margin-top: 0.25rem;
        }

        .account-cell div {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .account-cell div:last-child {
            padding-bottom: 0;
        }

        .account-cell i {
            width: 1.25rem;
            margin-right: 0.75rem;
            color: #3b82f6;
        }

        /* Period and date styling */
        td[data-label="Periode"],
        td[data-label="Tanggal Dibuat"] {
            font-size: 0.875rem;
        }

        /* Token styling */
        td[data-label="Token"] {
            font-size: 0.875rem;
            font-weight: 500;
        }
        

        /* Extra small devices */
        @media (max-width: 480px) {
            .container {
                padding: 0.75rem;
            }

            .table tbody tr {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }

            .table td {
                padding: 0.625rem 0;
            }

            .status-badge {
                width: 100%;
                justify-content: center;
            }

            .account-cell div {
                padding: 0.425rem 0;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

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

        <div class="table-container">
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
                    echo "<th>Wallet Info</th>";  // New column
                    echo "<th>Tanggal Dibuat</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td data-label='Nama Project'><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                        echo "<td data-label='Token'>" . htmlspecialchars($row['token']) . "</td>";
                        echo "<td data-label='Link'><a href='" . htmlspecialchars($row['link']) . "' target='_blank'><i class='fas fa-external-link-alt'></i>Link</a></td>";
                        echo "<td data-label='Periode'>" . htmlspecialchars($row['start_date']) . " - " . htmlspecialchars($row['end_date']) . "</td>";
                        echo "<td data-label='Status'><span class='status-badge status-" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td data-label='Akun Terdaftar' class='account-cell'>
                                <div><i class='fab fa-google'></i>" . htmlspecialchars($row['account_google']) . "</div>
                                <div><i class='fab fa-discord'></i>" . htmlspecialchars($row['account_discord']) . "</div>
                                <div><i class='fab fa-twitter'></i>" . htmlspecialchars($row['account_twitter']) . "</div>
                            </td>";
                        echo "<td data-label='Wallet Info' class='account-cell'>
                                <div><i class='fas fa-wallet'></i>" . htmlspecialchars($row['wallet']) . "</div>
                                <div><i class='fas fa-key'></i>" . substr(htmlspecialchars($row['address']), 0, 8) . "..." . substr(htmlspecialchars($row['address']), -6) . "</div>
                            </td>";
                        echo "<td data-label='Tanggal Dibuat'>" . htmlspecialchars($row['created_at']) . "</td>";
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>