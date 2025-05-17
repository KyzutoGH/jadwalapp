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