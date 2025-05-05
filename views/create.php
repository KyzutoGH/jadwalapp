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
                                    <label for="nama_sekolah">Nama Sekolah <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat Sekolah <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="form-group">
                                    <label for="nomor_kontak">Nomor Kontak (10–15 digit angka tanpa spasi) <span
                                            style="color: red;">*</span></label>
                                    <input type="tel" class="form-control" id="nomor_kontak" name="nomor_kontak"
                                        pattern="[0-9]{10,15}" required>
                                    <small class="form-text text-muted">Masukkan hanya angka, tanpa spasi atau simbol
                                        (+, -, dll).</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pemilik_kontak">Pemilik Kontak <span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="pemilik_kontak" name="pemilik_kontak"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="jabatan">Jabatan <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_dn">Tanggal Dies Natalis <span
                                            style="color: red;">*</span></label>
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
                    <form action="config/create_dp.php?debug=1" method="POST" id="penagihanForm"
                        onsubmit="return validatePenagihanForm()">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal">Tanggal Pre Order <span style="color: red;">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_selection">Pilih Customer <span
                                        style="color: red;">*</span></label>
                                <select class="form-control" id="customer_selection" name="customer_selection"
                                    onchange="handleCustomerSelection()">
                                    <option value="new">Tambah Customer Baru</option>
                                    <option value="existing">Pilih dari Database</option>
                                </select>
                            </div>
                        </div>

                        <!-- Form untuk customer baru -->
                        <div id="new_customer_form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer">Nama Customer <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="customer" name="customer" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kontak">Kontak Customer <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="kontak" name="kontak" required>
                                </div>
                            </div>
                        </div>

                        <!-- Form untuk memilih customer dari database -->
                        <div id="existing_customer_form" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="existing_customer">Pilih Sekolah <span
                                            style="color: red;">*</span></label>
                                    <select class="form-control" id="existing_customer" name="existing_customer"
                                        onchange="fillCustomerDetails()">
                                        <option value="">Pilih sekolah</option>
                                        <!-- PHP untuk generate opsi dari database -->
                                        <?php
                                        // Get all schools from datadn table
                                        $query = "SELECT id, nama_sekolah, pemilik_kontak, nomor FROM datadn WHERE status = 1 OR status = 2 ORDER BY nama_sekolah ASC";
                                        $result = mysqli_query($db, $query);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row['id'] . '" data-name="' . $row['pemilik_kontak'] . '" data-contact="' . $row['nomor'] . '" data-school="' . $row['nama_sekolah'] . '">' . $row['nama_sekolah'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="kontak_existing">Kontak Person <span
                                            style="color: red;">*</span></label>
                                    <div class="input-group">
                                        <!-- Teks tampil -->
                                        <span class="form-control" id="kontak_name_display" readonly></span>
                                        <span class="form-control" id="kontak_existing_display" readonly></span>

                                        <!-- Hidden input untuk submit -->
                                        <input type="hidden" id="kontak_name" name="kontak_name">
                                        <input type="hidden" id="kontak_existing" name="kontak_existing">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Pilih Produk <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="jaket_check"
                                                    name="product_types[]" value="jaket"
                                                    onchange="updateProductSelection()">
                                                <label class="custom-control-label" for="jaket_check">Jaket</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="stiker_check"
                                                    name="product_types[]" value="stiker"
                                                    onchange="updateProductSelection()">
                                                <label class="custom-control-label" for="stiker_check">Stiker</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox"
                                                    id="barang_jadi_check" name="product_types[]" value="barang_jadi"
                                                    onchange="updateProductSelection()">
                                                <label class="custom-control-label" for="barang_jadi_check">Barang
                                                    Jadi</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Produk Jaket selection (hidden by default) -->
                        <div id="jaket_selection" class="product-selection" style="display: none;">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="jaket_product">Pilih Jaket</label>
                                    <select class="form-control" id="jaket_product" name="jaket_product"
                                        onchange="calculateTotal()">
                                        <option value="">Pilih Jaket</option>
                                        <!-- PHP untuk generate opsi dari database -->
                                        <?php
                                        // Get jackets with stock > 0
                                        $query = "SELECT id_jaket, jenis, namabarang, ukuran, harga, stock FROM jaket WHERE stock > 0 ORDER BY namabarang ASC";
                                        $result = mysqli_query($db, $query);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row['id_jaket'] . '" data-price="' . $row['harga'] . '" data-stock="' . $row['stock'] . '">'
                                                . $row['namabarang'] . ' - ' . $row['jenis'] . ' - ' . $row['ukuran']
                                                . ' - Rp ' . number_format($row['harga'], 0, ',', '.')
                                                . ' (Stok: ' . $row['stock'] . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="jaket_qty">Jumlah</label>
                                    <input type="number" class="form-control" id="jaket_qty" name="jaket_qty" min="1"
                                        value="1" onchange="calculateTotal(); validateQuantity('jaket');">
                                    <small class="text-danger" id="jaket_stock_warning" style="display: none;">Jumlah
                                        melebihi stok tersedia!</small>
                                </div>
                            </div>
                        </div>
                        <!-- Produk Stiker selection (hidden by default) -->
                        <div id="stiker_selection" class="product-selection" style="display: none;">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="stiker_product">Pilih Stiker</label>
                                    <select class="form-control" id="stiker_product" name="stiker_product"
                                        onchange="calculateTotal()">
                                        <option value="">Pilih Stiker</option>
                                        <!-- PHP untuk generate opsi dari database -->
                                        <?php
                                        // Get stickers with stock > 0
                                        $query = "SELECT id_sticker, nama, bagian, harga, stock FROM stiker WHERE stock > 0 ORDER BY nama ASC";
                                        $result = mysqli_query($db, $query);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row['id_sticker'] . '" data-stock="' . $row['stock'] . '" data-price="' . $row['harga'] . '">'
                                                . $row['nama'] . ' - ' . $row['bagian']
                                                . ' (Stok: ' . $row['stock'] . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="stiker_qty">Jumlah</label>
                                    <input type="number" class="form-control" id="stiker_qty" name="stiker_qty" min="1"
                                        value="1" onchange="calculateTotal(); validateQuantity('stiker');">
                                    <small class="text-danger" id="stiker_stock_warning" style="display: none;">Jumlah
                                        melebihi stok tersedia!</small>
                                </div>
                            </div>
                        </div>
                        <!-- Produk Barang Jadi selection (hidden by default) -->
                        <div id="barang_jadi_selection" class="product-selection" style="display: none;">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="barang_jadi_product">Pilih Barang Jadi</label>
                                    <select class="form-control" id="barang_jadi_product" name="barang_jadi_product"
                                        onchange="calculateTotal()">
                                        <option value="">Pilih Barang Jadi</option>
                                        <!-- PHP untuk generate opsi dari database -->
                                        <?php
                                        // Get finished goods with stock > 0
                                        $query = "SELECT bj.id_barang, bj.nama_produk, 
            j.harga AS harga_jaket, j.namabarang AS nama_jaket, 
            j.jenis AS jenis_jaket, j.ukuran AS ukuran_jaket,
            s.nama AS nama_stiker, s.bagian AS bagian_stiker, 
            s.harga AS harga_stiker, bj.stock 
          FROM barang_jadi bj
          LEFT JOIN jaket j ON bj.id_jaket = j.id_jaket
          LEFT JOIN stiker s ON bj.id_sticker = s.id_sticker
          WHERE bj.stock > 0
          ORDER BY bj.nama_produk ASC";

                                        $result = mysqli_query($db, $query);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Calculate total price for finished goods (jaket + stiker)
                                            $total_price = $row['harga_jaket'] + $row['harga_stiker'];

                                            // Description includes jaket and stiker details
                                            $description = $row['nama_produk'];
                                            if ($row['nama_jaket']) {
                                                $description .= ' - Jaket: ' . $row['nama_jaket'] . ' (' . $row['jenis_jaket'] . ', ' . $row['ukuran_jaket'] . ')';
                                            }
                                            if ($row['nama_stiker']) {
                                                $description .= ' - Stiker: ' . $row['nama_stiker'] . ' (' . $row['bagian_stiker'] . ')';
                                            }

                                            // Output the option with combined price
                                            echo '<option value="' . $row['id_barang'] . '" data-price="' . $total_price . '" data-stock="' . $row['stock'] . '">'
                                                . $description . ' - Rp ' . number_format($total_price, 0, ',', '.')
                                                . ' (Stok: ' . $row['stock'] . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="barang_jadi_qty">Jumlah</label>
                                    <input type="number" class="form-control" id="barang_jadi_qty"
                                        name="barang_jadi_qty" min="1" value="1"
                                        onchange="calculateTotal(); validateQuantity('barang_jadi');">
                                    <small class="text-danger" id="barang_jadi_stock_warning"
                                        style="display: none;">Jumlah melebihi stok tersedia!</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="keterangan">Keterangan Produk</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2"
                                    placeholder="Deskripsi produk/pesanan"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pengambilan">Tanggal Pengambilan Barang <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control" id="tanggal_pengambilan"
                                    name="tanggal_pengambilan" required onchange="validateInstallments()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total">Total Keseluruhan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="total" name="total" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_dp">Rencana Cicilan DP <span style="color: red;">*</span></label>
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

            // Document ready function
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize date fields with today's date
                const today = new Date();
                const formattedDate = formatDate(today);

                const tanggalInput = document.getElementById('tanggal');
                if (tanggalInput && !tanggalInput.value) {
                    tanggalInput.value = formattedDate;
                }

                // Set minimum date for pengambilan to be at least tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowFormatted = formatDate(tomorrow);

                const pengambilanInput = document.getElementById('tanggal_pengambilan');
                if (pengambilanInput) {
                    pengambilanInput.min = tomorrowFormatted;
                }

                // Initialize the tanggal_dn selector for the Contact tab
                initializeDateSelectors();

                // Set default selection to 'new customer'
                handleCustomerSelection();

                // Add listeners for product selections to highlight active section
                const productTypes = ['jaket', 'stiker', 'barang_jadi'];
                productTypes.forEach(type => {
                    const select = document.getElementById(type + '_product');
                    if (select) {
                        select.addEventListener('change', function () {
                            if (this.value) {
                                highlightActiveSection(type);
                                validateQuantity(type);
                            }
                        });
                    }
                });

                // Set min values for quantity inputs
                const qtyInputs = document.querySelectorAll('input[type="number"]');
                qtyInputs.forEach(input => {
                    input.min = 1;
                    if (!input.value || parseInt(input.value) < 1) {
                        input.value = 1;
                    }

                    // Add event listener for real-time validation
                    input.addEventListener('input', function () {
                        if (this.value < 1) {
                            this.value = 1;
                        }

                        // Extract product type from ID
                        const productType = this.id.split('_')[0];
                        validateQuantity(productType);
                    });
                });

                // Initialize form submission validation
                if (document.getElementById('penagihanForm')) {
                    document.getElementById('penagihanForm').addEventListener('submit', validatePenagihanForm);
                }

                // Initialize contact form submission validation
                if (document.getElementById('contactForm')) {
                    document.getElementById('contactForm').addEventListener('submit', validateContactForm);
                }

                // Add event listeners for date changes
                if (tanggalInput) {
                    tanggalInput.addEventListener('change', function () {
                        updatePengambilanMinDate();
                        if (document.getElementById('jumlah_dp').value) {
                            calculateJatuhTempo();
                        }
                    });
                }

                if (pengambilanInput) {
                    pengambilanInput.addEventListener('change', function () {
                        if (document.getElementById('jumlah_dp').value) {
                            validateInstallments();
                            calculateJatuhTempo();
                        }
                    });
                }

                // Initialize tanggal_dn hidden input for Contact tab
                if (document.getElementById('dn_tanggal') && document.getElementById('dn_bulan')) {
                    document.getElementById('dn_tanggal').addEventListener('change', updateHiddenDateInput);
                    document.getElementById('dn_bulan').addEventListener('change', updateHiddenDateInput);
                }
            });

            // Format date as YYYY-MM-DD
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            }

            // Update minimum date for pengambilan based on tanggal
            function updatePengambilanMinDate() {
                const tanggalInput = document.getElementById('tanggal');
                const pengambilanInput = document.getElementById('tanggal_pengambilan');

                if (tanggalInput && pengambilanInput) {
                    const newPreOrderDate = new Date(tanggalInput.value);
                    if (!isNaN(newPreOrderDate.getTime())) {
                        const nextDay = new Date(newPreOrderDate);
                        nextDay.setDate(nextDay.getDate() + 1);
                        const nextDayFormatted = formatDate(nextDay);

                        pengambilanInput.min = nextDayFormatted;

                        // If current pengambilan date is now invalid, reset it
                        const currentPengambilan = new Date(pengambilanInput.value);
                        if (!isNaN(currentPengambilan.getTime()) && currentPengambilan <= newPreOrderDate) {
                            pengambilanInput.value = nextDayFormatted;
                        }
                    }
                }
            }

            // Initialize date selectors for the dies natalis form
            function initializeDateSelectors() {
                const tanggalSelect = document.getElementById('dn_tanggal');
                const bulanSelect = document.getElementById('dn_bulan');

                if (tanggalSelect && bulanSelect) {
                    // Populate days (1-31)
                    for (let i = 1; i <= 31; i++) {
                        const option = document.createElement('option');
                        option.value = String(i).padStart(2, '0');
                        option.textContent = i;
                        tanggalSelect.appendChild(option);
                    }

                    // Populate months (1-12)
                    const monthNames = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    for (let i = 0; i < 12; i++) {
                        const option = document.createElement('option');
                        option.value = String(i + 1).padStart(2, '0');
                        option.textContent = monthNames[i];
                        bulanSelect.appendChild(option);
                    }
                }
            }

            // Update hidden date input for dies natalis
            function updateHiddenDateInput() {
                const tanggalSelect = document.getElementById('dn_tanggal');
                const bulanSelect = document.getElementById('dn_bulan');
                const hiddenInput = document.getElementById('tanggal_dn');
                const displayElement = document.getElementById('display_tanggal_dn');

                if (tanggalSelect && bulanSelect && hiddenInput) {
                    const tanggal = tanggalSelect.value;
                    const bulan = bulanSelect.value;

                    if (tanggal && bulan) {
                        const formattedDate = `${tanggal}-${bulan}`;
                        hiddenInput.value = formattedDate;

                        if (displayElement) {
                            displayElement.textContent = `Tanggal Dies Natalis: ${formattedDate}`;
                        }
                    } else {
                        hiddenInput.value = '';
                        if (displayElement) {
                            displayElement.textContent = '';
                        }
                    }
                }
            }

            // Handle customer selection
            function handleCustomerSelection() {
                const selectionType = document.getElementById('customer_selection');

                if (!selectionType) return;

                const selectedValue = selectionType.value;

                if (selectedValue === 'new') {
                    document.getElementById('new_customer_form').style.display = 'block';
                    document.getElementById('existing_customer_form').style.display = 'none';

                    // Reset existing customer fields
                    const existingCustomer = document.getElementById('existing_customer');
                    const kontakName = document.getElementById('kontak_name');
                    const kontakExisting = document.getElementById('kontak_existing');

                    if (existingCustomer) existingCustomer.value = '';
                    if (kontakName) kontakName.value = '';
                    if (kontakExisting) kontakExisting.value = '';

                    // Make new customer fields required
                    const customer = document.getElementById('customer');
                    const kontak = document.getElementById('kontak');
                    const existingCustomerField = document.getElementById('existing_customer');

                    if (customer) customer.required = true;
                    if (kontak) kontak.required = true;
                    if (existingCustomerField) existingCustomerField.required = false;
                } else {
                    document.getElementById('new_customer_form').style.display = 'none';
                    document.getElementById('existing_customer_form').style.display = 'block';

                    // Reset new customer fields
                    const customer = document.getElementById('customer');
                    const kontak = document.getElementById('kontak');

                    if (customer) customer.value = '';
                    if (kontak) kontak.value = '';

                    // Make existing customer selection required
                    const customerField = document.getElementById('customer');
                    const kontakField = document.getElementById('kontak');
                    const existingCustomerField = document.getElementById('existing_customer');

                    if (customerField) customerField.required = false;
                    if (kontakField) kontakField.required = false;
                    if (existingCustomerField) existingCustomerField.required = true;
                }
            }

            // Fill customer details from selected existing customer
            function fillCustomerDetails() {
                const selectElement = document.getElementById('existing_customer');

                if (!selectElement) return;

                const kontakName = document.getElementById('kontak_name');
                const kontakExisting = document.getElementById('kontak_existing');
                const kontakNameDisplay = document.getElementById('kontak_name_display');
                const kontakExistingDisplay = document.getElementById('kontak_existing_display');
                const customer = document.getElementById('customer');
                const kontak = document.getElementById('kontak');

                if (selectElement.value) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const contactName = selectedOption.getAttribute('data-name') || '';
                    const contactNumber = selectedOption.getAttribute('data-contact') || '';
                    const schoolName = selectedOption.getAttribute('data-school') || '';

                    // Hidden inputs
                    if (kontakName) kontakName.value = contactName;
                    if (kontakExisting) kontakExisting.value = contactNumber;

                    // Display to user
                    if (kontakNameDisplay) kontakNameDisplay.textContent = contactName;
                    if (kontakExistingDisplay) kontakExistingDisplay.textContent = contactNumber;

                    // Untuk keperluan submit form
                    if (customer) customer.value = schoolName;
                    if (kontak) kontak.value = contactNumber;

                } else {
                    if (kontakName) kontakName.value = '';
                    if (kontakExisting) kontakExisting.value = '';
                    if (kontakNameDisplay) kontakNameDisplay.textContent = '';
                    if (kontakExistingDisplay) kontakExistingDisplay.textContent = '';
                }
            }

            // Update product selection based on checkboxes
            function updateProductSelection() {
                const jaketCheck = document.getElementById('jaket_check');
                const stikerCheck = document.getElementById('stiker_check');
                const barangJadiCheck = document.getElementById('barang_jadi_check');

                if (!jaketCheck || !stikerCheck || !barangJadiCheck) return;

                const jaketChecked = jaketCheck.checked;
                const stikerChecked = stikerCheck.checked;
                const barangJadiChecked = barangJadiCheck.checked;

                // Show/hide product selections
                const jaketSelection = document.getElementById('jaket_selection');
                const stikerSelection = document.getElementById('stiker_selection');
                const barangJadiSelection = document.getElementById('barang_jadi_selection');

                if (jaketSelection) jaketSelection.style.display = jaketChecked ? 'block' : 'none';
                if (stikerSelection) stikerSelection.style.display = stikerChecked ? 'block' : 'none';
                if (barangJadiSelection) barangJadiSelection.style.display = barangJadiChecked ? 'block' : 'none';

                // Reset unselected product values and quantities
                if (!jaketChecked) {
                    const jaketProduct = document.getElementById('jaket_product');
                    const jaketQty = document.getElementById('jaket_qty');
                    const jaketWarning = document.getElementById('jaket_stock_warning');

                    if (jaketProduct) jaketProduct.value = '';
                    if (jaketQty) jaketQty.value = 1;
                    if (jaketWarning) jaketWarning.style.display = 'none';
                }

                if (!stikerChecked) {
                    const stikerProduct = document.getElementById('stiker_product');
                    const stikerQty = document.getElementById('stiker_qty');
                    const stikerWarning = document.getElementById('stiker_stock_warning');

                    if (stikerProduct) stikerProduct.value = '';
                    if (stikerQty) stikerQty.value = 1;
                    if (stikerWarning) stikerWarning.style.display = 'none';
                }

                if (!barangJadiChecked) {
                    const barangJadiProduct = document.getElementById('barang_jadi_product');
                    const barangJadiQty = document.getElementById('barang_jadi_qty');
                    const barangJadiWarning = document.getElementById('barang_jadi_stock_warning');

                    if (barangJadiProduct) barangJadiProduct.value = '';
                    if (barangJadiQty) barangJadiQty.value = 1;
                    if (barangJadiWarning) barangJadiWarning.style.display = 'none';
                }

                // Update the total price
                calculateTotal();
            }

            // Calculate total price from selected products with quantities
            function calculateTotal() {
                let total = 0;

                // Check if required elements exist
                const jaketCheck = document.getElementById('jaket_check');
                const stikerCheck = document.getElementById('stiker_check');
                const barangJadiCheck = document.getElementById('barang_jadi_check');

                if (!jaketCheck || !stikerCheck || !barangJadiCheck) return 0;

                // Add jaket price if selected
                if (jaketCheck.checked) {
                    const jaketSelect = document.getElementById('jaket_product');
                    const jaketQty = document.getElementById('jaket_qty');

                    if (jaketSelect && jaketSelect.value && jaketQty) {
                        const selectedOption = jaketSelect.options[jaketSelect.selectedIndex];
                        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        const qty = parseInt(jaketQty.value) || 1;
                        total += price * qty;
                    }
                }

                // Add stiker price if it has price attribute
                if (stikerCheck.checked) {
                    const stikerSelect = document.getElementById('stiker_product');
                    const stikerQty = document.getElementById('stiker_qty');

                    if (stikerSelect && stikerSelect.value && stikerQty) {
                        const selectedOption = stikerSelect.options[stikerSelect.selectedIndex];
                        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        const qty = parseInt(stikerQty.value) || 1;
                        if (price > 0) {
                            total += price * qty;
                        }
                    }
                }

                // Add barang jadi price if selected
                if (barangJadiCheck.checked) {
                    const barangJadiSelect = document.getElementById('barang_jadi_product');
                    const barangJadiQty = document.getElementById('barang_jadi_qty');

                    if (barangJadiSelect && barangJadiSelect.value && barangJadiQty) {
                        const selectedOption = barangJadiSelect.options[barangJadiSelect.selectedIndex];
                        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        const qty = parseInt(barangJadiQty.value) || 1;
                        total += price * qty;
                    }
                }

                // Format the total as currency
                const formattedTotal = new Intl.NumberFormat('id-ID').format(total);

                // Update the total field
                const totalField = document.getElementById('total');
                if (totalField) {
                    totalField.value = formattedTotal;
                }

                // If total_raw field exists, update it with the unformatted value
                const totalRawField = document.getElementById('total_raw');
                if (totalRawField) {
                    totalRawField.value = total;
                }

                return total;
            }

            // Validate quantity against available stock
            function validateQuantity(productType) {
                const select = document.getElementById(productType + '_product');
                const qtyInput = document.getElementById(productType + '_qty');
                const warning = document.getElementById(productType + '_stock_warning');

                if (!select || !qtyInput || !warning) {
                    return; // Exit if elements don't exist
                }

                if (!select.value) {
                    warning.style.display = 'none';
                    return; // No product selected
                }

                const selectedOption = select.options[select.selectedIndex];
                const availableStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                const requestedQty = parseInt(qtyInput.value) || 0;

                if (requestedQty > availableStock) {
                    warning.textContent = 'Jumlah melebihi stok tersedia (' + availableStock + ')!';
                    warning.style.display = 'block';
                    qtyInput.value = availableStock; // Reset to max available
                    calculateTotal(); // Recalculate with corrected value
                } else if (requestedQty <= 0) {
                    warning.textContent = 'Jumlah minimal 1';
                    warning.style.display = 'block';
                    qtyInput.value = 1; // Set to minimum
                    calculateTotal();
                } else {
                    warning.style.display = 'none';
                }
            }

            // Calculate jatuh tempo dates
            function calculateJatuhTempo() {
                var jumlah_dp = parseInt(document.getElementById('jumlah_dp').value) || 0;
                var tanggalPreOrderStr = document.getElementById("tanggal").value;
                var tanggalPengambilanStr = document.getElementById("tanggal_pengambilan").value;

                // Reset all jatuh tempo display and values
                for (var i = 1; i <= 3; i++) {
                    const jatuhTempoDisplay = document.getElementById('jatuh_tempo_' + i);
                    const dpTenggatInput = document.getElementById('dp' + i + '_tenggat');

                    if (jatuhTempoDisplay) jatuhTempoDisplay.textContent = '-';
                    if (dpTenggatInput) dpTenggatInput.value = '';
                }

                // Check if we have all required values before proceeding
                if (jumlah_dp <= 0 || !tanggalPreOrderStr || !tanggalPengambilanStr) {
                    return; // Exit if any required value is missing
                }

                // Parse dates safely
                var tanggalPreOrder = new Date(tanggalPreOrderStr);
                var tanggalPengambilan = new Date(tanggalPengambilanStr);

                // Validate dates
                if (isNaN(tanggalPreOrder.getTime()) || isNaN(tanggalPengambilan.getTime())) {
                    console.error("Invalid date value detected");
                    return; // Exit if dates are invalid
                }

                // Ensure pengambilan date is after pre-order date
                if (tanggalPengambilan <= tanggalPreOrder) {
                    alert("Tanggal pengambilan harus setelah tanggal pre-order");
                    document.getElementById('tanggal_pengambilan').value = '';
                    return;
                }

                // Calculate days between pre-order and pickup
                var selisihHari = Math.floor((tanggalPengambilan - tanggalPreOrder) / (1000 * 60 * 60 * 24));

                // Calculate interval for installments
                var intervalHari = Math.floor(selisihHari / jumlah_dp);

                if (intervalHari <= 0) {
                    alert("Jarak waktu terlalu pendek untuk " + jumlah_dp + " cicilan");
                    document.getElementById('jumlah_dp').value = '';
                    return;
                }

                // Calculate and set jatuh tempo dates
                for (var i = 1; i <= jumlah_dp; i++) {
                    var jatuhTempo = new Date(tanggalPreOrder);
                    jatuhTempo.setDate(jatuhTempo.getDate() + (intervalHari * i));

                    // Ensure date is valid
                    if (isNaN(jatuhTempo.getTime())) {
                        console.error("Invalid calculated date");
                        continue; // Skip this iteration
                    }

                    // Format date as YYYY-MM-DD for display and value
                    var jatuhTempoStr = formatDate(jatuhTempo);

                    // Set jatuh tempo date (for display)
                    const jatuhTempoDisplay = document.getElementById('jatuh_tempo_' + i);
                    if (jatuhTempoDisplay) jatuhTempoDisplay.textContent = jatuhTempoStr;

                    // Set tenggat value (for database)
                    const dpTenggatInput = document.getElementById('dp' + i + '_tenggat');
                    if (dpTenggatInput) dpTenggatInput.value = jatuhTempoStr;
                }
            }

            // Validate installment planning
            function validateInstallments() {
                const tanggalPengambilanInput = document.getElementById('tanggal_pengambilan');
                const tanggalInput = document.getElementById('tanggal');
                const jumlahDpSelect = document.getElementById('jumlah_dp');

                if (!tanggalPengambilanInput || !tanggalInput || !jumlahDpSelect) return;

                const tanggalPengambilan = new Date(tanggalPengambilanInput.value);
                const tanggalPenagihan = new Date(tanggalInput.value);
                const jumlah_dp = parseInt(jumlahDpSelect.value);

                if (jumlah_dp && tanggalPengambilan && tanggalPenagihan && tanggalPengambilan > tanggalPenagihan) {
                    let maxInstallments = countWorkingDays(tanggalPenagihan, tanggalPengambilan);
                    if (jumlah_dp > maxInstallments) {
                        alert(`Jumlah cicilan melebihi hari kerja sebelum pengambilan (${maxInstallments} hari kerja tersedia).`);
                        jumlahDpSelect.value = '';
                        calculateJatuhTempo();
                    }
                }
            }

            // Count working days between two dates
            function countWorkingDays(start, end) {
                let count = 0;
                let current = new Date(start);
                while (current < end) {
                    current.setDate(current.getDate() + 1);
                    if (current.getDay() !== 0 && current.getDay() !== 6) {
                        count++;
                    }
                }
                return Math.max(1, count); // At least 1 working day
            }

            // Add class to visually highlight selected product sections
            function highlightActiveSection(productType) {
                const section = document.getElementById(productType + '_selection');
                if (section) {
                    const allSections = document.querySelectorAll('.product-selection');
                    allSections.forEach(el => el.classList.remove('active'));
                    section.classList.add('active');
                }
            }

            // Fungsi validasi form yang diperbaiki
            function validatePenagihanForm() {
                console.log('Memulai validasi form...');

                // Validasi customer
                const customerSelection = document.getElementById('customer_selection').value;
                if (customerSelection === 'new') {
                    const customer = document.getElementById('customer').value;
                    const kontak = document.getElementById('kontak').value;
                    if (!customer || !kontak) {
                        alert('Silakan isi data customer baru lengkap');
                        return false;
                    }
                } else {
                    const existingCustomer = document.getElementById('existing_customer').value;
                    if (!existingCustomer) {
                        alert('Silakan pilih customer dari database');
                        return false;
                    }
                }

                // Validasi produk
                const jaketChecked = document.getElementById('jaket_check').checked;
                const stikerChecked = document.getElementById('stiker_check').checked;
                const barangJadiChecked = document.getElementById('barang_jadi_check').checked;

                if (!jaketChecked && !stikerChecked && !barangJadiChecked) {
                    alert('Silakan pilih minimal satu produk');
                    return false;
                }

                if (jaketChecked && !document.getElementById('jaket_product').value) {
                    alert('Silakan pilih jenis jaket');
                    return false;
                }

                if (stikerChecked && !document.getElementById('stiker_product').value) {
                    alert('Silakan pilih jenis stiker');
                    return false;
                }

                if (barangJadiChecked && !document.getElementById('barang_jadi_product').value) {
                    alert('Silakan pilih barang jadi');
                    return false;
                }

                // Validasi tanggal
                const tanggal = new Date(document.getElementById('tanggal').value);
                const tanggalPengambilan = new Date(document.getElementById('tanggal_pengambilan').value);

                if (tanggalPengambilan <= tanggal) {
                    alert('Tanggal pengambilan harus setelah tanggal pre-order');
                    return false;
                }

                // Validasi cicilan
                if (!document.getElementById('jumlah_dp').value) {
                    alert('Silakan pilih rencana cicilan');
                    return false;
                }

                console.log('Validasi form berhasil');
                return true;
            }
            // Validate contact form before submission
            function validateContactForm(e) {
                // Validate dies natalis date if form is for contact
                const dnTanggal = document.getElementById('dn_tanggal');
                const dnBulan = document.getElementById('dn_bulan');
                const hiddenDnInput = document.getElementById('tanggal_dn');

                if (dnTanggal && dnBulan && hiddenDnInput) {
                    if (!dnTanggal.value || !dnBulan.value) {
                        e.preventDefault();
                        alert('Silakan pilih tanggal dan bulan Dies Natalis.');
                        return false;
                    }

                    // Ensure the hidden input is populated
                    updateHiddenDateInput();
                }

                return true;
            }
        </script>

        <style>
            /* Enhanced styling for form elements */
            .product-selection {
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                background-color: #f8f9fa;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            #pembayaran_section {
                margin-top: 20px;
                padding: 15px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                background-color: #f8f9fa;
            }

            .form-check {
                padding: 12px;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                background-color: #ffffff;
                transition: all 0.2s ease;
            }

            .form-check:hover {
                background-color: #f1f1f1;
            }

            .form-check-input:checked+.form-check-label {
                font-weight: bold;
                color: #007bff;
            }

            /* Styling for quantity inputs */
            input[type="number"] {
                text-align: center;
                font-weight: bold;
            }

            /* Make the product selections stand out more when selected */
            .product-selection.active {
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            /* Add some spacing between sections */
            .row {
                margin-bottom: 10px;
            }

            /* Better tab styling */
            .nav-tabs .nav-link {
                font-weight: 500;
            }

            .nav-tabs .nav-link.active {
                background-color: #f8f9fa;
                border-bottom-color: #f8f9fa;
            }

            /* Better styling for date inputs */
            input[type="date"] {
                height: calc(1.5em + 0.75rem + 2px);
            }
        </style>
    </div>
</div>