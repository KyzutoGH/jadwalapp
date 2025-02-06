<div class="pt-3">
    <table id="tabelStikerKeluar" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Stiker</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT l.*, s.nama as nama_stiker 
                         FROM log_barang l
                         JOIN stiker s ON l.id_sticker = s.id_sticker
                         WHERE l.jenis_log = 'Kurangi' AND l.id_sticker IS NOT NULL
                         ORDER BY l.tanggal DESC";
            $data = mysqli_query($db, $query);
            while ($bm = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($bm['tanggal']) ?></td>
                    <td><?= htmlspecialchars($bm['nama_stiker']) ?></td>
                    <td><?= "- " . htmlspecialchars($bm['jumlah']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>