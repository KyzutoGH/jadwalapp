<?php
session_start();
require_once 'koneksi.php';

// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// // Fungsi helper
// require_once 'helpers.php'; // Jika dipisah

try {
    $db->autocommit(FALSE); // Mulai transaksi

    // Validasi input utama
    $required_fields = ['tanggal', 'tanggal_pengambilan', 'total', 'jumlah_dp', 'customer_selection'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Kolom $field wajib diisi!");
        }
    }

    // Proses data customer
    $customer_data = process_customer($db, $_POST);
    $customer = $customer_data['customer'];
    $kontak = $customer_data['kontak'];

    // Simpan ke tabel utama penagihan
    $penagihan_id = save_penagihan($db, $_POST, $customer, $kontak);

    // Proses produk dan simpan detail
    $products_saved = 0;
    $error_details = [];

    if (!isset($_POST['product_types']) || empty($_POST['product_types'])) {
        throw new Exception("Pilih minimal 1 produk!");
    }

    foreach ($_POST['product_types'] as $type) {
        try {
            $result = process_product($db, $type, $penagihan_id, $_POST);
            if ($result)
                $products_saved++;
        } catch (Exception $e) {
            $error_details[] = $e->getMessage();
            error_log("Product Error [$type]: " . $e->getMessage());
        }
    }

    if ($products_saved === 0) {
        throw new Exception(
            "Gagal menyimpan produk:<br>- " .
            implode("<br>- ", $error_details)
        );
    }

    $db->commit(); // Commit transaksi

    $_SESSION['toastr'] = [
        'type' => 'success',
        'message' => '✅ Data berhasil disimpan! ID: ' . $penagihan_id
    ];
    header("Location: ../index.php?menu=Penagihan");
    exit;

} catch (Exception $e) {
    $db->rollback(); // Rollback jika error
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => '❌ Error: ' . $e->getMessage()
    ];
    error_log("Penagihan Error: " . $e->getMessage());
    header("Location: ../index.php?menu=Create&submenu=Penagihan");
    exit;
}

// ============================================
// FUNGSI PEMROSESAN UTAMA
// ============================================

function process_customer($db, $post)
{
    if ($post['customer_selection'] === 'new') {
        if (empty($post['customer'])) {
            throw new Exception("Nama customer tidak boleh kosong");
        }
        return [
            'customer' => $db->real_escape_string(trim($post['customer'])),
            'kontak' => preg_replace('/^0/', '', $db->real_escape_string(trim($post['kontak'])))
        ];
    }

    // Existing customer
    $id_customer = intval($post['existing_customer']);
    $query = "SELECT nama_sekolah, pemilik_kontak, nomor FROM datadn WHERE id = $id_customer";
    $result = $db->query($query);

    if ($result->num_rows === 0) {
        throw new Exception("Customer tidak ditemukan");
    }

    $row = $result->fetch_assoc();
    $kontak = preg_replace('/^0/', '', $row['nomor']);
    $customer = $row['nama_sekolah'];

    if (!empty($row['pemilik_kontak'])) {
        $customer .= " (PIC: " . $row['pemilik_kontak'] . ")";
    }

    return [
        'customer' => $db->real_escape_string($customer),
        'kontak' => $kontak
    ];
}

function save_penagihan($db, $post, $customer, $kontak)
{
    $fields = [
        'tanggal' => $db->real_escape_string($post['tanggal']),
        'customer' => $customer,
        'total' => floatval(str_replace('.', '', $post['total'])),
        'jumlah_dp' => intval($post['jumlah_dp']),
        'tanggal_pengambilan' => $db->real_escape_string($post['tanggal_pengambilan']),
        'keterangan' => isset($post['keterangan']) ? $db->real_escape_string($post['keterangan']) : '',
        'status' => '1',
        'kontak' => $kontak
    ];

    // Proses DP
    for ($i = 1; $i <= 3; $i++) {
        $fields["dp{$i}_tenggat"] = isset($post["dp{$i}_tenggat"]) && !empty($post["dp{$i}_tenggat"]) ?
            "'" . $db->real_escape_string($post["dp{$i}_tenggat"]) . "'" : 'NULL';
        $fields["dp{$i}_nominal"] = 0;
        $fields["dp{$i}_metode"] = 'cash';
        $fields["dp{$i}_status"] = '0';
    }

    $sql = "INSERT INTO penagihan (
        tanggal, customer, total, jumlah_dp, tanggal_pengambilan,
        dp1_tenggat, dp1_nominal, dp1_metode, dp1_status,
        dp2_tenggat, dp2_nominal, dp2_metode, dp2_status,
        dp3_tenggat, dp3_nominal, dp3_metode, dp3_status,
        keterangan, status, kontak
    ) VALUES (
        '{$fields['tanggal']}', 
        '{$fields['customer']}', 
        {$fields['total']}, 
        {$fields['jumlah_dp']},
        '{$fields['tanggal_pengambilan']}',
        {$fields['dp1_tenggat']}, {$fields['dp1_nominal']}, '{$fields['dp1_metode']}', '{$fields['dp1_status']}',
        {$fields['dp2_tenggat']}, {$fields['dp2_nominal']}, '{$fields['dp2_metode']}', '{$fields['dp2_status']}',
        {$fields['dp3_tenggat']}, {$fields['dp3_nominal']}, '{$fields['dp3_metode']}', '{$fields['dp3_status']}',
        '{$fields['keterangan']}', 
        '{$fields['status']}', 
        '{$fields['kontak']}'
    )";

    if (!$db->query($sql)) {
        throw new Exception("Gagal menyimpan penagihan: " . $db->error);
    }
    return $db->insert_id;
}

function process_product($db, $type, $penagihan_id, $post)
{
    $product_map = [
        'jaket' => [
            'field' => 'jaket_product',
            'qty' => 'jaket_qty',
            'table' => 'jaket',
            'id_field' => 'id_jaket'
        ],
        'stiker' => [
            'field' => 'stiker_product',
            'qty' => 'stiker_qty',
            'table' => 'stiker',
            'id_field' => 'id_sticker'
        ],
        'barang_jadi' => [
            'field' => 'barang_jadi_product',
            'qty' => 'barang_jadi_qty',
            'table' => 'barang_jadi',
            'id_field' => 'id_barang'
        ]
    ];

    if (!isset($product_map[$type])) {
        throw new Exception("Jenis produk tidak valid: $type");
    }

    $config = $product_map[$type];
    if (empty($post[$config['field']])) {
        throw new Exception("Produk $type belum dipilih");
    }

    $produk_id = intval($post[$config['field']]);
    $qty = intval($post[$config['qty']]);

    // Dapatkan harga
    $harga = getHargaBarang($db, $type, $produk_id);
    if ($harga <= 0) {
        throw new Exception("Harga $type tidak valid");
    }

    // Cek stok
    $stok = getStokBarang($db, $type, $produk_id);
    if ($stok < $qty) {
        throw new Exception("Stok $type tidak cukup (Tersedia: $stok)");
    }

    // Insert detail
    $sql_detail = sprintf(
        "INSERT INTO penagihan_detail 
        (penagihan_id, jenis_barang, produk_id, qty, harga_satuan, subtotal)
        VALUES (%d, '%s', %d, %d, %.2f, %.2f)",
        $penagihan_id,
        $type,
        $produk_id,
        $qty,
        $harga,
        ($qty * $harga)
    );

    if (!$db->query($sql_detail)) {
        throw new Exception("Gagal simpan $type: " . $db->error);
    }

    // Update stok
    updateStokBarang($db, $type, $produk_id, $qty);
    return true;
}

// Fungsi untuk mendapatkan harga barang
function getHargaBarang($db, $jenis, $id)
{
    switch ($jenis) {
        case 'jaket':
            $query = "SELECT harga FROM jaket WHERE id_jaket = $id";
            break;
        case 'stiker':
            $query = "SELECT harga FROM stiker WHERE id_sticker = $id";
            break;
        case 'barang_jadi':
            $query = "SELECT j.harga FROM barang_jadi bj
                      LEFT JOIN jaket j ON bj.id_jaket = j.id_jaket
                      WHERE bj.id_barang = $id";
            break;
        default:
            return 0;
    }
    $result = $db->query($query);
    if (!$result) {
        throw new Exception("Gagal mendapatkan harga barang: " . $db->error);
    }
    $row = $result->fetch_assoc();
    return $row ? floatval($row['harga']) : 0;
}

// Fungsi untuk mendapatkan stok barang
function getStokBarang($db, $jenis, $id)
{
    switch ($jenis) {
        case 'jaket':
            $query = "SELECT stock FROM jaket WHERE id_jaket = $id";
            break;
        case 'stiker':
            $query = "SELECT stock FROM stiker WHERE id_sticker = $id";
            break;
        case 'barang_jadi':
            $query = "SELECT stock FROM barang_jadi WHERE id_barang = $id";
            break;
        default:
            return 0;
    }
    $result = $db->query($query);
    if (!$result) {
        throw new Exception("Gagal mendapatkan stok barang: " . $db->error);
    }
    $row = $result->fetch_assoc();
    return $row ? intval($row['stock']) : 0;
}

// Fungsi untuk update stok barang
function updateStokBarang($db, $jenis, $id, $qty)
{
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

    $sql = "UPDATE $table SET stock = stock - $qty WHERE $id_field = $id";
    if (!$db->query($sql)) {
        throw new Exception("Gagal mengupdate stok $jenis: " . $db->error);
    }
    return true;
}
?>