<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/koneksi.php';

if (!isset($pdo)) {
    die("Koneksi database tidak tersedia");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Airdrops</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styles */
        .navbar {
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link {
            color: white;
            /* Mengatur warna teks menjadi putih */
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                margin-top: 1rem;
                padding: 0.5rem 0;
            }

            .nav-item {
                padding: 0.5rem 0;
            }
        }

        /* Main Content Styles */
        .hero-section {
            padding: 2rem 0;
            background: linear-gradient(135deg, #f8f9fa 100%, #e9ecef 0%);
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin: 0 0.5rem;
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .table-container {
                padding: 1rem;
                margin: 0;
            }
        }

        /* Header and Filter Styles */
        .header-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .header-content {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .filter-controls {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        @media (min-width: 768px) {
            .filter-controls {
                flex-direction: row;
                width: auto;
            }
        }

        /* Table Styles */
        .table-responsive {
            margin: 0 -1rem;
            padding: 0 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 800px;
            /* Minimum width to prevent cramping */
        }

        @media (max-width: 768px) {

            .table th,
            .table td {
                min-width: 120px;
                /* Minimum width for cells on mobile */
                white-space: nowrap;
                /* Prevent text wrapping */
            }
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            white-space: nowrap;
        }

        .status-active {
            background-color: rgba(25, 135, 84, 0.1);
            color: var(--success-color);
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #856404;
        }

        .status-ended {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        /* Card View for Mobile */
        @media (max-width: 767px) {
            .mobile-card {
                display: flex;
                flex-direction: column;
                background: white;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .mobile-card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.5rem;
            }

            .mobile-card-body {
                display: grid;
                grid-gap: 0.5rem;
            }

            .mobile-card-label {
                font-weight: 600;
                color: #6c757d;
                font-size: 0.875rem;
            }

            .table-view {
                display: none;
            }

            .card-view {
                display: block;
            }

        }

        @media (min-width: 768px) {
            .table-view {
                display: block;
            }

            .card-view {
                display: none;
            }
        }

        /* Pagination Styles */
        .pagination-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
            margin-top: 1.5rem;
        }

        @media (min-width: 768px) {
            .pagination-container {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .pagination {
            margin: 0;
        }

        .page-link {
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            margin: 0 2px;
        }

        /* Footer Styles */
        footer {
            margin-top: auto;
            background-color: #212529;
            color: white;
            padding: 2rem 0;
        }

        @media (max-width: 768px) {
            footer .text-md-end {
                text-align: left !important;
                margin-top: 1.5rem;
            }
        }

        /* Form Control Styles */
        .form-select,
        .form-control {
            width: 100%;
        }

        @media (min-width: 768px) {
            .form-select {
                width: 150px;
            }

            .form-control {
                width: 200px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
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
                        <a class="nav-link" href="../auth/login.php">Masuk</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="airdrop.php">Airdrop</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content-wrapper">
        <section class="hero-section">
            <div class="container">
                <div class="table-container">
                    <!-- Header with Search and Filter -->
                    <div class="header-content">
                        <h2 class="h4 mb-0">Daftar Airdrop Terbaru</h2>
                        <div class="filter-controls">
                            <select class="form-select">
                                <option value="all">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="pending">Pending</option>
                                <option value="ended">Selesai</option>
                            </select>
                            <input type="search" class="form-control" placeholder="Cari airdrop...">
                        </div>
                    </div>

                    <!-- Table View (Desktop) -->
                    <div class="table-view">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Project</th>
                                        <th>Token</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Status</th>
                                        <th>Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT * FROM airdrops_list ORDER BY created_at DESC");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['token']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                                            echo "<td><span class='status-badge status-" . htmlspecialchars($row['status']) . "'>"
                                                . ucfirst(htmlspecialchars($row['status'])) . "</span></td>";
                                            echo "<td><a href='" . htmlspecialchars($row['link']) . "' class='btn btn-sm btn-primary' target='_blank'>"
                                                . "<i class='fas fa-external-link-alt me-1'></i>Kunjungi</a></td>";
                                            echo "</tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "<tr><td colspan='6' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card View (Mobile) -->
                    <div class="card-view">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM airdrops_list ORDER BY created_at DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<div class='mobile-card'>";
                                echo "<div class='mobile-card-header'>";
                                echo "<h3 class='h6 mb-0'>" . htmlspecialchars($row['name']) . "</h3>";
                                echo "<span class='status-badge status-" . htmlspecialchars($row['status']) . "'>"
                                    . ucfirst(htmlspecialchars($row['status'])) . "</span>";
                                echo "</div>";
                                echo "<div class='mobile-card-body'>";
                                echo "<div><span class='mobile-card-label'>Token:</span> " . htmlspecialchars($row['token']) . "</div>";
                                echo "<div><span class='mobile-card-label'>Mulai:</span> " . htmlspecialchars($row['start_date']) . "</div>";
                                echo "<div><span class='mobile-card-label'>Selesai:</span> " . htmlspecialchars($row['end_date']) . "</div>";
                                echo "<div class='mt-2'><a href='" . htmlspecialchars($row['link']) . "' class='btn btn-sm btn-primary w-100' target='_blank'>"
                                    . "<i class='fas fa-external-link-alt me-1'></i>Kunjungi</a></div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } catch (PDOException $e) {
                            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                        }
                        ?>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="text-muted small">
                            Menampilkan data airdrop terbaru
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Catatan Airdrop Platform</h5>
                    <p>Platform airdrop terkemuka List Airdrops.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Kontak Order</h5>
                    <p>Email: efbeesone@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>