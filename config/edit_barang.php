<?php
include 'koneksi.php';

$id = $_POST['id'];
$jenis = $_POST['jenis'];
$namabarang = $_POST['namabarang'];
$harga = $_POST['harga'];

$query = "UPDATE jaket SET 
        jenis = '$jenis',
        namabarang = '$namabarang'
        WHERE id_jaket = $id";

if (mysqli_query($db, $query)) {
        echo "<script>
            alert('Data berhasil diupdate!');
            window.location.href = '../index.php?menu=Barang&submenu=DataBarang';
        </script>";
} else {
        echo "<script>
            alert('Error: " . mysqli_error($db) . "');
            window.location.href = '../index.php?menu=Barang&submenu=DataBarang';
        </script>";
}

?>