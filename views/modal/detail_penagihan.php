<!-- Modal Detail yang Dioptimalkan -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetailLabel">Detail Pesanan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Konten detail akan dimuat dinamis -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="#" id="whatsappCustomerBtn" class="btn btn-success" target="_blank">
                    <i class="fab fa-whatsapp mr-1"></i> Hubungi Customer
                </a>
                <button type="button" id="printDetailBtn" class="btn btn-info">
                    <i class="fas fa-print mr-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>