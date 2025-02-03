<?php
// Info Boxes
$total_sekolah = $db->query("SELECT COUNT(*) as total FROM datadn")->fetch_assoc()['total'];

$dies_natalis_bulan_ini = $db->query("
    SELECT COUNT(*) as total 
    FROM datadn 
    WHERE MONTH(tanggal_dn) = MONTH(CURRENT_DATE())
")->fetch_assoc()['total'];

$total_tagihan = $db->query("
    SELECT SUM(total) as total 
    FROM penagihan 
    WHERE status != '4'
")->fetch_assoc()['total'];

$total_stok = $db->query("
    SELECT SUM(stock) as total 
    FROM jaket
")->fetch_assoc()['total'];

// Status Tagihan
$tagihan_lunas = $db->query("
    SELECT COUNT(*) as total 
    FROM penagihan 
    WHERE status = '4'
")->fetch_assoc()['total'];

$tagihan_cicilan = $db->query("
    SELECT COUNT(*) as total 
    FROM penagihan 
    WHERE dp1_nominal > 0 AND status != '4'
")->fetch_assoc()['total'];

$tagihan_belum_lunas = $db->query("
    SELECT COUNT(*) as total 
    FROM penagihan 
    WHERE dp1_nominal = 0 AND status != '4'
")->fetch_assoc()['total'];

// Dies Natalis Terdekat
$dies_natalis_terdekat = $db->query("
    SELECT * 
    FROM datadn 
    WHERE tanggal_dn >= CURRENT_DATE()
    ORDER BY tanggal_dn ASC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Stok Kritis
$stok_kritis = $db->query("
    SELECT COUNT(*) as total 
    FROM jaket 
    WHERE stock < 5
")->fetch_assoc()['total'];

// Total Terbayar
$total_terbayar = $db->query("
    SELECT SUM(dp1_nominal + dp2_nominal + dp3_nominal) as total 
    FROM penagihan
")->fetch_assoc()['total'];

// Jatuh Tempo
$jatuh_tempo = $db->query("
    SELECT COUNT(*) as total 
    FROM penagihan 
    WHERE (dp1_tenggat BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY))
    OR (dp2_tenggat BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY))
    OR (dp3_tenggat BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY))
    AND status != '4'
")->fetch_assoc()['total'];

// Data untuk Chart Dies Natalis
$data_dies_natalis = [];
$query = $db->query("
    SELECT MONTH(tanggal_dn) as bulan, COUNT(*) as total 
    FROM datadn 
    WHERE YEAR(tanggal_dn) = YEAR(CURRENT_DATE())
    GROUP BY MONTH(tanggal_dn)
");
while ($row = $query->fetch_assoc()) {
  $data_dies_natalis[$row['bulan'] - 1] = $row['total'];
}
// Fill empty months with 0
for ($i = 0; $i < 12; $i++) {
  if (!isset($data_dies_natalis[$i])) {
    $data_dies_natalis[$i] = 0;
  }
}
ksort($data_dies_natalis);
?>
<div class="container-fluid">
  <!-- Info boxes -->
  <div class="row">
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-school"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Sekolah</span>
          <span class="info-box-number">
            <?php echo $total_sekolah; ?>
          </span>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Dies Natalis Bulan Ini</span>
          <span class="info-box-number"><?php echo $dies_natalis_bulan_ini; ?></span>
        </div>
      </div>
    </div>

    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Tagihan</span>
          <span class="info-box-number"><?php echo number_format($total_tagihan, 0, ',', '.'); ?></span>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tshirt"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Stok</span>
          <span class="info-box-number"><?php echo $total_stok; ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8">
      <!-- Dies Natalis Chart -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Grafik Dies Natalis Tahun <?php echo date('Y'); ?></h5>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="diesNatalisChart" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <!-- Status Tagihan -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Status Tagihan</h3>
        </div>
        <div class="card-body">
          <div class="progress-group">
            Lunas
            <span class="float-right"><b><?php echo $tagihan_lunas; ?></b>/<?php echo $total_tagihan; ?></span>
            <div class="progress progress-sm">
              <div class="progress-bar bg-success"
                style="width: <?php echo ($tagihan_lunas / $total_tagihan) * 100; ?>%">
              </div>
            </div>
          </div>

          <div class="progress-group">
            Cicilan
            <span class="float-right"><b><?php echo $tagihan_cicilan; ?></b>/<?php echo $total_tagihan; ?></span>
            <div class="progress progress-sm">
              <div class="progress-bar bg-warning"
                style="width: <?php echo ($tagihan_cicilan / $total_tagihan) * 100; ?>%">
              </div>
            </div>
          </div>

          <div class="progress-group">
            Belum Lunas
            <span class="float-right"><b><?php echo $tagihan_belum_lunas; ?></b>/<?php echo $total_tagihan; ?></span>
            <div class="progress progress-sm">
              <div class="progress-bar bg-danger"
                style="width: <?php echo ($tagihan_belum_lunas / $total_tagihan) * 100; ?>%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8">
      <!-- Dies Natalis Terdekat -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Dies Natalis Terdekat</h3>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Nama Sekolah</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dies_natalis_terdekat as $dn): ?>
                <tr>
                  <td><?php echo $dn['nama_sekolah']; ?></td>
                  <td><?php echo $dn['tanggal_dn']; ?></td>
                  <td>
                    <?php if ($dn['status'] == 1): ?>
                      <span class="badge badge-success">Aktif</span>
                    <?php else: ?>
                      <span class="badge badge-warning">Belum Dihubungi</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="#" class="btn btn-xs btn-info">Detail</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <!-- Stok Kritis -->
      <div class="info-box mb-3 bg-warning">
        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Stok Kritis</span>
          <span class="info-box-number"><?php echo $stok_kritis; ?> Item</span>
        </div>
      </div>

      <!-- Total Penagihan -->
      <div class="info-box mb-3 bg-success">
        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Terbayar</span>
          <span class="info-box-number">Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></span>
        </div>
      </div>

      <!-- Penagihan Jatuh Tempo -->
      <div class="info-box mb-3 bg-danger">
        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Jatuh Tempo Minggu Ini</span>
          <span class="info-box-number"><?php echo $jatuh_tempo; ?> Tagihan</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Dies Natalis Chart
  var ctx = document.getElementById('diesNatalisChart').getContext('2d');
  var diesNatalisChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
      datasets: [{
        label: 'Jumlah Dies Natalis',
        data: <?php echo json_encode($data_dies_natalis); ?>,
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>