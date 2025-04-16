<?php
if ($menu == "Barang") {
    ?>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs" id="stockTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="stock-barang-tab" data-toggle="tab" href="#stock-barang" role="tab">
                            Stock Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="stock-stiker-tab" data-toggle="tab" href="#stock-stiker" role="tab">
                            Stock Stiker
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="stock-sablon-tab" data-toggle="tab" href="#stock-sablon" role="tab">
                            Stock Barang Jadi
                        </a>
                    </li>
                </ul>
                <div>
                    <!-- Tombol pengurangan manual -->
                    <button onclick="kurangiManual()" class="btn btn-danger">Kurangi Barang</button>

                    <!-- Tombol barcode -->
                    <button data-toggle="modal" data-target="#modalScan" class="btn btn-warning">📷 Barcode</button>
                    <!-- Modal -->
                    <div class="modal fade" id="modalScan" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Scan Barcode</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="mb-3">
                                        <button class="btn btn-success" onclick="startScan('tambah')">Tambah Stok</button>
                                        <button class="btn btn-danger" onclick="startScan('kurangi')">Kurangi Stok</button>
                                    </div>
                                    <div id="reader" style="width: 500px; margin: auto;"></div>
                                    <div id="result" class="mt-3 fw-bold"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="index.php?menu=CreateBarang&submenu=BarangAdd" class="btn btn-success">
                        Tambah Barang
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="tab-content" id="stockTabsContent">
                <!-- Tab Stock Barang -->
                <div class="tab-pane fade" id="stock-barang" role="tabpanel">
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
                <div class="tab-pane fade" id="stock-stiker" role="tabpanel">
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
                <div class="tab-pane fade show active" id="stock-sablon" role="tabpanel">
                    <ul class="nav nav-tabs mb-3" id="sablon-custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="sablon-tab-masuk" data-toggle="tab" href="#sablon-masuk"
                                role="tab">Sablon Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sablon-tab-data" data-toggle="tab" href="#sablon-data" role="tab">Data
                                Sablon</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sablon-tab-keluar" data-toggle="tab" href="#sablon-keluar"
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
    </div>

    <script>
        $(document).ready(function () {
            // Inisialisasi DataTables untuk semua tabel
            $('.table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });

            // Event handler untuk memperbaiki layout saat pergantian tab
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });

            // Event handler untuk tab switches
            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

            // Inisialisasi tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Function untuk menampilkan modal stock
        function showModal(type, id, action) {
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

            const config = modalConfig[type] || modalConfig.barang;
            const modalLabel = document.getElementById(config.labelId);
            const actionInput = document.getElementById(config.actionId);
            const idInput = document.getElementById(config.inputId);

            idInput.value = id;
            actionInput.value = action;
            modalLabel.textContent = `${action === 'tambah' ? 'Tambah' : 'Kurangi'} Stock ${type.charAt(0).toUpperCase() + type.slice(1)}`;

            $(config.modalId).modal('show');
        }
    </script>
<?php } ?>