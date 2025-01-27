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
                <?php if ($submenu == "DataBarang") { ?>
                    <a href="index.php?menu=CreateBarang&submenu=BarangAdd" class="btn btn-primary">
                        Tambah Barang
                    </a>
                <?php } ?>
            </div>
        </div>
        <div class="card-body">
            <?php if ($submenu == "BarangMasuk") { ?>
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
WHERE l.jenis_log = 'Tambah';
");
                        while ($bm = mysqli_fetch_array($data)) {
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                                <td>
                                    <?php
                                    if (!empty($bm['id_jaket'])) {
                                        // Jika id_jaket ada isinya, ambil 'jenis'
                                        echo htmlspecialchars($bm['jenis']);
                                    } elseif (!empty($bm['id_sticker'])) {
                                        // Jika id_sticker ada isinya, ambil 'nama' + 'bagian'
                                        echo htmlspecialchars($bm['nama'] . ' ' . $bm['bagian']);
                                    } else {
                                        // Jika keduanya kosong
                                        echo '-';
                                    }
                                    ?>
                                </td>

                                <td><?= "+ " . htmlspecialchars($bm['jumlah']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <?php } else if ($submenu == "DataBarang") { ?>
                    <table id="tabelBarang" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Jenis</th>
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
                                    <td><?php if ($b['jenis'] == "Jaket") {
                                        echo "JKT" . $b["id_jaket"];
                                    } else {
                                        echo "VAR" . $b['id_jaket'];
                                    } ?></td>
                                    <td><?= htmlspecialchars($b['jenis']) ?></td>
                                    <td><?= htmlspecialchars($b['ukuran']) ?></td>
                                    <td><?= htmlspecialchars($b['harga']) ?></td>
                                    <td><?= htmlspecialchars($b['stock']) ?></td>
                                    <td><span class="badge badge-<?php if ($b['stock'] > 0) {
                                        echo 'success';
                                    } else {
                                        echo 'danger';
                                    } ?>""><?php if ($b['stock'] > 0) {
                                         echo 'Tersedia';
                                     } else {
                                         echo 'Habis';
                                     } ?></span></td>
                                    <td class=" text-center">
                                            <div class="btn-group" role="group" aria-label="Actions">
                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#modalEditBarang<?= $b['id_jaket'] ?>">
                                                    <i class="far fa-edit"></i>
                                                </button>

                                                <div class="modal fade" id="modalEditBarang<?= $b['id_jaket'] ?>" tabindex="-1"
                                                    role="dialog">
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
                                                                    <input type="hidden" name="id" value="<?= $b['id_jaket'] ?>">
                                                                    <div class="form-group">
                                                                        <label>Jenis</label>
                                                                        <select class="form-control" name="jenis">
                                                                            <option value="Jaket" <?= $b['jenis'] == 'Jaket' ? 'selected' : '' ?>>Jaket</option>
                                                                            <option value="Varsity" <?= $b['jenis'] == 'Varsity' ? 'selected' : '' ?>>Varsity</option>
                                                                        </select>
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
                                                <!-- Tombol -->
                                                <button class="btn btn-sm btn-success"
                                                    onclick="showModal(<?= $b['id_jaket'] ?>, 'tambah')" title="Tambah Stock">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="showModal(<?= $b['id_jaket'] ?>, 'kurangi')" title="Kurangi Stock">
                                                    <i class="fas fa-minus"></i>
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form id="stockForm" method="POST" action="./config/update_stock.php">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="stockModalLabel"></h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id_jaket" id="id_jaket">
                                                                    <input type="hidden" name="action" id="action">
                                                                    <div class="mb-3">
                                                                        <label for="jumlah" class="form-label">Jumlah</label>
                                                                        <input type="number" class="form-control" id="jumlah"
                                                                            name="jumlah" required min="1">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form id="hapusBarangForm<?= $b['id_jaket'] ?>" action="config/hapus_jaket.php"
                                                    method="POST" style="display:none;">
                                                    <input type="hidden" name="kode" value="<?= $b['id_jaket'] ?>">
                                                </form>
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
WHERE l.jenis_log = 'Kurangi';
");
                            while ($bm = mysqli_fetch_array($data)) {
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                                        <td>
                                        <?php
                                        if (!empty($bm['id_jaket'])) {
                                            // Jika id_jaket ada isinya, ambil 'jenis'
                                            echo htmlspecialchars($bm['jenis']);
                                        } elseif (!empty($bm['id_sticker'])) {
                                            // Jika id_sticker ada isinya, ambil 'nama' + 'bagian'
                                            echo htmlspecialchars($bm['nama'] . ' ' . $bm['bagian']);
                                        } else {
                                            // Jika keduanya kosong
                                            echo '-';
                                        }
                                        ?>
                                        </td>

                                        <td><?= "- " . htmlspecialchars($bm['jumlah']) ?></td>
                                    </tr>
                        <?php } ?>
                            </tbody>
                        </table>
            <?php } ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#tabelBarang, #tabelBarangKeluar').DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });
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

            const modal = new bootstrap.Modal(document.getElementById('stockModal'));
            modal.show();
        }
    </script>
<?php } ?>