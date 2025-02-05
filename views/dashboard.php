<?php
// Info Boxes Queries
$total_sekolah = $db->query("SELECT COUNT(*) as total FROM datadn")->fetch_assoc()['total'];

$dies_natalis_bulan_ini = $db->query("
    SELECT COUNT(*) as total 
    FROM datadn 
    WHERE MONTH(STR_TO_DATE(CONCAT(tanggal_dn, '-', YEAR(CURRENT_DATE())), '%d-%m-%Y')) = MONTH(CURRENT_DATE())
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

$data_dies_natalis = array_fill(0, 12, 0); // Initialize array with 0 for 12 months

// Query untuk mengambil data dies natalis per bulan
$query = $db->query("SELECT 
        MONTH(STR_TO_DATE(CONCAT(tanggal_dn, '-', YEAR(CURRENT_DATE())), '%d-%m-%Y')) as bulan,
        COUNT(*) as total,
        GROUP_CONCAT(nama_sekolah) as sekolah_list
    FROM datadn 
    WHERE YEAR(STR_TO_DATE(CONCAT(tanggal_dn, '-', YEAR(CURRENT_DATE())), '%d-%m-%Y')) = YEAR(CURRENT_DATE())
    GROUP BY bulan
    ORDER BY bulan");

$data_dies_natalis = array_fill(0, 12, 0); // Inisialisasi array dengan 0 untuk 12 bulan

while ($row = $query->fetch_assoc()) {
  $data_dies_natalis[$row['bulan'] - 1] = (int) $row['total'];
}

// Data for tooltip/detail
$chart_details = [
  'current_year' => date('Y'),
  'total_annual' => array_sum($data_dies_natalis),
  'highest_month' => array_search(max($data_dies_natalis), $data_dies_natalis) + 1,
  'lowest_month' => array_search(min(array_filter($data_dies_natalis)), $data_dies_natalis) + 1
];

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
    <?php
    $data_dies_natalis = [];
    for ($i = 1; $i <= 12; $i++) {
      $query = "SELECT COUNT(*) as jumlah FROM datadn WHERE MONTH(tanggal_dn) = $i";
      $result = $db->query($query);
      $row = $result->fetch_assoc();
      $data_dies_natalis[] = (int) $row['jumlah'];
    }

    // Query untuk status tagihan
    $query_tagihan = "SELECT 
                    SUM(CASE WHEN status = 'Lunas' THEN 1 ELSE 0 END) AS lunas,
                    SUM(CASE WHEN status = 'Cicilan' THEN 1 ELSE 0 END) AS cicilan,
                    SUM(CASE WHEN status = 'Belum Lunas' THEN 1 ELSE 0 END) AS belum_lunas,
                    COUNT(*) AS total
                FROM penagihan";
    $result_tagihan = $db->query($query_tagihan);
    $tagihan = $result_tagihan->fetch_assoc();

    $tagihan_lunas = $tagihan['lunas'];
    $tagihan_cicilan = $tagihan['cicilan'];
    $tagihan_belum_lunas = $tagihan['belum_lunas'];
    $total_tagihan = $tagihan['total'];

    // Query untuk mendapatkan stok kritis
    $query_stok = "
    SELECT
        (SELECT COUNT(*) FROM jaket WHERE stock < 5) + (SELECT COUNT(*) FROM stiker WHERE stock < 5) AS stok_kritis_total;
";
    $result_stok = $db->query($query_stok);
    $row_stok = $result_stok->fetch_assoc();
    $stok_kritis = $row_stok['stok_kritis_total'];

    // Query untuk mendapatkan total pembayaran
    $query_pembayaran = "SELECT SUM(total) AS total_terbayar 
FROM penagihan 
WHERE status IN ('2', '3', '4') AND tgllunas IS NOT NULL;
";
    $result_pembayaran = $db->query($query_pembayaran);
    $row_pembayaran = $result_pembayaran->fetch_assoc();
    $total_terbayar = $row_pembayaran['total_terbayar'];
    ?>

    <!-- Monthly Recap and Status Tagihan Row -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Laporan Bulanan</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <p class="text-center">
                  <strong>Dies Natalis: Januari - Desember <?php echo date('Y'); ?></strong>
                </p>
                <div class="chart">
                  <canvas id="diesNatalisChart" height="180" style="height: 180px;"></canvas>
                  <script>
                    const diesNatalisData = <?php echo json_encode($data_dies_natalis); ?>;
                  </script>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dies Natalis and Info Boxes Row -->
    <div class="row">
      <div class="col-md-12">
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