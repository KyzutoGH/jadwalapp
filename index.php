<?php 
$menu = isset($_GET['menu']) ? $_GET['menu'] : '';
$submenu = isset($_GET['submenu']) ? $_GET['submenu'] : '';
$judul_browser = "Manajemen Apps - Alpha : ". $menu;

require_once('bagian/Header.php'); ?>

<?php require_once('bagian/Navbar.php') ?>

<?php require_once('bagian/Sidebar.php'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if($menu == "Dashboard"){
        require_once('bagian/header/dashboard.php');
    } else if($menu == "Tabel") {
        require_once('bagian/header/tabel.php');
    } else if($menu == "Inbox") {
        require_once('bagian/header/inbox.php');
    } else if($menu == "Kalender") {
        require_once('bagian/header/kalender.php');
    }  else {
        require_once('bagian/header/404.php');
    }?>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <?php if ($menu == "Dashboard") {
            require_once('views/dashboard.php');
        } else if ($menu == "Tabel") {
            require_once('views/tabel.php');
        } else if ($menu == "Inbox") {
            require_once('views/inbox.php');
        } else if ($menu == "Kalender") {
            require_once('views/kalender.php');
        } else {
            require_once('views/404.php');
        }?>
    </section>
</div>


<?php require_once('bagian/Footer.php'); ?>