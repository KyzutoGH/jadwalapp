<?php
include 'koneksi.php';
session_start(); // Add session start for toastr

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jaket = $_POST['id_jaket'];
    $id_sticker = isset($_POST['id_sticker']) ? $_POST['id_sticker'] : null;
    $action = $_POST['action'];
    $jumlah = (int) $_POST['jumlah'];
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');

    // Ambil stok lama dari tabel jaket
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

        // Periksa apakah id_sticker ada
        if ($id_sticker) {
            $logStmt->bind_param("iisis", $id_jaket, $id_sticker, $jenis_log, $jumlah, $tanggal);
        } else {
            $logStmt->bind_param("iisis", $id_jaket, $id_sticker, $jenis_log, $jumlah, $tanggal);
        }

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
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Metode tidak valid!'
    ];
    header('Location: ../index.php?menu=Barang&submenu=DataBarang');
    exit;
}
?>