<style>
    .product-details {
        line-height: 1.6;
        padding: 5px 0;
    }

    .product-details strong {
        color: #2a6496;
    }
</style>
<?php
// Query untuk data dashboard
$sql_dashboard = "SELECT 
    SUM(total) as total_tagihan,
    SUM(CASE WHEN status IN ('2','3','4') THEN total ELSE 0 END) as total_lunas,
    COUNT(CASE WHEN status IN ('2','3','4') THEN 1 END) as count_lunas,
    COUNT(CASE WHEN status = '1' THEN 1 END) as count_belum_lunas,
    COUNT(CASE WHEN status = '1' AND 
        ((jumlah_dp >= 1 AND dp1_status = 0 AND dp1_tenggat < CURDATE()) OR
         (jumlah_dp >= 2 AND dp2_status = 0 AND dp2_tenggat < CURDATE()) OR
         (jumlah_dp = 3 AND dp3_status = 0 AND dp3_tenggat < CURDATE())) 
    THEN 1 END) as count_jatuh_tempo
FROM penagihan";

$result_dashboard = mysqli_query($db, $sql_dashboard);
$dashboard_data = mysqli_fetch_assoc($result_dashboard);

extract($dashboard_data);
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Penagihan</h3>
        <div class="float-right">
            <a href="index.php?menu=Create&submenu=Penagihan" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penagihan
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Dashboard Stats Cards - AdminLTE 3 -->
        <div class="row mb-4">
            <!-- Total Tagihan Card -->
            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>Rp <?= number_format((float) ($total_tagihan ?? 0), 0, ',', '.') ?></h3>
                        <p>Total Tagihan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

            <!-- Lunas Card -->
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $count_lunas ?></h3>
                        <p>Transaksi Lunas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- Belum Lunas Card -->
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $count_belum_lunas ?></h3>
                        <p>Transaksi Belum Lunas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Jatuh Tempo Card -->
            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $count_jatuh_tempo ?></h3>
                        <p>Transaksi Jatuh Tempo</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once __DIR__ . '/data/data_penagihan.php'; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../modal/detail_penagihan.php';
require_once __DIR__ . '/../modal/cicilan_modal.php';
require_once __DIR__ . '/../modal/batalkan_cicilan.php';
require_once __DIR__ . '/../modal/alasan_batal_cicilan.php';
require_once __DIR__ . '/../modal/konfirmasi_status_cicilan.php';
require_once __DIR__ . '/../../config/javscript/script_penagihan.php';
?>