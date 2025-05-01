<?php
// Koneksi ke database
include 'koneksi.php';

// Mulai session
session_start();

// Ambil dan sanitasi data dari form
$id = mysqli_real_escape_string($db, $_POST['id']);
$nama_sekolah = mysqli_real_escape_string($db, $_POST['nama_sekolah']);
$alamat = mysqli_real_escape_string($db, $_POST['alamat']);
$nomor = mysqli_real_escape_string($db, $_POST['nomor']);
$pemilik_kontak = mysqli_real_escape_string($db, $_POST['pemilik_kontak']);
$jabatan = mysqli_real_escape_string($db, $_POST['jabatan']);
$tanggal_dn = mysqli_real_escape_string($db, $_POST['tanggal_dn']);
$status = mysqli_real_escape_string($db, $_POST['status']);

// Tentukan jenis berdasarkan nomor jika belum ada di form
if (!isset($_POST['jenis'])) {
    if (is_numeric($nomor)) {
        $jenis = 'Whatsapp';
    } else {
        $jenis = 'Instagram';
    }
} else {
    $jenis = mysqli_real_escape_string($db, $_POST['jenis']);
}

// Validasi data
if (
    empty($nama_sekolah) || empty($alamat) || empty($nomor) ||
    empty($pemilik_kontak) || empty($jabatan) || empty($tanggal_dn) || !isset($status)
) {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Semua field harus diisi!',
    ];
    header("Location: ../index.php?menu=Tabel");
    exit;
} else {
    // Validasi format tanggal DD-MM
    $parts = explode('-', $tanggal_dn);
    if (count($parts) == 2) {
        $day = $parts[0];
        $month = $parts[1];
        $currentYear = date('Y');

        // Format untuk MySQL DATE: YYYY-MM-DD
        $formattedDate = "$currentYear-$month-$day";

        // Validasi format tanggal
        if (checkdate((int) $month, (int) $day, (int) $currentYear)) {
            // Gunakan prepared statement untuk mencegah SQL injection
            $stmt = mysqli_prepare($db, "UPDATE datadn SET 
                    nama_sekolah = ?,
                    alamat = ?,
                    jenis = ?,
                    nomor = ?,
                    pemilik_kontak = ?,
                    jabatan = ?,
                    tanggal_dn = ?,
                    status = ?
                    WHERE id = ?");

            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssssi",
                    $nama_sekolah,
                    $alamat,
                    $jenis,
                    $nomor,
                    $pemilik_kontak,
                    $jabatan,
                    $formattedDate,  // Gunakan format tanggal yang benar untuk MySQL
                    $status,
                    $id
                );

                // Execute the statement
                $success = mysqli_stmt_execute($stmt);

                // Close statement
                mysqli_stmt_close($stmt);

                if ($success) {
                    $_SESSION['toastr'] = [
                        'type' => 'success',
                        'message' => 'Data berhasil diupdate!',
                    ];
                    header("Location: ../index.php?menu=Tabel");
                    exit;
                } else {
                    $_SESSION['toastr'] = [
                        'type' => 'error',
                        'message' => 'Error: ' . mysqli_error($db),
                    ];
                    header("Location: ../index.php?menu=Tabel");
                    exit;
                }
            } else {
                $_SESSION['toastr'] = [
                    'type' => 'error',
                    'message' => 'Error: ' . mysqli_error($db),
                ];
                header("Location: ../index.php?menu=Tabel");
                exit;
            }
        } else {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Format tanggal tidak valid!',
            ];
            header("Location: ../index.php?menu=Tabel");
            exit;
        }
    } else {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Format tanggal harus DD-MM!',
        ];
        header("Location: ../index.php?menu=Tabel");
        exit;
    }
}
?>