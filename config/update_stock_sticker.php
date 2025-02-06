<?php
session_start();
require_once('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sticker = $_POST['id_sticker'];
    $action = $_POST['action'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    // Get current stock
    $query = "SELECT stock FROM stiker WHERE id_sticker = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_sticker);
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

        // Update stok di tabel stiker
        $updateQuery = "UPDATE stiker SET stock = ? WHERE id_sticker = ?";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bind_param("ii", $stok_baru, $id_sticker);
        $updateStmt->execute();

        // Tambahkan log ke tabel log_barang
        $logQuery = "INSERT INTO log_barang (id_jaket, id_sticker, jenis_log, jumlah, tanggal) VALUES (NULL, ?, ?, ?, ?)";
        $logStmt = $db->prepare($logQuery);

        // Changed type to 's' for tanggal (datetime is treated as string in bind_param)
        $logStmt->bind_param("isis", $id_sticker, $jenis_log, $jumlah, $tanggal);
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
            'message' => 'Sticker tidak ditemukan!'
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