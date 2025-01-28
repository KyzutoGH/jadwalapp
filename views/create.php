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
                <div class="card-body"><!-- Form Tambah Penagihan -->
                    <form action="config/create_dp.php" method="POST">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>

                        <div class="form-group">
                            <label for="customer">Nama Customer</label>
                            <input type="text" class="form-control" id="customer" name="customer" required>
                        </div>

                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="number" class="form-control" id="total" name="total" required>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_dp">Jumlah DP</label>
                            <select class="form-control" id="jumlah_dp" name="jumlah_dp" required
                                onchange="showDpFields()">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>

                        <!-- DP 1 (Selalu Tampil) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dp1_tenggat">Tanggal DP 1</label>
                                    <input type="date" class="form-control" id="dp1_tenggat" name="dp1_tenggat"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dp1_nominal">Nominal DP 1</label>
                                    <input type="number" class="form-control" id="dp1_nominal" name="dp1_nominal"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- DP 2 (Initially Hidden) -->
                        <div class="row dp2-fields" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dp2_tenggat">Tanggal DP 2</label>
                                    <input type="date" class="form-control" id="dp2_tenggat" name="dp2_tenggat">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dp2_nominal">Nominal DP 2</label>
                                    <input type="number" class="form-control" id="dp2_nominal" name="dp2_nominal">
                                </div>
                            </div>
                        </div>

                        <!-- DP 3 (Initially Hidden) -->
                        <div class="row dp3-fields" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dp3_tenggat">Tanggal DP 3</label>
                                    <input type="date" class="form-control" id="dp3_tenggat" name="dp3_tenggat">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dp3_nominal">Nominal DP 3</label>
                                    <input type="number" class="form-control" id="dp3_nominal" name="dp3_nominal">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="alert alert-info">
                                <strong>Sisa Pembayaran: </strong>
                                <span id="sisa_pembayaran">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                    <script>
                        function showDpFields() {
                            const jumlahDp = document.getElementById('jumlah_dp').value;
                            const dp2Fields = document.querySelector('.dp2-fields');
                            const dp3Fields = document.querySelector('.dp3-fields');

                            // Reset display
                            dp2Fields.style.display = 'none';
                            dp3Fields.style.display = 'none';

                            if (jumlahDp >= 2) {
                                dp2Fields.style.display = 'flex';
                            }
                            if (jumlahDp >= 3) {
                                dp3Fields.style.display = 'flex';
                            }
                        }

                        function calculateSisaPembayaran() {
                            const total = parseFloat(document.getElementById('total').value) || 0;
                            const dp1 = parseFloat(document.getElementById('dp1_nominal').value) || 0;
                            const dp2 = parseFloat(document.getElementById('dp2_nominal').value) || 0;
                            const dp3 = parseFloat(document.getElementById('dp3_nominal').value) || 0;

                            const sisa = total - (dp1 + dp2 + dp3);
                            document.getElementById('sisa_pembayaran').textContent = 'Rp ' + sisa.toLocaleString();
                        }

                        // Add event listeners
                        document.getElementById('total').addEventListener('input', calculateSisaPembayaran);
                        document.getElementById('dp1_nominal').addEventListener('input', calculateSisaPembayaran);
                        document.getElementById('dp2_nominal').addEventListener('input', calculateSisaPembayaran);
                        document.getElementById('dp3_nominal').addEventListener('input', calculateSisaPembayaran);
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>