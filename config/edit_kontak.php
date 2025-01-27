<?php
include 'koneksi.php';

$id = $_POST['id'];
$nama_sekolah = $_POST['nama_sekolah'];
$alamat = $_POST['alamat'];
$nomor = $_POST['nomor'];
$pemilik_kontak = $_POST['pemilik_kontak'];
$jabatan = $_POST['jabatan'];
$tanggal_dn = $_POST['tanggal_dn'];
$status = $_POST['status'];

$query = "UPDATE datadn SET 
        nama_sekolah = '$nama_sekolah',
        alamat = '$alamat',
        nomor = '$nomor',
        pemilik_kontak = '$pemilik_kontak',
        jabatan = '$jabatan',
        tanggal_dn = '$tanggal_dn',
        status = '$status'
        WHERE id = '$id'";

if (mysqli_query($db, $query)) {
    echo "<script>
            alert('Data berhasil diupdate!');
            window.location.href = '../index.php?menu=Tabel';
        </script>";
} else {
    echo "<script>
            alert('Error: " . mysqli_error($db) . "');
            window.location.href = '../index.php?menu=Tabel';
        </script>";
}

?>