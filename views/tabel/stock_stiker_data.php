<!-- tabel/stock_stiker_data.php -->
<div class="tab-pane fade show active" id="stiker-data" role="tabpanel">
    <div class="pt-3">
        <table id="tabelStiker" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Bagian</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($db, "SELECT * FROM stiker");
                while ($b = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($b['nama']) ?></td>
                        <td><?= htmlspecialchars($b['bagian']) ?></td>
                        <td><?= htmlspecialchars($b['stock']) ?></td>
                        <td>
                            <span class="badge badge-<?= $b['stock'] > 0 ? 'success' : 'danger' ?>">
                                <?= $b['stock'] > 0 ? 'Tersedia' : 'Habis' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#modalEditStiker<?= $b['id_sticker'] ?>">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-success"
                                    onclick="showModal('stiker', <?= $b['id_sticker'] ?>, 'tambah')" title="Tambah Stock">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="showModal('stiker', <?= $b['id_sticker'] ?>, 'kurangi')" title="Kurangi Stock">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEditStiker<?= $b['id_sticker'] ?>" tabindex="-1" role="dialog">
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
                                                <input type="hidden" name="id" value="<?= $b['id_sticker'] ?>">
                                                <div class="form-group">
                                                    <label>Nama Stiker</label>
                                                    <input type="text" class="form-control" name="nama"
                                                        value="<?= htmlspecialchars($b['nama']) ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Bagian</label>
                                                    <input type="text" class="form-control" name="bagian"
                                                        value="<?= htmlspecialchars($b['bagian']) ?>" required>
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