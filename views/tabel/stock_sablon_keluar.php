<div class="pt-3">
    <table id="tabelSablonKeluar" class="tabelBarang table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                    SELECT l.*,  
                           bj.nama_produk
                    FROM log_barang l
                    LEFT JOIN barang_jadi bj ON l.id_barang = bj.id_barang
                    WHERE l.jenis_log = 'Kurangi'
                    AND l.id_barang IS NOT NULL
                ";
            $data = mysqli_query($db, $query);
            while ($bm = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                    <td>
                        <?= htmlspecialchars($bm['nama_produk']) ?>
                    </td>
                    <td><?= "- " . htmlspecialchars($bm['jumlah']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>