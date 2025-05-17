<!-- Modal Batalkan -->
<div class="modal fade" id="modalBatalkan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pembatalan Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formBatalkan" action="config/batalkan_pesanan.php" method="POST">
                    <input type="hidden" id="custIdBatal" name="custIdBatal">

                    <div class="form-group">
                        <label>Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasanBatal" name="alasanBatal" rows="3" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>