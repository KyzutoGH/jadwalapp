<?php
// Koneksi ke database
include 'koneksi.php';

// Mulai session
session_start();

// Ambil dan sanitasi data dari form
$id = mt_rand(100000000, 999999999); // ID acak 9 digit
$nama_sekolah = mysqli_real_escape_string($db, $_POST['nama_sekolah']);
$alamat = mysqli_real_escape_string($db, $_POST['alamat']);
$nomor = mysqli_real_escape_string($db, $_POST['nomor_kontak']);
$pemilik_kontak = mysqli_real_escape_string($db, $_POST['pemilik_kontak']);
$jabatan = mysqli_real_escape_string($db, $_POST['jabatan']);
$tanggal_dn = mysqli_real_escape_string($db, $_POST['tanggal_dn']);
$status = 1; // Kontak Belum Dihubungi

// Validasi data
if (
    empty($nama_sekolah) || empty($alamat) || empty($nomor) ||
    empty($pemilik_kontak) || empty($jabatan) || empty($tanggal_dn)
) {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Semua field harus diisi!',
    ];
    header("Location: ../index.php?menu=Tabel");
    exit;
}

// Validasi format tanggal DD-MM
$parts = explode('-', $tanggal_dn);
if (count($parts) == 2) {
    $day = $parts[0];
    $month = $parts[1];
    $currentYear = date('Y');

    if (checkdate((int) $month, (int) $day, (int) $currentYear)) {
        $formattedDate = "$currentYear-$month-$day"; // Format YYYY-MM-DD

        // Siapkan statement
        $stmt = mysqli_prepare($db, "INSERT INTO datadn (
            id, nama_sekolah, alamat, nomor, pemilik_kontak, jabatan, tanggal_dn, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param(
                $stmt,
                "sssssssi",
                $id,
                $nama_sekolah,
                $alamat,
                $nomor,
                $pemilik_kontak,
                $jabatan,
                $formattedDate,
                $status
            );

            // Eksekusi statement
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($success) {
                $_SESSION['toastr'] = [
                    'type' => 'success',
                    'message' => 'Data berhasil ditambahkan!',
                ];
            } else {
                $_SESSION['toastr'] = [
                    'type' => 'error',
                    'message' => 'Gagal menambahkan data: ' . mysqli_error($db),
                ];
            }
        } else {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Query error: ' . mysqli_error($db),
            ];
        }
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Format tanggal tidak valid!',
        ];
    }
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Format tanggal harus DD-MM!',
    ];
}

// Redirect ke halaman tabel
header("Location: ../index.php?menu=Tabel");
exit;
?>