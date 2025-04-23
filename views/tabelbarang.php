<?php
if ($menu == "Barang") {
    ?>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs" id="stockTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="stock-barang-tab" data-toggle="tab" href="#stock-barang" role="tab"
                            aria-controls="stock-barang" aria-selected="true">
                            Stock Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="stock-stiker-tab" data-toggle="tab" href="#stock-stiker" role="tab"
                            aria-controls="stock-stiker" aria-selected="false">
                            Stock Stiker
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="stock-sablon-tab" data-toggle="tab" href="#stock-sablon" role="tab"
                            aria-controls="stock-sablon" aria-selected="false">
                            Stock Sablon
                        </a>
                    </li>
                </ul>
                <div>
                    <a href="index.php?menu=CreateBarang&submenu=BarangAdd" class="btn btn-success">Tambah Barang</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="stockTabsContent">
                <!-- Stock Barang Content -->
                <div class="tab-pane fade show active" id="stock-barang" role="tabpanel" aria-labelledby="stock-barang-tab">
                    <ul class="nav nav-pills mb-3" id="barang-custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="barang-tab-masuk" data-toggle="pill" href="#barang-masuk" role="tab"
                                aria-controls="barang-masuk" aria-selected="false">Stock Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="barang-tab-data" data-toggle="pill" href="#barang-data"
                                role="tab" aria-controls="barang-data" aria-selected="true">Data Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="barang-tab-keluar" data-toggle="pill" href="#barang-keluar" role="tab"
                                aria-controls="barang-keluar" aria-selected="false">Stock Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="barang-custom-tabs-content">
                        <div class="tab-pane fade" id="barang-masuk" role="tabpanel" aria-labelledby="barang-tab-masuk">
                            <?php require_once('tabel/stock_barang_masuk.php'); ?>
                        </div>
                        <div class="tab-pane fade show active" id="barang-data" role="tabpanel"
                            aria-labelledby="barang-tab-data">
                            <?php require_once('tabel/stock_barang_data.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="barang-keluar" role="tabpanel" aria-labelledby="barang-tab-keluar">
                            <?php require_once('tabel/stock_barang_keluar.php'); ?>
                        </div>
                    </div>
                </div>
                <!-- Stock Stiker Content -->
                <div class="tab-pane fade" id="stock-stiker" role="tabpanel" aria-labelledby="stock-stiker-tab">
                    <ul class="nav nav-pills mb-3" id="stiker-custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="stiker-tab-masuk" data-toggle="pill" href="#stiker-masuk" role="tab"
                                aria-controls="stiker-masuk" aria-selected="false">Stock Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="stiker-tab-data" data-toggle="pill" href="#stiker-data"
                                role="tab" aria-controls="stiker-data" aria-selected="true">Data Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="stiker-tab-keluar" data-toggle="pill" href="#stiker-keluar" role="tab"
                                aria-controls="stiker-keluar" aria-selected="false">Stock Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="stiker-custom-tabs-content">
                        <div class="tab-pane fade" id="stiker-masuk" role="tabpanel" aria-labelledby="stiker-tab-masuk">
                            <?php require_once('tabel/stock_stiker_masuk.php'); ?>
                        </div>
                        <div class="tab-pane fade show active" id="stiker-data" role="tabpanel"
                            aria-labelledby="stiker-tab-data">
                            <?php require_once('tabel/stock_stiker_data.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="stiker-keluar" role="tabpanel" aria-labelledby="stiker-tab-keluar">
                            <?php require_once('tabel/stock_stiker_keluar.php'); ?>
                        </div>
                    </div>
                </div>
                <!-- Stock Sablon Content -->
                <div class="tab-pane fade" id="stock-sablon" role="tabpanel" aria-labelledby="stock-sablon-tab">
                    <ul class="nav nav-pills mb-3" id="sablon-custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="sablon-tab-masuk" data-toggle="pill" href="#sablon-masuk" role="tab"
                                aria-controls="sablon-masuk" aria-selected="true">Sablon Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sablon-tab-data" data-toggle="pill" href="#sablon-data"
                                role="tab" aria-controls="sablon-data" aria-selected="false">Data Sablon</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sablon-tab-keluar" data-toggle="pill" href="#sablon-keluar" role="tab"
                                aria-controls="sablon-keluar" aria-selected="false">Sablon Keluar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="sablon-custom-tabs-content">
                        <div class="tab-pane fade show active" id="sablon-masuk" role="tabpanel"
                            aria-labelledby="sablon-tab-masuk">
                            <?php require_once('tabel/stock_sablon_masuk.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="sablon-data" role="tabpanel" aria-labelledby="sablon-tab-data">
                            <?php require_once('tabel/stock_sablon_data.php'); ?>
                        </div>
                        <div class="tab-pane fade" id="sablon-keluar" role="tabpanel" aria-labelledby="sablon-tab-keluar">
                            <?php require_once('tabel/stock_sablon_keluar.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.table').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" }
            });
            $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });
            $('.nav-tabs a').on('shown.bs.tab', function () {
                $.fn.dataTable.tables(true).DataTable().columns.adjust();
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

        function showModal(type, id, action) {
            const modalConfig = {
                barang: { modalId: '#stockModalBarang', labelId: 'stockModalLabel', actionId: 'action', inputId: 'id_jaket' },
                stiker: { modalId: '#stockModalStiker', labelId: 'stockModalLabelStiker', actionId: 'action_stiker', inputId: 'id_sticker_modal' },
                sablon: { modalId: '#stockModalSablon', labelId: 'stockModalLabelSablon', actionId: 'actionSablon', inputId: 'id_barang' }
            };
            const cfg = modalConfig[type] || modalConfig.barang;
            document.getElementById(cfg.inputId).value = id;
            document.getElementById(cfg.actionId).value = action;
            document.getElementById(cfg.labelId).textContent = `${action === 'tambah' ? 'Tambah' : 'Kurangi'} Stock ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            $(cfg.modalId).modal('show');
        }
    </script>
    <?php
}
