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

        <!-- Billing Form Tab -->
        <div class="tab-pane fade" id="sablon" role="tabpanel">
            <div class="card card-default">
                <div class="card-body">
                    <form id="barangJadiForm" action="config/create_sablon.php" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_produk">Nama Produk</label>
                                    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="id_jaket">Pilih Jaket</label>
                                    <select class="form-control" name="id_jaket" id="id_jaket" required>
                                        <option value="">Pilih Jaket</option>
                                        <?php
                                        $query_jaket = "SELECT id_jaket, namabarang, ukuran, jenis FROM jaket";
                                        $result_jaket = mysqli_query($db, $query_jaket);
                                        while ($jaket = mysqli_fetch_assoc($result_jaket)) {
                                            echo "<option value='{$jaket['id_jaket']}'>{$jaket['namabarang']} - {$jaket['jenis']} ({$jaket['ukuran']})</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pilih atau Tambah Stiker</label>
                                    <div id="stikerContainer">
                                        <div class="stiker-item mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input stiker-type" type="checkbox"
                                                            data-target="new-stiker-1">
                                                        <label class="form-check-label">
                                                            Tambah Stiker Baru
                                                        </label>
                                                    </div>

                                                    <!-- Existing Stiker Selection -->
                                                    <div class="existing-stiker-select">
                                                        <select class="form-control stiker-select" name="stiker_ids[]">
                                                            <option value="">Pilih Stiker</option>
                                                            <?php
                                                            $query_stiker = "SELECT id_sticker, nama, bagian FROM stiker";
                                                            $result_stiker = mysqli_query($db, $query_stiker);
                                                            while ($stiker = mysqli_fetch_assoc($result_stiker)) {
                                                                echo "<option value='{$stiker['id_sticker']}'>{$stiker['nama']} - {$stiker['bagian']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <!-- New Stiker Form -->
                                                    <div class="new-stiker-form" style="display: none;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control mb-2"
                                                                name="new_stiker_nama[]" placeholder="Nama Stiker">
                                                            <input type="text" class="form-control"
                                                                name="new_stiker_bagian[]" placeholder="Bagian">
                                                        </div>
                                                    </div>

                                                    <button type="button" class="btn btn-danger btn-sm remove-stiker"
                                                        style="display: none;">
                                                        Hapus Stiker
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm mt-2" id="addStiker">
                                        <i class="fas fa-plus"></i> Tambah Stiker Lain
                                    </button>
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

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const stikerContainer = document.getElementById('stikerContainer');
                    const addStikerBtn = document.getElementById('addStiker');
                    const form = document.getElementById('barangJadiForm');

                    // Toggle between new and existing stiker
                    function setupStikerTypeToggle(container) {
                        const checkbox = container.querySelector('.stiker-type');
                        const existingSelect = container.querySelector('.existing-stiker-select');
                        const newForm = container.querySelector('.new-stiker-form');

                        checkbox.addEventListener('change', function () {
                            if (this.checked) {
                                existingSelect.style.display = 'none';
                                newForm.style.display = 'block';
                                existingSelect.querySelector('select').removeAttribute('required');
                                newForm.querySelectorAll('input').forEach(input => input.setAttribute('required', ''));
                            } else {
                                existingSelect.style.display = 'block';
                                newForm.style.display = 'none';
                                existingSelect.querySelector('select').setAttribute('required', '');
                                newForm.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
                            }
                        });
                    }

                    // Show/hide remove buttons
                    function updateRemoveButtons() {
                        const removeButtons = document.querySelectorAll('.remove-stiker');
                        removeButtons.forEach(button => {
                            button.style.display = removeButtons.length > 1 ? 'block' : 'none';
                        });
                    }

                    // Add new stiker section
                    addStikerBtn.addEventListener('click', function () {
                        const stikerItems = document.querySelectorAll('.stiker-item');
                        const newItem = stikerItems[0].cloneNode(true);
                        const index = stikerItems.length + 1;

                        // Reset form values
                        newItem.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                        newItem.querySelector('.stiker-type').checked = false;
                        newItem.querySelector('.existing-stiker-select').style.display = 'block';
                        newItem.querySelector('.new-stiker-form').style.display = 'none';

                        // Update IDs and names
                        newItem.querySelector('.stiker-type').dataset.target = `new-stiker-${index}`;

                        stikerContainer.appendChild(newItem);
                        setupStikerTypeToggle(newItem);
                        updateRemoveButtons();
                    });

                    // Remove stiker section
                    stikerContainer.addEventListener('click', function (e) {
                        if (e.target.classList.contains('remove-stiker')) {
                            const item = e.target.closest('.stiker-item');
                            item.remove();
                            updateRemoveButtons();
                        }
                    });

                    // Setup initial stiker type toggle
                    setupStikerTypeToggle(document.querySelector('.stiker-item'));

                    // Form validation
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        // Get all selected existing stiker IDs
                        const selectedStikers = Array.from(document.querySelectorAll('.stiker-select:required'))
                            .map(select => select.value)
                            .filter(value => value !== '');

                        // Check for duplicate selections
                        const uniqueStikers = new Set(selectedStikers);
                        if (selectedStikers.length !== uniqueStikers.size) {
                            alert('Stiker tidak boleh duplikat!');
                            return;
                        }

                        // If all validations pass, submit the form
                        this.submit();
                    });
                });
            </script>