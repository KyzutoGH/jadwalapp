<div class="modal fade" id="modalCicilan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Cicilan Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCicilan">
                    <input type="hidden" id="custId" name="custId">
                    <input type="hidden" id="cicilanKe" name="cicilanKe">
                    <div class="form-group">
                        <label>Jumlah Pembayaran</label>
                        <input type="text" class="form-control" id="jumlahBayar" name="jumlahBayar" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="tglBayar" name="tglBayar" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="saveCicilan()">Simpan Pembayaran</button>
            </div>
        </div>
    </div>
</div>