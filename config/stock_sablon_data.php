<div class="pt-3">
    <table id="tabelBarangJadi" class="tabelBarang table table-bordered table-striped" width="100%">
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
            // Query untuk mengambil data barang_jadi, jaket, dan daftar stiker dalam 1 baris
            $query = "
    SELECT 
        ANY_VALUE(bj.id_barang) AS id_barang, 
        bj.nama_produk, 
        ANY_VALUE(bj.gambar) AS gambar, 
        ANY_VALUE(bj.stock) AS stock, 
        j.namabarang AS nama_jaket, 
        j.ukuran, 
        GROUP_CONCAT(DISTINCT CONCAT(s.nama, ' (', s.bagian, ')') ORDER BY s.nama SEPARATOR ', ') AS daftar_stiker
    FROM barang_jadi bj
    LEFT JOIN jaket j ON bj.id_jaket = j.id_jaket
    LEFT JOIN stiker s ON bj.id_sticker = s.id_sticker
    GROUP BY bj.nama_produk, j.namabarang, j.ukuran
";

            $data = mysqli_query($db, $query);

            while ($bj = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?= "BJ" . $bj["id_barang"] ?></td>
                    <td><?= htmlspecialchars($bj['nama_produk']) . " - " . htmlspecialchars($bj['ukuran']) ?></td>
                    <td><?= htmlspecialchars($bj['nama_jaket']) ?></td>
                    <td><?= nl2br(htmlspecialchars($bj['daftar_stiker'])) ?></td> <!-- Stiker dalam 1 sel -->
                    <td><?= htmlspecialchars($bj['stock']) ?></td>
                    <td>
                        <?php if (!empty($bj['gambar'])): ?>
                            <img src="uploads/<?= htmlspecialchars($bj['gambar']) ?>" width="80" height="80"
                                alt="Gambar Produk">
                        <?php else: ?>
                            <span class="text-muted">Tidak Ada Gambar</span>
                        <?php endif; ?>
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
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<!-- Modal Tambah/Kurangi Stok -->
<div class="modal fade" id="stockModalBarangJadi" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockModalLabel">Ubah Stok Barang Jadi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="config/update_stock_sablon.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_barang" id="id_barang">
                    <input type="hidden" name="action" id="actionBarangJadi">

                    <div class="form-group">
                        <label for="jumlah">Jumlah:</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1">
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

<script>
    function showModalBarangJadi(id, action) {
        $('#id_barang').val(id);
        $('#actionBarangJadi').val(action);
        $('#stockModalBarangJadi').modal('show');
    }
</script>