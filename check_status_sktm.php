<?php
// check_status_sktm.php
header('Content-Type: application/json');

$host     = 'localhost';
$username = 'root';
$password = ''; // Sesuaikan jika ada password MySQL/phpMyAdmin
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

// Cari di tabel sktm_submissions berdasarkan tracking_code, no_kk, nik_kk, atau nik_siswa
$sql = "SELECT tracking_code, nama_kk, no_kk, nama_siswa, kategori_sktm, status, DATE_FORMAT(created_at, '%d %b %Y, %H:%i') AS created_at 
        FROM sktm_submissions 
        WHERE tracking_code = :kw OR no_kk = :kw OR nik_kk = :kw OR nik_siswa = :kw 
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