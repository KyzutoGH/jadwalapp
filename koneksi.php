<?php
$db = new mysqli("sql207.infinityfree.com", "if0_38850044", "fukubistore", "if0_38850044_fukubi_db");

if ($db->connect_error) {
    die("Koneksi Database Gagal: " . $db->connect_error);
}
?>