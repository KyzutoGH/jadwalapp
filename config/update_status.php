<?php
include 'koneksi.php'; // Sesuaikan dengan file koneksi database Anda

$id = $_GET['id'];
$status = $_GET['status'];

$sql = "UPDATE penagihan SET status = '$status' WHERE id = $id";

if ($db->query($sql) === TRUE) {
    // Set session untuk notifikasi
    session_start();
    $_SESSION['toastr'] = [
        'type' => 'success', // atau 'error', 'warning', 'info'
        'message' => 'Status telah diperbarui'
    ];
} else {
    session_start();
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Terjadi kesalahan saat memperbarui status'
    ];
}

$db->close();

// Redirect kembali ke halaman sebelumnya
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>