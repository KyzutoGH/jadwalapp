<div class="pt-3">
    <table id="tabelBarangJadi" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Jaket</th>
                <th>Stiker</th>
                <th>Stock</th>
                <th>Gambar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk mengambil data barang_jadi dan menggabungkan stiker dalam satu kolom
            $query = "
                SELECT bj.*, 
                       j.namabarang AS nama_jaket, 
                       bj.gambar, 
                       GROUP_CONCAT(CONCAT(s.nama, ' (', s.bagian, ')') SEPARATOR '\n') AS daftar_stiker
                FROM barang_jadi bj
                LEFT JOIN jaket j ON bj.id_jaket = j.id_jaket
                LEFT JOIN stiker s ON bj.id_sticker = s.id_sticker
                GROUP BY bj.id_barang
            ";

            $data = mysqli_query($db, $query);

            while ($bj = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?= "BJ" . $bj["id_barang"] ?></td>
                    <td><?= htmlspecialchars($bj['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($bj['nama_jaket']) ?></td>
                    <td><?= nl2br(htmlspecialchars($bj['daftar_stiker'])) ?></td>
                    <!-- Menampilkan stiker dengan baris baru -->
                    <td><?= htmlspecialchars($bj['stock']) ?></td>
                    <td>
                        <img src="uploads/<?= htmlspecialchars($bj['gambar']) ?>" width="80" height="80"
                            alt="Gambar Produk">
                    </td>
                    <td>
                        <span class="badge badge-<?= $bj['stock'] > 0 ? 'success' : 'danger' ?>">
                            <?= $bj['stock'] > 0 ? 'Tersedia' : 'Habis' ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#modalEditBarangJadi<?= $bj['id_barang'] ?>">
                                <i class="far fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-success"
                                onclick="showModalBarangJadi(<?= $bj['id_barang'] ?>, 'tambah')" title="Tambah Stock">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-danger"
                                onclick="showModalBarangJadi(<?= $bj['id_barang'] ?>, 'kurangi')" title="Kurangi Stock">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>

                        <!-- Modal Edit Barang Jadi -->
                        <div class="modal fade" id="modalEditBarangJadi<?= $bj['id_barang'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Barang Jadi</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="config/edit_barang_jadi.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $bj['id_barang'] ?>">
                                            <div class="form-group">
                                                <label>Nama Produk</label>
                                                <input type="text" class="form-control" name="nama_produk"
                                                    value="<?= htmlspecialchars($bj['nama_produk']) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Jaket</label>
                                                <select class="form-control" name="id_jaket" required>
                                                    <?php
                                                    $jaket_query = mysqli_query($db, "SELECT * FROM jaket");
                                                    while ($jaket = mysqli_fetch_array($jaket_query)) {
                                                        $selected = ($jaket['id_jaket'] == $bj['id_jaket']) ? 'selected' : '';
                                                        echo "<option value='" . $jaket['id_jaket'] . "' " . $selected . ">" .
                                                            htmlspecialchars($jaket['namabarang']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Upload Gambar</label>
                                                <input type="file" class="form-control" name="gambar">
                                                <small>Format: JPG, PNG, Max 2MB</small>
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
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    function showModalBarangJadi(id, action) {
        $('#id_barang').val(id);
        $('#actionBarangJadi').val(action);
        $('#stockModalBarangJadi').modal('show');
    }
</script>