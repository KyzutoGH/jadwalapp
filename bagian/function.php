<?php
require_once 'db.php';

function add_school($name, $type, $address) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO jadwal_sekolah (nama_sekolah, jenis_sekolah, alamat) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $type, $address);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function get_schools() {
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM jadwal_sekolah");
    $schools = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $schools;
}
?>