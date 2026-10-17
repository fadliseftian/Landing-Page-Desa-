<?php
// process_sku.php
header('Content-Type: application/json');
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama            = trim($_POST['namaPemohon'] ?? '');
    $nik             = trim($_POST['nikPemohon'] ?? '');
    $hp              = trim($_POST['hpPemohon'] ?? '');
    $rtrw            = trim($_POST['rtrwPemohon'] ?? '');
    $namaUsaha       = trim($_POST['namaUsaha'] ?? '');
    $jenisUsaha      = trim($_POST['jenisUsaha'] ?? '');
    $lamaUsaha       = trim($_POST['lamaUsaha'] ?? '');
    $tujuanPengajuan = trim($_POST['tujuanPengajuan'] ?? '');
    $alamatUsaha     = trim($_POST['alamatUsaha'] ?? '');

    if (empty($nama) || empty($nik) || empty($hp) || empty($namaUsaha)) {
        echo json_encode(['status' => 'error', 'message' => 'Harap lengkapi kolom yang wajib diisi.']);
        exit();
    }

    $trackingCode = 'SKU-' . date('Ymd') . '-' . rand(1000, 9999);

    $sql = "INSERT INTO sku_submissions 
            (tracking_code, nama_pemohon, nik_pemohon, hp_pemohon, rtrw_pemohon, nama_usaha, jenis_usaha, lama_usaha, tujuan_pengajuan, alamat_usaha) 
            VALUES (:code, :nama, :nik, :hp, :rtrw, :namaUsaha, :jenisUsaha, :lamaUsaha, :tujuan, :alamat)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':code'        => $trackingCode,
            ':nama'        => $nama,
            ':nik'         => $nik,
            ':hp'          => $hp,
            ':rtrw'        => $rtrw,
            ':namaUsaha'   => $namaUsaha,
            ':jenisUsaha'  => $jenisUsaha,
            ':lamaUsaha'   => $lamaUsaha,
            ':tujuan'      => $tujuanPengajuan,
            ':alamat'      => $alamatUsaha
        ]);

        echo json_encode([
            'status'        => 'success',
            'trackingCode'  => $trackingCode,
            'nama'          => $nama,
            'namaUsaha'     => $namaUsaha
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    }
}
?>