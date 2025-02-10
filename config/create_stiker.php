<?php
// Koneksi ke database
include("koneksi.php");

// Mulai session
session_start();

// Ambil data dari form
$nama = $_POST['nama'];
$bagian = $_POST['bagian'];

// Cek apakah data dengan jenis, ukuran, dan nama barang (case insensitive) yang sama sudah ada
$sql_check = "SELECT * FROM stiker WHERE bagian = '$bagian' AND LOWER(nama) = LOWER('$nama')";
$result = mysqli_query($db, $sql_check);

if (mysqli_num_rows($result) > 0) {
    // Jika data sudah ada, beri pesan error
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Data dengan nama, dan bagian yang sama sudah ada!',
    ];
    header("Location: ../index.php?menu=CreateBarang&submenu=BarangAdd"); // Redirect ke halaman form
    exit;
} else {
    // Jika data belum ada, tambahkan ke tabel
    $sql_insert = "INSERT INTO stiker (nama, bagian, stock) 
                   VALUES ('$nama', '$bagian', 0)";
    if (mysqli_query($db, $sql_insert)) {
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Data berhasil ditambahkan!',
        ];
        header("Location: ../index.php?menu=Barang&submenu=DataBarang"); // Redirect setelah sukses
        exit;
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Gagal menambahkan data: ' . mysqli_error($db),
        ];
        header("Location: ../index.php?menu=CreateBarang&submenu=BarangAdd"); // Redirect ke halaman form
        exit;
    }
}
?>