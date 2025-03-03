<?php
session_start();
require_once 'koneksi.php';

// For debugging - uncomment these lines if needed
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('debug_log.txt', print_r($_POST, true));

try {
    // Basic data validation
    if (!isset($_POST['customer']) || !isset($_POST['total']) || !isset($_POST['jumlah_dp'])) {
        throw new Exception("Data yang dikirimkan tidak lengkap");
    }

    // Sanitize and gather form inputs
    $tanggal = sanitize_input($db, $_POST['tanggal']);
    $customer = sanitize_input($db, $_POST['customer']);
    $tanggal_pengambilan = sanitize_input($db, $_POST['tanggal_pengambilan']);
    $keterangan = sanitize_input($db, $_POST['keterangan']);
    $total = floatval($_POST['total']);
    $jumlah_dp = intval($_POST['jumlah_dp']);

    // DP1 data (always required)
    $dp1_tenggat = sanitize_input($db, $_POST['dp1_tenggat']);
    $dp1_nominal = floatval($_POST['dp1_nominal']);
    $dp1_metode = sanitize_input($db, $_POST['dp1_metode']);
    $dp1_status = sanitize_input($db, $_POST['dp1_status']);

    // Convert status text to numeric value for database
    $dp1_status_numeric = ($dp1_status == 'lunas') ? '1' : '0';

    // DP2 data (if applicable)
    $dp2_tenggat = ($jumlah_dp >= 2 && isset($_POST['dp2_tenggat'])) ? sanitize_input($db, $_POST['dp2_tenggat']) : null;
    $dp2_nominal = ($jumlah_dp >= 2 && isset($_POST['dp2_nominal'])) ? floatval($_POST['dp2_nominal']) : 0;
    $dp2_metode = ($jumlah_dp >= 2 && isset($_POST['dp2_metode'])) ? sanitize_input($db, $_POST['dp2_metode']) : 'pending';
    $dp2_status = '0'; // Always pending for future payments

    // DP3 data (if applicable)
    $dp3_tenggat = ($jumlah_dp >= 3 && isset($_POST['dp3_tenggat'])) ? sanitize_input($db, $_POST['dp3_tenggat']) : null;
    $dp3_nominal = ($jumlah_dp >= 3 && isset($_POST['dp3_nominal'])) ? floatval($_POST['dp3_nominal']) : 0;
    $dp3_metode = ($jumlah_dp >= 3 && isset($_POST['dp3_metode'])) ? sanitize_input($db, $_POST['dp3_metode']) : 'pending';
    $dp3_status = '0'; // Always pending for future payments

    // Validasi data
    if ($jumlah_dp < 1 || $jumlah_dp > 3) {
        throw new Exception("Jumlah DP harus antara 1 sampai 3");
    }

    if ($total <= 0) {
        throw new Exception("Total pembayaran harus lebih dari 0");
    }

    if ($dp1_nominal <= 0) {
        throw new Exception("Nominal DP pertama tidak valid");
    }

    // Validate total DP doesn't exceed total amount (only check DP1 since others are set to 0)
    if ($dp1_nominal > $total) {
        throw new Exception("Total DP tidak boleh melebihi total pembayaran");
    }

    // Overall payment status based on DP1
    $status = $dp1_status_numeric; // 1 = DP pertama sudah dibayar, 0 = belum dibayar

    // Query untuk insert data - revised to handle NULL values correctly
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
            '$dp1_status_numeric', 
            " . ($dp2_tenggat ? "'$dp2_tenggat'" : "NULL") . ", 
            $dp2_nominal,
            '$dp2_metode',
            '$dp2_status', 
            " . ($dp3_tenggat ? "'$dp3_tenggat'" : "NULL") . ", 
            $dp3_nominal,
            '$dp3_metode',
            '$dp3_status',
            '$keterangan',
            '$status'
        )";

    // For debugging - uncomment if needed
    // file_put_contents('sql_debug.txt', $sql);

    if (mysqli_query($db, $sql)) {
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Data pembayaran DP berhasil disimpan!'
        ];
        header("Location: ../index.php?menu=Penagihan");
        exit;
    } else {
        throw new Exception(mysqli_error($db));
    }
} catch (Exception $e) {
    // Log the error for debugging
    error_log("Error in create_dp.php: " . $e->getMessage());

    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Gagal memproses data: ' . $e->getMessage()
    ];
    header("Location: ../index.php?menu=Create&submenu=Penagihan");
    exit;
}

/**
 * Helper function to sanitize input data
 * @param mysqli $db Database connection
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize_input($db, $data)
{
    if (empty($data)) {
        return '';
    }
    $data = trim($data);
    $data = mysqli_real_escape_string($db, $data);
    return $data;
}
?>