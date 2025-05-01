<div class="container-fluid">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="formTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?php if ($submenu == "ContactAdd") {
                echo "active";
            } ?>" id="contact-tab" data-toggle="tab" href="#contact" role="tab">Tambah Data Dies
                Natalis</a>
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
                                    <label for="tanggal_dn">Tanggal Dies Natalis (Format: DD-MM. Angka Saja)</label>
                                    <div class="d-flex">
                                        <select class="form-control" id="dn_tanggal"
                                            style="width: 50%; margin-right: 5px;">
                                            <option value="">Tanggal</option>
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <select class="form-control" id="dn_bulan" style="width: 50%;">
                                            <option value="">Bulan</option>
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <!-- Hidden input untuk menyimpan nilai gabungan dalam format DD-MM -->
                                        <input type="hidden" id="tanggal_dn" name="tanggal_dn">
                                    </div>
                                    <small class="form-text text-muted" id="display_tanggal_dn"></small>
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
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2"
                                    placeholder="Deskripsi produk/pesanan"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pengambilan">Tanggal Pengambilan Barang</label>
                                <input type="date" class="form-control" id="tanggal_pengambilan"
                                    name="tanggal_pengambilan" required onchange="validateInstallments()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total">Total Keseluruhan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="total" name="total" required min="0"
                                        step="1000">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_dp">Rencana Cicilan DP</label>
                                <select class="form-control" id="jumlah_dp" name="jumlah_dp" required
                                    onchange="calculateJatuhTempo()">
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
            document.addEventListener('DOMContentLoaded', function () {
                // Elemen-elemen DOM
                const tanggalSelect = document.getElementById('dn_tanggal');
                const bulanSelect = document.getElementById('dn_bulan');
                const hiddenInput = document.getElementById('tanggal_dn');
                const displayText = document.getElementById('display_tanggal_dn');

                // Data bulan dengan jumlah hari
                const dataBulan = [
                    { nama: 'Januari', hari: 31 },
                    { nama: 'Februari', hari: 29 }, // Menggunakan 29 untuk tahun kabisat
                    { nama: 'Maret', hari: 31 },
                    { nama: 'April', hari: 30 },
                    { nama: 'Mei', hari: 31 },
                    { nama: 'Juni', hari: 30 },
                    { nama: 'Juli', hari: 31 },
                    { nama: 'Agustus', hari: 31 },
                    { nama: 'September', hari: 30 },
                    { nama: 'Oktober', hari: 31 },
                    { nama: 'November', hari: 30 },
                    { nama: 'Desember', hari: 31 }
                ];

                // Populate dropdown bulan
                dataBulan.forEach((bulan, index) => {
                    const option = document.createElement('option');
                    option.value = (index + 1).toString().padStart(2, '0');
                    option.textContent = bulan.nama;
                    bulanSelect.appendChild(option);
                });

                // Fungsi untuk memperbarui dropdown tanggal berdasarkan bulan yang dipilih
                function updateTanggalDropdown() {
                    // Simpan tanggal yang dipilih sebelumnya (jika ada)
                    const selectedTanggal = tanggalSelect.value;

                    // Kosongkan dropdown tanggal
                    tanggalSelect.innerHTML = '<option value="">Tanggal</option>';

                    // Tentukan jumlah hari berdasarkan bulan yang dipilih
                    const bulanValue = bulanSelect.value;
                    if (bulanValue) {
                        const bulanIndex = parseInt(bulanValue) - 1;
                        const jumlahHari = dataBulan[bulanIndex].hari;

                        // Populate tanggal (1-jumlahHari)
                        for (let i = 1; i <= jumlahHari; i++) {
                            const option = document.createElement('option');
                            option.value = i.toString().padStart(2, '0');
                            option.textContent = i;
                            tanggalSelect.appendChild(option);
                        }

                        // Coba pilih kembali tanggal yang sebelumnya dipilih jika masih valid
                        if (selectedTanggal && parseInt(selectedTanggal) <= jumlahHari) {
                            tanggalSelect.value = selectedTanggal;
                        }
                    }
                }

                // Function untuk memperbarui nilai gabungan
                function updateTanggalDN() {
                    const tanggal = tanggalSelect.value;
                    const bulan = bulanSelect.value;

                    if (tanggal && bulan) {
                        // Format DD-MM
                        hiddenInput.value = `${tanggal}-${bulan}`;

                        // Tampilkan format yang lebih user-friendly
                        const bulanIndex = parseInt(bulan) - 1;
                        displayText.textContent = `Dipilih: ${tanggal} ${dataBulan[bulanIndex].nama}`;
                    } else {
                        hiddenInput.value = '';
                        displayText.textContent = '';
                    }
                }

                // Event listeners
                bulanSelect.addEventListener('change', function () {
                    updateTanggalDropdown();
                    updateTanggalDN();
                });

                tanggalSelect.addEventListener('change', updateTanggalDN);

                // Jika sudah ada nilai yang disimpan, populate select
                if (hiddenInput.value) {
                    const parts = hiddenInput.value.split('-');
                    if (parts.length === 2) {
                        bulanSelect.value = parts[1];
                        updateTanggalDropdown();
                        tanggalSelect.value = parts[0];
                        updateTanggalDN();
                    }
                }

                // Validasi saat form di-submit
                const form = hiddenInput.closest('form');
                if (form) {
                    form.addEventListener('submit', function (e) {
                        if (form.checkValidity() === false || !hiddenInput.value) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Highlight jika belum dipilih
                            if (!hiddenInput.value) {
                                if (!tanggalSelect.value) tanggalSelect.classList.add('is-invalid');
                                if (!bulanSelect.value) bulanSelect.classList.add('is-invalid');
                            }
                        }
                    });

                    // Hapus highlight saat dipilih
                    tanggalSelect.addEventListener('change', function () {
                        this.classList.remove('is-invalid');
                    });
                    bulanSelect.addEventListener('change', function () {
                        this.classList.remove('is-invalid');
                    });
                }
            });
        </script>

        <style>
            /* Styling untuk dropdown */
            #dn_tanggal,
            #dn_bulan {
                border-radius: 4px;
            }

            #display_tanggal_dn {
                margin-top: 5px;
                font-weight: 500;
            }
        </style>
    </div>
</div>