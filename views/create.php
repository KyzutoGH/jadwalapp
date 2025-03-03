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
                                    <label for="tanggal_pengambilan">Tanggal Pengambilan Barang</label>
                                    <input type="date" class="form-control" id="tanggal_pengambilan"
                                        name="tanggal_pengambilan" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan Produk</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2"
                                        placeholder="Deskripsi produk/pesanan"></textarea>
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
                                            min="0" step="1000">
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

                        <!-- Container untuk DP1 (selalu muncul) -->
                        <div class="card mt-4 mb-4" id="dp1_container">
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
                                                    name="dp1_nominal" required min="0" step="1000">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dp1_metode">Metode Pembayaran</label>
                                            <select class="form-control" id="dp1_metode" name="dp1_metode" required>
                                                <option value="cash">Cash</option>
                                                <option value="transfer">Transfer Bank</option>
                                                <option value="qris">QRIS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dp1_status">Status Pembayaran</label>
                                            <select class="form-control" id="dp1_status" name="dp1_status" required>
                                                <option value="lunas">Lunas</option>
                                                <option value="pending">Belum Dibayar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Container untuk DP2 dan DP3 (dinamis) -->
                        <div id="dp_container"></div>

                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Total Pembayaran:</strong>
                                    <span id="display_total">Rp 0</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Total DP Direncanakan:</strong>
                                    <span id="total_dp_direncanakan">Rp 0</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Sisa yang Harus Dibayar:</strong>
                                    <span id="sisa_pembayaran">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                            <button type="button" class="btn btn-info" id="autofillDP">Isi DP Pertama Saja</button>
                            <a href="index.php?menu=Penagihan" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>

                    <script>
                        /**
                         * Calculates installment dates based on start date, end date, and number of installments
                         * @param {Date} startDate - Date of first payment
                         * @param {Date} endDate - Date when goods will be picked up
                         * @param {Number} installments - Number of installments (1-3)
                         * @returns {Array} Array of date objects for each installment
                         */
                        function calculateInstallmentDates(startDate, endDate, installments) {
                            // Validate inputs
                            if (installments < 1 || installments > 3) {
                                throw new Error("Jumlah cicilan harus antara 1 sampai 3");
                            }

                            if (!(startDate instanceof Date) || isNaN(startDate.getTime())) {
                                throw new Error("Tanggal awal tidak valid");
                            }

                            if (!(endDate instanceof Date) || isNaN(endDate.getTime())) {
                                throw new Error("Tanggal akhir tidak valid");
                            }

                            if (endDate <= startDate) {
                                throw new Error("Tanggal pengambilan barang harus setelah tanggal pembayaran pertama");
                            }

                            // Calculate time difference and interval
                            const totalDays = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24));
                            const intervalDays = Math.floor(totalDays / installments);

                            // Create dates array
                            const dates = [new Date(startDate)]; // First payment date is the start date

                            // Calculate subsequent payment dates
                            for (let i = 1; i < installments; i++) {
                                const nextDate = new Date(startDate);
                                nextDate.setDate(startDate.getDate() + (intervalDays * i));
                                dates.push(nextDate);
                            }

                            return dates;
                        }

                        /**
                         * Formats a date as YYYY-MM-DD for input[type="date"]
                         * @param {Date} date - Date to format
                         * @returns {String} Formatted date string
                         */
                        function formatDateForInput(date) {
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const day = String(date.getDate()).padStart(2, '0');
                            return `${year}-${month}-${day}`;
                        }

                        /**
                         * Formats a number as Indonesian Rupiah
                         * @param {Number} angka - Number to format
                         * @returns {String} Formatted currency string
                         */
                        function formatRupiah(angka) {
                            return 'Rp ' + Number(angka).toLocaleString('id-ID');
                        }

                        /**
                         * Updates payment information display
                         */
                        function updatePembayaran() {
                            const total = parseFloat(document.getElementById('total').value) || 0;
                            let totalDP = 0;

                            // Get value of DP1
                            totalDP += parseFloat(document.getElementById('dp1_nominal').value) || 0;

                            // Do not include DP2 and DP3 in the calculation as they will be filled later
                            // We only calculate what's actually been entered in DP1

                            const sisa = total - totalDP;

                            document.getElementById('display_total').textContent = formatRupiah(total);
                            document.getElementById('total_dp_direncanakan').textContent = formatRupiah(totalDP);
                            document.getElementById('sisa_pembayaran').textContent = formatRupiah(sisa);

                            // Highlight in red if DP1 exceeds the total amount
                            const totalDPElement = document.getElementById('total_dp_direncanakan');
                            if (totalDP > total) {
                                totalDPElement.classList.add('text-danger');
                                totalDPElement.classList.add('font-weight-bold');
                            } else {
                                totalDPElement.classList.remove('text-danger');
                                totalDPElement.classList.remove('font-weight-bold');
                            }
                        }

                        /**
                         * Updates DP sections based on number of installments
                         */
                        function updateDPSections() {
                            const jumlahDP = parseInt(document.getElementById('jumlah_dp').value) || 0;
                            const startDate = new Date(document.getElementById('tanggal').value);
                            const endDate = new Date(document.getElementById('tanggal_pengambilan').value);
                            const dpContainer = document.getElementById('dp_container');

                            // Clear previous DP sections
                            dpContainer.innerHTML = '';

                            if (jumlahDP > 0 && !isNaN(startDate.getTime()) && !isNaN(endDate.getTime())) {
                                try {
                                    const dates = calculateInstallmentDates(startDate, endDate, jumlahDP);

                                    // Update DP1 date
                                    if (dates[0]) {
                                        document.getElementById('dp1_tenggat').value = formatDateForInput(dates[0]);
                                    }

                                    // Create sections for DP2 and DP3 if needed, but with hidden inputs
                                    // They will be recorded in the database but not shown on this form
                                    for (let i = 1; i < jumlahDP; i++) {
                                        const dpNumber = i + 1;
                                        const dpCard = document.createElement('div');
                                        dpCard.className = 'card mt-4 mb-4';
                                        dpCard.innerHTML = `
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title mb-0">Pembayaran DP ke-${dpNumber} (akan diisi kemudian)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="alert alert-info">
                                                Pembayaran DP ke-${dpNumber} direncanakan pada tanggal 
                                                <strong>${formatDateForInput(dates[i])}</strong>.
                                                Silakan isi data pembayaran di menu "Update Penagihan" sesuai tenggat.
                                            </p>
                                            <input type="hidden" id="dp${dpNumber}_tenggat" name="dp${dpNumber}_tenggat" 
                                                value="${formatDateForInput(dates[i])}">
                                            <input type="hidden" id="dp${dpNumber}_nominal" name="dp${dpNumber}_nominal" value="0">
                                            <input type="hidden" id="dp${dpNumber}_metode" name="dp${dpNumber}_metode" value="pending">
                                            <input type="hidden" id="dp${dpNumber}_status" name="dp${dpNumber}_status" value="pending">
                                        </div>
                                    </div>
                                </div>
                            `;
                                        dpContainer.appendChild(dpCard);
                                    }

                                    // Update payment calculations - only include DP1
                                    updatePembayaran();
                                } catch (error) {
                                    alert(error.message);
                                }
                            }
                        }

                        /**
                         * Set DP1 to the total amount for 1-time payment
                         */
                        function autofillDP1() {
                            const total = parseFloat(document.getElementById('total').value) || 0;
                            const jumlahDP = parseInt(document.getElementById('jumlah_dp').value) || 0;

                            if (total <= 0) {
                                alert('Masukkan total pembayaran terlebih dahulu');
                                return;
                            }

                            if (jumlahDP <= 0) {
                                alert('Pilih jumlah cicilan terlebih dahulu');
                                return;
                            }

                            // If 1 payment, set DP1 to full amount
                            if (jumlahDP === 1) {
                                document.getElementById('dp1_nominal').value = total;
                            } else {
                                // For multiple payments, set DP1 to a suggested 50% of total
                                const suggestedAmount = Math.floor(total * 0.5 / 1000) * 1000; // Round to nearest thousand
                                document.getElementById('dp1_nominal').value = suggestedAmount;
                            }

                            updatePembayaran();
                        }

                        // Add event listeners when the page loads
                        document.addEventListener('DOMContentLoaded', function () {
                            // Initialize Select2 for customer dropdown
                            if (typeof $.fn !== 'undefined' && $.fn.select2) {
                                $('.select2').select2({
                                    placeholder: 'Pilih customer',
                                    allowClear: true
                                });
                            }

                            // Set today's date as default for tanggal and tanggal_pengambilan
                            const today = new Date();
                            document.getElementById('tanggal').value = formatDateForInput(today);

                            // Set default pengambilan date to 30 days from now
                            const defaultEndDate = new Date(today);
                            defaultEndDate.setDate(today.getDate() + 30);
                            document.getElementById('tanggal_pengambilan').value = formatDateForInput(defaultEndDate);

                            // Add event listeners for date and installment changes
                            document.getElementById('tanggal').addEventListener('change', updateDPSections);
                            document.getElementById('tanggal_pengambilan').addEventListener('change', updateDPSections);
                            document.getElementById('jumlah_dp').addEventListener('change', updateDPSections);
                            document.getElementById('total').addEventListener('input', updatePembayaran);
                            document.getElementById('dp1_nominal').addEventListener('input', updatePembayaran);
                            document.getElementById('autofillDP').addEventListener('click', autofillDP1);

                            // Initialize the form with default values
                            updateDPSections();

                            // Validate form before submission
                            document.getElementById('penagihanForm').onsubmit = function (e) {
                                const total = parseFloat(document.getElementById('total').value) || 0;
                                const dp1Value = parseFloat(document.getElementById('dp1_nominal').value) || 0;

                                // Check if customer is selected
                                if (!document.getElementById('customer').value) {
                                    e.preventDefault();
                                    alert('Silakan pilih customer terlebih dahulu!');
                                    return false;
                                }

                                // Check total amount
                                if (total <= 0) {
                                    e.preventDefault();
                                    alert('Total pembayaran harus lebih dari 0!');
                                    return false;
                                }

                                // Check DP1 amount
                                if (dp1Value <= 0) {
                                    e.preventDefault();
                                    alert('Nominal DP pertama harus lebih dari 0!');
                                    return false;
                                }

                                // Check if DP1 exceeds total
                                if (dp1Value > total) {
                                    e.preventDefault();
                                    alert('Nominal DP pertama tidak boleh melebihi total pembayaran!');
                                    return false;
                                }

                                return confirm('Apakah data yang dimasukkan sudah benar?');
                            };
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>