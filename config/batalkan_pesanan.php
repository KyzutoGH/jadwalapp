<?php
session_start();
include 'koneksi.php';

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ambil dari POST sesuai formBatalkan
$id = isset($_POST['custIdBatal']) ? (int) $_POST['custIdBatal'] : 0;
$alasan = isset($_POST['alasanBatal']) ? mysqli_real_escape_string($db, $_POST['alasanBatal']) : '';

if ($id && $alasan) {
    // Mulai transaksi
    $db->autocommit(FALSE);

    try {
        // 1. Ambil detail produk dari pesanan yang akan dibatalkan
        $sql_detail = "SELECT jenis_barang, produk_id, qty 
                       FROM penagihan_detail 
                       WHERE penagihan_id = $id";

        $result_detail = $db->query($sql_detail);

        if (!$result_detail) {
            throw new Exception("Gagal mengambil detail pesanan: " . $db->error);
        }

        // 2. Kembalikan stok untuk setiap produk
        while ($row = $result_detail->fetch_assoc()) {
            $jenis_barang = $row['jenis_barang'];
            $produk_id = (int) $row['produk_id'];
            $qty = (int) $row['qty'];

            // Kembalikan stok berdasarkan jenis barang
            if (!kembalikanStokBarang($db, $jenis_barang, $produk_id, $qty)) {
                throw new Exception("Gagal mengembalikan stok untuk $jenis_barang ID: $produk_id");
            }
        }

        // 3. Update status pesanan menjadi dibatalkan
        $sql_update = "UPDATE penagihan 
                       SET status = '5',
                           alasan_batal = '$alasan',
                           tgl_batal = CURRENT_TIMESTAMP
                       WHERE id = $id";

        if (!$db->query($sql_update)) {
            throw new Exception("Gagal mengupdate status pesanan: " . $db->error);
        }

        // Commit transaksi jika semua berhasil
        $db->commit();

        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Pesanan berhasil dibatalkan dan stok telah dikembalikan'
        ];

    } catch (Exception $e) {
        // Rollback jika ada error
        $db->rollback();

        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
        ];

        // Log error untuk debugging
        error_log("Cancel Order Error: " . $e->getMessage());
    }

} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Data tidak lengkap'
    ];
}

$db->close();
header('Location: ../index.php?menu=Penagihan');
exit;

// ============================================
// FUNGSI UNTUK MENGEMBALIKAN STOK
// ============================================

function kembalikanStokBarang($db, $jenis, $id, $qty)
{
    // Tentukan tabel dan kolom ID berdasarkan jenis barang
    switch ($jenis) {
        case 'jaket':
            $table = 'jaket';
            $id_field = 'id_jaket';
            break;
        case 'stiker':
            $table = 'stiker';
            $id_field = 'id_sticker';
            break;
        case 'barang_jadi':
            $table = 'barang_jadi';
            $id_field = 'id_barang';
            break;
        default:
            return false;
    }

    // Dapatkan nama produk untuk log
    $nama_produk = getNamaBarang($db, $jenis, $id);

    try {
        // Update stok barang (tambahkan kembali)
        $sql_update = "UPDATE $table SET stock = stock + $qty WHERE $id_field = $id";
        if (!$db->query($sql_update)) {
            throw new Exception("Gagal mengembalikan stok $jenis: " . $db->error);
        }

        // Catat log pengembalian stok
        $jenis_log = "tambah";
        $deskripsi = "$qty (Pengembalian dari pembatalan pesanan)";

        $sql_log = "INSERT INTO log_barang (
            id_jaket, 
            id_sticker, 
            id_barang, 
            jenis_log, 
            jumlah, 
            tanggal
        ) VALUES (
            " . ($jenis == 'jaket' ? $id : 'NULL') . ",
            " . ($jenis == 'stiker' ? $id : 'NULL') . ",
            " . ($jenis == 'barang_jadi' ? $id : 'NULL') . ",
            '" . $db->real_escape_string($jenis_log) . "',
            '" . $db->real_escape_string($deskripsi) . "',
            NOW()
        )";

        if (!$db->query($sql_log)) {
            throw new Exception("Gagal mencatat log pengembalian: " . $db->error);
        }

        return true;

    } catch (Exception $e) {
        throw $e; // Lanjutkan error ke pemanggil fungsi
    }
}

function getNamaBarang($db, $jenis, $id)
{
    switch ($jenis) {
        case 'jaket':
            $query = "SELECT namabarang FROM jaket WHERE id_jaket = $id";
            $field = 'namabarang';
            break;
        case 'stiker':
            $query = "SELECT nama FROM stiker WHERE id_sticker = $id";
            $field = 'nama';
            break;
        case 'barang_jadi':
            $query = "SELECT nama_produk FROM barang_jadi WHERE id_barang = $id";
            $field = 'nama_produk';
            break;
        default:
            return "Produk Tidak Dikenal";
    }

    $result = $db->query($query);
    if ($result && $row = $result->fetch_assoc()) {
        return $row[$field];
    }
    return "Produk ID $id";
}
?>