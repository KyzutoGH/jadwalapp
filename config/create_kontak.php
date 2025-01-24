<?php
// Koneksi ke database
include("koneksi.php");

// Mulai session
session_start();

// Ambil data dari form
$nama_sekolah = $_POST['nama_sekolah'];
$alamat = $_POST['alamat'];
$nomor = $_POST['nomor_kontak'];
$pemilik_kontak = $_POST['pemilik_kontak'];
$jabatan = $_POST['jabatan'];
$tanggal_dn = $_POST['tanggal_dn'];
$status = 1; // Default status

// Tentukan jenis berdasarkan nomor
if (is_numeric($nomor)) {
    $jenis = 'Whatsapp';
} else {
    $jenis = 'Instagram';
}

// Validasi data
if (empty($nama_sekolah) || empty($alamat) || empty($nomor) || empty($pemilik_kontak) || empty($jabatan) || empty($tanggal_dn)) {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Semua field harus diisi!',
    ];
    header("Location: form_page.php"); // Ganti dengan halaman form Anda
    exit;
} else {
    // Query insert data
    $sql = "INSERT INTO datadn (nama_sekolah, alamat, jenis, nomor, pemilik_kontak, jabatan, tanggal_dn, status) 
            VALUES ('$nama_sekolah', '$alamat', '$jenis', '$nomor', '$pemilik_kontak', '$jabatan', '$tanggal_dn', $status)";

    // Eksekusi query
    if (mysqli_query($db, $sql)) {
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Data berhasil ditambahkan!',
        ];
        header("Location: ../index.php?menu=Tabel"); // Redirect setelah sukses
        exit;
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Gagal menambahkan data: ' . mysqli_error($db),
        ];
        header("Location: ../index.php?menu=Create&submenu=ContactAdd"); // Ganti dengan halaman form Anda
        exit;
    }
}
?>