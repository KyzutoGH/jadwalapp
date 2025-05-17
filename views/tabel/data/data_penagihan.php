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
        function generateInstallmentBadge($current, $total, $warning = false, $status = null)
        {
            // Check if status is paid or canceled first
            if ($status == '2' || $status == '3' || $status == '4') {
                return '<span class="badge badge-success" style="font-size: 0.9rem; padding: 6px 12px;" data-toggle="tooltip" title="Pembayaran sudah lunas">
                <i class="fas fa-check-circle mr-1"></i> Sudah Lunas
            </span>';
            } else if ($status == '5') {
                return '<span class="badge badge-danger" style="font-size: 0.9rem; padding: 6px 12px;" data-toggle="tooltip" title="Pesanan dibatalkan">
                <i class="fas fa-times-circle mr-1"></i> Dibatalkan
            </span>';
            }

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
        (CASE WHEN p.dp1_status > 0 THEN 1 ELSE 0 END +
         CASE WHEN p.dp2_status > 0 THEN 1 ELSE 0 END +
         CASE WHEN p.dp3_status > 0 THEN 1 ELSE 0 END),
        ' dari ', p.jumlah_dp
    ) AS dp_status,

    -- Perbaikan untuk total_dibayar
    (CASE
        WHEN p.tgllunas IS NOT NULL THEN p.total -- Jika sudah lunas, total dibayar = total tagihan
        ELSE
            (CASE WHEN p.dp1_status > 0 THEN COALESCE(p.dp1_nominal, 0) ELSE 0 END) +
            (CASE WHEN p.dp2_status > 0 THEN COALESCE(p.dp2_nominal, 0) ELSE 0 END) +
            (CASE WHEN p.dp3_status > 0 THEN COALESCE(p.dp3_nominal, 0) ELSE 0 END)
    END) AS total_dibayar,

    -- Perbaikan untuk sisa_tagihan
    p.total - (CASE
        WHEN p.tgllunas IS NOT NULL THEN p.total -- Jika sudah lunas, total dibayar = total tagihan
        ELSE
            (CASE WHEN p.dp1_status > 0 THEN COALESCE(p.dp1_nominal, 0) ELSE 0 END) +
            (CASE WHEN p.dp2_status > 0 THEN COALESCE(p.dp2_nominal, 0) ELSE 0 END) +
            (CASE WHEN p.dp3_status > 0 THEN COALESCE(p.dp3_nominal, 0) ELSE 0 END)
    END) AS sisa_tagihan,

    p.dp1_tenggat,
    p.dp2_tenggat,
    p.dp3_tenggat,
    p.tgllunas,
    p.pelunasan, -- Menambahkan kolom pelunasan jika ingin ditampilkan

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
    p.keterangan,
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
ORDER BY p.tanggal DESC;";

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
            // Add this at the beginning of your code or in an appropriate place
            $alertMessages = []; // Array to collect alert messages
        
            // Then modify your deadline checking code like this:
            if ($p['jumlah_dp'] >= 1 && $p['dp1_status'] == 0 && !empty($p['dp1_tenggat'])) {
                $dp1Date = strtotime($p['dp1_tenggat']);
                $todayStamp = strtotime('today');

                if ($todayStamp > $dp1Date) {
                    $warning = true;
                    // Calculate days overdue
                    $daysOverdue = floor(($todayStamp - $dp1Date) / (60 * 60 * 24));
                    $alertMessages[] = [
                        'type' => 'danger',
                        'message' => "Pembayaran DP1 untuk {$p['customer']} telah lewat {$daysOverdue} hari! Tenggat: " . date('d/m/Y', $dp1Date) . " - Rp " . number_format($p['dp1_nominal'], 0, ',', '.'),
                        'id' => $p['id']
                    ];
                }
                // Check if deadline is approaching (within 3 days)
                else if ($dp1Date - $todayStamp <= 3 * 24 * 60 * 60) {
                    $daysLeft = ceil(($dp1Date - $todayStamp) / (60 * 60 * 24));
                    $alertMessages[] = [
                        'type' => 'warning',
                        'message' => "Pembayaran DP1 untuk {$p['customer']} akan jatuh tempo dalam {$daysLeft} hari! Tenggat: " . date('d/m/Y', $dp1Date) . " - Rp " . number_format($p['dp1_nominal'], 0, ',', '.'),
                        'id' => $p['id']
                    ];
                }

                $jatuhTempoInfo[] = "DP1: " . date('d/m/Y', $dp1Date) .
                    " (Rp " . number_format($p['dp1_nominal'], 0, ',', '.') . ")";
            }

            // Do similar for DP2
            if ($p['jumlah_dp'] >= 2 && $p['dp2_status'] == 0 && !empty($p['dp2_tenggat'])) {
                $dp2Date = strtotime($p['dp2_tenggat']);
                $todayStamp = strtotime('today');

                if ($todayStamp > $dp2Date) {
                    $warning = true;
                    $daysOverdue = floor(($todayStamp - $dp2Date) / (60 * 60 * 24));
                    $alertMessages[] = [
                        'type' => 'danger',
                        'message' => "Pembayaran DP2 untuk {$p['customer']} telah lewat {$daysOverdue} hari! Tenggat: " . date('d/m/Y', $dp2Date) . " - Rp " . number_format($p['dp2_nominal'], 0, ',', '.'),
                        'id' => $p['id']
                    ];
                } else if ($dp2Date - $todayStamp <= 3 * 24 * 60 * 60) {
                    $daysLeft = ceil(($dp2Date - $todayStamp) / (60 * 60 * 24));
                    $alertMessages[] = [
                        'type' => 'warning',
                        'message' => "Pembayaran DP2 untuk {$p['customer']} akan jatuh tempo dalam {$daysLeft} hari! Tenggat: " . date('d/m/Y', $dp2Date) . " - Rp " . number_format($p['dp2_nominal'], 0, ',', '.'),
                        'id' => $p['id']
                    ];
                }

                $jatuhTempoInfo[] = "DP2: " . date('d/m/Y', $dp2Date) .
                    " (Rp " . number_format($p['dp2_nominal'], 0, ',', '.') . ")";
            }

            // And for DP3
            if ($p['jumlah_dp'] == 3 && $p['dp3_status'] == 0 && !empty($p['dp3_tenggat'])) {
                $dp3Date = strtotime($p['dp3_tenggat']);
                $todayStamp = strtotime('today');

                if ($todayStamp > $dp3Date) {
                    $warning = true;
                    $daysOverdue = floor(($todayStamp - $dp3Date) / (60 * 60 * 24));
                    $alertMessages[] = [
                        'type' => 'danger',
                        'message' => "Pembayaran DP3 untuk {$p['customer']} telah lewat {$daysOverdue} hari! Tenggat: " . date('d/m/Y', $dp3Date) . " - Rp " . number_format($p['dp3_nominal'], 0, ',', '.'),
                        'id' => $p['id']
                    ];
                } else if ($dp3Date - $todayStamp <= 3 * 24 * 60 * 60) {
                    $daysLeft = ceil(($dp3Date - $todayStamp) / (60 * 60 * 24));
                    $alertMessages[] = [
                        'type' => 'warning',
                        'message' => "Pembayaran DP3 untuk {$p['customer']} akan jatuh tempo dalam {$daysLeft} hari! Tenggat: " . date('d/m/Y', $dp3Date) . " - Rp " . number_format($p['dp3_nominal'], 0, ',', '.'),
                        'id' => $p['id']
                    ];
                }

                $jatuhTempoInfo[] = "DP3: " . date('d/m/Y', $dp3Date) .
                    " (Rp " . number_format($p['dp3_nominal'], 0, ',', '.') . ")";
            }

            $dpDisplay = generateInstallmentBadge($currentCicilan, $totalCicilan, $warning, $p['status']);
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
                    $jatuhTempoDisplay = '';
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
                    $jatuhTempoDisplay = '';
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
                    $jatuhTempoDisplay = '';
                    $statusBadge = '<span class="badge badge-secondary">Selesai</span>';
                    $actionButton = '<div class="text-muted">';
                    break;

                case '5': // Dibatalkan
                    $jatuhTempoDisplay = '';
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
            data-keterangan='%s'
            data-kontak='%s'
            data-dp1='%s'
            data-dp2='%s'
            data-dp3='%s'
            data-pelunasan='%s'
            data-dp1status='%d'
            data-dp2status='%d'
            data-dp3status='%d'
            data-tenggat1='%s'
            data-tenggat2='%s'
            data-tenggat3='%s'
            data-tglpelunasan='%s'
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
                htmlspecialchars($p['keterangan'] ?? '-', ENT_QUOTES),
                htmlspecialchars($p['kontak'] ?? '-', ENT_QUOTES),
                htmlspecialchars($p['dp1_nominal'] ?? '0', ENT_QUOTES),
                htmlspecialchars($p['dp2_nominal'] ?? '0', ENT_QUOTES),
                htmlspecialchars($p['dp3_nominal'] ?? '0', ENT_QUOTES),
                htmlspecialchars($p['pelunasan'] ?? '0', ENT_QUOTES),
                $p['dp1_status'] ?? 0,
                $p['dp2_status'] ?? 0,
                $p['dp3_status'] ?? 0,
                htmlspecialchars(!empty($p['dp1_tenggat']) ? date('d/m/Y', strtotime($p['dp1_tenggat'])) : '-', ENT_QUOTES),
                htmlspecialchars(!empty($p['dp2_tenggat']) ? date('d/m/Y', strtotime($p['dp2_tenggat'])) : '-', ENT_QUOTES),
                htmlspecialchars(!empty($p['dp3_tenggat']) ? date('d/m/Y', strtotime($p['dp3_tenggat'])) : '-', ENT_QUOTES),
                htmlspecialchars(!empty($p['tgllunas']) ? date('d/m/Y', strtotime($p['tgllunas'])) : '-', ENT_QUOTES),
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
        // After your while loop ends, add this to display the alerts:
        if (!empty($alertMessages)) {
            echo '<div class="alert-container" style="margin-top: 20px;">';

            foreach ($alertMessages as $alert) {
                echo '<div class="alert alert-' . $alert['type'] . ' alert-dismissible fade show" role="alert">
            <strong>' . ($alert['type'] == 'danger' ? '⚠️ PEMBAYARAN TERLAMBAT!' : '⚠️ SEGERA JATUH TEMPO!') . '</strong> 
            ' . $alert['message'] . '
            <div class="mt-2">
                <a href="https://wa.me/62' . $p['kontak'] . '" class="btn btn-sm btn-outline-dark" target="_blank">
                    <i class="fab fa-whatsapp"></i> Hubungi Customer
                </a>
                <button class="btn btn-sm btn-outline-' . ($alert['type'] == 'danger' ? 'danger' : 'warning') . '" onclick="showCicilanModal(' . $alert['id'] . ')">
                    <i class="fas fa-money-bill"></i> Proses Pembayaran
                </button>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
            }

            echo '</div>';
        }
        ?>
    </tbody>
</table>