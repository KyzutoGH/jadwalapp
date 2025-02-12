<div class="container-fluid">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="formTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?php if ($submenu == "BarangAdd") {
                echo "active";
            } ?>" id="barang-tab" data-toggle="tab" href="#barang" role="tab">Tambah Barang</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($submenu == "Stiker") {
                echo "active";
            } ?>" id="sticker-tab" data-toggle="tab" href="#stiker" role="tab">Tambah Stiker</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($submenu == "Sablon") {
                echo "active";
            } ?>" id="sablon-tab" data-toggle="tab" href="#sablon" role="tab">Tambah Barang Jadi</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content">
        <!-- Contact Person Form Tab -->
        <div class="tab-pane fade show active" id="barang" role="tabpanel">
            <div class="card card-default">
                <div class="card-body">
                    <form id="barangForm" action="config/create_barang.php" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama_sekolah">Jenis</label>
                                    <select class="form-control" name="jenis">
                                        <option value="Jaket">Jaket
                                        </option>
                                        <option value="Varsity">Varsity
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Ukuran</label>
                                    <select class="form-control" name="ukuran">
                                        <option value="S">S
                                        </option>
                                        <option value="M">M
                                        </option>
                                        <option value="L">L
                                        </option>
                                        <option value="XL">XL
                                        </option>
                                        <option value="XXL">XXL
                                        </option>
                                        <option value="3XL">3XL
                                        </option>
                                        <option value="4XL">4XL
                                        </option>
                                        <option value="5XL">5XL
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" required>
                                </div>
                                <div class="form-group">
                                    <label for="namabarang">Nama Barang</label>
                                    <input type="text" class="form-control" id="namabarang" name="namabarang" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stok</label>
                                    <input type="number" class="form-control" id="stock" name="stock" required>
                                </div>
                                <button type="submit" name="Submit" class="btn btn-primary float-right">Simpan</button>
                                <button type="reset" class="btn btn-secondary float-right mr-2">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Billing Form Tab -->
        <div class="tab-pane fade" id="stiker" role="tabpanel">
            <div class="card card-default">
                <div class="card-body">
                    <form id="billingForm" action="config/create_stiker.php" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="form-group">
                                    <label for="bagian">Bagian</label>
                                    <input type="text" class="form-control" id="bagian" name="bagian" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary float-right">Simpan</button>
                                <button type="reset" class="btn btn-secondary float-right mr-2">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="sablon" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <form action="config/create_sablon.php" method="POST" enctype="multipart/form-data">
                        <!-- Nama Produk -->
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" required>
                        </div>

                        <!-- Pilih Jaket -->
                        <div class="form-group">
                            <label>Pilih Jaket</label>
                            <select class="form-control" name="id_jaket" required>
                                <option value="">Pilih Jaket</option>
                                <?php
                                $query = "SELECT * FROM jaket";
                                $result = mysqli_query($db, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='{$row['id_jaket']}'>{$row['namabarang']} - {$row['ukuran']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Upload Gambar -->
                        <div class="form-group">
                            <label>Upload Gambar</label>
                            <input type="file" class="form-control-file" name="gambar_jadi" accept="image/*" required>
                        </div>

                        <!-- Container untuk Stiker -->
                        <div class="form-group">
                            <label>Stiker</label>
                            <div id="stikerContainer">
                                <div class="stiker-row mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- Radio untuk pilih tipe stiker -->
                                            <div class="form-group d-flex align-items-center">
                                                <div class="form-check mr-3">
                                                    <input type="radio" name="stiker_type_0" value="existing" checked
                                                        class="form-check-input" onchange="toggleStikerForm(0)">
                                                    <label class="form-check-label">Pilih Stiker Yang Ada</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="stiker_type_0" value="new"
                                                        class="form-check-input" onchange="toggleStikerForm(0)">
                                                    <label class="form-check-label">Tambah Stiker Baru</label>
                                                </div>
                                            </div>

                                            <!-- Form untuk pilih stiker yang ada -->
                                            <div id="existing_stiker_0">
                                                <select class="form-control" name="existing_sticker[]">
                                                    <option value="">Pilih Stiker</option>
                                                    <?php
                                                    $query = "SELECT * FROM stiker";
                                                    $result = mysqli_query($db, $query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        echo "<option value='{$row['id_sticker']}'>{$row['nama']} - {$row['bagian']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- Form untuk stiker baru -->
                                            <div id="new_stiker_0" style="display:none;">
                                                <input type="text" class="form-control mb-2" name="new_stiker_nama[]"
                                                    placeholder="Nama Stiker">
                                                <input type="text" class="form-control" name="new_stiker_bagian[]"
                                                    placeholder="Bagian">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-2" onclick="tambahStiker()">+ Tambah
                                Stiker Lain</button>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            let stikerCount = 1;

            function toggleStikerForm(index) {
                const existingForm = document.getElementById(`existing_stiker_${index}`);
                const newForm = document.getElementById(`new_stiker_${index}`);
                const radioValue = document.querySelector(`input[name="stiker_type_${index}"]:checked`).value;

                if (radioValue === 'existing') {
                    existingForm.style.display = 'block';
                    newForm.style.display = 'none';
                } else {
                    existingForm.style.display = 'none';
                    newForm.style.display = 'block';
                }
            }

            function tambahStiker() {
                const container = document.getElementById('stikerContainer');
                const original = document.querySelector('.stiker-row');
                const newRow = original.cloneNode(true);

                // Update semua ID dan name untuk elemen yang baru
                newRow.querySelectorAll('[id]').forEach(el => {
                    el.id = el.id.replace(/\d+/, stikerCount);
                });

                // Update name dan event listener untuk radio button baru
                newRow.querySelectorAll('input[type="radio"]').forEach((radio, i) => {
                    radio.name = `stiker_type_${stikerCount}`;
                    radio.checked = i === 0;
                    radio.setAttribute("onchange", `toggleStikerForm(${stikerCount})`);
                });

                // Reset input values
                newRow.querySelector('select').value = "";
                newRow.querySelectorAll('input[type="text"]').forEach(input => input.value = "");

                // Tambahkan ke dalam container
                container.appendChild(newRow);
                stikerCount++;
            }
        </script>