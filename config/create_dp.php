<?php
// proses_penagihan.php
session_start();
require_once 'koneksi.php';

// Ambil data dari form
$tanggal = $_POST['tanggal'];
$customer = $_POST['customer'];
$total = $_POST['total'];
$jumlah_dp = $_POST['jumlah_dp'];
$dp1_tenggat = $_POST['dp1_tenggat'];
$dp1_nominal = $_POST['dp1_nominal'];
$dp2_tenggat = !empty($_POST['dp2_tenggat']) ? "'" . $_POST['dp2_tenggat'] . "'" : "NULL";
$dp2_nominal = !empty($_POST['dp2_nominal']) ? $_POST['dp2_nominal'] : "NULL";
$dp3_tenggat = !empty($_POST['dp3_tenggat']) ? "'" . $_POST['dp3_tenggat'] . "'" : "NULL";
$dp3_nominal = !empty($_POST['dp3_nominal']) ? $_POST['dp3_nominal'] : "NULL";
$status = '1'; // Set initial status

$sql = "INSERT INTO penagihan (
        tanggal, customer, total, jumlah_dp, 
        dp1_tenggat, dp1_nominal, 
        dp2_tenggat, dp2_nominal, 
        dp3_tenggat, dp3_nominal,
        status
    ) VALUES (
        '$tanggal', '$customer', $total, $jumlah_dp, 
        '$dp1_tenggat', $dp1_nominal, 
        $dp2_tenggat, $dp2_nominal, 
        $dp3_tenggat, $dp3_nominal, 
        '$status'
    )";

if (mysqli_query($db, $sql)) {
    $_SESSION['toastr'] = [
        'type' => 'success',
        'message' => 'Data berhasil ditambahkan!',
    ];
    header("Location: ../index.php?menu=Penagihan"); // Redirect setelah sukses
    exit;
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Gagal menambahkan data: ' . mysqli_error($db),
    ];
    header("Location: ../index.php?menu=Create&submenu=Penagihan"); // Ganti dengan halaman form Anda
    exit;
}
?>