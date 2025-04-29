<!-- Card Header -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Penagihan</h3>
        <div class="float-right">
            <a href="index.php?menu=Create&submenu=Penagihan" class="btn btn-primary">
                Tambah Penagihan
            </a>
        </div>
    </div>

    <!-- Card Body with Filters -->
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Filter Tanggal Mulai:</label>
                    <input type="date" id="tgl_mulai" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Filter Tanggal Akhir:</label>
                    <input type="date" id="tgl_akhir" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Filter Status:</label>
                    <select id="filter_status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="Belum Lunas">Belum Lunas</option>
                        <option value="Lunas - Proses">Lunas - Proses</option>
                        <option value="Lunas - Siap Diambil">Lunas - Siap Diambil</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Data Table -->
        <table id="tabelPenagihan" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>NAMA CUSTOMER</th>
                    <th>TOTAL</th>
                    <th>DP</th>
                    <th>TGL LUNAS/SISA TAGIHAN</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Helper function untuk menampilkan badge cicilan
                function generateInstallmentBadge($current, $total, $warning = false)
                {
                    $badgeClass = 'badge ';
                    if ($warning) {
                        $badgeClass .= 'badge-danger'; // Warna merah jika cicilan melewati tenggat
                    } elseif ($current == $total) {
                        $badgeClass .= 'badge-success';
                    } elseif ($current == 0) {
                        $badgeClass .= 'badge-secondary';
                    } else {
                        $badgeClass .= 'badge-info';
                    }

                    $icon = $warning ? "<i class='fas fa-exclamation-triangle mr-1'></i>" : "<i class='fas fa-clock mr-1'></i>";

                    return sprintf(
                        '<span class="%s" style="font-size: 0.9rem; padding: 6px 12px;">
                    %s Cicilan %d dari %d
                </span>',
                        $badgeClass,
                        $icon,
                        $current,
                        $total
                    );
                }

                // Query untuk mengambil data penagihan
                $sql = "SELECT 
    p.*, 
    p.dp1_tenggat, p.dp2_tenggat, p.dp3_tenggat,

    -- Progress DP
    CONCAT(
        (
            CASE WHEN dp1_status > 0 THEN 1 ELSE 0 END +
            CASE WHEN dp2_status > 0 THEN 1 ELSE 0 END +
            CASE WHEN dp3_status > 0 THEN 1 ELSE 0 END
        ), 
        ' dari ', jumlah_dp
    ) AS dp,

    -- Total dibayar (hanya yang status-nya aktif)
    (CASE WHEN dp1_status > 0 THEN dp1_nominal ELSE 0 END) +
    (CASE WHEN dp2_status > 0 THEN dp2_nominal ELSE 0 END) +
    (CASE WHEN dp3_status > 0 THEN dp3_nominal ELSE 0 END) AS total_dibayar,

    -- Total tagihan
    total AS total_tagihan,

    -- Sisa tagihan
    total - (
        (CASE WHEN dp1_status > 0 THEN dp1_nominal ELSE 0 END) +
        (CASE WHEN dp2_status > 0 THEN dp2_nominal ELSE 0 END) +
        (CASE WHEN dp3_status > 0 THEN dp3_nominal ELSE 0 END)
    ) AS sisa_tagihan,

    -- Tanggal lunas
    tgllunas

FROM penagihan p
ORDER BY tanggal DESC";

                $result = mysqli_query($db, $sql);
                if (!$result)
                    die("Query gagal: " . mysqli_error($db));

                while ($p = mysqli_fetch_assoc($result)) {
                    // Konversi nilai numerik
                    $total_numeric = $p['total_tagihan'];
                    $total_dibayar_numeric = $p['total_dibayar'];
                    $sisaPembayaran = $total_numeric - $total_dibayar_numeric;

                    // Format tampilan
                    $p['total_display'] = 'Rp ' . number_format($total_numeric, 0, ',', '.');
                    $p['tanggal'] = date('d/m/Y', strtotime($p['tanggal']));
                    if (!empty($p['tgllunas'])) {
                        $p['tgllunas'] = date('d/m/Y', strtotime($p['tgllunas']));
                    }

                    // Hitung cicilan yang telah dibayar - PERBAIKAN
                    $dpParts = explode(" dari ", $p['dp']);
                    $currentCicilan = (int) $dpParts[0];
                    $totalCicilan = (int) $dpParts[1];

                    // // Debug - untuk verifikasi
                    // echo "current=$currentCicilan, total=$totalCicilan, status={$p['status']}";
                    // Cek jika cicilan jatuh tempo dan belum dibayar
                    $today = date('Y-m-d');
                    $warning = false;
                    if (
                        ($p['jumlah_dp'] >= 1 && $p['dp1_nominal'] == 0 && !empty($p['dp1_tenggat']) && $today > $p['dp1_tenggat']) ||
                        ($p['jumlah_dp'] >= 2 && $p['dp2_nominal'] == 0 && !empty($p['dp2_tenggat']) && $today > $p['dp2_tenggat']) ||
                        ($p['jumlah_dp'] == 3 && $p['dp3_nominal'] == 0 && !empty($p['dp3_tenggat']) && $today > $p['dp3_tenggat'])
                    ) {
                        $warning = true;
                    }

                    // Tampilkan status cicilan
                    $dpDisplay = generateInstallmentBadge($currentCicilan, $totalCicilan, $warning);

                    // Status dan tombol aksi
                    $statusBadge = '';
                    $actionButton = '';

                    switch ($p['status']) {
                        case '1': // Belum Lunas
                            $statusBadge = '<span class="badge badge-warning">Belum Lunas</span>';
                            $actionButton = '<div class="btn-group">';

                            // Jika masih ada sisa cicilan
                            if ($currentCicilan < $totalCicilan) {
                                $nextCicilan = $currentCicilan + 1;
                                $actionButton .= "
                                    <button class='btn btn-warning btn-sm'
                                            onclick='showCicilanModal({$p['id']}, {$nextCicilan}, {$totalCicilan}, {$sisaPembayaran})'>
                                        <i class='fas fa-money-bill'></i> Cicilan ke-{$nextCicilan}
                                    </button>";
                            } else {
                                $actionButton .= "
    <button class='btn btn-success btn-sm'
            onclick='showCicilanModal({$p['id']}, " . ($totalCicilan + 1) . ", {$totalCicilan}, {$sisaPembayaran})'>
        <i class='fas fa-check-circle'></i> Pelunasan
    </button>";
                            }

                            // Tombol Batalkan selalu ditampilkan selama belum selesai/dibatalkan
                            $actionButton .= "
                                <button class='btn btn-danger btn-sm' onclick='showBatalkanModal({$p['id']})'>
                                    <i class='fas fa-times'></i> Batalkan
                                </button>
                                    <a href='https://wa.me/62" . $p['kontak'] . "' target='_blank' class='btn btn-info btn-sm'>
                                        <i class='fab fa-whatsapp'></i> Chat Customer
                                    </a>
                            </div>";
                            break;

                        case '2': // Lunas - Proses
                            $statusBadge = '<span class="badge badge-info">Lunas - Proses</span>';
                            $actionButton = '<div class="btn-group">
                                    <button class="btn btn-success btn-sm" onclick="updateStatus(' . $p['id'] . ', 3)">
                                        <i class="fas fa-box-open"></i> Tandai Siap Diambil
                                    </button>
                                    <a href="https://wa.me/62' . $p['kontak'] . '" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fab fa-whatsapp"></i> Chat Customer
                                    </a>
                                </div>';
                            break;

                        case '3': // Lunas - Siap Diambil
                            $statusBadge = '<span class="badge badge-success">Lunas - Siap Diambil</span>';
                            $actionButton = '<div class="btn-group">
                                <button class="btn btn-secondary btn-sm" onclick="updateStatus(' . $p['id'] . ', 4)">
                                    <i class="fas fa-check"></i> Tandai Selesai
                                </button>
                                    <a href="https://wa.me/62' . $p['kontak'] . '" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fab fa-whatsapp"></i> Chat Customer
                                    </a>
                            </div>';
                            break;

                        case '4': // Selesai
                            $statusBadge = '<span class="badge badge-secondary">Selesai</span>';
                            $actionButton = '<div class="text-muted"><i class="fas fa-check-circle"></i> Selesai</div>';
                            break;
                        case '5': // Dibatalkan
                            // 1) Badge status
                            $statusBadge = '<span class="badge badge-danger">Dibatalkan</span>';

                            // 2) Escape alasan supaya aman dipakai di single‑quote
                            //    ENT_QUOTES menangani kutip tunggal dan ganda
                            $alasanEscaped = htmlspecialchars($p['alasan_batal'], ENT_QUOTES);

                            // 3) Tombol “Lihat Alasan”
                            $actionButton = "
                                    <button class='btn btn-info btn-sm'
                                            onclick=\"tampilkanAlasan('{$alasanEscaped}')\"
                                            data-toggle='modal'
                                            data-target='#alasanModal'>
                                        <i class='fas fa-info-circle'></i> Lihat Alasan
                                    </button>
                                ";
                            break;
                    }

                    // Output row
                    echo "<tr>
                    <td>" . htmlspecialchars($p['tanggal']) . "</td>
                    <td>" . htmlspecialchars($p['customer']) . "</td>
                    <td>" . htmlspecialchars($p['total_display']) . "</td>
                    <td>$dpDisplay</td>
                    <td>";

                    if ($p['status'] == '5') {
                        echo '<span class="badge badge-danger">Dibatalkan</span>';
                    } else {
                        echo empty($p['tgllunas']) ? "Rp " . number_format($sisaPembayaran, 2, ',', '.') : $p['tgllunas'];
                    }

                    echo "</td>
                  <td>$statusBadge</td>
                  <td>$actionButton</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</div>

<!-- Modal Cicilan -->
<div class="modal fade" id="modalCicilan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Cicilan Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCicilan" action="config/proses_cicilan.php" method="GET">
                    <input type="hidden" id="custId" name="custId">
                    <input type="hidden" id="cicilanKe" name="cicilanKe">
                    <input type="hidden" id="tanggalPembayaran" name="tanggalPembayaran">

                    <div class="form-group">
                        <label>Jumlah Pembayaran</label>
                        <input type="number" class="form-control" id="jumlahBayar" name="jumlahBayar" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
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

<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="konfirmasiText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnKonfirmasi">Ya, Lanjutkan</button>
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

<script>
    // Form validation for cancellation
    $('#formBatalkan').on('submit', function(e) {
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
<!-- JavaScript for handling modals and forms -->
<script>
    // memanggil confirm modal dengan status 3 (Siap Diambil)
    function showSiapAmbilModal(id) {
        updateStatus(id, 3);
    }

    // memanggil confirm modal dengan status 4 (Selesai)
    function showSelesaikanModal(id) {
        updateStatus(id, 4);
    }

    function showCicilanModal(id, cicilanKe, totalCicilan, sisaPembayaran) {
        $('#custId').val(id);
        $('#cicilanKe').val(cicilanKe);

        // Set current date
        const today = new Date().toISOString().split('T')[0];
        $('#tanggalPembayaran').val(today);

        // Get form elements
        const $jumlahBayar = $('#jumlahBayar');
        const $keteranganGroup = $('.form-group:has(#keterangan)');
        const $modalTitle = $('.modal-title');
        const $submitBtn = $('#formCicilan button[type="submit"]');

        // Handle final payment case
        if (cicilanKe > totalCicilan) {
            // Setup for final payment
            $modalTitle.text('Form Pelunasan');
            $jumlahBayar.val(sisaPembayaran);
            $jumlahBayar.prop('readonly', true);
            $jumlahBayar.attr('min', sisaPembayaran);
            $jumlahBayar.attr('max', sisaPembayaran);
            $keteranganGroup.hide();
            $submitBtn.text('Konfirmasi Pelunasan');

            // Add info text
            const $infoText = $('<small>', {
                class: 'text-muted d-block mt-2',
                text: 'Jumlah pelunasan sudah ditetapkan sesuai sisa tagihan'
            });
            $jumlahBayar.after($infoText);
        } else {
            // Setup for regular installment
            $modalTitle.text(`Form Cicilan ke-${cicilanKe}`);
            $jumlahBayar.val('');
            $jumlahBayar.prop('readonly', false);
            $jumlahBayar.removeAttr('min max');
            $keteranganGroup.show();
            $submitBtn.text('Simpan Pembayaran');
            $jumlahBayar.next('small').remove();
        }

        $('#modalCicilan').modal('show');
    }

    // Form validation
    $('#formCicilan').on('submit', function(e) {
        const cicilanKe = parseInt($('#cicilanKe').val());
        const totalCicilan = parseInt($(this).data('total-cicilan'));
        const $jumlahBayar = $('#jumlahBayar');
        const jumlahBayar = parseFloat($jumlahBayar.val());

        if (cicilanKe > totalCicilan) {
            // Validate final payment amount
            const sisaPembayaran = parseFloat($jumlahBayar.attr('min'));

            if (jumlahBayar !== sisaPembayaran) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah pelunasan harus sesuai dengan sisa pembayaran'
                });
                return false;
            }
        } else if (!jumlahBayar || jumlahBayar <= 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Jumlah pembayaran harus lebih dari 0'
            });
            return false;
        }
    });

    // Status update function (continued)
    function updateStatus(id, newStatus) {
        let title = newStatus === 3 ? 'Konfirmasi Barang Siap' : 'Konfirmasi Pengambilan';
        let text = newStatus === 3 ?
            'Apakah barang sudah selesai diproduksi dan siap diambil?' :
            'Apakah barang sudah diambil oleh customer?';

        // Set teks konfirmasi
        $('#konfirmasiText').text(text);

        // Tampilkan modal
        $('#modalKonfirmasi').modal('show');

        // Set aksi saat tombol konfirmasi diklik
        $('#btnKonfirmasi').off('click').on('click', function() {
            window.location.href = `config/update_status.php?id=${id}&status=${newStatus}`;
        });
    }
    // Function to show cancellation modal
    function showBatalkanModal(id) {
        $('#custIdBatal').val(id);
        $('#modalBatalkan').modal('show');
    }

    // Function to display cancellation reason
    function tampilkanAlasan(alasan) {
        console.log("Alasan Pembatalan: " + alasan); // Tambahkan log
        document.getElementById('alasanText').innerText = alasan;
    }


    // Initialize DataTable with date range and status filtering
    $(document).ready(function() {
        var table = $('#tabelPenagihan').DataTable({
            "responsive": true,
            "order": [
                [0, "desc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Date range filter
        $('#tgl_mulai, #tgl_akhir').on('change', function() {
            table.draw();
        });

        // Status filter
        $('#filter_status').on('change', function() {
            table.draw();
        });

        // Custom filtering function
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var min = $('#tgl_mulai').val();
            var max = $('#tgl_akhir').val();
            var status = $('#filter_status').val();
            var date = data[0]; // assumes date is in column 0
            var rowStatus = data[5]; // assumes status is in column 5

            // Convert date format (from dd/mm/yyyy to yyyy-mm-dd for comparison)
            var parts = date.split('/');
            var convertedDate = parts[2] + '-' + parts[1] + '-' + parts[0];

            var dateCheck = (!min || !max || (convertedDate >= min && convertedDate <= max));
            var statusCheck = (!status || rowStatus.includes(status));

            return dateCheck && statusCheck;
        });
    });
</script>