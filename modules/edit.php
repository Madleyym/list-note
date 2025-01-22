<?php
session_start();
require_once '../config/koneksi.php';

// Ambil data airdrop berdasarkan ID
if (isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM airdrops_list WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $airdrop = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$airdrop) {
            $_SESSION['error'] = "Data tidak ditemukan!";
            header('Location: dashboard.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header('Location: dashboard.php');
        exit;
    }
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi input
        $required_fields = ['name', 'link', 'token', 'start_date', 'end_date', 'status'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field tidak boleh kosong");
            }
        }

        // Update data
        $sql = "UPDATE airdrops_list SET 
                name = :name,
                link = :link,
                token = :token,
                start_date = :start_date,
                end_date = :end_date,
                status = :status,
                account_google = :account_google,
                account_discord = :account_discord,
                account_twitter = :account_twitter
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':link', $_POST['link']);
        $stmt->bindParam(':token', $_POST['token']);
        $stmt->bindParam(':start_date', $_POST['start_date']);
        $stmt->bindParam(':end_date', $_POST['end_date']);
        $stmt->bindParam(':status', $_POST['status']);
        $stmt->bindParam(':account_google', $_POST['account_google']);
        $stmt->bindParam(':account_discord', $_POST['account_discord']);
        $stmt->bindParam(':account_twitter', $_POST['account_twitter']);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Data airdrop berhasil diupdate!";
            header('Location: dashboard.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Airdrop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Airdrop</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $airdrop['id']; ?>">

                    <div class="row g-3">
                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="projectName" name="name"
                                    value="<?php echo $airdrop['name']; ?>" required>
                                <label for="projectName">Nama Project</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tokenName" name="token"
                                    value="<?php echo $airdrop['token']; ?>" required>
                                <label for="tokenName">Nama Token</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="url" class="form-control" id="projectLink" name="link"
                                    value="<?php echo $airdrop['link']; ?>" required>
                                <label for="projectLink">Link Project</label>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="startDate" name="start_date"
                                    value="<?php echo $airdrop['start_date']; ?>" required>
                                <label for="startDate">Tanggal Mulai</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="endDate" name="end_date"
                                    value="<?php echo $airdrop['end_date']; ?>" required>
                                <label for="endDate">Tanggal Berakhir</label>
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="googleAccount" name="account_google"
                                    value="<?php echo $airdrop['account_google']; ?>">
                                <label for="googleAccount">Akun Google</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="discordAccount" name="account_discord"
                                    value="<?php echo $airdrop['account_discord']; ?>">
                                <label for="discordAccount">Username Discord</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="twitterAccount" name="account_twitter"
                                    value="<?php echo $airdrop['account_twitter']; ?>">
                                <label for="twitterAccount">Username Twitter</label>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" <?php echo $airdrop['status'] == 'active' ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="pending" <?php echo $airdrop['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="ended" <?php echo $airdrop['status'] == 'ended' ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Airdrop</button>
                            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>