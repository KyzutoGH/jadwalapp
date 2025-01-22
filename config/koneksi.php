<?php
    $koneksi = mysqli_connect("localhost","root","","fukubi_db");
    $db = new mysqli("localhost", "root", "", "fukubi_db");
    if(mysqli_connect_errno()){
        echo"Koneksi Database Gagal : ".
        mysqli_connect_error();
    }
?>