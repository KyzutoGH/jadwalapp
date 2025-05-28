<?php
// Tambahkan tombol cetak laporan di card-header
// Tambahkan ini di bawah tombol Tambah Data yang sudah ada
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Pembayaran Penagihan</h3>
        <div class="float-right">
            <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-print"></i> Cetak Laporan Bulanan
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <?php
                    // Menampilkan daftar bulan untuk dipilih
                    $months = [
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember'
                    ];

                    $current_year = date('Y');
                    // Menampilkan opsi untuk tahun saat ini dan tahun sebelumnya
                    for ($year = $current_year; $year >= $current_year - 1; $year--) {
                        echo "<li class='dropdown-header'>$year</li>";
                        foreach ($months as $num => $name) {
                            echo "<li><a href='cetak_laporan_pembayaran.php?bulan=$num&tahun=$year' target='_blank' class='dropdown-item'>$name $year</a></li>";
                        }
                        if ($year != $current_year - 1) {
                            echo "<li class='divider'></li>";
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="tabelPembayaran" class="tabelBarang table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Kontak</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Cicilan Ke</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Total Tagihan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($db, "SELECT p.id AS id_pembayaran, p.tanggal AS tanggal_pembayaran, p.cicilan_ke, p.nominal AS jumlah_pembayaran, p.metode AS metode_pembayaran, p.keterangan AS keterangan_pembayaran, n.id AS id_penagihan, n.customer, n.kontak, n.total AS total_tagihan, n.status FROM pembayaran_history p JOIN penagihan n ON p.penagihan_id = n.id ORDER BY p.tanggal DESC, n.customer ASC;");
                if (!$data) {
                    die('Database query error: ' . mysqli_error($db));
                }
                while ($d = mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($d['customer']) ?></td>
                        <td><?= htmlspecialchars($d['kontak']); ?></td>
                        <?php
                        $bulanIndo = [
                            'January' => 'Januari',
                            'February' => 'Februari',
                            'March' => 'Maret',
                            'April' => 'April',
                            'May' => 'Mei',
                            'June' => 'Juni',
                            'July' => 'Juli',
                            'August' => 'Agustus',
                            'September' => 'September',
                            'October' => 'Oktober',
                            'November' => 'November',
                            'December' => 'Desember'
                        ];

                        $tanggal = strtotime($d['tanggal_pembayaran']);
                        $namaBulan = $bulanIndo[date('F', $tanggal)];
                        $tanggalFormatted = date('d', $tanggal) . ' ' . $namaBulan . ' ' . date('Y', $tanggal);
                        ?>
                        <td><?= $tanggalFormatted ?></td>
                        <td><?= htmlspecialchars($d['cicilan_ke']) ?></td>
                        <td><?= 'Rp ' . number_format($d['jumlah_pembayaran'], 0, ',', '.') ?></td>
                        <td><?= 'Rp ' . number_format($d['total_tagihan'], 0, ',', '.') ?></td>
                        <td><span class="badge 
                        <?php
                        if ($d['status'] == 1) { // Belum Lunas
                            echo "badge-warning";
                        } else if ($d['status'] == 2 || $d['status'] == 3 || $d['status'] == 4) { // Lunas
                            echo "badge-success";
                        } else if ($d['status'] == 5) { // Batal
                            echo "badge-danger";
                        } ?>"><?php
                         if ($d['status'] == 1) {
                             echo "Belum Lunas";
                         } else if ($d['status'] == 2) {
                             echo "Lunas - Proses";
                         } else if ($d['status'] == 3) {
                             echo "Lunas - Siap Ambil";
                         } else if ($d['status'] == 4) {
                             echo "Pesanan Selesai";
                         } else if ($d['status'] == 5) {
                             echo "Dibatalkan";
                         } ?></span></td>
                        <td class="text-center">
                            <!-- Tombol aksi bisa ditambahkan di sini -->
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>