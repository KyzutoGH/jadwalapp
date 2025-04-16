<?php
session_start();
include 'koneksi.php';

$id = isset($_GET['custId']) ? (int) $_GET['custId'] : 0;
$cicilanKe = isset($_GET['cicilanKe']) ? (int) $_GET['cicilanKe'] : 0;
$jumlahBayar = isset($_GET['jumlahBayar']) ? (float) $_GET['jumlahBayar'] : 0;
$tanggalPembayaran = isset($_GET['tanggalPembayaran']) ? $_GET['tanggalPembayaran'] : date('Y-m-d');
$keterangan = isset($_GET['keterangan']) ? mysqli_real_escape_string($db, $_GET['keterangan']) : '';

if ($id && $cicilanKe && $jumlahBayar) {
    // Ambil data penagihan
    $checkSql = "SELECT 
                    total,
                    jumlah_dp,
                    COALESCE(dp1_nominal, 0) as dp1_nominal,
                    COALESCE(dp2_nominal, 0) as dp2_nominal,
                    COALESCE(dp3_nominal, 0) as dp3_nominal,
                    COALESCE(dp1_status, 0) as dp1_status,
                    COALESCE(dp2_status, 0) as dp2_status,
                    COALESCE(dp3_status, 0) as dp3_status
                FROM penagihan 
                WHERE id = $id";

    $result = $db->query($checkSql);
    if (!$result) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Error checking payment status: ' . $db->error
        ];
        header('Location: ../index.php?menu=Penagihan');
        exit;
    }

    $row = $result->fetch_assoc();

    // Hitung total yang sudah dibayar berdasarkan status
    $totalPaid = 0;
    if ($row['jumlah_dp'] >= 1 && $row['dp1_status'] > 0)
        $totalPaid += $row['dp1_nominal'];
    if ($row['jumlah_dp'] >= 2 && $row['dp2_status'] > 0)
        $totalPaid += $row['dp2_nominal'];
    if ($row['jumlah_dp'] >= 3 && $row['dp3_status'] > 0)
        $totalPaid += $row['dp3_nominal'];

    $remainingBalance = $row['total'] - $totalPaid;

    // Jika pelunasan (di luar jumlah_dp)
    if ($cicilanKe > $row['jumlah_dp']) {
        if ($jumlahBayar < $remainingBalance) {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Jumlah pelunasan kurang dari sisa tagihan: ' . number_format($remainingBalance, 0, ',', '.')
            ];
            header('Location: ../index.php?menu=Penagihan');
            exit;
        }

        $sql = "UPDATE penagihan 
                SET pelunasan = $jumlahBayar,
                    tgllunas = '$tanggalPembayaran'
                WHERE id = $id";
    } else {
        // Pembayaran cicilan biasa
        $columnNominal = "dp{$cicilanKe}_nominal";
        $columnTanggal = "dp{$cicilanKe}_tenggat";
        $columnStatus = "dp{$cicilanKe}_status";

        if (($totalPaid + $jumlahBayar) > $row['total']) {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Jumlah pembayaran melebihi total tagihan'
            ];
            header('Location: ../index.php?menu=Penagihan');
            exit;
        }

        $sql = "UPDATE penagihan 
                SET $columnNominal = $jumlahBayar,
                    $columnTanggal = '$tanggalPembayaran',
                    $columnStatus = '1'
                WHERE id = $id";
    }

    // Eksekusi query pembayaran
    if ($db->query($sql)) {
        $newTotalPaid = $totalPaid + $jumlahBayar;

        // Cek apakah sudah lunas
        if ($newTotalPaid >= $row['total']) {
            $db->query("UPDATE penagihan 
                        SET status = '2',
                            tgllunas = '$tanggalPembayaran'
                        WHERE id = $id");
        } else {
            $db->query("UPDATE penagihan SET status = '1' WHERE id = $id");
        }

        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => ($cicilanKe > $row['jumlah_dp']) ?
                'Pelunasan berhasil disimpan' :
                'Pembayaran cicilan berhasil disimpan'
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