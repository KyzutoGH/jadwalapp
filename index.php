<?php
session_start();
$data_dies_natalis = array_fill(0, 12, 0); // Initialize array with 0 for 12 months
$formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
$formatter->setPattern('MMMM');
require_once('config/koneksi.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user role
$userRole = $_SESSION['role'];

// Define role-based access control
$roleAccess = [
    'owner' => [
        'Dashboard',
        'Tabel',
        'Inbox',
        'Kalender',
        'Create',
        'Penagihan',
        'Barang',
        'Stiker',
        'CreateBarang'
    ],
    'marketing' => [
        'Dashboard',
        'Tabel',
        'Inbox',
        'Kalender',
        'Create',
        'Penagihan'
    ],
    'stock' => [
        'Dashboard',
        'Barang',
        'Stiker',
        'CreateBarang'
    ]
];

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
    ],
    'Barang' => [
        'header' => 'bagian/header/tabelbarang.php',
        'view' => 'views/tabelbarang.php'
    ],
    'Stiker' => [
        'header' => 'bagian/header/tabelstiker.php',
        'view' => 'views/tabelstiker.php'
    ],
    'CreateBarang' => [
        'header' => 'bagian/header/createbarang.php',
        'view' => 'views/createbarang.php'
    ]
];

// Definisi submenu yang valid untuk setiap menu
const VALID_SUBMENUS = [
    'Tabel' => ['Sekolah', 'Contact', 'Penagihan'],
    'Create' => ['Sekolah', 'Contact', 'Penagihan'],
    'Barang' => ['DataBarang'],
    'Stiker' => ['DataStiker'],
    'CreateBarang' => ['BarangAdd', 'StikerAdd']
];

// Sanitasi input
$menu = isset($_GET['menu']) ? htmlspecialchars($_GET['menu']) : 'Dashboard';
$submenu = isset($_GET['submenu']) ? htmlspecialchars($_GET['submenu']) : '';

// Validasi menu berdasarkan peran pengguna
$isValidMenu = array_key_exists($menu, VALID_MENUS) && in_array($menu, $roleAccess[$userRole]);
$currentMenu = $isValidMenu ? $menu : 'Dashboard';

// Jika menu tidak valid, redirect ke Dashboard
if (!$isValidMenu) {
    header("Location: index.php?menu=Dashboard");
    exit;
}

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
    $judul_browser = "Fukubi Admin - Dashboard";
}

// Load templates
require_once('bagian/Header.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
    <?php
    if (isset($_SESSION['toastr'])) {
        $toastr = $_SESSION['toastr'];
        echo "<script>
        window.onload = function() {
            toastr['{$toastr['type']}']('{$toastr['message']}');
        }
    </script>";
        unset($_SESSION['toastr']); // Hapus notifikasi setelah ditampilkan
    } ?>
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
                require_once(VALID_MENUS[$currentMenu]['header']);
            } else {
                require_once('bagian/header/dashboard.php');
            }
            ?>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <?php
                if ($isValidMenu) {
                    require_once(VALID_MENUS[$currentMenu]['view']);
                } else {
                    require_once('views/dashboard.php');
                }
                ?>
            </section>
        </div>

        <?php require_once('bagian/Copyright.php'); ?>
        <?php require_once('bagian/Footer.php'); ?>