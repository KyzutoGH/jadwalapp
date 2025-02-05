<?php
// Info Boxes Queries
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

// Status Tagihan Queries
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

// Additional Info Boxes Queries
$stok_kritis = $db->query("
    SELECT COUNT(*) as total 
    FROM jaket 
    WHERE stock < 5
")->fetch_assoc()['total'];

$total_terbayar = $db->query("
    SELECT SUM(dp1_nominal + dp2_nominal + dp3_nominal) as total 
    FROM penagihan
")->fetch_assoc()['total'];

$jatuh_tempo = $db->query("
    SELECT COUNT(*) as total 
    FROM penagihan 
    WHERE (dp1_tenggat BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY))
    OR (dp2_tenggat BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY))
    OR (dp3_tenggat BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY))
    AND status != '4'
")->fetch_assoc()['total'];

// Dies Natalis Chart Data
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

// Dies Natalis Table Data
$bulan = [
  '01' => 'Januari',
  '02' => 'Februari',
  '03' => 'Maret',
  '04' => 'April',
  '05' => 'Mei',
  '06' => 'Juni',
  '07' => 'Juli',
  '08' => 'Agustus',
  '09' => 'September',
  '10' => 'Oktober',
  '11' => 'November',
  '12' => 'Desember'
];

$query_dies_natalis = "SELECT * FROM datadn 
    WHERE STR_TO_DATE(CONCAT(tanggal_dn, '-' , YEAR(CURRENT_DATE())), '%d-%m-%Y') >= CURRENT_DATE()
    ORDER BY 
        CASE 
            WHEN STR_TO_DATE(CONCAT(tanggal_dn, '-' , YEAR(CURRENT_DATE())), '%d-%m-%Y') < CURRENT_DATE()
            THEN STR_TO_DATE(CONCAT(tanggal_dn, '-' , YEAR(CURRENT_DATE()) + 1), '%d-%m-%Y')
            ELSE STR_TO_DATE(CONCAT(tanggal_dn, '-' , YEAR(CURRENT_DATE())), '%d-%m-%Y')
        END ASC
    LIMIT 5";

$data = mysqli_query($db, $query_dies_natalis);
?>

<section class="content">
  <div class="container-fluid">
    <!-- Info boxes Row -->
    <div class="row">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-school"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Sekolah</span>
            <span class="info-box-number"><?php echo $total_sekolah; ?></span>
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

    <!-- Monthly Recap and Status Tagihan Row -->
    <div class="row">
      <div class="col-md-8">
        <!-- Monthly Recap Card -->
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Monthly Recap Report</h5>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                  <i class="fas fa-wrench"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                  <a href="#" class="dropdown-item">Action</a>
                  <a href="#" class="dropdown-item">Another action</a>
                  <a href="#" class="dropdown-item">Something else here</a>
                  <a class="dropdown-divider"></a>
                  <a href="#" class="dropdown-item">Separated link</a>
                </div>
              </div>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <p class="text-center">
                  <strong>Dies Natalis: Januari - Desember <?php echo date('Y'); ?></strong>
                </p>
                <div class="chart">
                  <canvas id="diesNatalisChart" height="180" style="height: 180px;"></canvas>
                </div>
              </div>
              <div class="col-md-4">
                <p class="text-center">
                  <strong>Status Pencapaian</strong>
                </p>
                <div class="progress-group">
                  Add Products to Cart
                  <span class="float-right"><b>160</b>/200</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: 80%"></div>
                  </div>
                </div>
                <div class="progress-group">
                  Complete Purchase
                  <span class="float-right"><b>310</b>/400</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-danger" style="width: 75%"></div>
                  </div>
                </div>
                <div class="progress-group">
                  <span class="progress-text">Visit Premium Page</span>
                  <span class="float-right"><b>480</b>/800</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-success" style="width: 60%"></div>
                  </div>
                </div>
                <div class="progress-group">
                  Send Inquiries
                  <span class="float-right"><b>250</b>/500</span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-warning" style="width: 50%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                  <h5 class="description-header">$35,210.43</h5>
                  <span class="description-text">TOTAL REVENUE</span>
                </div>
              </div>
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                  <h5 class="description-header">$10,390.90</h5>
                  <span class="description-text">TOTAL COST</span>
                </div>
              </div>
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                  <h5 class="description-header">$24,813.53</h5>
                  <span class="description-text">TOTAL PROFIT</span>
                </div>
              </div>
              <div class="col-sm-3 col-6">
                <div class="description-block">
                  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                  <h5 class="description-header">1200</h5>
                  <span class="description-text">GOAL COMPLETIONS</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <!-- Status Tagihan Card -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Status Tagihan</h3>
          </div>
          <div class="card-body">
            <div class="progress-group">
              Lunas
              <span class="float-right"><b><?php echo $tagihan_lunas; ?></b></span>
              <div class="progress progress-sm">
                <div class="progress-bar bg-success"
                  style="width: <?php echo ($tagihan_lunas / $total_tagihan) * 100; ?>%"></div>
              </div>
            </div>

            <div class="progress-group">
              Cicilan
              <span class="float-right"><b><?php echo $tagihan_cicilan; ?></b></span>
              <div class="progress progress-sm">
                <div class="progress-bar bg-warning"
                  style="width: <?php echo ($tagihan_cicilan / $total_tagihan) * 100; ?>%"></div>
              </div>
            </div>

            <div class="progress-group">
              Belum Lunas
              <span class="float-right"><b><?php echo $tagihan_belum_lunas; ?></b></span>
              <div class="progress progress-sm">
                <div class="progress-bar bg-danger"
                  style="width: <?php echo ($tagihan_belum_lunas / $total_tagihan) * 100; ?>%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dies Natalis and Info Boxes Row -->
    <div class="row">
      <div class="col-md-8">
        <!-- Dies Natalis Table Card -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Dies Natalis Terdekat</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Sekolah</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (mysqli_num_rows($data) > 0) {
                  $no = 1;
                  while ($d = mysqli_fetch_assoc($data)) {
                    $tanggal = explode('-', $d['tanggal_dn']);
                    $tanggal_format = $tanggal[0] . ' ' . $bulan[$tanggal[1]];
                    ?>
                    <tr>
                      <td><?php echo $no++; ?></td>
                      <td><?php echo htmlspecialchars($d['nama_sekolah']); ?></td>
                      <td><?php echo $tanggal_format; ?></td>
                      <td>
                        <?php if ($d['status'] == 1): ?>
                          <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                          <span class="badge badge-warning">Belum Dihubungi</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <button type="button" class="btn btn-xs btn-info" data-toggle="modal"
                          data-target="#detailModal<?php echo $d['id']; ?>">
                          Detail
                        </button>
                      </td>
                    </tr>
                    <?php
                  }
                } else {
                  ?>
                  <tr>
                    <td colspan="5" class="text-center">Tidak ada data dies natalis terdekat</td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <!-- Info Boxes -->
        <div class="info-box mb-3 bg-warning">
          <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Stok Kritis</span>
            <span class="info-box-number"><?php echo $stok_kritis; ?> Item</span>
          </div>
        </div>

        <div class="info-box mb-3 bg-success">
          <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Terbayar</span>
            <span class="info-box-number">Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></span>
          </div>
        </div>

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
</section>
</div>
</div>

<!-- Modals -->
<?php
if (mysqli_num_rows($data) > 0) {
  mysqli_data_seek($data, 0); // Reset pointer to start of result set
  while ($d = mysqli_fetch_assoc($data)) {
    $tanggal = explode('-', $d['tanggal_dn']);
    $tanggal_format = $tanggal[0] . ' ' . $bulan[$tanggal[1]];
    ?>
    <div class="modal fade" id="detailModal<?php echo $d['id']; ?>" tabindex="-1" role="dialog"
      aria-labelledby="detailModalLabel<?php echo $d['id']; ?>" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detailModalLabel<?php echo $d['id']; ?>">Detail Dies Natalis</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-borderless">
              <tr>
                <th width="30%">Nama Sekolah</th>
                <td width="5%">:</td>
                <td><?php echo htmlspecialchars($d['nama_sekolah']); ?></td>
              </tr>
              <tr>
                <th>Tanggal</th>
                <td>:</td>
                <td><?php echo $tanggal_format; ?></td>
              </tr>
              <tr>
                <th>Status</th>
                <td>:</td>
                <td>
                  <?php if ($d['status'] == 1): ?>
                    <span class="badge badge-success">Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-warning">Belum Dihubungi</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <th>Alamat</th>
                <td>:</td>
                <td><?php echo htmlspecialchars($d['alamat']); ?></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
}
?>

<!-- Dies Natalis Chart Script -->
<script>
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
</body>

</html>