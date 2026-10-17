<?php
// process_aduan.php
header('Content-Type: application/json');
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAnonim      = isset($_POST['checkAnonim']) ? 1 : 0;
    $namaPelapor   = $isAnonim ? 'Anonim (Rahasia)' : trim($_POST['namaPelapor'] ?? '');
    $hpPelapor     = $isAnonim ? '-' : trim($_POST['hpPelapor'] ?? '');
    $rtrwPelapor   = trim($_POST['rtrwPelapor'] ?? '');
    $jenisLaporan  = trim($_POST['jenisLaporan'] ?? '');
    $kategoriTopik = trim($_POST['kategoriTopik'] ?? '');
    $judulLaporan  = trim($_POST['judulLaporan'] ?? '');
    $lokasiLaporan = trim($_POST['lokasiLaporan'] ?? '');
    $isiLaporan    = trim($_POST['isiLaporan'] ?? '');

    // Upload Foto Bukti (opsional)
    $fotoFilename = null;
    if (isset($_FILES['fotoBukti']) && $_FILES['fotoBukti']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['fotoBukti']['tmp_name'];
        $fileName    = $_FILES['fotoBukti']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadFileDir = 'uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $newFileName = 'bukti_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
            $dest_path   = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $fotoFilename = $newFileName;
            }
        }
    }

    $ticketCode = 'LAP-' . date('Ymd') . '-' . rand(1000, 9999);

    $sql = "INSERT INTO aduan_submissions 
            (ticket_code, is_anonim, nama_pelapor, hp_pelapor, rtrw_pelapor, jenis_laporan, kategori_topik, judul_laporan, lokasi_laporan, isi_laporan, foto_bukti) 
            VALUES (:ticket, :anonim, :nama, :hp, :rtrw, :jenis, :kategori, :judul, :lokasi, :isi, :foto)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ticket'   => $ticketCode,
            ':anonim'   => $isAnonim,
            ':nama'     => $namaPelapor,
            ':hp'       => $hpPelapor,
            ':rtrw'     => $rtrwPelapor,
            ':jenis'    => $jenisLaporan,
            ':kategori' => $kategoriTopik,
            ':judul'    => $judulLaporan,
            ':lokasi'   => $lokasiLaporan,
            ':isi'      => $isiLaporan,
            ':foto'     => $fotoFilename
        ]);

        echo json_encode([
            'status'     => 'success',
            'ticketCode' => $ticketCode,
            'nama'       => $namaPelapor,
            'judul'      => $judulLaporan
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan laporan: ' . $e->getMessage()]);
    }
}
?>