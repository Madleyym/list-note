<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/koneksi.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Airdrops</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- <img src="assets/images/airdrop.png" alt="Airdrop Platform" class="img-fluid" style="margin-left: -5px;"> -->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">List Airdrops</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php">Masuk</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="airdrop.php">Airdrop</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 mb-4">Catatan Airdrops!</h1>
                    <p>Aplikasi platform airdrop ini hanya digunakan untuk mencatat airdrop.</p>
                    <a href="./modules/list-airdrops.php" class="btn btn-primary btn-lg">List Airdrops</a>
                </div>
                <div class="col-md-4">
                    <img src="assets/images/airdrop.png" alt="Airdrop Platform" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

 <!-- Airdrop Features Section -->
<div class="features py-5 mb-5">
    <div class="container">
        <h2 class="text-center mb-5">Airdrop Feature</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-gift fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Airdrop Gratis</h5>
                        <p class="card-text">Dapatkan token gratis hanya dengan mengikuti tugas sederhana!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Komunitas Aktif</h5>
                        <p class="card-text">Bergabung dengan komunitas yang aktif dan berbagi informasi tentang airdrop.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Pelacakan Airdrop</h5>
                        <p class="card-text">Pantau perkembangan airdrop yang telah kamu ikuti dengan mudah.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
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

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>
