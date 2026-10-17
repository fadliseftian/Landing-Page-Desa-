<?php
// check_status_sku.php
header('Content-Type: application/json');

$host     = 'localhost';
$username = 'root';
$password = ''; // Sesuaikan dengan konfigurasi phpMyAdmin
$db_name  = 'db_desasukasari';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal']);
    exit();
}

$keyword = trim($_GET['keyword'] ?? '');

if (empty($keyword)) {
    echo json_encode(['status' => 'error', 'message' => 'Kata kunci pencarian kosong']);
    exit();
}

// Cari berdasarkan tracking_code ATAU nik_pemohon
$sql = "SELECT tracking_code, nama_pemohon, nik_pemohon, nama_usaha, status, DATE_FORMAT(created_at, '%d %b %Y, %H:%i') AS created_at 
        FROM sku_submissions 
        WHERE tracking_code = :kw OR nik_pemohon = :kw 
        ORDER BY id DESC LIMIT 1";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':kw' => $keyword]);
    $row = $stmt->fetch();

    if ($row) {
        echo json_encode([
            'status' => 'success',
            'data'   => $row
        ]);
    } else {
        echo json_encode([
            'status'  => 'not_found',
            'message' => 'Data tidak ditemukan'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>