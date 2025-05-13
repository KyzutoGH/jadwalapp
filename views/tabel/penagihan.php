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
        <!-- Tabel Penagihan -->
        <table id="tabelPenagihan" class="tabelBarang table table-bordered table-striped dataTable dtr-inline"
            style="width:100%">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>NAMA CUSTOMER (PIC)</th>
                    <th>TOTAL | SISA</th>
                    <th>DP (DETAIL)</th>
                    <th>TGL JATUH TEMPO</th>
                    <th>BARANG</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                function generateInstallmentBadge($current, $total, $warning = false)
                {
                    $badgeClass = 'badge ';
                    if ($warning) {
                        $badgeClass .= 'badge-danger';
                    } elseif ($current == $total) {
                        $badgeClass .= 'badge-success';
                    } elseif ($current == 0) {
                        $badgeClass .= 'badge-secondary';
                    } else {
                        $badgeClass .= 'badge-info';
                    }

                    $icon = $warning ? "<i class='fas fa-exclamation-triangle mr-1'></i>" : "<i class='fas fa-clock mr-1'></i>";

                    return sprintf(
                        '<span class="%s" style="font-size: 0.9rem; padding: 6px 12px;" data-toggle="tooltip" title="%s">
                %s Cicilan %d dari %d
            </span>',
                        $badgeClass,
                        $warning ? 'Ada cicilan yang melewati jatuh tempo' : 'Status cicilan pembayaran',
                        $icon,
                        $current,
                        $total
                    );
                }

                $sql = "SELECT 
        p.id,
        p.tanggal,
        p.customer,
        p.total,
        p.jumlah_dp,
        CONCAT(
            (CASE WHEN dp1_status > 0 THEN 1 ELSE 0 END +
             CASE WHEN dp2_status > 0 THEN 1 ELSE 0 END +
             CASE WHEN dp3_status > 0 THEN 1 ELSE 0 END), 
            ' dari ', p.jumlah_dp
        ) AS dp_status,
        
        (CASE WHEN dp1_status > 0 THEN dp1_nominal ELSE 0 END) +
        (CASE WHEN dp2_status > 0 THEN dp2_nominal ELSE 0 END) +
        (CASE WHEN dp3_status > 0 THEN dp3_nominal ELSE 0 END) AS total_dibayar,
        
        p.total - (
            (CASE WHEN dp1_status > 0 THEN dp1_nominal ELSE 0 END) +
            (CASE WHEN dp2_status > 0 THEN dp2_nominal ELSE 0 END) +
            (CASE WHEN dp3_status > 0 THEN dp3_nominal ELSE 0 END)
        ) AS sisa_tagihan,
        
        p.dp1_tenggat,
        p.dp2_tenggat, 
        p.dp3_tenggat,
        p.tgllunas,
        
        p.dp1_metode,
        p.dp2_metode, 
        p.dp3_metode,
        
        GROUP_CONCAT(
            CASE 
                WHEN pd.jenis_barang = 'jaket' THEN 
                    CONCAT('Jaket: ', j.namabarang, 
                           ' (', pd.qty, ' × Rp', FORMAT(pd.harga_satuan, 0), ' = Rp', FORMAT(pd.qty * pd.harga_satuan, 0), ')')
                WHEN pd.jenis_barang = 'stiker' THEN 
                    CONCAT('Stiker: ', s.nama, 
                           ' (', pd.qty, ' × Rp', FORMAT(pd.harga_satuan, 0), ' = Rp', FORMAT(pd.qty * pd.harga_satuan, 0), ')')
                WHEN pd.jenis_barang = 'barang_jadi' THEN 
                    CONCAT('Barang Jadi: ', bj.nama_produk, 
                           ' (', pd.qty, ' × Rp', FORMAT(pd.harga_satuan, 0), ' = Rp', FORMAT(pd.qty * pd.harga_satuan, 0), ')')
                ELSE 
                    CONCAT('Produk Lain: ', pd.jenis_barang, 
                           ' (', pd.qty, ' × Rp', FORMAT(pd.harga_satuan, 0), ' = Rp', FORMAT(pd.qty * pd.harga_satuan, 0), ')')
            END
        SEPARATOR '\n'
        ) AS daftar_produk,
        
        GROUP_CONCAT(
            CASE 
                WHEN pd.jenis_barang = 'jaket' THEN j.ukuran
                WHEN pd.jenis_barang = 'stiker' THEN s.bagian
                ELSE NULL
            END
            SEPARATOR ' | '
        ) AS detail_produk,
        
        p.status,
        p.kontak,
        p.alasan_batal,
        p.dp1_nominal,
        p.dp2_nominal,
        p.dp3_nominal,
        p.dp1_status,
        p.dp2_status,
        p.dp3_status
        
    FROM penagihan p
    LEFT JOIN penagihan_detail pd ON p.id = pd.penagihan_id
    LEFT JOIN jaket j ON pd.jenis_barang = 'jaket' AND pd.produk_id = j.id_jaket
    LEFT JOIN stiker s ON pd.jenis_barang = 'stiker' AND pd.produk_id = s.id_sticker
    LEFT JOIN barang_jadi bj ON pd.jenis_barang = 'barang_jadi' AND pd.produk_id = bj.id_barang
    GROUP BY p.id
    ORDER BY p.tanggal DESC";

                $result = mysqli_query($db, $sql);
                while ($p = mysqli_fetch_assoc($result)) {
                    $total_numeric = $p['total'];
                    $total_dibayar_numeric = $p['total_dibayar'];
                    $sisaPembayaran = $p['sisa_tagihan'];


                    $total_formatted = 'Rp ' . number_format($total_numeric, 0, ',', '.');
                    $sisa_formatted = 'Rp ' . number_format($sisaPembayaran, 0, ',', '.');
                    $p['tanggal'] = date('d/m/Y', strtotime($p['tanggal']));
                    $p['tgllunas'] = !empty($p['tgllunas']) ? date('d/m/Y', strtotime($p['tgllunas'])) : '';

                    $dpParts = explode(" dari ", $p['dp_status']);
                    $currentCicilan = (int) $dpParts[0];
                    $totalCicilan = (int) $dpParts[1];

                    // Cek jika cicilan jatuh tempo dan belum dibayar
                    $today = date('Y-m-d');
                    $warning = false;
                    $jatuhTempoInfo = [];

                    // Format kolom TOTAL | SISA berdasarkan status
                    if ($p['status'] == '2' || $p['status'] == '3' || $p['status'] == '4') {
                        // Jika status Lunas (status 2, 3, atau 4)
                        $p['total_display'] = $total_formatted . ' | <span class="text-success font-weight-bold">LUNAS</span>';
                    } else if ($sisaPembayaran <= 0) {
                        // Jika sisa pembayaran 0 atau negatif tapi status belum diubah
                        $p['total_display'] = $total_formatted . ' | <span class="text-success font-weight-bold">LUNAS</span>';
                    } else if ($p['status'] == '5') {
                        // Jika status Dibatalkan
                        $p['total_display'] = $total_formatted . ' | <span class="text-danger">BATAL</span>';
                    } else {
                        // Jika belum lunas
                        $p['total_display'] = $total_formatted . ' | <span class="text-navy font-weight-bold">' . $sisa_formatted . '</span>';
                    }

                    if ($p['jumlah_dp'] >= 1 && $p['dp1_status'] == 0 && !empty($p['dp1_tenggat'])) {
                        if ($today > $p['dp1_tenggat']) {
                            $warning = true;
                        }
                        $jatuhTempoInfo[] = "DP1: " . date('d/m/Y', strtotime($p['dp1_tenggat'])) .
                            " (Rp " . number_format($p['dp1_nominal'], 0, ',', '.') . ")";
                    }

                    if ($p['jumlah_dp'] >= 2 && $p['dp2_status'] == 0 && !empty($p['dp2_tenggat'])) {
                        if ($today > $p['dp2_tenggat']) {
                            $warning = true;
                        }
                        $jatuhTempoInfo[] = "DP2: " . date('d/m/Y', strtotime($p['dp2_tenggat'])) . ")";
                    }

                    if ($p['jumlah_dp'] == 3 && $p['dp3_status'] == 0 && !empty($p['dp3_tenggat'])) {
                        if ($today > $p['dp3_tenggat']) {
                            $warning = true;
                        }
                        $jatuhTempoInfo[] = "DP3: " . date('d/m/Y', strtotime($p['dp3_tenggat'])) . ")";
                    }

                    $dpDisplay = generateInstallmentBadge($currentCicilan, $totalCicilan, $warning);
                    $jatuhTempoDisplay = implode("<br>", $jatuhTempoInfo);

                    $statusBadge = '';
                    $actionButton = '';

                    switch ($p['status']) {
                        case '1': // Belum Lunas
                            $statusBadge = '<span class="badge badge-warning">Belum Lunas</span>';
                            $actionButton = '<div class="btn-group">';

                            if ($currentCicilan < $totalCicilan) {
                                $nextCicilan = $currentCicilan + 1;

                                $actionButton .= "
                    <button class='btn btn-warning btn-sm'
                            onclick='showCicilanModal(
                                {$p['id']}, 
                                {$nextCicilan}, 
                                {$totalCicilan},
                                {$sisaPembayaran},
                                \"" . (!empty($p['dp' . $nextCicilan . '_tenggat']) ? date('d/m/Y', strtotime($p['dp' . $nextCicilan . '_tenggat'])) : "Sekarang") . "\"
                            )'
                            data-toggle='tooltip' title='Bayar DP {$nextCicilan}'>
                        <i class='fas fa-money-bill'></i> 
                    </button>";
                            } else {
                                $actionButton .= "
                    <button class='btn btn-success btn-sm'
                            onclick='showCicilanModal(
                                {$p['id']}, 
                                " . ($totalCicilan + 1) . ", 
                                {$totalCicilan}, 
                                {$sisaPembayaran},
                                \"Sekarang\"
                            )'
                            data-toggle='tooltip' title='Pelunasan - Rp " . number_format($sisaPembayaran, 0, ',', '.') . "'>
                        <i class='fas fa-check-circle'></i> Lunas
                    </button>";
                            }

                            $actionButton .= "
                <button class='btn btn-danger btn-sm' 
                        onclick='showBatalkanModal({$p['id']})'
                        data-toggle='tooltip' title='Batalkan Pesanan'> 
                    <i class='fas fa-times'></i>
                </button>
                <a href='https://wa.me/62" . $p['kontak'] . "' 
                   target='_blank' 
                   class='btn btn-info btn-sm'
                   data-toggle='tooltip' title='Chat Customer'>
                    <i class='fab fa-whatsapp'></i>
                </a>";
                            break;

                        case '2': // Lunas - Proses
                            $statusBadge = '<span class="badge badge-info">Lunas - Proses</span>';
                            $actionButton = '<div class="btn-group">
                <button class="btn btn-success btn-sm" 
                        onclick="updateStatus(' . $p['id'] . ', 3)"
                        data-toggle="tooltip" title="Tandai Siap Diambil">
                    <i class="fas fa-box-open"></i>
                </button>
                <a href="https://wa.me/62' . $p['kontak'] . '" 
                   target="_blank" 
                   class="btn btn-info btn-sm"
                   data-toggle="tooltip" title="Chat Customer">
                    <i class="fab fa-whatsapp"></i>
                </a>';
                            break;

                        case '3': // Lunas - Siap Diambil
                            $statusBadge = '<span class="badge badge-success">Lunas - Siap Diambil</span>';
                            $actionButton = '<div class="btn-group">
                <button class="btn btn-secondary btn-sm" 
                        onclick="updateStatus(' . $p['id'] . ', 4)"
                        data-toggle="tooltip" title="Tandai Selesai">
                    <i class="fas fa-check"></i>
                </button>
                <a href="https://wa.me/62' . $p['kontak'] . '" 
                   target="_blank" 
                   class="btn btn-info btn-sm"
                   data-toggle="tooltip" title="Chat Customer">
                    <i class="fab fa-whatsapp"></i>
                </a>';
                            break;

                        case '4': // Selesai
                            $statusBadge = '<span class="badge badge-secondary">Selesai</span>';
                            $actionButton = '<div class="text-muted">';
                            break;

                        case '5': // Dibatalkan
                            $statusBadge = '<span class="badge badge-danger">Dibatalkan</span>';
                            $alasanEscaped = htmlspecialchars($p['alasan_batal'], ENT_QUOTES);
                            $actionButton = "
                <button class='btn btn-info btn-sm'
                        onclick=\"tampilkanAlasan('{$alasanEscaped}')\"
                        data-toggle='modal'
                        data-target='#alasanModal'
                        title='Lihat Alasan Pembatalan'>
                    <i class='fas fa-info-circle'></i>
                </button>";
                            break;
                    }

                    // Tambahkan tombol detail
                    $detailAttr = sprintf(
                        "data-id='%d' 
            data-customer='%s' 
            data-tanggal='%s'
            data-total='%s' 
            data-total-dibayar='%s'
            data-sisa-tagihan='%s'
            data-status='%s' 
            data-dp='%s'
            data-produk='%s' 
            data-catatan='%s'
            data-dp1='%s'
            data-dp2='%s'
            data-dp3='%s'
            data-dp1status='%d'
            data-dp2status='%d'
            data-dp3status='%d'
            data-tenggat1='%s'
            data-tenggat2='%s'
            data-tenggat3='%s'
            data-metode1='%s'
            data-metode2='%s'
            data-metode3='%s'",
                        $p['id'],
                        htmlspecialchars($p['customer'], ENT_QUOTES),
                        htmlspecialchars($p['tanggal'], ENT_QUOTES),
                        htmlspecialchars($p['total'], ENT_QUOTES),
                        htmlspecialchars($p['total_dibayar'] ?? '0', ENT_QUOTES),
                        htmlspecialchars($p['sisa_tagihan'] ?? $p['total'], ENT_QUOTES),
                        htmlspecialchars(strip_tags($statusBadge), ENT_QUOTES),
                        htmlspecialchars($p['dp_status'] ?? '-', ENT_QUOTES),
                        htmlspecialchars($p['daftar_produk'] ?? '-', ENT_QUOTES),
                        htmlspecialchars($p['catatan'] ?? '-', ENT_QUOTES),
                        htmlspecialchars($p['dp1_nominal'] ?? '0', ENT_QUOTES),
                        htmlspecialchars($p['dp2_nominal'] ?? '0', ENT_QUOTES),
                        htmlspecialchars($p['dp3_nominal'] ?? '0', ENT_QUOTES),
                        $p['dp1_status'] ?? 0,
                        $p['dp2_status'] ?? 0,
                        $p['dp3_status'] ?? 0,
                        htmlspecialchars(!empty($p['dp1_tenggat']) ? date('d/m/Y', strtotime($p['dp1_tenggat'])) : '-', ENT_QUOTES),
                        htmlspecialchars(!empty($p['dp2_tenggat']) ? date('d/m/Y', strtotime($p['dp2_tenggat'])) : '-', ENT_QUOTES),
                        htmlspecialchars(!empty($p['dp3_tenggat']) ? date('d/m/Y', strtotime($p['dp3_tenggat'])) : '-', ENT_QUOTES),
                        htmlspecialchars($p['dp1_metode'] ?? '-', ENT_QUOTES),
                        htmlspecialchars($p['dp2_metode'] ?? '-', ENT_QUOTES),
                        htmlspecialchars($p['dp3_metode'] ?? '-', ENT_QUOTES)
                    );

                    $actionButton .= "
            <button class='btn btn-secondary btn-sm' 
                    onclick='showDetailModal(this)'
                    $detailAttr
                    data-toggle='tooltip' title='Detail Pesanan'>
                <i class='fas fa-info-circle'></i>
            </button>
        </div>";
                    // Bagian kode yang menampilkan kolom tersebut dalam tabel
                    echo "<tr>
    <td>" . htmlspecialchars($p['tanggal']) . "</td>
    <td>" . htmlspecialchars($p['customer']) . "</td>
    <td>" . $p['total_display'] . "</td>
    <td>$dpDisplay</td>
    <td>$jatuhTempoDisplay</td>
    <td>
        <div class='product-details'>" .
                        nl2br(htmlspecialchars($p['daftar_produk'])) .
                        "</div>
    </td>
    <td>$statusBadge</td>
    <td>$actionButton</td>
</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail yang Dioptimalkan -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetailLabel">Detail Pesanan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Konten detail akan dimuat dinamis -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="#" id="whatsappCustomerBtn" class="btn btn-success" target="_blank">
                    <i class="fab fa-whatsapp mr-1"></i> Hubungi Customer
                </a>
                <button type="button" id="printDetailBtn" class="btn btn-info">
                    <i class="fas fa-print mr-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cicilan -->
<div class="modal fade" id="cicilanModal" tabindex="-1" role="dialog" aria-labelledby="modalCicilanTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCicilanTitle">Pembayaran Cicilan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formCicilan" action="config/proses_cicilan.php" method="POST">
                    <input type="hidden" id="penagihan_id" name="penagihan_id">
                    <input type="hidden" id="cicilan_ke" name="cicilan_ke">
                    <input type="hidden" id="total_cicilan" name="total_cicilan">

                    <div class="form-group">
                        <label for="nominal">Nominal Pembayaran</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_bayar">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar" required
                            value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal Batalkan -->
<div class="modal fade" id="modalBatalkan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pembatalan Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formBatalkan" action="config/batalkan_pesanan.php" method="POST">
                    <input type="hidden" id="custIdBatal" name="custIdBatal">

                    <div class="form-group">
                        <label>Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasanBatal" name="alasanBatal" rows="3" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alasan -->
<div class="modal fade" id="alasanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alasan Pembatalan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="alasanText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Status -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Konfirmasi Perubahan Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="statusModalBody">
                Apakah Anda yakin ingin mengubah status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="konfirmasiStatusBtn" class="btn btn-primary">Ya, Konfirmasi</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetailModal(el) {
        // Format angka ke format mata uang
        const formatCurrency = (amount) => {
            return 'Rp ' + parseFloat(amount).toLocaleString('id-ID');
        };

        // Persiapan data tanggal dan cek jatuh tempo
        const today = new Date();
        const formatDate = (dateStr) => {
            if (!dateStr || dateStr === '-') return '-';
            const parts = dateStr.split('/');
            if (parts.length !== 3) return dateStr;
            return `${parts[0]}/${parts[1]}/${parts[2]}`;
        };

        // Parse tanggal jatuh tempo
        const parseDate = (dateStr) => {
            if (!dateStr || dateStr === '-') return null;
            const parts = dateStr.split('/');
            if (parts.length !== 3) return null;
            return new Date(parts[2], parts[1] - 1, parts[0]);
        };

        const dp1_tenggat = parseDate(el.dataset.tenggat1);
        const dp2_tenggat = parseDate(el.dataset.tenggat2);
        const dp3_tenggat = parseDate(el.dataset.tenggat3);

        // Cek jatuh tempo
        const isDp1Overdue = dp1_tenggat && today > dp1_tenggat && el.dataset.dp1status === '0';
        const isDp2Overdue = dp2_tenggat && today > dp2_tenggat && el.dataset.dp2status === '0';
        const isDp3Overdue = dp3_tenggat && today > dp3_tenggat && el.dataset.dp3status === '0';
        const hasOverdue = isDp1Overdue || isDp2Overdue || isDp3Overdue;

        // Status badge dengan warna
        const getStatusBadge = (status) => {
            let badgeClass = 'badge ';
            if (status.includes('Belum Lunas')) {
                badgeClass += 'badge-warning';
            } else if (status.includes('Siap Diambil')) {
                badgeClass += 'badge-success';
            } else if (status.includes('Proses')) {
                badgeClass += 'badge-info';
            } else if (status.includes('Selesai')) {
                badgeClass += 'badge-secondary';
            } else if (status.includes('Dibatalkan')) {
                badgeClass += 'badge-danger';
            } else {
                badgeClass += 'badge-primary';
            }
            return `<span class="${badgeClass}">${status}</span>`;
        };

        // Peringatan pembayaran jatuh tempo
        let overdueWarnings = '';
        if (hasOverdue) {
            overdueWarnings = '<div class="alert alert-danger mb-4">';
            overdueWarnings += '<i class="fas fa-exclamation-triangle mr-2"></i><strong>Peringatan:</strong> Ada pembayaran yang melewati jatuh tempo!';
            overdueWarnings += '<ul class="mb-0 mt-2">';

            if (isDp1Overdue) {
                overdueWarnings += `<li>DP1 seharusnya dibayar sebelum ${formatDate(el.dataset.tenggat1)}</li>`;
            }
            if (isDp2Overdue) {
                overdueWarnings += `<li>DP2 seharusnya dibayar sebelum ${formatDate(el.dataset.tenggat2)}</li>`;
            }
            if (isDp3Overdue) {
                overdueWarnings += `<li>DP3 seharusnya dibayar sebelum ${formatDate(el.dataset.tenggat3)}</li>`;
            }

            overdueWarnings += '</ul></div>';
        }

        // Kartu informasi dasar
        let basicInfo = `
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Customer:</strong> ${el.dataset.customer}</p>
                        <p><strong>Tanggal Pesan:</strong> ${el.dataset.tanggal}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>ID Pesanan:</strong> #${el.dataset.id}</p>
                        <p><strong>Status:</strong> ${getStatusBadge(el.dataset.status)}</p>
                    </div>
                </div>
            </div>
        </div>`;

        // Kartu detail pembayaran
        let paymentDetails = `
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Detail Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="text-center border rounded p-3 h-100 bg-light">
                            <p class="mb-1"><strong>Total Tagihan</strong></p>
                            <h4 class="mb-0">${formatCurrency(el.dataset.total)}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center border rounded p-3 h-100 bg-light">
                            <p class="mb-1"><strong>Total Dibayar</strong></p>
                            <h4 class="mb-0">${formatCurrency(el.dataset.totalDibayar)}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center border rounded p-3 h-100 ${parseFloat(el.dataset.sisaTagihan) > 0 ? 'bg-warning' : 'bg-success text-white'}">
                            <p class="mb-1"><strong>Sisa Tagihan</strong></p>
                            <h4 class="mb-0">${parseFloat(el.dataset.sisaTagihan) > 0 ? formatCurrency(el.dataset.sisaTagihan) : 'LUNAS'}</h4>
                        </div>
                    </div>
                </div>`;

        // Rincian DP
        if (el.dataset.dp1 || el.dataset.dp2 || el.dataset.dp3) {
            paymentDetails += '<h6 class="mt-4 mb-3 border-bottom pb-2"><strong>Rincian Pembayaran</strong></h6>';
            paymentDetails += '<div class="table-responsive"><table class="table table-sm table-bordered table-hover">';
            paymentDetails += `<thead class="thead-light">
                <tr>
                    <th>Pembayaran</th>
                    <th>Nominal</th>
                    <th>Metode</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>`;

            // DP1
            if (el.dataset.dp1) {
                const dp1Status = el.dataset.dp1status === '1' ?
                    '<span class="badge badge-success">Dibayar</span>' :
                    (isDp1Overdue ? '<span class="badge badge-danger">Terlambat</span>' : '<span class="badge badge-warning">Belum Dibayar</span>');

                paymentDetails += `
                <tr>
                    <td>DP1</td>
                    <td>${formatCurrency(el.dataset.dp1)}</td>
                    <td>${el.dataset.metode1 || '-'}</td>
                    <td>${formatDate(el.dataset.tenggat1)}</td>
                    <td>${dp1Status}</td>
                </tr>`;
            }

            // DP2
            if (el.dataset.dp2) {
                const dp2Status = el.dataset.dp2status === '1' ?
                    '<span class="badge badge-success">Dibayar</span>' :
                    (isDp2Overdue ? '<span class="badge badge-danger">Terlambat</span>' : '<span class="badge badge-warning">Belum Dibayar</span>');

                paymentDetails += `
                <tr>
                    <td>DP2</td>
                    <td>${formatCurrency(el.dataset.dp2)}</td>
                    <td>${el.dataset.metode2 || '-'}</td>
                    <td>${formatDate(el.dataset.tenggat2)}</td>
                    <td>${dp2Status}</td>
                </tr>`;
            }

            // DP3
            if (el.dataset.dp3) {
                const dp3Status = el.dataset.dp3status === '1' ?
                    '<span class="badge badge-success">Dibayar</span>' :
                    (isDp3Overdue ? '<span class="badge badge-danger">Terlambat</span>' : '<span class="badge badge-warning">Belum Dibayar</span>');

                paymentDetails += `
                <tr>
                    <td>DP3</td>
                    <td>${formatCurrency(el.dataset.dp3)}</td>
                    <td>${el.dataset.metode3 || '-'}</td>
                    <td>${formatDate(el.dataset.tenggat3)}</td>
                    <td>${dp3Status}</td>
                </tr>`;
            }

            paymentDetails += '</tbody></table></div>';
        }

        paymentDetails += '</div></div>';

        // Kartu detail produk
        let productDetails = `
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-box mr-2"></i>Detail Produk</h5>
            </div>
            <div class="card-body">`;

        if (el.dataset.produk && el.dataset.produk !== '-') {
            const produkLines = el.dataset.produk.split('\n');
            productDetails += '<ul class="list-group">';
            produkLines.forEach(line => {
                productDetails += `<li class="list-group-item">${line}</li>`;
            });
            productDetails += '</ul>';
        } else {
            productDetails += '<p class="text-muted">Tidak ada detail produk.</p>';
        }

        if (el.dataset.catatan && el.dataset.catatan !== '-') {
            productDetails += `
            <div class="mt-3">
                <h6><strong>Catatan:</strong></h6>
                <div class="p-3 bg-light rounded">${el.dataset.catatan}</div>
            </div>`;
        }

        productDetails += '</div></div>';

        // Susun modal
        const html = `
            ${overdueWarnings}
            ${basicInfo}
            ${paymentDetails}
            ${productDetails}
        `;

        // Update modal content
        document.getElementById('detailContent').innerHTML = html;

        // Set WhatsApp link
        const phoneNumber = el.dataset.kontak || '';
        if (phoneNumber) {
            $('#whatsappCustomerBtn').attr('href', `https://wa.me/62${phoneNumber}`).show();
        } else {
            $('#whatsappCustomerBtn').hide();
        }

        // Tampilkan modal
        $('#modalDetail').modal('show');
    }

    // Fungsi untuk mencetak detail
    document.getElementById('printDetailBtn').addEventListener('click', function () {
        const printContent = document.getElementById('detailContent').innerHTML;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Struk Pemesanan</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body {
                    padding: 40px;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: #f8f9fa;
                }
                .receipt-container {
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 8px;
                    padding: 30px;
                    max-width: 700px;
                    margin: auto;
                    box-shadow: 0 0 15px rgba(0,0,0,0.1);
                }
                .store-info {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .store-info img {
                    width: 100px;
                    margin-bottom: 10px;
                }
                .store-info h5 {
                    margin-bottom: 5px;
                    font-weight: bold;
                }
                .store-info p {
                    margin: 0;
                    font-size: 13px;
                    color: #6c757d;
                }
                .receipt-header {
                    border-top: 2px dashed #6c757d;
                    border-bottom: 2px dashed #6c757d;
                    margin: 20px 0;
                    padding: 10px 0;
                    text-align: center;
                }
                .receipt-content {
                    font-size: 15px;
                }
                .receipt-footer {
                    border-top: 2px dashed #6c757d;
                    margin-top: 30px;
                    padding-top: 10px;
                    text-align: center;
                    font-size: 13px;
                    color: #6c757d;
                }
                .btn-print {
                    margin-top: 30px;
                    text-align: center;
                }
                @media print {
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="store-info">
                    <img src="assets/img/fukubistorelogo.png" alt="Fubuki Konveksi">
                    <h5>Fubuki Konveksi</h5>
                    <p>Dadapan, Kedung Bunder, Kec. Sutojayan, Kabupaten Blitar, Jawa Timur 66172</p>
                    <p>WA: 0856-4962-3058</p>
                </div>

                <div class="receipt-header">
                    <h4>Detail Pemesanan</h4>
                </div>

                <div class="receipt-content">
                    ${printContent}
                </div>

                <div class="receipt-footer">
                    Terima kasih telah memesan di Fubuki Konveksi.
                </div>

                <div class="btn-print no-print">
                    <button onclick="window.print()" class="btn btn-primary">Print</button>
                    <button onclick="window.close()" class="btn btn-secondary ml-2">Tutup</button>
                </div>
            </div>
        </body>
        </html>
    `);
        printWindow.document.close();
    });
    function updateStatus(id, newStatus) {
        // Set judul dan pesan berdasarkan status yang akan diubah
        let title = newStatus === 3 ? 'Konfirmasi Barang Siap' : 'Konfirmasi Pengambilan';
        let text = newStatus === 3 ?
            'Apakah barang sudah selesai diproduksi dan siap diambil?' :
            'Apakah barang sudah diambil oleh customer?';

        // Update modal content
        $('#statusModalLabel').text(title);
        $('#statusModalBody').text(text);

        // Set link konfirmasi
        $('#konfirmasiStatusBtn').attr('href', `config/update_status.php?id=${id}&status=${newStatus}`);

        // Tampilkan modal
        $('#statusModal').modal('show');
    }

    function showCicilanModal(id, cicilanKe, totalCicilan, nominal, tenggat) {
        console.log('Parameters received:', id, cicilanKe, totalCicilan, nominal, tenggat);

        // Convert nominal ke number jika perlu
        nominal = parseFloat(nominal) || 0;

        // Set nilai ke form
        $('#penagihan_id').val(id);
        $('#cicilan_ke').val(cicilanKe);
        $('#total_cicilan').val(totalCicilan);
        // Format tampilan
        var infoText = '';
        if (cicilanKe <= totalCicilan) {
            infoText = 'Pembayaran DP ' + cicilanKe + ' dari ' + totalCicilan +
                '<br>Nominal: Rp ' + nominal.toLocaleString('id-ID') +
                '<br>Jatuh Tempo: ' + tenggat;
        } else {
            infoText = 'Pelunasan Pembayaran' +
                '<br>Nominal: Rp ' + nominal.toLocaleString('id-ID');
        }
        $('#infoCicilan').html(infoText);

        // Debugging
        console.log('Values set:', {
            id: $('#penagihan_id').val(),
            cicilanKe: $('#cicilan_ke').val(),
            totalCicilan: $('#total_cicilan').val(),
            nominal: $('#nominal').val()
        });

        $('#cicilanModal').modal('show');
    }
    function showBatalkanModal(id) {
        $('#custIdBatal').val(id);
        $('#modalBatalkan').modal('show');
    }

    function tampilkanAlasan(alasan) {
        document.getElementById('alasanText').innerText = alasan;
    }

    // Payment form validation
    $('#formCicilan').on('submit', function (e) {
        e.preventDefault(); // Mencegah pengiriman form secara default

        const cicilanKe = parseInt($('#cicilanKe').val());
        const totalCicilan = parseInt($(this).data('total-cicilan'));
        const $jumlahBayar = $('#jumlahBayar');
        const jumlahBayar = parseFloat($jumlahBayar.val());

        // Validasi form
        if (cicilanKe > totalCicilan) {
            const sisaPembayaran = parseFloat($jumlahBayar.attr('min'));

            if (jumlahBayar !== sisaPembayaran) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah pelunasan harus sesuai dengan sisa pembayaran'
                });
                return false;
            }
        } else if (!jumlahBayar || jumlahBayar <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Jumlah pembayaran harus lebih dari 0'
            });
            return false;
        }

        // Jika validasi sukses, kirim form secara manual
        this.submit(); // Kirim form jika validasi sukses
    });

    // Cancel form validation
    $('#formBatalkan').on('submit', function (e) {
        const alasanBatal = $('#alasanBatal').val().trim();

        if (!alasanBatal) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Alasan pembatalan harus diisi'
            });
            return false;
        }
    });
</script>