<?php
// data.php - Menyimpan data statis
class DataRepository
{

    public static function getPenagihan()
    {
        return [
            [
                'tanggal' => '7 Januari 2007',
                'customer' => 'Wawan',
                'total' => 'Rp. 2.500.000',
                'dp' => '1 dari 3',
                'pelunasan' => 'Rp. 2.500.000',
                'tgllunas' => '',
                'status' => '1', //1 = Belum Lunas, 2 = Lunas - Belum Siap, 3 = Lunas - Tinggal Ambil, 4 = Selesai, 5 = Dibatalkan
                'alasan_batal' => ''
            ],
            [
                'tanggal' => '17 September 2009',
                'customer' => 'Saleh',
                'total' => 'Rp. 3.000.000',
                'dp' => '2 dari 2',
                'pelunasan' => 'Rp. 2.500.000',
                'tgllunas' => '11 September 2011',
                'status' => '3',
                'alasan_batal' => ''
            ],
            [
                'tanggal' => '23 Oktober 2023',
                'customer' => 'Dedi',
                'total' => 'Rp. 4.500.000',
                'dp' => '1 dari 4',
                'pelunasan' => 'Rp. 1.125.000',
                'tgllunas' => '',
                'status' => '5',
                'alasan_batal' => 'Permintaan customer - perubahan desain'
            ],
            [
                'tanggal' => '15 November 2023',
                'customer' => 'Sinta',
                'total' => 'Rp. 1.800.000',
                'dp' => '1 dari 2',
                'pelunasan' => 'Rp. 900.000',
                'tgllunas' => '',
                'status' => '5',
                'alasan_batal' => 'Masalah teknis produksi'
            ]
        ];
    }
}

// index.php
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
    // Modifikasi pada bagian tabel penagihan
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
                            <th>TGL LUNAS</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $penagihan = DataRepository::getPenagihan();
                        foreach ($penagihan as $index => $p):
                            $statusBadge = '';
                            $actionButton = '';

                            switch ($p['status']) {
                                case '1': // Belum Lunas
                                    $statusBadge = '<span class="badge badge-warning">Belum Lunas</span>';
                                    $dpParts = explode(" dari ", $p['dp']);
                                    $currentCicilan = (int) $dpParts[0];
                                    $totalCicilan = (int) $dpParts[1];

                                    $actionButton = '<div class="btn-group">';
                                    if ($currentCicilan < $totalCicilan) {
                                        $actionButton .= '<button class="btn btn-warning btn-sm" onclick="showCicilanModal(' . $index . ', ' . ($currentCicilan + 1) . ', ' . $totalCicilan . ')">
                    <i class="fas fa-money-bill"></i> Cicilan ke-' . ($currentCicilan + 1) . '
                </button>';
                                    } else {
                                        $actionButton .= '<button class="btn btn-success btn-sm" onclick="showCicilanModal(' . $index . ', ' . ($currentCicilan + 1) . ', ' . $totalCicilan . ')">
                    <i class="fas fa-check"></i> Pelunasan
                </button>';
                                    }
                                    $actionButton .= '<button class="btn btn-danger btn-sm" onclick="showBatalkanModal(' . $index . ')">
                <i class="fas fa-times"></i> Batalkan
            </button></div>';
                                    break;

                                case '2': // Lunas - Belum Siap
                                    $statusBadge = '<span class="badge badge-info">Lunas - Proses</span>';
                                    $actionButton = '<div class="btn-group">
                <button class="btn btn-info btn-sm" onclick="updateStatus(' . $index . ', 3)">
                    <i class="fas fa-box"></i> Barang Sudah Siap
                </button>
                <button class="btn btn-danger btn-sm" onclick="showBatalkanModal(' . $index . ')">
                    <i class="fas fa-times"></i> Batalkan
                </button>
            </div>';
                                    break;

                                case '3': // Lunas - Tinggal Ambil
                                    $statusBadge = '<span class="badge badge-success">Lunas - Siap Diambil</span>';
                                    $actionButton = '<button class="btn btn-success btn-sm" onclick="updateStatus(' . $index . ', 4)">
                <i class="fas fa-hand-holding"></i> Barang Sudah Diambil
            </button>';
                                    break;

                                case '4': // Selesai
                                    $statusBadge = '<span class="badge badge-secondary">Selesai</span>';
                                    $actionButton = '<button class="btn btn-secondary btn-sm" disabled>
                <i class="fas fa-check-circle"></i> Selesai
            </button>';
                                    break;

                                case '5': // Dibatalkan
                                    $statusBadge = '<span class="badge badge-danger">Dibatalkan</span>';
                                    $actionButton = '<button class="btn btn-secondary btn-sm" onclick="showAlasanBatal(\'' . htmlspecialchars($p['alasan_batal']) . '\')">
                <i class="fas fa-info-circle"></i> Lihat Alasan
            </button>';
                                    break;
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($p['tanggal']) ?></td>
                                <td><?= htmlspecialchars($p['customer']) ?></td>
                                <td><?= htmlspecialchars($p['total']) ?></td>
                                <td><?= htmlspecialchars($p['dp']) ?></td>
                                <td><?= htmlspecialchars($p['tgllunas']) ?></td>
                                <td><?= $statusBadge ?></td>
                                <td><?= $actionButton ?></td>
                            </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Include Modal -->
    <?php include 'bagian/modal/cicilan_modal.php'; ?>

        <!-- Scripts for handling actions -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function showCicilanModal(index, cicilanKe, totalCicilan) {
                $('#custId').val(index);
                $('#cicilanKe').val(cicilanKe);
                $('#modalCicilan').modal('show');
            }

            function saveCicilan() {
                // Here you would normally save to database
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Pembayaran cicilan telah disimpan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#modalCicilan').modal('hide');
                        location.reload();
                    }
                });
            }

            function updateStatus(index, newStatus) {
                let title, text, icon;

                if (newStatus === 3) {
                    title = 'Konfirmasi Barang Siap';
                    text = 'Apakah barang sudah selesai diproduksi dan siap diambil?';
                    icon = 'question';
                } else if (newStatus === 4) {
                    title = 'Konfirmasi Pengambilan';
                    text = 'Apakah barang sudah diambil oleh customer?';
                    icon = 'question';
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Benar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Here you would normally update the database
                        Swal.fire(
                            'Berhasil!',
                            'Status telah diperbarui',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        </script>
<?php } else { ?>
        <h1>Tidak Ada</h1>
<?php } ?>
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
                <form id="formBatalkan">
                    <input type="hidden" id="custIdBatal">
                    <div class="form-group">
                        <label for="alasanBatal">Alasan Pembatalan:</label>
                        <textarea class="form-control" id="alasanBatal" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="batalkanPesanan()">Konfirmasi Pembatalan</button>
            </div>
        </div>
    </div>
</div>