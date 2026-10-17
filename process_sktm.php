<?php
// process_sktm.php
header('Content-Type: application/json');
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaKK         = trim($_POST['namaKK'] ?? '');
    $noKK           = trim($_POST['noKK'] ?? '');
    $nikKK          = trim($_POST['nikKK'] ?? '');
    $pekerjaanKK    = trim($_POST['pekerjaanKK'] ?? '');
    $namaSiswa      = trim($_POST['namaSiswa'] ?? '');
    $nikSiswa       = trim($_POST['nikSiswa'] ?? '');
    $hp             = trim($_POST['hpPemohon'] ?? '');
    $rtrwRumah      = trim($_POST['rtrwRumah'] ?? '');
    $kategoriSKTM   = trim($_POST['kategoriSKTM'] ?? '');
    $instansiTujuan = trim($_POST['instansiTujuan'] ?? '');
    $alamatRumah    = trim($_POST['alamatRumah'] ?? '');

    $trackingCode = 'SKTM-' . date('Ymd') . '-' . rand(1000, 9999);

    $sql = "INSERT INTO sktm_submissions 
            (tracking_code, nama_kk, no_kk, nik_kk, pekerjaan_kk, nama_siswa, nik_siswa, hp_pemohon, rtrw_rumah, kategori_sktm, instansi_tujuan, alamat_rumah) 
            VALUES (:code, :namaKK, :noKK, :nikKK, :pekerjaanKK, :namaSiswa, :nikSiswa, :hp, :rtrw, :kategori, :instansi, :alamat)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':code'        => $trackingCode,
            ':namaKK'      => $namaKK,
            ':noKK'        => $noKK,
            ':nikKK'       => $nikKK,
            ':pekerjaanKK' => $pekerjaanKK,
            ':namaSiswa'   => $namaSiswa,
            ':nikSiswa'    => $nikSiswa,
            ':hp'          => $hp,
            ':rtrw'        => $rtrwRumah,
            ':kategori'    => $kategoriSKTM,
            ':instansi'    => $instansiTujuan,
            ':alamat'      => $alamatRumah
        ]);

        echo json_encode([
            'status'       => 'success',
            'trackingCode' => $trackingCode,
            'namaKK'       => $namaKK,
            'namaSiswa'    => $namaSiswa
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    }
}
?>