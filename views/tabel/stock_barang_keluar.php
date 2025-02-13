<div class="pt-3">
    <table id="tabelBarangKeluar" class="tabelBarang table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = mysqli_query($db, "SELECT l.*, j.namabarang as nama_jaket, s.nama as nama_stiker
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
                            echo htmlspecialchars($bm['nama_jaket']);
                        } elseif (!empty($bm['id_sticker'])) {
                            echo htmlspecialchars($bm['nama_stiker']);
                        } else {
                            echo 'Data tidak ditemukan';
                        }
                        ?>
                    </td>
                    <td><?= "- " . htmlspecialchars($bm['jumlah']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>