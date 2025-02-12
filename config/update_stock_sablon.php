<?php
session_start();
require 'koneksi.php';

$id_barang = intval($_POST['id_barang']);
$jumlah = intval($_POST['jumlah']);
$action = $_POST['action'];

if ($id_barang <= 0 || $jumlah <= 0) {
    $_SESSION['toastr'] = ['type' => 'error', 'message' => 'ID barang atau jumlah tidak valid.'];
    header('Location: ../index.php?menu=Barang&submenu=DataBarang');
    exit();
}

// Ambil informasi detail barang
$query = "
    SELECT 
        bj.id_barang, 
        bj.nama_produk, 
        bj.id_jaket, 
        bj.id_sticker, 
        bj.stock,
        j.stock AS stock_jaket,
        j.namabarang AS nama_jaket,
        s.stock AS stock_stiker,
        s.nama AS nama_stiker,
        s.bagian AS bagian_stiker
    FROM barang_jadi bj
    LEFT JOIN jaket j ON bj.id_jaket = j.id_jaket
    LEFT JOIN stiker s ON bj.id_sticker = s.id_sticker
    WHERE bj.id_barang = ?
";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_barang);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$barang = mysqli_fetch_assoc($result);

if (!$barang) {
    $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Barang tidak ditemukan.'];
    header('Location: ../index.php?menu=Barang&submenu=DataBarang');
    exit();
}

$stok_barang_jadi = $barang['stock'];
$stok_jaket = $barang['stock_jaket'];
$stok_stiker = $barang['stock_stiker'];

if ($action === "tambah") {
    // Cek stok dan buat pesan error yang spesifik
    $error_messages = [];
    $stok_kurang = false;

    if ($stok_jaket < $jumlah) {
        $error_messages[] = "Jaket {$barang['nama_jaket']} (tersisa: $stok_jaket)";
        $stok_kurang = true;
    }

    if ($stok_stiker < $jumlah) {
        $error_messages[] = "Stiker {$barang['nama_stiker']} bagian {$barang['bagian_stiker']} (tersisa: $stok_stiker)";
        $stok_kurang = true;
    }

    if ($stok_kurang) {
        $error_message = "Stok tidak mencukupi untuk: " . implode(" dan ", $error_messages);
        $_SESSION['toastr'] = ['type' => 'error', 'message' => $error_message];
        header('Location: ../index.php?menu=Barang&submenu=DataBarang');
        exit();
    }

    // Tambah stok barang jadi
    $update_barang = "UPDATE barang_jadi SET stock = stock + ? WHERE id_barang = ?";
    $stmt = mysqli_prepare($db, $update_barang);
    mysqli_stmt_bind_param($stmt, 'ii', $jumlah, $id_barang);
    mysqli_stmt_execute($stmt);

    // Kurangi stok jaket
    $update_jaket = "UPDATE jaket SET stock = stock - ? WHERE id_jaket = ?";
    $stmt = mysqli_prepare($db, $update_jaket);
    mysqli_stmt_bind_param($stmt, 'ii', $jumlah, $barang['id_jaket']);
    mysqli_stmt_execute($stmt);

    // Kurangi stok stiker
    $update_stiker = "UPDATE stiker SET stock = stock - ? WHERE id_sticker = ?";
    $stmt = mysqli_prepare($db, $update_stiker);
    mysqli_stmt_bind_param($stmt, 'ii', $jumlah, $barang['id_sticker']);
    mysqli_stmt_execute($stmt);

    // Tambahkan ke log_barang
    $insert_log = "INSERT INTO log_barang (id_barang, id_jaket, id_sticker, jenis_log, jumlah, tanggal) 
                   VALUES (?, ?, ?, 'Tambah', ?, NOW())";
    $stmt = mysqli_prepare($db, $insert_log);
    mysqli_stmt_bind_param($stmt, 'iiii', $id_barang, $barang['id_jaket'], $barang['id_sticker'], $jumlah);
    mysqli_stmt_execute($stmt);

    $_SESSION['toastr'] = ['type' => 'success', 'message' => "Stok barang jadi berhasil ditambah (+$jumlah)."];

} elseif ($action === "kurangi") {
    // Periksa apakah stok barang jadi cukup
    if ($stok_barang_jadi < $jumlah) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => "Stok {$barang['nama_produk']} tidak mencukupi (tersisa: $stok_barang_jadi)."
        ];
        header('Location: ../index.php?menu=Barang&submenu=DataBarang');
        exit();
    }

    // Kurangi stok barang jadi
    $update_barang = "UPDATE barang_jadi SET stock = stock - ? WHERE id_barang = ?";
    $stmt = mysqli_prepare($db, $update_barang);
    mysqli_stmt_bind_param($stmt, 'ii', $jumlah, $id_barang);
    mysqli_stmt_execute($stmt);

    // Tambahkan ke log_barang
    $insert_log = "INSERT INTO log_barang (id_barang, id_jaket, id_sticker, jenis_log, jumlah, tanggal) 
                   VALUES (?, ?, ?, 'Kurangi', ?, NOW())";
    $stmt = mysqli_prepare($db, $insert_log);
    mysqli_stmt_bind_param($stmt, 'iiii', $id_barang, $barang['id_jaket'], $barang['id_sticker'], $jumlah);
    mysqli_stmt_execute($stmt);

    $_SESSION['toastr'] = ['type' => 'success', 'message' => "Stok barang jadi berhasil dikurangi (-$jumlah)."];
}

header('Location: ../index.php?menu=Barang&submenu=DataBarang');
exit();
?>