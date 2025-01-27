<?php
// Data barang masuk
$barangMasuk = [
    [
        'tanggal' => '2023-01-01',
        'nama_customer' => 'Customer A',
        'total' => 'Rp. 2.000.000',
        'dp' => 'Rp. 500.000',
        'tgl_lunas' => '2023-01-10',
        'status' => 'Lunas',
        'id' => 1
    ],
    [
        'tanggal' => '2023-01-02',
        'nama_customer' => 'Customer B',
        'total' => 'Rp. 1.500.000',
        'dp' => 'Rp. 300.000',
        'tgl_lunas' => '2023-01-12',
        'status' => 'Belum Lunas',
        'id' => 2
    ]
];

// Data barang
$barang = [
    [
        'kode' => 'BRG001',
        'nama' => 'Produk A',
        'kategori' => 'Elektronik',
        'stok' => 10,
        'harga' => 'Rp. 1.500.000',
        'status' => 'Tersedia'
    ],
    [
        'kode' => 'BRG002',
        'nama' => 'Produk B',
        'kategori' => 'Aksesoris',
        'stok' => 5,
        'harga' => 'Rp. 800.000',
        'status' => 'Terbatas'
    ]
];

// Data barang keluar
$barangKeluar = [
    [
        'tanggal' => '2023-01-05',
        'nama_customer' => 'Customer C',
        'total' => 'Rp. 1.000.000',
        'status' => 'Terkirim',
        'id' => 1
    ],
    [
        'tanggal' => '2023-01-06',
        'nama_customer' => 'Customer D',
        'total' => 'Rp. 2.500.000',
        'status' => 'Belum Terkirim',
        'id' => 2
    ]
];
?>

<?php
if ($menu == "Barang") {
    ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <?php
                if ($submenu == "BarangMasuk") {
                    echo "Data Barang Masuk";
                } else if ($submenu == "DataBarang") {
                    echo "Data Barang";
                } else if ($submenu == "BarangKeluar") {
                    echo "Data Barang Keluar";
                }
                ?>
            </h3>
            <div class="float-right">
                <a href="index.php?menu=Create&submenu=Penagihan" class="btn btn-primary">
                    Tambah Penagihan
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if ($submenu == "BarangMasuk") { ?>
                <table id="tabelBarangMasuk" class="table table-bordered table-striped">
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
                        <?php foreach ($barangMasuk as $bm): ?>
                            <tr>
                                <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                                <td><?= htmlspecialchars($bm['nama_customer']) ?></td>
                                <td><?= htmlspecialchars($bm['total']) ?></td>
                                <td><?= htmlspecialchars($bm['dp']) ?></td>
                                <td><?= htmlspecialchars($bm['tgl_lunas']) ?></td>
                                <td><?= htmlspecialchars($bm['status']) ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEditBarangMasuk<?= $bm['id'] ?>">
                                            <i class="far fa-edit"></i>
                                        </button>

                                        <div class="modal fade" id="modalEditBarangMasuk<?= $bm['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data Barang Masuk</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="config/edit_barang_masuk.php" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= $bm['id'] ?>">
                                                            <div class="form-group">
                                                                <label>Tanggal</label>
                                                                <input type="date" class="form-control" name="tanggal" value="<?= htmlspecialchars($bm['tanggal']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama Customer</label>
                                                                <input type="text" class="form-control" name="nama_customer" value="<?= htmlspecialchars($bm['nama_customer']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Total</label>
                                                                <input type="text" class="form-control" name="total" value="<?= htmlspecialchars($bm['total']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>DP</label>
                                                                <input type="text" class="form-control" name="dp" value="<?= htmlspecialchars($bm['dp']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tanggal Lunas</label>
                                                                <input type="date" class="form-control" name="tgl_lunas" value="<?= htmlspecialchars($bm['tgl_lunas']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="Lunas" <?= $bm['status'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                                                                    <option value="Belum Lunas" <?= $bm['status'] == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="hapusBarangMasukForm<?= $bm['id'] ?>" action="config/hapus_barang_masuk.php" method="POST" style="display:none;">
                                            <input type="hidden" name="id" value="<?= $bm['id'] ?>">
                                        </form>
                                        <button class="btn btn-sm btn-danger" onclick="hapusBarangMasuk(<?= $bm['id'] ?>)" title="Hapus">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php } else if ($submenu == "DataBarang") { ?>
                <table id="tabelDataBarang" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>KODE</th>
                            <th>NAMA</th>
                            <th>KATEGORI</th>
                            <th>STOK</th>
                            <th>HARGA</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($barang as $b) {
                            $statusClass = $b['status'] == 'Tersedia' ? 'success' : 'warning';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($b['kode']) ?></td>
                                <td><?= htmlspecialchars($b['nama']) ?></td>
                                <td><?= htmlspecialchars($b['kategori']) ?></td>
                                <td><?= htmlspecialchars($b['stok']) ?></td>
                                <td><?= htmlspecialchars($b['harga']) ?></td>
                                <td><span class="badge badge-<?= $statusClass ?>"><?= htmlspecialchars($b['status']) ?></span></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEditBarang<?= $b['kode'] ?>">
                                            <i class="far fa-edit"></i>
                                        </button>

                                        <div class="modal fade" id="modalEditBarang<?= $b['kode'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data Barang</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="config/edit_barang.php" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="kode" value="<?= $b['kode'] ?>">
                                                            <div class="form-group">
                                                                <label>Nama Barang</label>
                                                                <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($b['nama']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Kategori</label>
                                                                <input type="text" class="form-control" name="kategori" value="<?= htmlspecialchars($b['kategori']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Stok</label>
                                                                <input type="number" class="form-control" name="stok" value="<?= htmlspecialchars($b['stok']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Harga</label>
                                                                <input type="text" class="form-control" name="harga" value="<?= htmlspecialchars($b['harga']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="Tersedia" <?= $b['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                                                    <option value="Terbatas" <?= $b['status'] == 'Terbatas' ? 'selected' : '' ?>>Terbatas</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="hapusBarangForm<?= $b['kode'] ?>" action="config/hapus_barang.php" method="POST" style="display:none;">
                                            <input type="hidden" name="kode" value="<?= $b['kode'] ?>">
                                        </form>
                                        <button class="btn btn-sm btn-danger" onclick="hapusBarang(<?= $b['kode'] ?>)" title="Hapus">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <?php } else if ($submenu == "BarangKeluar") { ?>
                <table id="tabelBarangKeluar" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>TANGGAL</th>
                            <th>NAMA CUSTOMER</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($barangKeluar as $bk): ?>
                            <tr>
                                <td><?= htmlspecialchars($bk['tanggal']) ?></td>
                                <td><?= htmlspecialchars($bk['nama_customer']) ?></td>
                                <td><?= htmlspecialchars($bk['total']) ?></td>
                                <td><?= htmlspecialchars($bk['status']) ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEditBarangKeluar<?= $bk['id'] ?>">
                                            <i class="far fa-edit"></i>
                                        </button>

                                        <div class="modal fade" id="modalEditBarangKeluar<?= $bk['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data Barang Keluar</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="config/edit_barang_keluar.php" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= $bk['id'] ?>">
                                                            <div class="form-group">
                                                                <label>Tanggal</label>
                                                                <input type="date" class="form-control" name="tanggal" value="<?= htmlspecialchars($bk['tanggal']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama Customer</label>
                                                                <input type="text" class="form-control" name="nama_customer" value="<?= htmlspecialchars($bk['nama_customer']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Total</label>
                                                                <input type="text" class="form-control" name="total" value="<?= htmlspecialchars($bk['total']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="Terkirim" <?= $bk['status'] == 'Terkirim' ? 'selected' : '' ?>>Terkirim</option>
                                                                    <option value="Belum Terkirim" <?= $bk['status'] == 'Belum Terkirim' ? 'selected' : '' ?>>Belum Terkirim</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="hapusBarangKeluarForm<?= $bk['id'] ?>" action="config/hapus_barang_keluar.php" method="POST" style="display:none;">
                                            <input type="hidden" name="id" value="<?= $bk['id'] ?>">
                                        </form>
                                        <button class="btn btn-sm btn-danger" onclick="hapusBarangKeluar(<?= $bk['id'] ?>)" title="Hapus">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabelBarang, #tabelBarangKeluar').DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });

        function hapusBarangMasuk(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                document.getElementById('hapusBarangMasukForm' + id).submit();
            }
        }

        function hapusBarang(kode) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                document.getElementById('hapusBarangForm' + kode).submit();
            }
        }

        function hapusBarangKeluar(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                document.getElementById('hapusBarangKeluarForm' + id).submit();
            }
        }
    </script>
<?php } ?>