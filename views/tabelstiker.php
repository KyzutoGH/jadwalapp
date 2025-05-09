<?php
if ($menu == "Stiker") {
    ?>
    <div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h3 class="card-title mb-0 mr-4">
                    <a href="index.php?menu=DataBarang" class="text-dark">Data Barang</a>
                </h3>
                <h3 class="card-title mb-0">
                    <a href="tabelstiker.php" class="text-secondary">Stock Sablon</a>
                </h3>
            </div>
            <div>
                <a href="index.php?menu=CreateBarang&submenu=BarangAdd" class="btn btn-primary">
                    Tambah Barang
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="tab-masuk" data-toggle="pill" href="#masuk" role="tab">Barang Masuk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="tab-data" data-toggle="pill" href="#data" role="tab">Data Barang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-keluar" data-toggle="pill" href="#keluar" role="tab">Barang Keluar</a>
            </li>
        </ul>
    </div>
</div>

            <div class="tab-content" id="custom-tabs-content">
                <!-- Tab Stiker Masuk -->
                <div class="tab-pane fade" id="masuk" role="tabpanel">
                    <div class="pt-3">
                        <table id="tabelStikerMasuk" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Stiker</th>
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
                                                echo htmlspecialchars($bm['jenis'] . ' ' . $bm['namabarang']);
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

                <!-- Tab Data Stiker -->
                <div class="tab-pane fade show active" id="data" role="tabpanel">
                    <div class="pt-3">
                        <table id="tabelStiker" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Jenis</th>
                                    <th>namabarang</th>
                                    <th>Ukuran</th>
                                    <th>Harga</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $data = mysqli_query($db, "select * from jaket");
                                while ($b = mysqli_fetch_array($data)) {
                                    ?>
                                    <tr>
                                        <td><?php echo ($b['jenis'] == "Jaket") ? "JKT" . $b["id_jaket"] : "VAR" . $b['id_jaket']; ?>
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
                                                    data-target="#modalEditStiker<?= $b['id_jaket'] ?>">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success"
                                                    onclick="showModal(<?= $b['id_jaket'] ?>, 'tambah')" title="Tambah Stock">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="showModal(<?= $b['id_jaket'] ?>, 'kurangi')" title="Kurangi Stock">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>

                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="modalEditBarang<?= $b['id_jaket'] ?>" tabindex="-1"
                                                role="dialog">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Data Stiker</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="config/edit_stiker.php" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?= $b['id_jaket'] ?>">
                                                                <div class="form-group">
                                                                    <label>Jenis</label>
                                                                    <select class="form-control" name="jenis">
                                                                        <option value="Jaket" <?= $b['jenis'] == 'Jaket' ? 'selected' : '' ?>>Jaket</option>
                                                                        <option value="Varsity" <?= $b['jenis'] == 'Varsity' ? 'selected' : '' ?>>Varsity</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Nama Stiker</label>
                                                                    <input type="text" class="form-control" name="namabarang"
                                                                        value="<?= htmlspecialchars($b['namabarang']) ?>"
                                                                        required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Harga</label>
                                                                    <input type="number" class="form-control" name="harga"
                                                                        value="<?= htmlspecialchars($b['harga']) ?>" required>
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
                                            <div class="modal fade" id="stockModal" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="stockModalLabel">Update Stock</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="config/update_stock.php" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_jaket" id="id_jaket">
                                                                <input type="hidden" name="action" id="action">
                                                                <div class="form-group">
                                                                    <label>Jumlah</label>
                                                                    <input type="number" class="form-control" name="jumlah"
                                                                        min="1" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Simpan</button>
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

                <!-- Tab Stiker Keluar -->
                <div class="tab-pane fade" id="keluar" role="tabpanel">
                    <div class="pt-3">
                        <table id="tabelStikerKeluar" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Stiker</th>
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
                                                echo htmlspecialchars($bm['jenis'] . ' ' . $bm['namabarang']);
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
            $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });

        // Modal untuk edit stok
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
    </script>
<?php } ?>