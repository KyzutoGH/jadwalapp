<?php
// cetak_laporan_pembayaran.php
require_once 'config/koneksi.php';

if (isset($_GET['bulan']) && !empty($_GET['bulan']) && isset($_GET['tahun']) && !empty($_GET['tahun'])) {
    $bulan = $_GET['bulan'];
    $tahun = $_GET['tahun'];

    if (!is_numeric($bulan) || $bulan < 1 || $bulan > 12 || !is_numeric($tahun) || $tahun < 2000 || $tahun > 2100) {
        die("Parameter tidak valid");
    }

    $query = "SELECT p.id AS id_pembayaran, p.tanggal AS tanggal_pembayaran, p.cicilan_ke, 
              p.nominal AS jumlah_pembayaran, p.metode AS metode_pembayaran,
              n.customer, n.kontak, n.total AS total_tagihan, n.status 
              FROM pembayaran_history p 
              JOIN penagihan n ON p.penagihan_id = n.id 
              WHERE MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun' 
              ORDER BY p.tanggal ASC, n.customer ASC";

    $result = mysqli_query($db, $query);
    if (!$result) die("Query Error: " . mysqli_error($db));

    $months = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $nama_bulan = $months[str_pad($bulan, 2, '0', STR_PAD_LEFT)];

    $query_total = "SELECT SUM(p.nominal) AS total_pembayaran 
                    FROM pembayaran_history p 
                    WHERE MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'";
    $result_total = mysqli_query($db, $query_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_pembayaran = $row_total['total_pembayaran'];
} else {
    die("Parameter bulan dan tahun tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran - <?= $nama_bulan ?> <?= $tahun ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .company-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .company-header img {
            max-height: 80px;
            margin-right: 20px;
        }
        .company-info h3 {
            margin: 0;
        }
        .company-info p {
            margin: 0;
            font-size: 13px;
        }
        hr {
            border-top: 3px solid #000;
        }
        .header {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            font-size: 12px;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .footer p {
            font-size: 13px;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="company-header">
        <img src="assets/img/fukubistorelogo.png" alt="Logo">
        <div class="company-info">
            <h3>FukuBI Sablon Satuan Blitar</h3>
            <p>Dadapan, Kedung Bunder, Kec. Sutojayan, Kabupaten Blitar, Jawa Timur 66172</p>
        </div>
    </div>
    <hr>
    <div class="header">
        <h4><strong>LAPORAN PEMBAYARAN</strong></h4>
        <p>PERIODE: <?= strtoupper($nama_bulan) ?> <?= $tahun ?></p>
    </div>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="glyphicon glyphicon-print"></i> Cetak
        </button>
        <a href="javascript:window.close()" class="btn btn-default">
            Tutup
        </a>
    </div>

    <?php if (mysqli_num_rows($result) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Kontak</th>
                    <th>Tanggal</th>
                    <th>Cicilan Ke</th>
                    <th class="text-right">Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kontak']) . "</td>";
                    echo "<td>" . $formatter->format(new DateTime($row['tanggal_pembayaran'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cicilan_ke']) . "</td>";
                    echo "<td class='text-right'>" . number_format($row['jumlah_pembayaran'], 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">TOTAL</th>
                    <th class="text-right"><?= number_format($total_pembayaran, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Blitar, <?= $formatter->format(new DateTime()) ?></p>
            <div style="margin-top: 80px;">
                <p>__________________________</p>
                <p>Team Marketing</p>
            </div>
        </div>
    <?php } else { ?>
        <div class="no-data">
            <p>Tidak ada data pembayaran untuk periode <?= $nama_bulan ?> <?= $tahun ?></p>
        </div>
    <?php } ?>
</div>
</body>
</html>
