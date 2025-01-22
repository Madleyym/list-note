<?php
session_start();
require_once '../config/koneksi.php';

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];

        // Prepare statement
        $stmt = $pdo->prepare("DELETE FROM airdrops_list WHERE id = :id");
        $stmt->bindParam(':id', $id);

        // Execute dan cek hasilnya
        if ($stmt->execute()) {
            $_SESSION['success'] = "Data airdrop berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data airdrop!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID tidak ditemukan!";
}

// Redirect kembali ke dashboard
header('Location: dashboard.php');
exit;
