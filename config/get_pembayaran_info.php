<?php
// get_pembayaran_info.php
include 'koneksi.php'; // Sesuaikan dengan file koneksi database Anda

$id = $_GET['id'];

$sql = "SELECT total, 
               COALESCE(dp1_nominal, 0) + COALESCE(dp2_nominal, 0) + COALESCE(dp3_nominal, 0) as jumlah_dibayar 
        FROM penagihan 
        WHERE id = $id";

$result = $db->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'total' => $row['total'],
        'jumlah_dibayar' => $row['jumlah_dibayar']
    ]);
} else {
    echo json_encode(['error' => 'Data tidak ditemukan']);
}

$db->close();
?>