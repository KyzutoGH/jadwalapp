<!-- Modal Cicilan -->
<div class="modal fade" id="cicilanModal" tabindex="-1" role="dialog" aria-labelledby="modalCicilanTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCicilanTitle">Pembayaran Cicilan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formCicilan" action="config/proses_cicilan.php" method="POST">
                    <input type="hidden" id="penagihan_id" name="penagihan_id">
                    <input type="hidden" id="cicilan_ke" name="cicilan_ke">
                    <input type="hidden" id="total_cicilan" name="total_cicilan">

                    <div class="form-group">
                        <label for="nominal">Nominal Pembayaran</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_bayar">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar" required
                            value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>