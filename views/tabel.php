<?php
$menu = $_GET['menu'] ?? '';
$submenu = $_GET['submenu'] ?? '';

if ($menu == "Tabel") {
    require_once('tabel/diesnatalis.php');
} else if ($menu == "Penagihan") {
    require_once('tabel/penagihan.php');
} else if ($menu == "History") {
    require_once('tabel/history.php');
} else {
    require_once('404.php');
}?>