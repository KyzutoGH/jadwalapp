<?php
include "../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    
    $query = "UPDATE stiker SET nama = '$nama' WHERE id_sticker = '$id'";
    $result = mysqli_query($db, $query);
    
    if ($result) {
        echo "<script>
            alert('Data berhasil diubah!');
            window.location.href='../index.php?menu=Barang';
        </script>";
    } else {
        echo "<script>
            alert('Gagal mengubah data!');
            window.location.href='../index.php?menu=Barang';
        </script>";
    }
}
?>