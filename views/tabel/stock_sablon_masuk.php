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
                $data = mysqli_query($db, "SELECT l.*, s.nama as nama_stiker 
                                        FROM log_barang l
                                        INNER JOIN stiker s ON l.id_sticker = s.id_sticker
                                        WHERE l.jenis_log = 'Tambah'");
                while ($bm = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                        <td><?= htmlspecialchars($bm['nama_stiker']) ?></td>
                        <td><?= "+ " . htmlspecialchars($bm['jumlah']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>