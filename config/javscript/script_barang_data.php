<!-- JS untuk initializing tabel dan toastr -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
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