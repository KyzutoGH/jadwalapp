<?php
if ($menu == "Barang") {
    ?>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs" id="stockTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="stock-barang-tab" data-toggle="tab" href="#stock-barang" role="tab"
                            aria-controls="stock-barang" aria-selected="true">
                            Stock Barang
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="stock-stiker-tab" data-toggle="tab" href="#stock-stiker" role="tab"
                            aria-controls="stock-stiker" aria-selected="false">
                            Stock Stiker
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="stock-sablon-tab" data-toggle="tab" href="#stock-sablon" role="tab"
                            aria-controls="stock-sablon" aria-selected="false">
                            Stock Barang Jadi
                        </a>
                    </li>
                </ul>
                <div>
                    <a href="index.php?menu=CreateBarang&submenu=BarangAdd" class="btn btn-primary">
                        Tambah Barang
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="stockTabsContent">
                <!-- Tab Stock Barang -->
                <div class="tab-pane fade show active" id="stock-barang" role="tabpanel" aria-labelledby="stock-barang-tab">
                    <ul class="nav nav-tabs" id="barang-custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="barang-tab-masuk" data-toggle="pill" href="#barang-masuk"
                                role="tab">Stock Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="barang-tab-data" data-toggle="pill" href="#barang-data"
                                role="tab">Data Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="barang-tab-keluar" data-toggle="pill" href="#barang-keluar"
                                role="tab">Stock Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="barang-custom-tabs-content">
                        <div class="tab-pane fade" id="barang-masuk" role="tabpanel">
                            <?php require_once('tabel/stock_barang_masuk.php'); ?>
                        </div>
                        <div class="tab-pane fade show active" id="barang-data" role="tabpanel">
                            <?php require_once('tabel/stock_barang_data.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="barang-keluar" role="tabpanel">
                            <?php require_once('tabel/stock_barang_keluar.php'); ?>
                        </div>
                    </div>
                </div>

                <!-- Tab Stock Stiker -->
                <div class="tab-pane fade" id="stock-stiker" role="tabpanel" aria-labelledby="stock-stiker-tab">
                    <ul class="nav nav-tabs" id="stiker-custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="stiker-tab-masuk" data-toggle="pill" href="#stiker-masuk"
                                role="tab">Stock Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="stiker-tab-data" data-toggle="pill" href="#stiker-data"
                                role="tab">Data Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="stiker-tab-keluar" data-toggle="pill" href="#stiker-keluar"
                                role="tab">Stock Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="stiker-custom-tabs-content">
                        <div class="tab-pane fade" id="stiker-masuk" role="tabpanel">
                            <?php require_once('tabel/stock_stiker_masuk.php'); ?>
                        </div>
                        <div class="tab-pane fade show active" id="stiker-data" role="tabpanel">
                            <?php require_once('tabel/stock_stiker_data.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="stiker-keluar" role="tabpanel">
                            <?php require_once('tabel/stock_stiker_keluar.php'); ?>
                        </div>
                    </div>
                </div>

                <!-- Tab Stock Sablon -->
                <div class="tab-pane fade" id="stock-sablon" role="tabpanel" aria-labelledby="stock-sablon-tab">
                    <ul class="nav nav-tabs" id="sablon-custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="sablon-tab-masuk" data-toggle="pill" href="#sablon-masuk"
                                role="tab">Sablon Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sablon-tab-data" data-toggle="pill" href="#sablon-data" role="tab">Data
                                Sablon</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sablon-tab-keluar" data-toggle="pill" href="#sablon-keluar"
                                role="tab">Sablon Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="sablon-custom-tabs-content">
                        <div class="tab-pane fade show active" id="sablon-masuk" role="tabpanel">
                            <?php require_once('tabel/stock_sablon_masuk.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="sablon-data" role="tabpanel">
                            <?php require_once('tabel/stock_sablon_data.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="sablon-keluar" role="tabpanel">
                            <?php require_once('tabel/stock_sablon_keluar.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Stock Barang -->
            <div class="modal fade" id="stockModalBarang" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="stockModalLabel">Update Stock</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="config/update_stock.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id_jaket" id="id_jaket">
                                <input type="hidden" name="action" id="action">
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah" required min="1">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Stock Stiker -->
            <div class="modal fade" id="stockModalStiker" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="stockModalLabelStiker">Update Stock Stiker</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="config/update_stock_sticker.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id_sticker" id="id_sticker_modal">
                                <input type="hidden" name="action" id="action_stiker">
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah" required min="1">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Stock Sablon -->
            <div class="modal fade" id="stockModalSablon" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="stockModalLabelSablon">Update Stock Sablon</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="config/update_stock_sablon.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id_barang" id="id_barang">
                                <input type="hidden" name="action" id="actionSablon">
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah" required min="1">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Sablon -->
            <div class="modal fade" id="modalEditSablon<?= $b['id_barang'] ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Sablon</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="config/edit_sablon.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $b['id_barang'] ?>">
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang"
                                        value="<?= htmlspecialchars($b['nama_barang']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="3"><?= htmlspecialchars($b['keterangan']) ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    // DataTables configuration
                    const dataTableConfig = {
                        paging: true,
                        lengthChange: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        autoWidth: false,
                        responsive: true,
                        language: {
                            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                        }
                    };

                    // Initialize all tables
                    $('.table').DataTable(dataTableConfig);

                    // Fix column sizes when switching tabs
                    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                    });

                    // Handle all tab switches to ensure proper table rendering
                    $('.nav-tabs a').on('shown.bs.tab', function(e) {
                        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                    });

                    // Initialize tooltips
                    $('[data-toggle="tooltip"]').tooltip();
                });

                // Unified modal handling function
                window.showModal = function(type, id, action) {
                    const modalConfig = {
                        barang: {
                            modalId: '#stockModalBarang',
                            labelId: 'stockModalLabel',
                            actionId: 'action',
                            inputId: 'id_jaket'
                        },
                        stiker: {
                            modalId: '#stockModalStiker',
                            labelId: 'stockModalLabelStiker',
                            actionId: 'action_stiker',
                            inputId: 'id_sticker_modal'
                        },
                        sablon: {
                            modalId: '#stockModalSablon',
                            labelId: 'stockModalLabelSablon',
                            actionId: 'actionSablon',
                            inputId: 'id_barang'
                        }
                    };

                    const config = modalConfig[type];
                    if (!config) return;

                    const modalLabel = document.getElementById(config.labelId);
                    const actionInput = document.getElementById(config.actionId);
                    const idInput = document.getElementById(config.inputId);

                    idInput.value = id;
                    actionInput.value = action;
                    modalLabel.textContent = `${action === 'tambah' ? 'Tambah' : 'Kurangi'} Stock ${type.charAt(0).toUpperCase() + type.slice(1)}`;

                    $(config.modalId).modal('show');
                };

                // Function to handle edit modals
                window.showEditModal = function(type, id) {
                    $(`#modalEdit${type}${id}`).modal('show');
                };

                // Confirm delete function
                window.confirmDelete = function(type, id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        window.location.href = `config/delete_${type.toLowerCase()}.php?id=${id}`;
                    }
                };
            </script>
        </div>
<?php } ?>