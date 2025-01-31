<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_sticker'];
    $action = $_POST['action'];
    $jumlah = $_POST['jumlah'];
    
    // Ambil stock saat ini
    $query_current = "SELECT stock FROM stiker WHERE id_sticker = '$id'";
    $result_current = mysqli_query($db, $query_current);
    $data = mysqli_fetch_assoc($result_current);
    $current_stock = $data['stock'];
    
    // Update stock berdasarkan action
    if ($action == 'tambah') {
        $new_stock = $current_stock + $jumlah;
        $jenis_log = 'Tambah';
    } else {
        if ($current_stock < $jumlah) {
            die("Stock tidak mencukupi!");
        }
        $new_stock = $current_stock - $jumlah;
        $jenis_log = 'Kurangi';
    }
    
    // Update stock di tabel stiker
    $query_update = "UPDATE stiker SET stock = '$new_stock' WHERE id_sticker = '$id'";
    $result_update = mysqli_query($db, $query_update);
    
    // Tambah log
    $query_log = "INSERT INTO log_barang (tanggal, id_sticker, jumlah, jenis_log) 
                  VALUES (NOW(), '$id', '$jumlah', '$jenis_log')";
    $result_log = mysqli_query($db, $query_log);
    
    if ($result_update && $result_log) {
        header("Location: ../index.php?menu=Barang");
        exit;
    } else {
        die("Gagal mengupdate stock!");
    }
}