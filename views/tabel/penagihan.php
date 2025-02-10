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
                // Helper function for installment badge
                function generateInstallmentBadge($current, $total)
                {
                    $badgeClass = 'badge ';
                    if ($current == $total) {
                        $badgeClass .= 'badge-success';
                    } elseif ($current == 0) {
                        $badgeClass .= 'badge-secondary';
                    } else {
                        $badgeClass .= 'badge-info';
                    }

                    return sprintf(
                        '<div class="d-flex align-items-center">
                            <span class="%s" style="font-size: 0.9rem; padding: 6px 12px;">
                                <i class="fas fa-clock mr-1"></i>
                                Cicilan %d dari %d
                            </span>
                        </div>',
                        $badgeClass,
                        $current,
                        $total
                    );
                }

                // Main query
                $sql = "SELECT 
                            p.*,
                            CASE 
                                WHEN jumlah_dp = 1 THEN 
                                    CONCAT(
                                        CASE 
                                            WHEN dp1_nominal IS NOT NULL THEN 1
                                            ELSE 0
                                        END,
                                        ' dari ',
                                        1
                                    )
                                WHEN jumlah_dp = 2 THEN 
                                    CONCAT(
                                        CASE 
                                            WHEN dp2_nominal IS NOT NULL THEN 2
                                            WHEN dp1_nominal IS NOT NULL THEN 1
                                            ELSE 0
                                        END,
                                        ' dari ',
                                        2
                                    )
                                WHEN jumlah_dp = 3 THEN 
                                    CONCAT(
                                        CASE 
                                            WHEN dp3_nominal IS NOT NULL THEN 3
                                            WHEN dp2_nominal IS NOT NULL THEN 2
                                            WHEN dp1_nominal IS NOT NULL THEN 1
                                            ELSE 0
                                        END,
                                        ' dari ',
                                        3
                                    )
                            END as dp,
                            COALESCE(dp1_nominal, 0) + COALESCE(dp2_nominal, 0) + COALESCE(dp3_nominal, 0) as total_dibayar,
                            total as total_tagihan,
                            COALESCE(
                                CASE 
                                    WHEN status = '2' OR status = '3' OR status = '4' THEN tgllunas
                                    ELSE NULL
                                END,
                                ''
                            ) as tgllunas
                        FROM penagihan p
                        ORDER BY tanggal DESC";

                $result = mysqli_query($db, $sql);
                if (!$result)
                    die("Query gagal: " . mysqli_error($db));

                while ($p = mysqli_fetch_assoc($result)) {
                    // Process numeric values
                    $total_numeric = $p['total_tagihan'];
                    $total_dibayar_numeric = $p['total_dibayar'];
                    $sisaPembayaran = $total_numeric - $total_dibayar_numeric;

                    // Format display values
                    $p['total_display'] = 'Rp ' . number_format($total_numeric, 0, ',', '.');
                    $p['tanggal'] = date('d/m/Y', strtotime($p['tanggal']));
                    if (!empty($p['tgllunas'])) {
                        $p['tgllunas'] = date('d/m/Y', strtotime($p['tgllunas']));
                    }

                    // Process installment display
                    $dpDisplay = '';
                    if ($p['status'] == '4') {
                        $dpDisplay = '<span class="badge badge-success">Lunas</span>';
                    } elseif ($p['status'] == '5') {
                        $dpDisplay = '<span class="badge badge-danger">Batal</span>';
                    } else {
                        $dpParts = explode(" dari ", $p['dp']);
                        $currentCicilan = (int) $dpParts[0];
                        $totalCicilan = (int) $dpParts[1];
                        $dpDisplay = generateInstallmentBadge($currentCicilan, $totalCicilan);
                    }

                    // Calculate status and action buttons
                    $statusBadge = '';
                    $actionButton = '';

                    // Di dalam loop while untuk menampilkan data
                    switch ($p['status']) {
                        case '1': // Belum Lunas
                            $statusBadge = '<span class="badge badge-warning">Belum Lunas</span>';
                            $actionButton = '<div class="btn-group">';

                            if ($currentCicilan >= $totalCicilan && $sisaPembayaran > 0) {
                                $actionButton .= "
                <button class='btn btn-success btn-sm' 
                        onclick='showCicilanModal({$p['id']}, {$currentCicilan}, {$totalCicilan}, {$sisaPembayaran})'>
                    <i class='fas fa-check'></i> Pelunasan
                </button>";
                            } elseif ($currentCicilan < $totalCicilan) {
                                $nextCicilan = $currentCicilan + 1;
                                $actionButton .= "
                <button class='btn btn-warning btn-sm' 
                        onclick='showCicilanModal({$p['id']}, {$nextCicilan}, {$totalCicilan}, {$sisaPembayaran})'>
                    <i class='fas fa-money-bill'></i> Cicilan ke-{$nextCicilan}
                </button>";
                            }

                            $actionButton .= "
            <button class='btn btn-danger btn-sm' onclick='showBatalkanModal({$p['id']})'>
                <i class='fas fa-times'></i> Batalkan
            </button>
        </div>";
                            break;

                        case '2': // Lunas - Proses
                            $statusBadge = '<span class="badge badge-info">Lunas - Proses</span>';
                            $actionButton = "<div class='btn-group'>
            <button class='btn btn-info btn-sm' onclick='updateStatus({$p['id']}, 3)'>
                <i class='fas fa-box'></i> Barang Sudah Siap
            </button>
            <button class='btn btn-danger btn-sm' onclick='showBatalkanModal({$p['id']})'>
                <i class='fas fa-times'></i> Batalkan
            </button>
        </div>";
                            break;

                        case '3': // Lunas - Siap Diambil
                            $statusBadge = '<span class="badge badge-success">Lunas - Siap Diambil</span>';
                            $actionButton = "<button class='btn btn-success btn-sm' onclick='updateStatus({$p['id']}, 4)'>
            <i class='fas fa-hand-holding'></i> Barang Sudah Diambil
        </button>";
                            break;

                        case '4': // Selesai
                            $statusBadge = '<span class="badge badge-secondary">Selesai</span>';
                            $actionButton = "<button class='btn btn-secondary btn-sm' disabled>
            <i class='fas fa-check-circle'></i> Selesai
        </button>";
                            break;

                        case '5': // Dibatalkan
                            $statusBadge = '<span class="badge badge-danger">Dibatalkan</span>';
                            $actionButton = "<button class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#alasanModal' onclick='tampilkanAlasan(\"" . htmlspecialchars($p['alasan_batal']) . "\")'>
            <i class='fas fa-info-circle'></i> Lihat Alasan
        </button>";
                            break;

                        default:
                            $statusBadge = '<span class="badge badge-secondary">Unknown</span>';
                            $actionButton = '';
                            break;
                    }

                    // Output the table row
                    echo "<tr>
                            <td>" . htmlspecialchars($p['tanggal']) . "</td>
                            <td>" . htmlspecialchars($p['customer']) . "</td>
                            <td>" . htmlspecialchars($p['total_display']) . "</td>
                            <td>$dpDisplay</td>
                            <td>";

                    if ($p['status'] == '5') {
                        echo '<span class="badge badge-danger">Dibatalkan</span>';
                    } else {
                        if (empty($p['tgllunas'])) {
                            if ($total_dibayar_numeric > 0) {
                                echo "Rp " . number_format($sisaPembayaran, 2, ',', '.');
                            } else {
                                echo '<span class="badge badge-warning">belum membayar sepeserpun</span>';
                            }
                        } else {
                            echo $p['tgllunas'];
                        }
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

<!-- JavaScript for handling modals and forms -->
<script>
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
    $('#formCicilan').on('submit', function (e) {
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
        $('#btnKonfirmasi').off('click').on('click', function () {
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
        document.getElementById('alasanText').innerText = alasan;
    }

    // Initialize DataTable with date range and status filtering
    $(document).ready(function () {
        var table = $('#tabelPenagihan').DataTable({
            "responsive": true,
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Date range filter
        $('#tgl_mulai, #tgl_akhir').on('change', function () {
            table.draw();
        });

        // Status filter
        $('#filter_status').on('change', function () {
            table.draw();
        });

        // Custom filtering function
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
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