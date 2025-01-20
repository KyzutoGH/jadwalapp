<?php
require_once 'config/Database.php';
require_once 'config/Settings.php';
require_once 'controller/JadwalController.php';
require_once 'controller/NotifikasiController.php';
require_once 'model/JadwalModel.php';

$database = new Database();
$db = $database->getConnection();

$jadwalController = new JadwalController($db);

include 'bagian/Header.php';
include 'bagian/Navbar.php';
?>

<div class="container">
    <?php include 'bagian/Sidebar.php'; ?>
    
    <div class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'kalender';
        
        switch($page) {
            case 'kalender':
                include 'views/Kalender.php';
                break;
            case 'tabel':
                include 'views/Tabel.php';
                break;
            default:
                include 'views/Kalender.php';
        }
        ?>
    </div>
</div>

<?php include 'bagian/Footer.php'; ?>