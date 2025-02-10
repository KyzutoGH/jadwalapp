<?php
include("koneksi.php");
session_start();

// Get POST data
$nama_produk = $_POST['nama_produk'];
$id_jaket = $_POST['id_jaket'];
$stiker_ids = $_POST['stiker_ids'] ?? [];
$new_stiker_nama = $_POST['new_stiker_nama'] ?? [];
$new_stiker_bagian = $_POST['new_stiker_bagian'] ?? [];

// Insert main product
$query = "INSERT INTO barang_jadi (id_jaket, nama_produk, stock) 
          VALUES ('$id_jaket', '$nama_produk', 0)";
$result = mysqli_query($db, $query);
$id_barang = mysqli_insert_id($db);

// Add existing stickers
foreach ($stiker_ids as $stiker_id) {
    if ($stiker_id) {
        $query = "INSERT INTO barang_jadi_stiker (id_barang, id_sticker) 
                  VALUES ($id_barang, $stiker_id)";
        mysqli_query($db, $query);
    }
}

// Add new stickers
for ($i = 0; $i < count($new_stiker_nama); $i++) {
    if ($new_stiker_nama[$i] && $new_stiker_bagian[$i]) {
        // Insert new sticker
        $query = "INSERT INTO stiker (nama, bagian, stock) 
                  VALUES ('$new_stiker_nama[$i]', '$new_stiker_bagian[$i]', 0)";
        mysqli_query($db, $query);
        $new_stiker_id = mysqli_insert_id($db);

        // Link sticker to product
        $query = "INSERT INTO barang_jadi_stiker (id_barang, id_sticker) 
                  VALUES ($id_barang, $new_stiker_id)";
        mysqli_query($db, $query);
    }
}

// Success message and redirect
$_SESSION['toastr'] = [
    'type' => 'success',
    'message' => 'Barang jadi berhasil ditambahkan!'
];
header("Location: ../index.php?menu=Barang&submenu=DataBarang");

?>