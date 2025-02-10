<div class="container-fluid">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="formTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?php if ($submenu == "ContactAdd") {
                echo "active";
            } ?>" id="contact-tab" data-toggle="tab" href="#contact" role="tab">Tambah Data Dies Natalis</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($submenu == "Penagihan") {
                echo "active";
            } ?>" id="billing-tab" data-toggle="tab" href="#billing" role="tab">Penagihan</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content">
        <!-- Contact Person Form Tab -->
        <div class="tab-pane fade <?php if ($submenu == "ContactAdd") {
            echo "show active";
        } ?>" id="contact" role="tabpanel">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Tambah Data</h3>
                </div>
                <div class="card-body">
                    <form id="contactForm" action="config/create_kontak.php" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_sekolah">Nama Sekolah</label>
                                    <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="form-group">
                                    <label for="nomor_kontak">Nomor Kontak</label>
                                    <input type="text" class="form-control" id="nomor_kontak" name="nomor_kontak"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pemilik_kontak">Pemilik Kontak</label>
                                    <input type="tel" class="form-control" id="pemilik_kontak" name="pemilik_kontak"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="jabatan">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_dn">Tanggal Dies Natalis (Format : DD-MM. Angka Saja)</label>
                                    <input type="text" class="form-control" id="tanggal_dn" name="tanggal_dn" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" name="Submit" class="btn btn-primary float-right">Simpan</button>
                                <button type="reset" class="btn btn-secondary float-right mr-2">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Billing Form Tab -->
        <div class="tab-pane fade <?php if ($submenu == "Penagihan") {
            echo "show active";
        } ?>" id="billing" role="tabpanel">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Data Penagihan</h3>
                </div>
                <div class="card-body">
                    <form action="config/create_dp.php" method="POST" id="penagihanForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal Penagihan</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer">Nama Customer</label>
                                    <input type="text" class="form-control" id="customer" name="customer" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total">Total Keseluruhan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" id="total" name="total" required
                                            min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_dp">Rencana Cicilan DP</label>
                                    <select class="form-control" id="jumlah_dp" name="jumlah_dp" required>
                                        <option value="">Pilih jumlah cicilan</option>
                                        <option value="1">1 kali pembayaran</option>
                                        <option value="2">2 kali pembayaran</option>
                                        <option value="3">3 kali pembayaran</option>
                                    </select>
                                    <small class="text-muted">*Pembayaran DP selanjutnya dapat dilakukan di menu Update
                                        Penagihan</small>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4 mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Pembayaran DP Pertama</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dp1_tenggat">Tanggal Pembayaran</label>
                                            <input type="date" class="form-control" id="dp1_tenggat" name="dp1_tenggat"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dp1_nominal">Nominal Pembayaran</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="dp1_nominal"
                                                    name="dp1_nominal" required min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Total Pembayaran:</strong>
                                    <span id="display_total">Rp 0</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Sisa yang Harus Dibayar:</strong>
                                    <span id="sisa_pembayaran">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                            <a href="index.php?menu=Penagihan" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>

                    <script>
                        function formatRupiah(angka) {
                            return 'Rp ' + Number(angka).toLocaleString('id-ID');
                        }

                        function updatePembayaran() {
                            const total = parseFloat(document.getElementById('total').value) || 0;
                            const dp1 = parseFloat(document.getElementById('dp1_nominal').value) || 0;
                            const sisa = total - dp1;

                            document.getElementById('display_total').textContent = formatRupiah(total);
                            document.getElementById('sisa_pembayaran').textContent = formatRupiah(sisa);
                        }

                        // Validasi form sebelum submit
                        document.getElementById('penagihanForm').onsubmit = function (e) {
                            const total = parseFloat(document.getElementById('total').value) || 0;
                            const dp1 = parseFloat(document.getElementById('dp1_nominal').value) || 0;

                            if (dp1 > total) {
                                e.preventDefault();
                                alert('Nominal DP tidak boleh melebihi total pembayaran!');
                                return false;
                            }

                            if (dp1 <= 0) {
                                e.preventDefault();
                                alert('Nominal DP harus lebih dari 0!');
                                return false;
                            }

                            return true;
                        };

                        // Event listeners
                        document.getElementById('total').addEventListener('input', updatePembayaran);
                        document.getElementById('dp1_nominal').addEventListener('input', updatePembayaran);
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>