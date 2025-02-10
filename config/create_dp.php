<?php
// proses_penagihan.php
session_start();
require_once 'koneksi.php';

// Validate and sanitize input data
function sanitize_input($db, $data)
{
    return mysqli_real_escape_string($db, trim($data));
}

try {
    // Ambil dan sanitasi data dari form
    $tanggal = sanitize_input($db, $_POST['tanggal']);
    $customer = sanitize_input($db, $_POST['customer']);
    $total = floatval($_POST['total']);
    $jumlah_dp = intval($_POST['jumlah_dp']);
    $dp1_tenggat = sanitize_input($db, $_POST['dp1_tenggat']);
    $dp1_nominal = floatval($_POST['dp1_nominal']);

    // Validasi data
    if ($jumlah_dp < 1 || $jumlah_dp > 3) {
        throw new Exception("Jumlah DP harus antara 1 sampai 3");
    }

    if ($total <= 0) {
        throw new Exception("Total pembayaran harus lebih dari 0");
    }

    if ($dp1_nominal <= 0 || $dp1_nominal > $total) {
        throw new Exception("Nominal DP pertama tidak valid");
    }

    // Status awal pembayaran
    $status = '1'; // 1 = DP pertama sudah dibayar

    // Query untuk insert data
    $sql = "INSERT INTO penagihan (
            tanggal, 
            customer, 
            total, 
            jumlah_dp, 
            dp1_tenggat, 
            dp1_nominal, 
            dp1_status,
            dp2_status,
            dp3_status,
            status
        ) VALUES (
            '$tanggal', 
            '$customer', 
            $total, 
            $jumlah_dp, 
            '$dp1_tenggat', 
            $dp1_nominal, 
            '1', -- dp1_status = 1 (sudah dibayar)
            '0', -- dp2_status = 0 (belum dibayar)
            '0', -- dp3_status = 0 (belum dibayar)
            '$status'
        )";

    if (mysqli_query($db, $sql)) {
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Data pembayaran DP pertama berhasil disimpan!'
        ];
        header("Location: ../index.php?menu=Penagihan");
        exit;
    } else {
        throw new Exception(mysqli_error($db));
    }

} catch (Exception $e) {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Gagal memproses data: ' . $e->getMessage()
    ];
    header("Location: ../index.php?menu=Create&submenu=Penagihan");
    exit;
}
?>