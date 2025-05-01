<?php
include 'koneksi.php';

// Validasi ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo "<script>
        alert('ID tidak valid!');
        window.location.href = '../index.php?menu=Tabel';
    </script>";
        exit;
}

// Ambil dan bersihkan data
$id = (int) $_POST['id'];
$nama_sekolah = mysqli_real_escape_string($db, $_POST['nama_sekolah']);
$alamat = mysqli_real_escape_string($db, $_POST['alamat']);
$nomor = mysqli_real_escape_string($db, $_POST['nomor']);
$pemilik_kontak = mysqli_real_escape_string($db, $_POST['pemilik_kontak']);
$jabatan = mysqli_real_escape_string($db, $_POST['jabatan']);
$status = mysqli_real_escape_string($db, $_POST['status']);

// Validasi tanggal_dn
if (isset($_POST['tanggal_dn']) && !empty($_POST['tanggal_dn'])) {
        $tanggal_dn = mysqli_real_escape_string($db, $_POST['tanggal_dn']);

        // Format yang masuk adalah YYYY-MM-DD, validasi dengan checkdate
        $parts = explode('-', $tanggal_dn);
        if (count($parts) === 3) {
                $year = (int) $parts[0];
                $month = (int) $parts[1];
                $day = (int) $parts[2];
                if (!checkdate($month, $day, $year)) {
                        echo "<script>
                alert('Tanggal tidak valid!');
                window.location.href = '../index.php?menu=Tabel';
            </script>";
                        exit;
                }
        } else {
                echo "<script>
            alert('Format tanggal tidak valid!');
            window.location.href = '../index.php?menu=Tabel';
        </script>";
                exit;
        }
} else {
        echo "<script>
        alert('Tanggal Dies Natalis tidak boleh kosong!');
        window.location.href = '../index.php?menu=Tabel';
    </script>";
        exit;
}

// Siapkan query update
$stmt = mysqli_prepare($db, "UPDATE datadn SET 
    nama_sekolah = ?,
    alamat = ?,
    nomor = ?,
    pemilik_kontak = ?,
    jabatan = ?,
    tanggal_dn = ?,
    status = ?
    WHERE id = ?");

if ($stmt) {
        mysqli_stmt_bind_param(
                $stmt,
                "sssssssi",
                $nama_sekolah,
                $alamat,
                $nomor,
                $pemilik_kontak,
                $jabatan,
                $tanggal_dn, // tetap pakai format YYYY-MM-DD
                $status,
                $id
        );

        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($success) {
                echo "<script>
            alert('Data berhasil diupdate!');
            window.location.href = '../index.php?menu=Tabel';
        </script>";
        } else {
                echo "<script>
            alert('Gagal mengupdate data: " . mysqli_error($db) . "');
            window.location.href = '../index.php?menu=Tabel';
        </script>";
        }
} else {
        echo "<script>
        alert('Query error: " . mysqli_error($db) . "');
        window.location.href = '../index.php?menu=Tabel';
    </script>";
}
?>