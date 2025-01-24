<?php
$db = new mysqli("localhost", "root", "", "fukubi_db");

if ($db->connect_error) {
    die("Koneksi Database Gagal: " . $db->connect_error);
}
?>