<?php
session_start();
include 'koneksi.php';

// Ambil dari POST sesuai formBatalkan
$id = isset($_POST['custIdBatal']) ? (int) $_POST['custIdBatal'] : 0;
$alasan = isset($_POST['alasanBatal']) ? mysqli_real_escape_string($db, $_POST['alasanBatal']) : '';

if ($id && $alasan) {
    $sql = "UPDATE penagihan 
            SET status = '5',
                alasan_batal = '$alasan',
                tgl_batal = CURRENT_TIMESTAMP
            WHERE id = $id";

    if ($db->query($sql)) {
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Pesanan berhasil dibatalkan'
        ];
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Gagal membatalkan pesanan: ' . $db->error
        ];
    }
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Data tidak lengkap'
    ];
}

$db->close();
header('Location: ../index.php?menu=Penagihan');
exit;
