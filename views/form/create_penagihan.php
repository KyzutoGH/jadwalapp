<form action="config/create_dp.php" method="POST" id="penagihanForm" onsubmit="return validatePenagihanForm()">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tanggal">Tanggal Pre Order <span style="color: red;">*</span></label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="customer_selection">Pilih Customer <span style="color: red;">*</span></label>
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
                <label for="kontak">Kontak Customer (10–13 digit, dimulai dari 0) <span
                        style="color: red;">*</span></label>
                <input type="text" class="form-control" id="kontak" name="kontak" pattern="0[0-9]{9,12}" maxlength="13"
                    placeholder="Contoh: 08123456789" required oninput="validatePhone(this)">
                <small class="form-text text-muted">
                    Masukkan nomor telepon yang dimulai dari 0 (contoh: 08123456789)
                </small>

                <script>
                    function validatePhone(input) {
                        // Hanya izinkan angka
                        input.value = input.value.replace(/[^0-9]/g, '');

                        // Jika ada input tapi tidak dimulai dari 0
                        if (input.value.length > 0 && !input.value.startsWith('0')) {
                            input.setCustomValidity('Nomor harus dimulai dari 0');
                        } else {
                            input.setCustomValidity('');
                        }
                    }
                </script>
            </div>
        </div>
    </div>

    <!-- Form untuk memilih customer dari database -->
    <div id="existing_customer_form" style="display: none;">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="existing_customer">Pilih Sekolah <span style="color: red;">*</span></label>
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
                <label for="kontak_existing">Kontak Person <span style="color: red;">*</span></label>
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
                            <input class="custom-control-input" type="checkbox" id="jaket_check" name="product_types[]"
                                value="jaket" onchange="updateProductSelection()">
                            <label class="custom-control-label" for="jaket_check">Jaket</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="stiker_check" name="product_types[]"
                                value="stiker" onchange="updateProductSelection()">
                            <label class="custom-control-label" for="stiker_check">Stiker</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="barang_jadi_check"
                                name="product_types[]" value="barang_jadi" onchange="updateProductSelection()">
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
                <select class="form-control" id="jaket_product" name="jaket_product" onchange="calculateTotal()">
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
                <input type="number" class="form-control" id="jaket_qty" name="jaket_qty" min="1" value="1"
                    onchange="calculateTotal(); validateQuantity('jaket');">
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
                <select class="form-control" id="stiker_product" name="stiker_product" onchange="calculateTotal()">
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
                <input type="number" class="form-control" id="stiker_qty" name="stiker_qty" min="1" value="1"
                    onchange="calculateTotal(); validateQuantity('stiker');">
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
                <input type="number" class="form-control" id="barang_jadi_qty" name="barang_jadi_qty" min="1" value="1"
                    onchange="calculateTotal(); validateQuantity('barang_jadi');">
                <small class="text-danger" id="barang_jadi_stock_warning" style="display: none;">Jumlah melebihi stok
                    tersedia!</small>
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
            <label for="tanggal_pengambilan">Tanggal Pengambilan Barang <span style="color: red;">*</span></label>
            <input type="date" class="form-control" id="tanggal_pengambilan" name="tanggal_pengambilan" required
                onchange="validateInstallments()">
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