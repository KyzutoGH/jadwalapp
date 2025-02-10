<?php
session_start();
include("koneksi.php");

$nama_produk = mysqli_real_escape_string($db, $_POST['nama_produk']);
$id_jaket = mysqli_real_escape_string($db, $_POST['id_jaket']);
$existing_stickers = isset($_POST['existing_sticker']) ? array_filter($_POST['existing_sticker']) : [];
$new_stiker_nama = isset($_POST['new_stiker_nama']) ? array_filter($_POST['new_stiker_nama']) : [];
$new_stiker_bagian = isset($_POST['new_stiker_bagian']) ? array_filter($_POST['new_stiker_bagian']) : [];

// Proses upload gambar jika ada
$gambar_jadi = null;
if (isset($_FILES['gambar_jadi']) && $_FILES['gambar_jadi']['error'] == 0) {
    $target_dir = "../uploads/";

    // Hindari karakter aneh dalam nama file
    $gambar_jadi = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($_FILES["gambar_jadi"]["name"]));

    $target_file = $target_dir . $gambar_jadi;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi ukuran file (maksimal 2MB)
    if ($_FILES["gambar_jadi"]["size"] > 2000000) {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Ukuran gambar terlalu besar! Maksimal 2MB.'];
        header("Location: ../index.php?menu=Barang&submenu=DataBarang");
        exit();
    }

    // Validasi format file (hanya JPG dan PNG)
    if (!in_array($image_file_type, ['jpg', 'jpeg', 'png'])) {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Format gambar harus JPG atau PNG!'];
        header("Location: ../index.php?menu=Barang&submenu=DataBarang");
        exit();
    }

    // Pastikan folder ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Pindahkan file ke folder uploads
    if (!move_uploaded_file($_FILES["gambar_jadi"]["tmp_name"], $target_file)) {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Gagal mengupload gambar!'];
        header("Location: ../index.php?menu=Barang&submenu=DataBarang");
        exit();
    }
}

// Escape nama file untuk SQL
$gambar_jadi = mysqli_real_escape_string($db, $gambar_jadi);

// Array untuk menyimpan semua ID stiker (existing dan baru)
$all_sticker_ids = [];

// 1. Proses stiker baru terlebih dahulu
for ($i = 0; $i < count($new_stiker_nama); $i++) {
    if (!empty($new_stiker_nama[$i]) && !empty($new_stiker_bagian[$i])) {
        $nama = mysqli_real_escape_string($db, $new_stiker_nama[$i]);
        $bagian = mysqli_real_escape_string($db, $new_stiker_bagian[$i]);

        // Cek apakah stiker dengan nama dan bagian yang sama sudah ada
        $check_query = "SELECT id_sticker FROM stiker WHERE nama = '$nama' AND bagian = '$bagian'";
        $check_result = mysqli_query($db, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Stiker dengan nama dan bagian yang sama sudah ada!'];
            header("Location: ../index.php?menu=Barang&submenu=DataBarang");
            exit();
        }

        // Insert stiker baru
        $insert_query = "INSERT INTO stiker (nama, bagian, stock) VALUES ('$nama', '$bagian', 0)";
        if (mysqli_query($db, $insert_query)) {
            $all_sticker_ids[] = mysqli_insert_id($db);
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Gagal menambahkan stiker baru!'];
            header("Location: ../index.php?menu=Barang&submenu=DataBarang");
            exit();
        }
    }
}

// 2. Tambahkan existing sticker IDs ke array
foreach ($existing_stickers as $sticker_id) {
    if (!empty($sticker_id)) {
        $all_sticker_ids[] = mysqli_real_escape_string($db, $sticker_id);
    }
}

// 3. Tambahkan barang jadi dengan setiap stiker
foreach ($all_sticker_ids as $sticker_id) {
    $insert_barang_query = "INSERT INTO barang_jadi (nama_produk, id_jaket, id_sticker, stock, gambar) 
                            VALUES ('$nama_produk', '$id_jaket', '$sticker_id', 0, '$gambar_jadi')";

    if (!mysqli_query($db, $insert_barang_query)) {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Gagal menambahkan barang jadi!'];
        header("Location: ../index.php?menu=Barang&submenu=DataBarang");
        exit();
    }
}

// Jika berhasil
$_SESSION['toastr'] = ['type' => 'success', 'message' => 'Barang jadi berhasil ditambahkan!'];
header("Location: ../index.php?menu=Barang&submenu=DataBarang");
exit();
?>