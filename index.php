<?php
require_once 'bagian/config.php';
require_once 'bagian/function.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'bagian/header.php';

switch ($page) {
    case 'home':
        include 'views/home.php';
        break;
    case 'tambahSekolah':
        include 'views/tambahSekolah.php';
        break;
    case 'listSekolah':
        include 'views/listSekolah.php';
        break;
    default:
        include 'views/home.php';
}

include 'bagian/footer.php';
