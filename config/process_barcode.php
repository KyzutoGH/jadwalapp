<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"]);
    $jumlah = intval($_POST["jumlah"]);
    $mode = $_POST["action"];

    $stmt = $db->prepare("SELECT stock FROM jaket WHERE id_jaket = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        if ($mode === "tambah") {
            $stmt = $db->prepare("UPDATE jaket SET stock = stock + ? WHERE id_jaket = ?");
        } else if ($mode === "kurangi") {
            $stmt = $db->prepare("UPDATE jaket SET stock = stock - ? WHERE id_jaket = ?");
        } else {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Mode tidak dikenali!',
            ];
            header("Location: ../index.php?menu=Barang&submenu=StockBarang");
            exit;
        }

        if ($stmt->bind_param("ii", $jumlah, $id) && $stmt->execute()) {
            // ✅ Simpan ke tabel log
            $logStmt = $db->prepare("INSERT INTO log_barang (id_jaket, jenis_log, jumlah, tanggal) VALUES (?, ?, ?, NOW())");
            $jumlahStr = strval($jumlah); // karena di DB jumlah bertipe varchar
            $logStmt->bind_param("iss", $id, $mode, $jumlahStr);
            $logStmt->execute();

            $_SESSION['toastr'] = [
                'type' => 'success',
                'message' => 'Stok berhasil diupdate & dicatat di log!',
            ];
        } else {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Gagal update stok: ' . $stmt->error,
            ];
        }
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Barang tidak ditemukan!',
        ];
    }

    header("Location: ../index.php?menu=Barang&submenu=StockBarang");
    exit;
}
?>