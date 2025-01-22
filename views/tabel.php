<?php
// data.php - Menyimpan data statis
class DataRepository
{
    public static function getSchools()
    {
        return [
            ['id' => 1, 'nama' => 'SMA Negeri 1', 'alamat' => 'Jl. Pendidikan No. 1', 'telepon' => '021-1234567', 'email' => 'sman1@edu.com'],
            ['id' => 2, 'nama' => 'SMK Negeri 1', 'alamat' => 'Jl. Kejuruan No. 2', 'telepon' => '021-7654321', 'email' => 'smkn1@edu.com']
        ];
    }

    public static function getContacts()
    {
        return [
            ['id' => 1, 'sekolah' => 'SMA Negeri 1', 'kelas' => 'X-1', 'nama' => 'Budi Santoso', 'hp' => '081234567890', 'jabatan' => 'Wali Kelas'],
            ['id' => 2, 'sekolah' => 'SMK Negeri 1', 'kelas' => 'XI-2', 'nama' => 'Ani Wijaya', 'hp' => '087654321098', 'jabatan' => 'Guru BK']
        ];
    }

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
            <h3 class="card-title">Data Master</h3>
            <div class="float-right">
                <a href="index.php?menu=Create&submenu=ContactAdd" class="btn btn-<?= ($submenu == 'ContactAdd') ? 'primary' : 'secondary' ?>">
                    Tambah Contact Person
                </a>
                <a href="index.php?menu=Tabel&submenu=Sekolah" class="btn btn-<?= ($submenu == 'Sekolah') ? 'primary' : 'secondary' ?>">
                    Data Sekolah
                </a>
                <a href="index.php?menu=Tabel&submenu=Contact" class="btn btn-<?= ($submenu == 'Contact') ? 'primary' : 'secondary' ?>">
                    Data Contact Person
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if ($submenu == 'Sekolah'): ?>
                <table id="tabelSekolah" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sekolah</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sekolah = DataRepository::getSchools();
                        foreach ($sekolah as $index => $s): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($s['nama']) ?></td>
                                <td><?= htmlspecialchars($s['alamat']) ?></td>
                                <td><?= htmlspecialchars($s['telepon']) ?></td>
                                <td><?= htmlspecialchars($s['email']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editSekolah(<?= $s['id'] ?>)">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="hapusSekolah(<?= $s['id'] ?>)">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php elseif ($submenu == 'Contact'): ?>
                <table id="tabelContact" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sekolah</th>
                            <th>Kelas</th>
                            <th>Nama Contact Person</th>
                            <th>No. HP</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contacts = DataRepository::getContacts();
                        foreach ($contacts as $index => $c): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($c['sekolah']) ?></td>
                                <td><?= htmlspecialchars($c['kelas']) ?></td>
                                <td><?= htmlspecialchars($c['nama']) ?></td>
                                <td><?= htmlspecialchars($c['hp']) ?></td>
                                <td><?= htmlspecialchars($c['jabatan']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editContact(<?= $c['id'] ?>)">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="hapusContact(<?= $c['id'] ?>)">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <div class="alert alert-info">Silahkan pilih menu di atas</div>
            <?php endif; ?>
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
                                $currentCicilan = (int)$dpParts[0];
                                $totalCicilan = (int)$dpParts[1];

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

<!-- Script initialization -->
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables untuk semua tabel yang ada
        ['#tabelSekolah', '#tabelContact', '#tabelPenagihan'].forEach(function(tableId) {
            if ($(tableId).length) {
                $(tableId).DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            }
        });
    });

    // Fungsi-fungsi CRUD
    function editSekolah(id) {
        alert('Edit sekolah dengan ID: ' + id);
        // Implementasi edit sekolah
    }

    function hapusSekolah(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data sekolah ini?')) {
            alert('Hapus sekolah dengan ID: ' + id);
            // Implementasi hapus sekolah
        }
    }

    function editContact(id) {
        alert('Edit contact dengan ID: ' + id);
        // Implementasi edit contact
    }

    function hapusContact(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data contact person ini?')) {
            alert('Hapus contact dengan ID: ' + id);
            // Implementasi hapus contact
        }
    }
</script>
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

<!-- Add these JavaScript functions -->
<script>
    function showBatalkanModal(index) {
        $('#custIdBatal').val(index);
        $('#alasanBatal').val('');
        $('#modalBatalkan').modal('show');
    }

    function batalkanPesanan() {
        const alasan = $('#alasanBatal').val().trim();
        if (!alasan) {
            Swal.fire({
                title: 'Error!',
                text: 'Alasan pembatalan harus diisi',
                icon: 'error'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pembatalan',
            text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you would normally update the database
                Swal.fire(
                    'Dibatalkan!',
                    'Pesanan telah dibatalkan.',
                    'success'
                ).then(() => {
                    $('#modalBatalkan').modal('hide');
                    location.reload();
                });
            }
        });
    }

    function showAlasanBatal(alasan) {
        Swal.fire({
            title: 'Alasan Pembatalan',
            text: alasan,
            icon: 'info'
        });
    }
</script>