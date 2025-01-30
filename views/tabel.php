<?php
$menu = $_GET['menu'] ?? '';
$submenu = $_GET['submenu'] ?? '';

if ($menu == "Tabel") { ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Dies Natalis Sekolah Blitar dan Sekitarnya</h3>
            <div class="float-right">
                <a href="index.php?menu=Create&submenu=ContactAdd"
                    class="btn btn-<?= ($submenu == 'ContactAdd') ? 'primary' : 'secondary' ?>">
                    Tambah Data
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="tabelSekolah" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Sekolah</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th>Pemilik Kontak</th>
                        <th>Jabatan</th>
                        <th>Tanggal DN</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $data = mysqli_query($db, "select * from datadn");
                    while ($d = mysqli_fetch_array($data)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($d['nama_sekolah']) ?></td>
                            <td><?= htmlspecialchars($d['alamat']) ?></td>
                            <td><a href="<?php if ($d['jenis'] == "Whatsapp") {
                                echo "tel:" . $d['nomor'];
                            } else if ($d['jenis'] == "Instagram") {
                                echo "https://www.instagram.com/" . $d['nomor'] . "/";
                            } else {
                                echo "fa-question-circle";
                            } ?>"><?= htmlspecialchars($d['nomor']) ?></a></td>
                            <td><?= htmlspecialchars($d['pemilik_kontak']) ?></a></td>
                            <td><?= htmlspecialchars($d['jabatan']) ?></a></td>
                            <td>
                                <?php
                                $date = $d['tanggal_dn']; // Format: DD-MM
                                list($day, $month) = explode('-', $date); // Memisahkan hari dan bulan
                                $months = [
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
                                $month_string = $months[$month];
                                echo htmlspecialchars("$day $month_string");
                                ?>
                            </td>
                            <td><span class=" badge <?php if ($d['status'] == 0) {
                                echo "badge-success";
                            } else {
                                echo "badge-danger";
                            } ?>"><?php if ($d['status'] == 0) {
                                 echo "Kontak Aktif";
                             } else if ($d['status'] == 1) {
                                 echo "Kontak Belum Dihubungi";
                             } else {
                                 echo "Kontak Tidak Aktif";
                             } ?></span></td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#modalEdit<?= $d['id'] ?>">
                                        <i class="far fa-edit"></i>
                                    </button>

                                    <div class="modal fade" id="modalEdit<?= $d['id'] ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Data</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="config/edit_kontak.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Nama Sekolah</label>
                                                                    <input type="text" class="form-control" name="nama_sekolah"
                                                                        value="<?= htmlspecialchars($d['nama_sekolah']) ?>"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Alamat</label>
                                                                    <input type="text" class="form-control" name="alamat"
                                                                        value="<?= htmlspecialchars($d['alamat']) ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Nomor Kontak</label>
                                                                    <input type="text" class="form-control" name="nomor"
                                                                        value="<?= htmlspecialchars($d['nomor']) ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Pemilik Kontak</label>
                                                                    <input type="text" class="form-control"
                                                                        name="pemilik_kontak"
                                                                        value="<?= htmlspecialchars($d['pemilik_kontak']) ?>"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Jabatan</label>
                                                                    <input type="text" class="form-control" name="jabatan"
                                                                        value="<?= htmlspecialchars($d['jabatan']) ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Tanggal Dies Natalis (DD-MM)</label>
                                                                    <input type="text" class="form-control" name="tanggal_dn"
                                                                        value="<?= htmlspecialchars($d['tanggal_dn']) ?>"
                                                                        required placeholder="DD-MM">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Status Kontak</label>
                                                                    <select class="form-control" name="status">
                                                                        <option value="0" <?= $d['status'] == 0 ? 'selected' : '' ?>>Kontak Aktif</option>
                                                                        <option value="1" <?= $d['status'] == 1 ? 'selected' : '' ?>>Kontak Belum Dihubungi
                                                                        </option>
                                                                        <option value="2" <?= $d['status'] == 2 ? 'selected' : '' ?>>Kontak Tidak Aktif
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="hapusSekolahForm" action="./config/hapus_kontak.php" method="POST"
                                        style="display:none;">
                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                    </form>
                                    <button class="btn btn-sm btn-danger" onclick="hapusSekolah(<?= $d['id'] ?>)" title="Hapus">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} else if ($menu == "Penagihan") { ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Penagihan</h3>
                <div class="float-right">
                    <a href="index.php?menu=Create&submenu=Penagihan" class="btn btn-primary">
                        Tambah Penagihan
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                        function generateInstallmentBadge($current, $total)
                        {
                            $badgeClass = 'badge ';

                            // Determine badge color based on progress
                            if ($current == $total) {
                                $badgeClass .= 'badge-success'; // Completed all installments
                            } elseif ($current == 0) {
                                $badgeClass .= 'badge-secondary'; // No installments yet
                            } else {
                                $badgeClass .= 'badge-info'; // In progress
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
                            // Keep numeric values for calculations
                            $total_numeric = $p['total_tagihan'];
                            $total_dibayar_numeric = $p['total_dibayar'];
                            $sisaPembayaran = $total_numeric - $total_dibayar_numeric;

                            // Format for display
                            $p['total_display'] = 'Rp ' . number_format($total_numeric, 0, ',', '.');
                            $p['tanggal'] = date('d/m/Y', strtotime($p['tanggal']));
                            if (!empty($p['tgllunas'])) {
                                $p['tgllunas'] = date('d/m/Y', strtotime($p['tgllunas']));
                            }

                            $dpDisplay = '';
                            if ($p['status'] == 4) { // Lunas
                                $dpDisplay = '<span class="badge badge-success">Lunas</span>';
                            } elseif ($p['status'] == 5) { // Dibatalkan
                                $dpDisplay = '<span class="badge badge-danger">Batal</span>';
                            } else {
                                // Tampilkan informasi cicilan menggunakan fungsi generateInstallmentBadge
                                $dpParts = explode(" dari ", $p['dp']);
                                $currentCicilan = (int) $dpParts[0];
                                $totalCicilan = (int) $dpParts[1];
                                $dpDisplay = generateInstallmentBadge($currentCicilan, $totalCicilan);
                            }

                            $dpParts = explode(" dari ", $p['dp']);
                            $currentCicilan = (int) $dpParts[0];
                            $totalCicilan = (int) $dpParts[1];

                            $statusBadge = '';
                            $actionButton = '';
                            // Hitung total pembayaran yang sudah dilakukan
                            $totalPembayaran = $p['dp1_nominal'] + $p['dp2_nominal'] + $p['dp3_nominal'];

                            // Hitung sisa pembayaran
                            $sisaPembayaran = $p['total'] - $totalPembayaran;

                            // Tentukan jumlah cicilan yang sudah dilakukan
                            $currentCicilan = 0;
                            if ($p['dp1_nominal'] > 0)
                                $currentCicilan++;
                            if ($p['dp2_nominal'] > 0)
                                $currentCicilan++;
                            if ($p['dp3_nominal'] > 0)
                                $currentCicilan++;

                            // Tentukan total cicilan maksimal berdasarkan jumlah_dp
                            $totalCicilan = $p['jumlah_dp'];

                            // Tentukan status badge
                            switch ($p['status']) {
                                case '1': // Belum Lunas
                                    $statusBadge = '<span class="badge badge-warning">Belum Lunas</span>';
                                    $actionButton = '<div class="btn-group">';

                                    // Jika cicilan sudah mencapai jumlah_dp dan sisa pembayaran > 0, tampilkan tombol pelunasan
                                    if ($currentCicilan >= $totalCicilan && $sisaPembayaran > 0) {
                                        $actionButton .= "
            <button class='btn btn-success btn-sm' onclick='showCicilanModal({$p['id']}, {$currentCicilan}, {$totalCicilan}, {$sisaPembayaran})'>
                <i class='fas fa-check'></i> Pelunasan
            </button>";
                                    }
                                    // Jika cicilan belum mencapai jumlah_dp, tampilkan tombol cicilan berikutnya
                                    elseif ($currentCicilan < $totalCicilan) {
                                        $nextCicilan = $currentCicilan + 1;
                                        $actionButton .= "
            <button class='btn btn-warning btn-sm' onclick='showCicilanModal({$p['id']}, {$nextCicilan}, {$totalCicilan}, {$sisaPembayaran})'>
                <i class='fas fa-money-bill'></i> Cicilan ke-{$nextCicilan}
            </button>";
                                    }

                                    // Tombol batalkan
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
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($p['tanggal']) ?></td>
                                <td><?= htmlspecialchars($p['customer']) ?></td>
                                <td><?= htmlspecialchars($p['total_display']) ?></td>
                            <?php if ($p['status'] == '5'): // If canceled ?>
                                    <td colspan="3">
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    </td>
                            <?php else: // Not canceled ?>
                                    <td><?= $dpDisplay ?></td>
                                    <td>
                                        <?php
                                        if (empty($p['tgllunas'])) {
                                            $totalPembayaran = $p['dp1_nominal'] + $p['dp2_nominal'] + $p['dp3_nominal'];
                                            $sisaPembayaran = $p['total'] - $totalPembayaran;

                                            if ($totalPembayaran > 0) {
                                                echo "Rp " . number_format($sisaPembayaran, 2, ',', '.');
                                            } else {
                                                echo '<span class="badge badge-warning">belum membayar sepeserpun</span>';
                                            }
                                        } else {
                                            echo $p['tgllunas'];
                                        }
                                        ?>
                                    </td>
                                    <td><?= $statusBadge ?></td>
                            <?php endif; ?>
                                <td><?= $actionButton ?></td>
                            </tr>
                    <?php } ?>
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

        <!-- Modal Batalkan -->
        <div class="modal fade" id="modalBatalkan" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Batalkan Pesanan</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formBatalkan" action="config/batalkan_pesanan.php" method="GET">
                            <input type="hidden" id="custIdBatal" name="id">
                            <div class="form-group">
                                <label for="alasanBatal">Alasan Pembatalan:</label>
                                <textarea class="form-control" id="alasanBatal" name="alasan" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Alasan Batal -->
        <div class="modal fade" id="alasanModal" tabindex="-1" role="dialog" aria-labelledby="alasanModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="alasanModalLabel">Alasan Pembatalan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="alasanText"></p> <!-- Tempat untuk menampilkan alasan -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function showCicilanModal(id, cicilanKe, totalCicilan, sisaPembayaran) {
                $('#custId').val(id);
                $('#cicilanKe').val(cicilanKe);

                // Set tanggal otomatis ke hari ini
                const today = new Date().toISOString().split('T')[0];
                $('#tanggalPembayaran').val(today);

                // Logic for payment amount field
                const $jumlahBayar = $('#jumlahBayar');

                if (cicilanKe > totalCicilan) {
                    // Pelunasan (after all installments)
                    $jumlahBayar.val(sisaPembayaran);
                    $jumlahBayar.prop('readonly', true);
                    $('.modal-title').text('Form Pelunasan');
                } else if (cicilanKe === totalCicilan && sisaPembayaran > 0) {
                    // If it's the third payment and there's remaining balance, it's pelunasan
                    $jumlahBayar.val(sisaPembayaran);
                    $jumlahBayar.prop('readonly', true);
                    $('.modal-title').text('Form Pelunasan');
                } else {
                    // Regular installments (cicilan 1-2)
                    $jumlahBayar.val('');
                    $jumlahBayar.prop('readonly', false);
                    $('.modal-title').text(`Form Cicilan ke-${cicilanKe}`);
                }

                // Show the modal
                $('#modalCicilan').modal('show');
            }
            function showBatalkanModal(id) {
                $('#custIdBatal').val(id);
                $('#modalBatalkan').modal('show');
            }

            function updateStatus(id, newStatus) {
                let title, text;

                if (newStatus === 3) {
                    title = 'Konfirmasi Barang Siap';
                    text = 'Apakah barang sudah selesai diproduksi dan siap diambil?';
                } else if (newStatus === 4) {
                    title = 'Konfirmasi Pengambilan';
                    text = 'Apakah barang sudah diambil oleh customer?';
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Benar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `config/update_status.php?id=${id}&status=${newStatus}`;
                    }
                });
            }

            function tampilkanAlasan(alasan) {
                // Isi teks alasan ke dalam modal
                document.getElementById('alasanText').innerText = alasan;
            }
        </script>
<?php } ?>