<?php
// Konstanta untuk menu yang valid
const VALID_MENUS = [
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

// Validasi submenu
$validSubmenus = ['Sekolah', 'Contact'];
// Sanitasi input
$menu = isset($_GET['menu']) ? htmlspecialchars($_GET['menu']) : '';
$submenu = isset($_GET['submenu']) ? htmlspecialchars($_GET['submenu']) : '';

// Validasi menu
$isValidMenu = array_key_exists($menu, VALID_MENUS);
$currentMenu = $isValidMenu ? $menu : '404';
$judul_browser = "Manajemen Apps - Alpha : " . $currentMenu;

// Load templates
require_once('bagian/Header.php');
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

<?php require_once('bagian/Footer.php'); ?>