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

<!-- Modal Scan QR -->
<div class="modal fade" id="modalScanQR" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan QR Code untuk Kelola Stok</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="text-center mb-3">
                    <div id="qr-reader" style="width: 100%"></div>
                    <div id="qr-reader-results"></div>
                </div>

                <div id="scannedProductInfo" class="d-none mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" id="productName">Nama Produk</h5>
                            <h6 class="card-subtitle mb-2 text-muted" id="productCode">Kode Produk</h6>
                            <p class="card-text" id="productSize">Ukuran: -</p>
                            <p class="card-text" id="currentStock">Stok saat ini: 0</p>

                            <form id="updateStockForm" method="POST" action="config/process_barcode.php">
                            <input type="hidden" name="id" id="productId">
                                <div class="form-group">
                                    <label for="stockAmount">Jumlah</label>
                                    <input type="number" class="form-control" id="stockAmount" name="jumlah" min="1">
                                </div>
                                <div class="btn-group w-100" role="group">
                                    <button type="submit" name="action" value="tambah" class="btn btn-success">Tambah
                                        Stok</button>
                                    <button type="submit" name="action" value="kurangi" class="btn btn-danger">Kurangi
                                        Stok</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS untuk initializing tabel dan toastr -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi DataTable
        try {
            $('#tabelBarang').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        } catch (e) {
            console.error('Error initializing DataTable:', e);
        }
        
        // Inisialisasi Toastr options jika belum diset
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };
        }
    });
</script>

<!-- Load External JS Libraries -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Load libraries
        loadScript('https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js', function () {
            console.log('jsQR loaded');
        });
    });

    function loadScript(url, callback) {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = url;
        script.onload = callback;
        document.head.appendChild(script);
    }
</script>

<!-- JS untuk QR Code, Download, dan Scanner -->
<script>
    // Fungsi untuk membuat QR code dengan informasi barang
    function createQRCodeWithInfo(canvasId, data, kode, nama, ukuran) {
        // Ambil elemen canvas
        var canvas = document.getElementById(canvasId);
        if (!canvas) return; // Safety check
        
        var ctx = canvas.getContext('2d');

        // Bersihkan canvas
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Buat QR code
        var qrImage = new Image();
        qrImage.crossOrigin = "Anonymous";
        qrImage.onload = function () {
            // Gambar QR di tengah canvas
            var x = (canvas.width - 200) / 2;
            ctx.drawImage(qrImage, x, 20, 200, 200);

            // Tambahkan teks informasi
            ctx.fillStyle = 'black';
            ctx.font = 'bold 14px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(kode, canvas.width / 2, 240);

            ctx.font = '14px Arial';
            ctx.fillText(nama, canvas.width / 2, 265);
            ctx.fillText('Ukuran: ' + ukuran, canvas.width / 2, 290);
        };

        qrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(data);
    }

    // Fungsi untuk mendownload QR code
    function downloadQR(canvasId, kode) {
        var canvas = document.getElementById(canvasId);
        if (!canvas) return; // Safety check
        
        var link = document.createElement('a');
        link.download = 'qr-' + kode + '.png';

        // Konversi canvas ke blob
        canvas.toBlob(function (blob) {
            // Buat URL object dari blob
            var url = URL.createObjectURL(blob);
            link.href = url;
            link.click();

            // Clean up
            setTimeout(function () {
                URL.revokeObjectURL(url);
            }, 100);
        });
    }

    // Fungsi untuk print QR code
    function printQR(button) {
        var modalBody = button.closest('.modal-content').querySelector('.modal-body');
        var canvas = modalBody.querySelector('canvas');
        if (!canvas) return; // Safety check

        // Buka popup untuk print
        var popup = window.open('', '', 'width=400,height=500');
        popup.document.write(
            '<html><head><title>Print QR</title></head>' +
            '<body style="text-align:center;font-family:sans-serif;">' +
            '<img src="' + canvas.toDataURL() + '" style="max-width:100%">' +
            '</body></html>'
        );

        popup.document.close();
        popup.onload = function () {
            popup.print();
            popup.close();
        };
    }

    // Inisialisasi semua QR code saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Aktifkan semua modal
        $('.modal').on('shown.bs.modal', function (e) {
            var modalId = $(this).attr('id');
            if (modalId && modalId.startsWith('modalGenerateQR')) {
                var itemId = modalId.replace('modalGenerateQR', '');
                var dataElement = document.querySelector('[data-target="#' + modalId + '"]');

                if (dataElement) {
                    var row = dataElement.closest('tr');
                    var kode = row.cells[0].innerText;
                    var nama = row.cells[2].innerText;
                    var ukuran = row.cells[3].innerText;

                    // Buat data JSON
                    var qrData = JSON.stringify({
                        id: itemId,
                        kode: kode,
                        nama: nama,
                        ukuran: ukuran
                    });

                    // Buat QR code
                    createQRCodeWithInfo('qrCanvas' + itemId, qrData, kode, nama, ukuran);
                }
            }
        });

        // Set up QR scanner
        $('#modalScanQR').on('shown.bs.modal', initQRScanner);
        $('#modalScanQR').on('hidden.bs.modal', stopQRScanner);
    });

    // Variabel untuk scanner
    var videoElement;
    var canvasElement;
    var canvasContext;
    var scanInterval;

    // Inisialisasi QR scanner
    function initQRScanner() {
        var qrReader = document.getElementById('qr-reader');
        if (!qrReader) return; // Safety check
        
        qrReader.innerHTML = '';

        // Buat elemen video
        videoElement = document.createElement('video');
        videoElement.style.width = '100%';
        qrReader.appendChild(videoElement);

        // Buat canvas
        canvasElement = document.createElement('canvas');
        canvasElement.style.display = 'none';
        qrReader.appendChild(canvasElement);
        canvasContext = canvasElement.getContext('2d');

        // Akses kamera
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
                }
            })
                .then(function (stream) {
                    videoElement.srcObject = stream;
                    videoElement.setAttribute('playsinline', true);
                    videoElement.play();

                    // Mulai scanning
                    scanInterval = setInterval(scanQRCode, 500);
                })
                .catch(function (err) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Gagal mengakses kamera: ' + err.message);
                    } else {
                        alert('Gagal mengakses kamera: ' + err.message);
                    }
                });
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error('Browser tidak mendukung akses kamera');
            } else {
                alert('Browser tidak mendukung akses kamera');
            }
        }
    }

    // Hentikan QR scanner
    function stopQRScanner() {
        if (scanInterval) {
            clearInterval(scanInterval);
        }

        if (videoElement && videoElement.srcObject) {
            videoElement.srcObject.getTracks().forEach(track => track.stop());
        }
    }

    // Scan QR code
    function scanQRCode() {
        if (!videoElement || !canvasElement || !canvasContext) return;
        
        if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
            // Set ukuran canvas
            canvasElement.height = videoElement.videoHeight;
            canvasElement.width = videoElement.videoWidth;

            // Ambil frame dari video
            canvasContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
            var imageData = canvasContext.getImageData(0, 0, canvasElement.width, canvasElement.height);

            // Check jika jsQR sudah dimuat
            if (typeof jsQR === 'function') {
                // Scan QR code
                var code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    try {
                        // Parse data
                        var data = JSON.parse(code.data);

                        // Tampilkan info produk
                        document.getElementById('productId').value = data.id;
                        document.getElementById('productCode').textContent = data.kode;
                        document.getElementById('productName').textContent = data.nama;
                        document.getElementById('productSize').textContent = 'Ukuran: ' + data.ukuran;

                        // Ambil stok saat ini
                        fetch('config/get_stock.php?id=' + data.id)
                            .then(response => response.json())
                            .then(stockData => {
                                if (stockData.success) {
                                    document.getElementById('currentStock').textContent = 'Stok saat ini: ' + stockData.stock;
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching stock:', error);
                                document.getElementById('currentStock').textContent = 'Stok saat ini: (tidak tersedia)';
                            });

                        // Tampilkan form
                        document.getElementById('scannedProductInfo').classList.remove('d-none');

                        // Hentikan scanning
                        clearInterval(scanInterval);
                        
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Berhasil memindai QR code');
                        }
                    } catch (e) {
                        if (typeof toastr !== 'undefined') {
                            toastr.warning('QR Code tidak valid: ' + e.message);
                        } else {
                            console.error('QR Code tidak valid:', e);
                        }
                    }
                }
            }
        }
    }
</script>