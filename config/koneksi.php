<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "airdrop";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO attributes for better error handling and consistent data types
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Fungsi helper untuk mencatat aktivitas user
function logUserActivity($pdo, $userId, $activityType, $description) {
    try {
        $stmt = $pdo->prepare("INSERT INTO user_activities (user_id, activity_type, description) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $activityType, $description]);
    } catch (PDOException $e) {
        error_log("Error logging user activity: " . $e->getMessage());
    }
}

// Fungsi helper untuk memformat tanggal
function formatDate($date) {
    return date('d M Y', strtotime($date));
}

// Fungsi helper untuk validasi input
function validateAirdropInput($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = "Nama project harus diisi";
    }
    
    if (empty($data['token'])) {
        $errors[] = "Token harus diisi";
    }
    
    if (empty($data['link']) || !filter_var($data['link'], FILTER_VALIDATE_URL)) {
        $errors[] = "Link project tidak valid";
    }
    
    if (empty($data['start_date']) || empty($data['end_date'])) {
        $errors[] = "Tanggal mulai dan selesai harus diisi";
    } elseif (strtotime($data['end_date']) < strtotime($data['start_date'])) {
        $errors[] = "Tanggal selesai tidak boleh lebih awal dari tanggal mulai";
    }
    
    return $errors;
}