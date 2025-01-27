<?php
// Koneksi ke database
include("koneksi.php");

// Mulai session
session_start();

// Ambil data dari form
$jenis = $_POST['jenis'];
$ukuran = $_POST['ukuran'];
$harga = $_POST['harga'];
$stock = $_POST['stock'];

// Cek apakah data dengan jenis dan ukuran yang sama sudah ada
$sql_check = "SELECT * FROM jaket WHERE jenis = '$jenis' AND ukuran = '$ukuran'";
$result = mysqli_query($db, $sql_check);

if (mysqli_num_rows($result) > 0) {
    // Jika data sudah ada, beri pesan error
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Data dengan jenis dan ukuran yang sama sudah ada!',
    ];
    header("Location: ../index.php?menu=CreateBarang&submenu=BarangAdd"); // Redirect ke halaman form
    exit;
} else {
    // Jika data belum ada, tambahkan ke tabel
    $sql_insert = "INSERT INTO jaket (jenis, ukuran, harga, stock) 
                   VALUES ('$jenis', '$ukuran', '$harga', $stock)";
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