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
        <div class="tab-pane fade" id="billing" role="tabpanel">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Data Penagihan</h3>
                </div>
                <div class="card-body">
                    <form id="billingForm" action="config/create_penagihan.php" method="POST">
                        <div class="row">
                            <div class="col-md-12">
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
                                    <input type="text" class="form-control" id="total" name="total" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dp">DP (Cicilan)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="dp_current" name="dp_current"
                                            min="1" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">dari</span>
                                        </div>
                                        <input type="number" class="form-control" id="dp_total" name="dp_total" min="1"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pelunasan">Pelunasan</label>
                                    <input type="text" class="form-control" id="pelunasan" name="pelunasan">
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="1">Belum Lunas</option>
                                        <option value="2">Lunas - Belum Siap</option>
                                        <option value="3">Lunas - Tinggal Ambil</option>
                                    </select>
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
    </div>
</div>