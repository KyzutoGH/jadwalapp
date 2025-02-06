<div class="pt-3">
    <table id="tabelSablon" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
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
                                onclick="showModalSablon(<?= $s['id_sticker'] ?>, 'tambah')" title="Tambah Stock">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-danger"
                                onclick="showModalSablon(<?= $s['id_sticker'] ?>, 'kurangi')" title="Kurangi Stock">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>

                        <!-- Modal Edit Sablon -->
                        <div class="modal fade" id="modalEditSablon<?= $s['id_sticker'] ?>" tabindex="-1" role="dialog">
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
                                            <input type="hidden" name="id" value="<?= $s['id_sticker'] ?>">
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" class="form-control" name="nama"
                                                    value="<?= htmlspecialchars($s['nama']) ?>" required>
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

                        <!-- Modal Stock Sablon -->
                        <div class="modal fade" id="stockModalSablon" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="stockModalLabelSablon">Update
                                            Stock Sablon</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="config/update_stock_sablon.php" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="id_sticker" id="id_sticker">
                                            <input type="hidden" name="action" id="actionSablon">
                                            <div class="form-group">
                                                <label>Jumlah</label>
                                                <input type="number" class="form-control" name="jumlah" required min="1">
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