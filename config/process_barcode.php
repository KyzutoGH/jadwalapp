<?php
// process_barcode.php
include "koneksi.php"; // atau koneksi ke DB kamu

$data = json_decode(file_get_contents("php://input"), true);

$namabarang = $data["namabarang"];
$jenis = $data["jenis"];
$ukuran = $data["ukuran"];
$mode = $data["mode"];

$stmt = $conn->prepare("SELECT * FROM jaket WHERE namabarang=? AND jenis=? AND ukuran=?");
$stmt->bind_param("sss", $namabarang, $jenis, $ukuran);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $barang = $result->fetch_assoc();
    $id = $barang["id_jaket"];

    if ($mode === "tambah") {
        $stmt = $conn->prepare("UPDATE jaket SET stock = stock + 1 WHERE id_jaket = ?");
    } else if ($mode === "kurangi") {
        $stmt = $conn->prepare("UPDATE jaket SET stock = GREATEST(stock - 1, 0) WHERE id_jaket = ?");
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Stok berhasil diupdate!"]);
} else {
    echo json_encode(["success" => false, "message" => "Barang tidak ditemukan!"]);
}
