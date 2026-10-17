<?php
// process_domisili.php
header('Content-Type: application/json');
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama           = trim($_POST['namaPemohon'] ?? '');
    $nik            = trim($_POST['nikPemohon'] ?? '');
    $tempatLahir    = trim($_POST['tempatLahir'] ?? '');
    $tglLahir       = trim($_POST['tglLahir'] ?? '');
    $jenisKelamin   = trim($_POST['jenisKelamin'] ?? '');
    $pekerjaan      = trim($_POST['pekerjaan'] ?? '');
    $hp             = trim($_POST['hpPemohon'] ?? '');
    $statusRumah    = trim($_POST['statusRumah'] ?? '');
    $rtrwDomisili   = trim($_POST['rtrwDomisili'] ?? '');
    $alamatDomisili = trim($_POST['alamatDomisili'] ?? '');
    $keperluan      = trim($_POST['keperluan'] ?? '');

    $trackingCode = 'DOM-' . date('Ymd') . '-' . rand(1000, 9999);

    $sql = "INSERT INTO domisili_submissions 
            (tracking_code, nama_pemohon, nik_pemohon, tempat_lahir, tgl_lahir, jenis_kelamin, pekerjaan, hp_pemohon, status_rumah, rtrw_domisili, alamat_domisili, keperluan) 
            VALUES (:code, :nama, :nik, :tempat, :tgl, :jk, :pekerjaan, :hp, :rumah, :rtrw, :alamat, :keperluan)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':code'      => $trackingCode,
            ':nama'      => $nama,
            ':nik'       => $nik,
            ':tempat'    => $tempatLahir,
            ':tgl'       => $tglLahir,
            ':jk'        => $jenisKelamin,
            ':pekerjaan' => $pekerjaan,
            ':hp'        => $hp,
            ':rumah'     => $statusRumah,
            ':rtrw'      => $rtrwDomisili,
            ':alamat'    => $alamatDomisili,
            ':keperluan' => $keperluan
        ]);

        echo json_encode([
            'status'       => 'success',
            'trackingCode' => $trackingCode,
            'nama'         => $nama,
            'rtrw'         => $rtrwDomisili
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    }
}
?>