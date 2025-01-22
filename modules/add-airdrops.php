<?php
session_start();
require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi input
        $required_fields = ['name', 'link', 'token', 'start_date', 'end_date', 'status'];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field tidak boleh kosong");
            }
        }

        // Prepare statement dengan PDO
        $sql = "INSERT INTO airdrops_list (name, link, token, start_date, end_date, status, 
                account_google, account_discord, account_twitter) 
                VALUES (:name, :link, :token, :start_date, :end_date, :status, 
                :account_google, :account_discord, :account_twitter)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':link', $_POST['link']);
        $stmt->bindParam(':token', $_POST['token']);
        $stmt->bindParam(':start_date', $_POST['start_date']);
        $stmt->bindParam(':end_date', $_POST['end_date']);
        $stmt->bindParam(':status', $_POST['status']);
        $stmt->bindParam(':account_google', $_POST['account_google']);
        $stmt->bindParam(':account_discord', $_POST['account_discord']);
        $stmt->bindParam(':account_twitter', $_POST['account_twitter']);

        // Execute query
        $stmt->execute();

        $_SESSION['success'] = "Airdrop berhasil ditambahkan!";
        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
