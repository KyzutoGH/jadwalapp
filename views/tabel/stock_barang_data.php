<!-- Tab Data Barang -->
<div class="tab-pane fade show active" id="data" role="tabpanel">
    <div class="pt-3">
        <table id="tabelBarang" class="tabelBarang table table-bordered table-striped" width="100%">
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
                                    onclick="showModal('barang',<?= $b['id_jaket'] ?>, 'tambah')" title="Tambah Stock">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="showModal('barang',<?= $b['id_jaket'] ?>, 'kurangi')" title="Kurangi Stock">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>

                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEditBarang<?= $b['id_jaket'] ?>" tabindex="-1" role="dialog">
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
                                                <option value="Jaket" <?= $b['jenis'] == 'Jaket' ? 'selected' : '' ?>>Jaket
                                                </option>
                                                <option value="Varsity" <?= $b['jenis'] == 'Varsity' ? 'selected' : '' ?>>
                                                    Varsity</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Barang</label>
                                            <input type="text" class="form-control" name="namabarang"
                                                value="<?= htmlspecialchars($b['namabarang']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Harga</label>
                                            <input type="number" class="form-control" name="harga"
                                                value="<?= htmlspecialchars($b['harga']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan
                                            Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </tbody>
        </table>

    </div>
</div>
<!-- Modal Stock Barang -->
<div class="modal fade" id="stockModalBarang" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockModalLabel">Update Stock Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="config/update_stock.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_jaket" name="id_jaket">
                    <input type="hidden" id="action" name="action">

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>