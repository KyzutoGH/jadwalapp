<?php
if ($menu == "Barang") {
    ?>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs" id="stockTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="stock-barang-tab" data-toggle="tab" href="#stock-barang" role="tab"
                            aria-controls="stock-barang" aria-selected="true">
                            Stock Barang
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="stock-sablon-tab" data-toggle="tab" href="#stock-sablon" role="tab"
                            aria-controls="stock-sablon" aria-selected="false">
                            Stock Sablon
                        </a>
                    </li>
                </ul>
                <div>
                    <a href="index.php?menu=CreateBarang&submenu=BarangAdd" class="btn btn-primary">
                        Tambah Barang
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="stockTabsContent">
                <div class="tab-pane fade show active" id="stock-barang" role="tabpanel" aria-labelledby="stock-barang-tab">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-masuk" data-toggle="pill" href="#masuk" role="tab">Stock
                                Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-data" data-toggle="pill" href="#data" role="tab">Data Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-keluar" data-toggle="pill" href="#keluar" role="tab">Stock
                                Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-tabs-content">
                        <!-- Tab Barang Masuk -->
                        <div class="tab-pane fade" id="masuk" role="tabpanel">
                            <div class="pt-3">
                                <table id="tabelBarangMasuk" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $data = mysqli_query($db, "SELECT * 
                                        FROM log_barang l
                                        LEFT JOIN jaket j ON l.id_jaket = j.id_jaket
                                        LEFT JOIN stiker s ON l.id_sticker = s.id_sticker
                                        WHERE l.jenis_log = 'Tambah'");
                                        while ($bm = mysqli_fetch_array($data)) {
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($bm['id_jaket'])) {
                                                        echo htmlspecialchars($bm['namabarang']);
                                                    } elseif (!empty($bm['id_sticker'])) {
                                                        echo htmlspecialchars($bm['nama'] . ' ' . $bm['bagian']);
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= "+ " . htmlspecialchars($bm['jumlah']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Data Barang -->
                        <div class="tab-pane fade show active" id="data" role="tabpanel">
                            <div class="pt-3">
                                <table id="tabelBarang" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Jenis</th>
                                            <th>NamaBarang</th>
                                            <th>Ukuran</th>
                                            <th>Harga</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $data = mysqli_query($db, "SELECT * FROM jaket");
                                        while ($b = mysqli_fetch_array($data)) {
                                            ?>
                                            <tr>
                                                <td><?= ($b['jenis'] == "Jaket") ? "JKT" . $b["id_jaket"] : "VAR" . $b['id_jaket']; ?>
                                                </td>
                                                <td><?= htmlspecialchars($b['jenis']) ?></td>
                                                <td><?= htmlspecialchars($b['namabarang']) ?></td>
                                                <td><?= htmlspecialchars($b['ukuran']) ?></td>
                                                <td><?= 'Rp ' . number_format((float) $b['harga'], 0, ',', '.') ?></td>
                                                <td><?= htmlspecialchars($b['stock']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $b['stock'] > 0 ? 'success' : 'danger' ?>">
                                                        <?= $b['stock'] > 0 ? 'Tersedia' : 'Habis' ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#modalEditBarang<?= $b['id_jaket'] ?>">
                                                            <i class="far fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-success"
                                                            onclick="showModal(<?= $b['id_jaket'] ?>, 'tambah')"
                                                            title="Tambah Stock">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="showModal(<?= $b['id_jaket'] ?>, 'kurangi')"
                                                            title="Kurangi Stock">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="modalEditBarang<?= $b['id_jaket'] ?>"
                                                        tabindex="-1" role="dialog">
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
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $b['id_jaket'] ?>">
                                                                        <div class="form-group">
                                                                            <label>Jenis</label>
                                                                            <select class="form-control" name="jenis">
                                                                                <option value="Jaket" <?= $b['jenis'] == 'Jaket' ? 'selected' : '' ?>>Jaket</option>
                                                                                <option value="Varsity"
                                                                                    <?= $b['jenis'] == 'Varsity' ? 'selected' : '' ?>>Varsity</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Nama Barang</label>
                                                                            <input type="text" class="form-control"
                                                                                name="namabarang"
                                                                                value="<?= htmlspecialchars($b['namabarang']) ?>"
                                                                                required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Harga</label>
                                                                            <input type="number" class="form-control"
                                                                                name="harga"
                                                                                value="<?= htmlspecialchars($b['harga']) ?>"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan
                                                                            Perubahan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Barang Keluar -->
                        <div class="tab-pane fade" id="keluar" role="tabpanel">
                            <div class="pt-3">
                                <table id="tabelBarangKeluar" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $data = mysqli_query($db, "SELECT * 
                                        FROM log_barang l
                                        LEFT JOIN jaket j ON l.id_jaket = j.id_jaket
                                        LEFT JOIN stiker s ON l.id_sticker = s.id_sticker
                                        WHERE l.jenis_log = 'Kurangi'");
                                        while ($bm = mysqli_fetch_array($data)) {
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($bm['id_jaket'])) {
                                                        echo htmlspecialchars($bm['namabarang']);
                                                    } elseif (!empty($bm['id_sticker'])) {
                                                        echo htmlspecialchars($bm['nama'] . ' ' . $bm['bagian']);
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= "- " . htmlspecialchars($bm['jumlah']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Stock Sablon -->
                <div class="tab-pane fade" id="stock-sablon" role="tabpanel" aria-labelledby="stock-sablon-tab">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-masuk" data-toggle="pill" href="#masuk-sablon"
                                role="tab">Sablon Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-data" data-toggle="pill" href="#data-sablon" role="tab">Data
                                Sablon</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-keluar" data-toggle="pill" href="#keluar-sablon" role="tab">Sablon
                                Keluar</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="custom-tabs-content">
                        <!-- Tab Sablon Masuk -->
                        <div class="tab-pane fade" id="masuk-sablon" role="tabpanel">
                            <div class="pt-3">
                                <table id="tabelSablonMasuk" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $data = mysqli_query($db, "SELECT * 
                                        FROM log_barang l
                                        LEFT JOIN stiker s ON l.id_sticker = s.id_sticker
                                        WHERE l.jenis_log = 'Tambah'");
                                        while ($bm = mysqli_fetch_array($data)) {
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                                                <td><?= htmlspecialchars($bm['nama'] . ' ' . $bm['bagian']) ?></td>
                                                <td><?= "+ " . htmlspecialchars($bm['jumlah']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Data Sablon -->
                        <div class="tab-pane fade show active" id="data-sablon" role="tabpanel">
                            <div class="pt-3">
                                <table id="tabelSablon" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Bagian</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $data = mysqli_query($db, "SELECT * FROM stiker");
                                        while ($s = mysqli_fetch_array($data)) {
                                            ?>
                                            <tr>
                                                <td><?= "STK" . $s["id_sticker"] ?></td>
                                                <td><?= htmlspecialchars($s['nama']) ?></td>
                                                <td><?= htmlspecialchars($s['bagian']) ?></td>
                                                <td><?= htmlspecialchars($s['stock']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $s['stock'] > 0 ? 'success' : 'danger' ?>">
                                                        <?= $s['stock'] > 0 ? 'Tersedia' : 'Habis' ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#modalEditSablon<?= $s['id_sticker'] ?>">
                                                            <i class="far fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-success"
                                                            onclick="showModalSablon(<?= $s['id_sticker'] ?>, 'tambah')"
                                                            title="Tambah Stock">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="showModalSablon(<?= $s['id_sticker'] ?>, 'kurangi')"
                                                            title="Kurangi Stock">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Modal Edit Sablon -->
                                                    <div class="modal fade" id="modalEditSablon<?= $s['id_sticker'] ?>"
                                                        tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit Data Sablon</h5>
                                                                    <button type="button" class="close" data-dismiss="modal">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="config/edit_sablon.php" method="POST">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= $s['id_sticker'] ?>">
                                                                        <div class="form-group">
                                                                            <label>Nama</label>
                                                                            <input type="text" class="form-control" name="nama"
                                                                                value="<?= htmlspecialchars($s['nama']) ?>"
                                                                                required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Bagian</label>
                                                                            <input type="text" class="form-control"
                                                                                name="bagian"
                                                                                value="<?= htmlspecialchars($s['bagian']) ?>"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan
                                                                            Perubahan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Sablon Keluar -->
                        <div class="tab-pane fade" id="keluar-sablon" role="tabpanel">
                            <div class="pt-3">
                                <table id="tabelSablonKeluar" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $data = mysqli_query($db, "SELECT * 
                                        FROM log_barang l
                                        LEFT JOIN stiker s ON l.id_sticker = s.id_sticker
                                        WHERE l.jenis_log = 'Kurangi'");
                                        while ($bm = mysqli_fetch_array($data)) {
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                                                <td><?= htmlspecialchars($bm['nama'] . ' ' . $bm['bagian']) ?></td>
                                                <td><?= "- " . htmlspecialchars($bm['jumlah']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Inisialisasi DataTables untuk semua tabel
            $('.table').DataTable({
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

            // Fungsi untuk memperbaiki ukuran kolom saat tab diubah
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });

        // Modal untuk edit stok barang
        function showModal(id_jaket, action) {
            const modalLabel = document.getElementById('stockModalLabel');
            const actionInput = document.getElementById('action');
            const idInput = document.getElementById('id_jaket');

            idInput.value = id_jaket;
            actionInput.value = action;

            if (action === 'tambah') {
                modalLabel.textContent = 'Tambah Stock';
            } else if (action === 'kurangi') {
                modalLabel.textContent = 'Kurangi Stock';
            }

            $('#stockModal').modal('show');
        }

        // Modal untuk edit stok sablon
        function showModalSablon(id_sticker, action) {
            const modalLabel = document.getElementById('stockModalLabelSablon');
            const actionInput = document.getElementById('actionSablon');
            const idInput = document.getElementById('id_sticker');

            idInput.value = id_sticker;
            actionInput.value = action;

            if (action === 'tambah') {
                modalLabel.textContent = 'Tambah Stock Sablon';
            } else if (action === 'kurangi') {
                modalLabel.textContent = 'Kurangi Stock Sablon';
            }

            $('#stockModalSablon').modal('show');
        }
    </script>
<?php } ?>