<?php 
$judul_browser = "Manajemen Apps - Alpha";
$menu = isset($_GET['menu']) ? $_GET['menu'] : '';
$submenu = isset($_GET['submenu']) ? $_GET['submenu'] : '';

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
    } else {
        require_once('bagian/header/404.php');
    }?>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <?php if ($menu == "Dashboard") {
            require_once('views/dashboard.php');
        } else if ($menu == "Tabel") {
            require_once('views/tabel.php');
        }else {
            require_once('views/404.php');
        }?>
    </section>
</div>


<?php require_once('bagian/Footer.php'); ?>