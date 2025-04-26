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
                                } ?>" id="billing-tab" data-toggle="tab" href="#billing" role="tab">Pre Order</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content">
        <!-- Contact Person Form Tab -->
        <div class="tab-pane fade <?php if ($submenu == "ContactAdd") {
                                        echo "show active";
                                    } ?>" id="contact" role="tabpanel">
            <!-- Contact form content - unchanged -->
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
                    <h3 class="card-title">Data Pre Order</h3>
                </div>
                <div class="card-body">
                    <form action="config/create_dp.php" method="POST" id="penagihanForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal">Tanggal Pre Order</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary" onclick="setToday()">Hari Ini</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer">Nama Customer</label>
                                <input type="text" class="form-control" id="customer" name="customer" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kontak">Kontak Customer</label>
                                <input type="number" class="form-control" id="kontak" name="kontak" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="keterangan">Keterangan Produk</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Deskripsi produk/pesanan"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pengambilan">Tanggal Pengambilan Barang</label>
                                <input type="date" class="form-control" id="tanggal_pengambilan" name="tanggal_pengambilan" required onchange="validateInstallments()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total">Total Keseluruhan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="total" name="total" required min="0" step="1000">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_dp">Rencana Cicilan DP</label>
                                <select class="form-control" id="jumlah_dp" name="jumlah_dp" required onchange="calculateJatuhTempo()">
                                    <option value="">Pilih jumlah cicilan</option>
                                    <option value="1">1 kali pembayaran</option>
                                    <option value="2">2 kali pembayaran</option>
                                    <option value="3">3 kali pembayaran</option>
                                </select>
                            </div>
                        </div>

                        <div class="row" id="pembayaran_section">
                            <div class="col-md-4 mb-3">
                                <label>Jatuh Tempo 1</label>
                                <div id="jatuh_tempo_1" class="form-control" style="background-color: #e9ecef;">-</div>
                                <input type="hidden" id="dp1_tenggat" name="dp1_tenggat">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Jatuh Tempo 2</label>
                                <div id="jatuh_tempo_2" class="form-control" style="background-color: #e9ecef;">-</div>
                                <input type="hidden" id="dp2_tenggat" name="dp2_tenggat">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Jatuh Tempo 3</label>
                                <div id="jatuh_tempo_3" class="form-control" style="background-color: #e9ecef;">-</div>
                                <input type="hidden" id="dp3_tenggat" name="dp3_tenggat">
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary w-100">Simpan Pre Order</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <script>
            function setToday() {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('tanggal').value = today;
            }

            function calculateJatuhTempo() {
                var jumlah_dp = parseInt(document.getElementById('jumlah_dp').value) || 0;
                var tanggalPreOrder = new Date(document.getElementById("tanggal").value);
                var tanggalPengambilan = new Date(document.getElementById("tanggal_pengambilan").value);

                // Reset semua jatuh tempo
                for (var i = 1; i <= 3; i++) {
                    document.getElementById('jatuh_tempo_' + i).textContent = '-';
                    document.getElementById('dp' + i + '_tenggat').value = '';
                }

                if (jumlah_dp > 0 && tanggalPreOrder && tanggalPengambilan) {
                    // Hitung selisih hari antara tanggal pre order dan tanggal pengambilan
                    var selisihHari = Math.floor((tanggalPengambilan - tanggalPreOrder) / (1000 * 60 * 60 * 24));

                    // Bagi selisih hari berdasarkan jumlah cicilan
                    var intervalHari = Math.floor(selisihHari / jumlah_dp);

                    for (var i = 1; i <= jumlah_dp; i++) {
                        var jatuhTempo = new Date(tanggalPreOrder);
                        jatuhTempo.setDate(jatuhTempo.getDate() + (intervalHari * i));

                        // Set jatuh tempo date (untuk display)
                        var jatuhTempoStr = jatuhTempo.toISOString().split('T')[0];
                        document.getElementById('jatuh_tempo_' + i).textContent = jatuhTempoStr;

                        // Set tenggat value (untuk database)
                        document.getElementById('dp' + i + '_tenggat').value = jatuhTempoStr;
                    }
                }
            }

            function getNextWeekday(startDate, offset) {
                let date = new Date(startDate);
                let addedDays = 0;
                while (addedDays <= offset) {
                    date.setDate(date.getDate() + 1);
                    if (date.getDay() != 0 && date.getDay() != 6) {
                        addedDays++;
                    }
                }
                return date;
            }

            function validateInstallments() {
                const tanggalPengambilan = new Date(document.getElementById('tanggal_pengambilan').value);
                const tanggalPenagihan = new Date(document.getElementById('tanggal').value);
                const jumlah_dp = parseInt(document.getElementById('jumlah_dp').value);

                if (jumlah_dp && tanggalPengambilan && tanggalPenagihan && tanggalPengambilan > tanggalPenagihan) {
                    let maxInstallments = countWorkingDays(tanggalPenagihan, tanggalPengambilan);
                    if (jumlah_dp > maxInstallments) {
                        alert(`Jumlah cicilan melebihi hari kerja sebelum pengambilan (${maxInstallments} hari kerja tersedia).`);
                        document.getElementById('jumlah_dp').value = '';
                        calculateJatuhTempo();
                    }
                }
            }

            function countWorkingDays(start, end) {
                let count = 0;
                let current = new Date(start);
                while (current < end) {
                    current.setDate(current.getDate() + 1);
                    if (current.getDay() !== 0 && current.getDay() !== 6) {
                        count++;
                    }
                }
                return count;
            }
        </script>

    </div>
</div>