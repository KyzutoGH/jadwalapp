<?php
session_start();
include 'koneksi.php';

// Mengaktifkan mode debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ambil data dari POST/GET
$id = isset($_REQUEST['penagihan_id']) ? (int) $_REQUEST['penagihan_id'] : (isset($_REQUEST['custId']) ? (int) $_REQUEST['custId'] : 0);
$cicilanKe = isset($_REQUEST['cicilan_ke']) ? (int) $_REQUEST['cicilan_ke'] : (isset($_REQUEST['cicilanKe']) ? (int) $_REQUEST['cicilanKe'] : 0);
$jumlahBayar = isset($_REQUEST['nominal']) ? (float) str_replace(['.', ','], ['', '.'], $_REQUEST['nominal']) : (isset($_REQUEST['jumlahBayar']) ? (float) $_REQUEST['jumlahBayar'] : 0);
$metodePembayaran = isset($_REQUEST['metode']) ? mysqli_real_escape_string($db, $_REQUEST['metode']) : '';
$tanggalPembayaran = isset($_REQUEST['tanggal_bayar']) ? $_REQUEST['tanggal_bayar'] : (isset($_REQUEST['tanggalPembayaran']) ? $_REQUEST['tanggalPembayaran'] : date('Y-m-d'));
$keterangan = isset($_REQUEST['keterangan']) ? mysqli_real_escape_string($db, $_REQUEST['keterangan']) : '';

// Debug array
$debugData = [
    'Input' => [
        'id' => $id,
        'cicilanKe' => $cicilanKe,
        'jumlahBayar' => $jumlahBayar,
        'metodePembayaran' => $metodePembayaran,
        'tanggalPembayaran' => $tanggalPembayaran,
        'keterangan' => $keterangan
    ]
];

// Validasi data
if (!$id || !$cicilanKe || !$jumlahBayar) {
    if (isset($_REQUEST['debug'])) {
        echo "<pre>";
        echo "Data tidak lengkap:\n";
        print_r($debugData);
        echo "</pre>";
        exit;
    }

    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Data tidak lengkap'
    ];
    header('Location: ../index.php?menu=Penagihan');
    exit;
}

// Mulai transaksi
$db->begin_transaction();

try {
    // Ambil data penagihan dengan FOR UPDATE untuk locking
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
                WHERE id = $id
                FOR UPDATE";

    $result = $db->query($checkSql);
    $debugData['SQL Query'] = $checkSql;

    if (!$result || $result->num_rows == 0) {
        throw new Exception('Data penagihan tidak ditemukan');
    }

    $row = $result->fetch_assoc();
    $debugData['Penagihan Data'] = $row;

    $totalTagihan = $row['total'];
    $jumlahDP = $row['jumlah_dp'];

    // Hitung total yang sudah dibayar berdasarkan status
    $totalPaid = 0;
    if ($jumlahDP >= 1 && $row['dp1_status'] > 0)
        $totalPaid += $row['dp1_nominal'];
    if ($jumlahDP >= 2 && $row['dp2_status'] > 0)
        $totalPaid += $row['dp2_nominal'];
    if ($jumlahDP >= 3 && $row['dp3_status'] > 0)
        $totalPaid += $row['dp3_nominal'];

    // Add current payment to calculation
    $newTotalPaid = $totalPaid + $jumlahBayar;
    $remainingBalance = $totalTagihan - $totalPaid;
    $debugData['Calculation'] = [
        'totalTagihan' => $totalTagihan,
        'totalPaid' => $totalPaid,
        'newTotalPaid' => $newTotalPaid,
        'remainingBalance' => $remainingBalance
    ];

    // Validasi pembayaran
    if ($cicilanKe > $jumlahDP + 1) {
        throw new Exception('Nomor cicilan tidak valid');
    }

    // Jika pelunasan (cicilan ke N+1)
    if ($cicilanKe > $jumlahDP) {
        if ($jumlahBayar < $remainingBalance) {
            throw new Exception('Jumlah pelunasan kurang dari sisa tagihan: Rp ' . number_format($remainingBalance, 0, ',', '.'));
        }

        $sql = "UPDATE penagihan 
                SET pelunasan = $jumlahBayar,
                    tgllunas = '$tanggalPembayaran',
                    status = '2'
                WHERE id = $id";
    } else {
        // Cek apakah cicilan ini sudah dibayar
        $statusColumn = "dp{$cicilanKe}_status";
        if ($row[$statusColumn] > 0) {
            throw new Exception('Cicilan ini sudah dibayar sebelumnya');
        }

        // Validasi nominal cicilan (opsional, bisa dihapus jika ingin fleksibel)
        $nominalColumn = "dp{$cicilanKe}_nominal";
        $expectedNominal = $row[$nominalColumn];
        if ($expectedNominal > 0 && $jumlahBayar < $expectedNominal) {
            throw new Exception('Jumlah pembayaran kurang dari nominal DP' . $cicilanKe . ': Rp ' . number_format($expectedNominal, 0, ',', '.'));
        }

        $sql = "UPDATE penagihan 
                SET dp{$cicilanKe}_status = 1,
                    dp{$cicilanKe}_nominal = $jumlahBayar,
                    dp{$cicilanKe}_metode = '$metodePembayaran',
                    dp{$cicilanKe}_tenggat = '$tanggalPembayaran',
                    status = '1'
                WHERE id = $id";
    }

    $debugData['Update Query'] = $sql;

    // Eksekusi query utama
    if (!$db->query($sql)) {
        throw new Exception('Gagal menyimpan pembayaran: ' . $db->error);
    }

    // Catat histori pembayaran
    $insertHistory = "INSERT INTO pembayaran_history (
                        penagihan_id, 
                        cicilan_ke, 
                        nominal, 
                        metode, 
                        tanggal, 
                        keterangan
                     ) VALUES (
                        ?, ?, ?, ?, ?, ?
                     )";

    $stmt = $db->prepare($insertHistory);
    if (!$stmt) {
        throw new Exception('Gagal menyiapkan query history: ' . $db->error);
    }

    // Bind parameter dengan benar (6 parameter)
    $stmt->bind_param("iidsss", $id, $cicilanKe, $jumlahBayar, $metodePembayaran, $tanggalPembayaran, $keterangan);

    if (!$stmt->execute()) {
        throw new Exception('Gagal mencatat histori pembayaran: ' . $stmt->error);
    }
    $stmt->close();

    // Cek apakah pembayaran ini sudah melunasi seluruh tagihan
    if ($newTotalPaid >= $totalTagihan && $cicilanKe <= $jumlahDP) {
        // Jika sudah lunas tapi bukan dari cicilan pelunasan, update status
        $db->query("UPDATE penagihan 
                    SET status = '2',
                        tgllunas = '$tanggalPembayaran'
                    WHERE id = $id");
    }

    // Commit transaksi
    $db->commit();

    $debugData['Status'] = 'Success';
    $_SESSION['debug_output'] = $debugData;
    $_SESSION['toastr'] = [
        'type' => 'success',
        'message' => ($cicilanKe > $jumlahDP) ?
            'Pelunasan berhasil disimpan' :
            'Pembayaran DP ' . $cicilanKe . ' berhasil disimpan'
    ];

    // Display debug data jika dalam mode debug
    if (isset($_REQUEST['debug'])) {
        echo "<pre>";
        print_r($debugData);
        echo "</pre>";
        exit;
    }

} catch (Exception $e) {
    $db->rollback();
    $debugData['Error'] = $e->getMessage();
    $_SESSION['debug_output'] = $debugData;
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => $e->getMessage()
    ];

    // Display debug data with error jika dalam mode debug
    if (isset($_REQUEST['debug'])) {
        echo "<pre>";
        print_r($debugData);
        echo "</pre>";
        exit;
    }
}

$db->close();

// Redirect ke halaman penagihan
header('Location: ../index.php?menu=Penagihan' . (isset($_REQUEST['debug']) ? '&debug=1' : ''));
exit;
?>