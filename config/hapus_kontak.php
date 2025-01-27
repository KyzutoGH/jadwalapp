<?php
include 'koneksi.php';
$id = $_POST['id'];
$sql = "DELETE FROM datadn WHERE id='$id'";
// Execute query
if (mysqli_query($db, $sql)) {
    $_SESSION['toastr'] = [
        'type' => 'success',
        'message' => 'Data berhasil dihapus!',
    ];
    header("Location: ../index.php?menu=Tabel");
    exit;
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Gagal menghapus data: ' . mysqli_error($db),
    ];
    header("Location: ../index.php?menu=Tabel");
    exit;
}
?>