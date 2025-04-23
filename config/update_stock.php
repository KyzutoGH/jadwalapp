<?php
session_start();
include 'koneksi.php';

// Set timezone untuk tanggal log
date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");

// Ambil dan validasi data
$id_jaket = isset($_POST['id']) ? intval($_POST['id']) : 0;
$jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$id_sticker = isset($_POST['id_sticker']) ? intval($_POST['id_sticker']) : null;

// Validasi data
if ($id_jaket <= 0 || $jumlah <= 0 || !in_array($action, ['tambah', 'kurangi'])) {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Data tidak valid!'
    ];
    header('Location: ../index.php?menu=Barang&submenu=DataBarang');
    exit;
}

// Ambil data stok saat ini
$query = "SELECT stock FROM jaket WHERE id_jaket = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id_jaket);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $stok_lama = (int) $row['stock'];

    if ($action === 'tambah') {
        $stok_baru = $stok_lama + $jumlah;
        $jenis_log = 'Tambah';
    } elseif ($action === 'kurangi') {
        $stok_baru = $stok_lama - $jumlah;
        $jenis_log = 'Kurangi';

        if ($stok_baru < 0) {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Stok tidak mencukupi!'
            ];
            header('Location: ../index.php?menu=Barang&submenu=DataBarang');
            exit;
        }
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Aksi tidak valid!'
        ];
        header('Location: ../index.php?menu=Barang&submenu=DataBarang');
        exit;
    }

    // Update stok di tabel jaket
    $updateQuery = "UPDATE jaket SET stock = ? WHERE id_jaket = ?";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bind_param("ii", $stok_baru, $id_jaket);
    $updateStmt->execute();

    // Tambahkan log ke tabel log_barang
    $logQuery = "INSERT INTO log_barang (id_jaket, id_sticker, jenis_log, jumlah, tanggal) VALUES (?, ?, ?, ?, ?)";
    $logStmt = $db->prepare($logQuery);
    $logStmt->bind_param("iisis", $id_jaket, $id_sticker, $jenis_log, $jumlah, $tanggal);
    $logStmt->execute();

    $_SESSION['toastr'] = [
        'type' => 'success',
        'message' => 'Stok berhasil diperbarui!'
    ];
    header('Location: ../index.php?menu=Barang&submenu=DataBarang');
    exit;
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Jaket tidak ditemukan!'
    ];
    header('Location: ../index.php?menu=Barang&submenu=DataBarang');
    exit;
}
?>