<?php
session_start();
require_once 'koneksi.php';

// Untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('debug_log.txt', print_r($_POST, true));

// Simpan data tanpa validasi yang rumit
try {
    // Ambil semua data dari form
    $tanggal = $_POST['tanggal'];
    $customer = $_POST['customer'];
    $tanggal_pengambilan = $_POST['tanggal_pengambilan'];
    $keterangan = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';
    $total = floatval($_POST['total']);
    $jumlah_dp = intval($_POST['jumlah_dp']);

    // Nominal pembayaran dari form
    $dp1_nominal = floatval($_POST['nominal_pembayaran_1']);
    $dp2_nominal = ($jumlah_dp >= 2) ? floatval($_POST['nominal_pembayaran_2']) : 0;
    $dp3_nominal = ($jumlah_dp >= 3) ? floatval($_POST['nominal_pembayaran_3']) : 0;

    // Tanggal jatuh tempo (ambil dari form jika ada atau set default)
    $dp1_tenggat = isset($_POST['dp1_tenggat']) ? $_POST['dp1_tenggat'] : date('Y-m-d', strtotime('+1 day'));
    $dp2_tenggat = isset($_POST['dp2_tenggat']) ? $_POST['dp2_tenggat'] : date('Y-m-d', strtotime('+2 day'));
    $dp3_tenggat = isset($_POST['dp3_tenggat']) ? $_POST['dp3_tenggat'] : date('Y-m-d', strtotime('+3 day'));

    // Metode dan status default
    $dp1_metode = 'cash'; // Default metode
    $dp2_metode = 'cash';
    $dp3_metode = 'cash';

    $dp1_status = '0'; // Default belum lunas
    $dp2_status = '0';
    $dp3_status = '0';

    // Status keseluruhan (1 = belum lunas)
    $status = '1';

    // Query sederhana
    $sql = "INSERT INTO penagihan (
                tanggal, 
                customer, 
                total, 
                jumlah_dp,
                tanggal_pengambilan,
                dp1_tenggat, 
                dp1_nominal, 
                dp1_metode,
                dp1_status,
                dp2_tenggat,
                dp2_nominal,
                dp2_metode,
                dp2_status,
                dp3_tenggat,
                dp3_nominal,
                dp3_metode,
                dp3_status,
                keterangan,
                status
            ) VALUES (
                '$tanggal', 
                '$customer', 
                $total, 
                $jumlah_dp,
                '$tanggal_pengambilan',
                '$dp1_tenggat', 
                $dp1_nominal, 
                '$dp1_metode',
                '$dp1_status',
                '$dp2_tenggat',
                $dp2_nominal,
                '$dp2_metode',
                '$dp2_status',
                '$dp3_tenggat',
                $dp3_nominal,
                '$dp3_metode',
                '$dp3_status',
                '$keterangan',
                '$status'
            )";

    // Eksekusi query langsung
    if ($db->query($sql)) {
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Data pembayaran DP berhasil disimpan!'
        ];
        header("Location: ../index.php?menu=Penagihan");
        exit;
    } else {
        throw new Exception("Error executing query: " . $db->error);
    }
} catch (Exception $e) {
    // Log error dan beri feedback kepada user
    error_log("Error in create_dp.php: " . $e->getMessage());

    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Gagal memproses data: ' . $e->getMessage()
    ];
    header("Location: ../index.php?menu=Create&submenu=Penagihan");
    exit;
}