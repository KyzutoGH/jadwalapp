<?php
session_start();
include "../koneksi.php";

$id = $_POST['id'];
$nama_produk = $_POST['nama_produk'];
$id_jaket = $_POST['id_jaket'];
$gambar_lama = $_POST['gambar_lama']; // Menyimpan gambar lama jika tidak diubah

$target_dir = "../uploads/";
$file_name = "";

// Cek apakah ada file gambar yang diunggah
if (!empty($_FILES['gambar']['name'])) {
    $file_name = time() . "_" . basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $file_name;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi ukuran file (maksimal 2MB)
    if ($_FILES["gambar"]["size"] > 2000000) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Ukuran gambar terlalu besar! Maksimal 2MB.'
        ];
        header("Location: ../index.php?menu=Barang");
        exit();
    }

    // Validasi format file (hanya JPG dan PNG)
    if (!in_array($image_file_type, ['jpg', 'jpeg', 'png'])) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Format gambar harus JPG atau PNG!'
        ];
        header("Location: ../index.php?menu=Barang");
        exit();
    }

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        // Hapus gambar lama jika ada
        if (!empty($gambar_lama) && file_exists($target_dir . $gambar_lama)) {
            unlink($target_dir . $gambar_lama);
        }
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Gagal mengupload gambar!'
        ];
        header("Location: ../index.php?menu=Barang");
        exit();
    }
} else {
    // Gunakan gambar lama jika tidak ada file yang diunggah
    $file_name = $gambar_lama;
}

// Query update barang jadi
$query = "UPDATE barang_jadi SET nama_produk = '$nama_produk', id_jaket = '$id_jaket', gambar = '$file_name' WHERE id_barang = '$id'";
$result = mysqli_query($db, $query);

if ($result) {
    $_SESSION['toastr'] = [
        'type' => 'success',
        'message' => 'Data berhasil diperbarui!'
    ];
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Gagal memperbarui data!'
    ];
}

header("Location: ../index.php?menu=Barang");
exit();
?>