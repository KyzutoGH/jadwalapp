<?php
session_start();
include 'koneksi.php';

$id = isset($_GET['custId']) ? (int) $_GET['custId'] : 0;
$cicilanKe = isset($_GET['cicilanKe']) ? (int) $_GET['cicilanKe'] : 0;
$jumlahBayar = isset($_GET['jumlahBayar']) ? (float) $_GET['jumlahBayar'] : 0;
$tanggalPembayaran = isset($_GET['tanggalPembayaran']) ? $_GET['tanggalPembayaran'] : date('Y-m-d');
$keterangan = isset($_GET['keterangan']) ? mysqli_real_escape_string($db, $_GET['keterangan']) : '';

if ($id && $cicilanKe && $jumlahBayar) {
    $columnNominal = "dp{$cicilanKe}_nominal";
    $columnTanggal = "dp{$cicilanKe}_tenggat";

    // Update pembayaran
    $sql = "UPDATE penagihan 
            SET $columnNominal = $jumlahBayar,
                $columnTanggal = '$tanggalPembayaran'
            WHERE id = $id";

    if ($db->query($sql)) {
        // Check if this is the final payment
        $checkSql = "SELECT total, 
                    COALESCE(dp1_nominal, 0) + COALESCE(dp2_nominal, 0) + COALESCE(dp3_nominal, 0) as total_dibayar 
                    FROM penagihan WHERE id = $id";

        $result = $db->query($checkSql);
        if ($result && $row = $result->fetch_assoc()) {
            // Jika total pembayaran sama dengan total tagihan, update status menjadi lunas
            if ($row['total_dibayar'] >= $row['total']) {
                $updateStatus = "UPDATE penagihan 
                                SET status = '2',
                                    tgllunas = '$tanggalPembayaran'
                                WHERE id = $id";
                $db->query($updateStatus);
            }
        }

        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Pembayaran cicilan berhasil disimpan'
        ];
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Gagal menyimpan pembayaran: ' . $db->error
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
?>