<?php
// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PENTING: Jangan ada output HTML sebelum semua operasi header() dan session

// Fungsi untuk menyimpan pesan dan redirect tanpa header()
function setFlashMessageAndRedirect($type, $message) {
    $_SESSION['toastr'] = [
        'type' => $type,
        'message' => $message
    ];
    
    // Simpan flag redirect dalam session
    $_SESSION['should_redirect'] = 'index.php?menu=Barang&submenu=StockBarang&updated='.time();
}

// Kode untuk memproses tambah/kurangi stok
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    // Ambil data dari form
    $id = (int) $_POST['id'];
    $action = $_POST['action'];

    // Validasi input
    if (!in_array($action, ['tambah', 'kurangi'])) {
        setFlashMessageAndRedirect('error', 'Aksi tidak valid');
        // Tidak menggunakan header() disini
    } else {
        // Ambil stock saat ini dengan prepared statement
        $stmt = $db->prepare("SELECT stock FROM jaket WHERE id_jaket = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            setFlashMessageAndRedirect('error', 'Barang tidak ditemukan');
            // Tidak menggunakan header() disini
        } else {
            $current_data = $result->fetch_assoc();
            $current_stock = $current_data['stock'];

            // Update stock
            if ($action == 'tambah') {
                $new_stock = $current_stock + 1;
                $message = 'Stok berhasil ditambahkan';
            } else {
                $new_stock = max(0, $current_stock - 1);
                $message = 'Stok berhasil dikurangi';
            }

            // Update data dengan prepared statement
            $stmt = $db->prepare("UPDATE jaket SET stock = ? WHERE id_jaket = ?");
            $stmt->bind_param("ii", $new_stock, $id);
            $result_update = $stmt->execute();

            if ($result_update) {
                setFlashMessageAndRedirect('success', $message);
                // Tidak menggunakan header() disini
            } else {
                setFlashMessageAndRedirect('error', 'Gagal memperbarui stok: ' . $db->error);
                // Tidak menggunakan header() disini
            }
        }
    }
}

// Kode untuk memproses edit barang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_barang'])) {
    // Ambil data dari form
    $id = (int) $_POST['id'];
    $namabarang = $_POST['namabarang'];
    $ukuran = $_POST['ukuran'];
    $harga = (int) $_POST['harga'];
    $stock = (int) $_POST['stock'];
    
    // Update data dengan prepared statement
    $stmt = $db->prepare("UPDATE jaket SET namabarang = ?, ukuran = ?, harga = ?, stock = ? WHERE id_jaket = ?");
    $stmt->bind_param("ssiii", $namabarang, $ukuran, $harga, $stock, $id);
    $result_update = $stmt->execute();
    
    if ($result_update) {
        setFlashMessageAndRedirect('success', 'Data barang berhasil diperbarui');
        // Tidak menggunakan header() disini
    } else {
        setFlashMessageAndRedirect('error', 'Gagal memperbarui data barang: ' . $db->error);
        // Tidak menggunakan header() disini
    }
}

// Cek apakah perlu redirect menggunakan JavaScript
$redirectScript = '';
if (isset($_SESSION['should_redirect'])) {
    $redirectUrl = $_SESSION['should_redirect'];
    $redirectScript = "<script>window.location.href = '$redirectUrl';</script>";
    unset($_SESSION['should_redirect']);
}
?>

<!-- Tab Data Barang -->
<div class="tab-pane fade show active" id="data" role="tabpanel">
    <div class="pt-3">
        <!-- Redirect script jika diperlukan -->
        <?= $redirectScript ?>
        
        <!-- Notifikasi dengan Toastr (jika ada) -->
        <?php if (isset($_SESSION['toastr'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.<?= $_SESSION['toastr']['type'] ?>('<?= $_SESSION['toastr']['message'] ?>');
            });
        </script>
        <?php 
        // Hapus notifikasi setelah ditampilkan
        unset($_SESSION['toastr']);
        endif; ?>
        
        <!-- Tombol Scan QR -->
        <div class="mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalScanQR">
                <i class="fa fa-qrcode mr-2"></i>Scan QR untuk Kelola Stok
            </button>
        </div>

        <table id="tabelBarang" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Jenis</th>
                    <th>Nama Barang</th>
                    <th>Ukuran</th>
                    <th>Harga</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Pastikan koneksi masih aktif
                if (!$db->ping()) {
                    $db->close();
                    // Gunakan parameter koneksi yang benar sesuai dengan file konfigurasi Anda
                    // Contoh sederhana:
                    $db = new mysqli($host, $user, $password, $database);
                }
                
                // Ambil data dengan error handling
                $data = mysqli_query($db, "SELECT * FROM jaket");
                if (!$data) {
                    echo '<tr><td colspan="8" class="text-center text-danger">Error mengambil data: ' . $db->error . '</td></tr>';
                } else if (mysqli_num_rows($data) == 0) {
                    echo '<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>';
                } else {
                    while ($b = mysqli_fetch_array($data)) {
                        // Tentukan kode dan versi URL encoded-nya
                        if ($b['jenis'] === 'Jaket') {
                            $kodeBarang = 'JKT' . $b['id_jaket'];
                        } else {
                            $kodeBarang = 'VAR' . $b['id_jaket'];
                        }

                        // Buat data untuk QR code
                        $qrData = json_encode([
                            'id' => $b['id_jaket'],
                            'kode' => $kodeBarang,
                            'nama' => $b['namabarang'],
                            'ukuran' => $b['ukuran']
                        ]);
                        $qrDataEncoded = urlencode($qrData);
                        ?>
                        <tr>
                            <td><?= $kodeBarang ?></td>
                            <td><?= htmlspecialchars($b['jenis']) ?></td>
                            <td><?= htmlspecialchars($b['namabarang']) ?></td>
                            <td><?= htmlspecialchars($b['ukuran']) ?></td>
                            <td><?= 'Rp ' . number_format($b['harga'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($b['stock']) ?></td>
                            <td>
                                <span class="badge badge-<?= $b['stock'] > 0 ? 'success' : 'danger' ?>">
                                    <?= $b['stock'] > 0 ? 'Tersedia' : 'Habis' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#modalGenerateQR<?= $b['id_jaket'] ?>">
                                        <i class="fa fa-qrcode"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#modalEditBarang<?= $b['id_jaket'] ?>">
                                        <i class="far fa-edit"></i>
                                    </button>

                                    <!-- Form untuk tombol tambah stok -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $b['id_jaket'] ?>">
                                        <input type="hidden" name="action" value="tambah">
                                        <input type="hidden" name="update_stock" value="1">
                                        <button type="submit" class="btn btn-sm btn-success" title="Tambah Stock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </form>

                                    <!-- Form untuk tombol kurangi stok -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $b['id_jaket'] ?>">
                                        <input type="hidden" name="action" value="kurangi">
                                        <input type="hidden" name="update_stock" value="1">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Kurangi Stock" 
                                            <?= $b['stock'] <= 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Barang -->
                        <div class="modal fade" id="modalEditBarang<?= $b['id_jaket'] ?>" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Barang</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $b['id_jaket'] ?>">
                                            <input type="hidden" name="edit_barang" value="true">
                                            <div class="form-group">
                                                <label>Nama Barang</label>
                                                <input type="text" class="form-control" name="namabarang"
                                                    value="<?= htmlspecialchars($b['namabarang']) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Ukuran</label>
                                                <input type="text" class="form-control" name="ukuran"
                                                    value="<?= htmlspecialchars($b['ukuran']) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Harga</label>
                                                <input type="number" class="form-control" name="harga"
                                                    value="<?= htmlspecialchars($b['harga']) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Stock</label>
                                                <input type="number" class="form-control" name="stock"
                                                    value="<?= htmlspecialchars($b['stock']) ?>" min="0" required>
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

                        <!-- Modal QR Code Barang -->
                        <div class="modal fade" id="modalGenerateQR<?= $b['id_jaket'] ?>" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content text-center">
                                    <div class="modal-header">
                                        <h5 class="modal-title">QR Code Barang</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- QR dan info barang dalam satu canvas -->
                                        <canvas id="qrCanvas<?= $b['id_jaket'] ?>" width="300" height="350"
                                            style="border:1px solid #ddd"></canvas>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button class="btn btn-primary"
                                            onclick="downloadQR('qrCanvas<?= $b['id_jaket'] ?>', '<?= $kodeBarang ?>')">
                                            Download QR
                                        </button>
                                        <button class="btn btn-secondary" onclick="printQR(this)">
                                            Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } // end while 
                } // end else
                ?>
            </tbody>
        </table>
    </div>
</div>
        <?php
        require_once __DIR__ . '/../modal/qr_barang_data.php';
        require_once __DIR__ . '/../../config/javscript/script_barang_data.php'; ?>

