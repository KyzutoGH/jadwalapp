<?php
// Konstanta untuk menu yang valid
const VALID_MENUS    = [
    'Dashboard' => [
        'header' => 'bagian/header/dashboard.php',
        'view' => 'views/dashboard.php'
    ],
    'Tabel' => [
        'header' => 'bagian/header/tabel.php',
        'view' => 'views/tabel.php'
    ],
    'Inbox' => [
        'header' => 'bagian/header/inbox.php',
        'view' => 'views/inbox.php'
    ],
    'Kalender' => [
        'header' => 'bagian/header/kalender.php',
        'view' => 'views/kalender.php'
    ],
    'Create' => [
        'header' => 'bagian/header/create.php',
        'view' => 'views/create.php'
    ],
    'Penagihan' => [
        'header' => 'bagian/header/tabel.php',
        'view' => 'views/tabel.php'
    ]
];

// Definisi submenu yang valid untuk setiap menu
const VALID_SUBMENUS = [
    'Tabel' => ['Sekolah', 'Contact', 'Penagihan'],
    'Create' => ['Sekolah', 'Contact', 'Penagihan']
    // Tambahkan menu lain yang memiliki submenu
];

// Sanitasi input
$menu = isset($_GET['menu']) ? htmlspecialchars($_GET['menu']) : '';
$submenu = isset($_GET['submenu']) ? htmlspecialchars($_GET['submenu']) : '';

// Validasi menu
$isValidMenu = array_key_exists($menu, VALID_MENUS);
$currentMenu = $isValidMenu ? $menu : '404';

// Validasi submenu
$isValidSubmenu = false;
if ($isValidMenu && !empty($submenu)) {
    // Cek apakah menu memiliki submenu dan submenu yang dipilih valid
    $isValidSubmenu = isset(VALID_SUBMENUS[$menu]) &&
        in_array($submenu, VALID_SUBMENUS[$menu]);
}

// Generate judul browser
if ($isValidMenu) {
    if ($isValidSubmenu) {
        $judul_browser = "Fukubi Admin - $currentMenu $submenu";
    } else {
        $judul_browser = "Fukubi Admin - $currentMenu";
    }
} else {
    $judul_browser = "Fukubi Admin - 404";
}

// Load templates
require_once('bagian/Header.php');
?>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="assets/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div><?php
                require_once('bagian/Navbar.php');
                require_once('bagian/Sidebar.php');
                ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <?php
            if ($isValidMenu) {
                require_once(VALID_MENUS[$menu]['header']);
            } else {
                require_once('bagian/header/404.php');
            }
            ?>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <?php
                if ($isValidMenu) {
                    require_once(VALID_MENUS[$menu]['view']);
                } else {
                    require_once('views/404.php');
                }
                ?>
            </section>
        </div>

        <?php require_once('bagian/Copyright.php'); ?>
        <?php require_once('bagian/Footer.php'); ?>