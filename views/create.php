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
                    <?php
                    require_once __DIR__ . '/form/create_diesnatalis.php'; ?>
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
                    <?php
                    require_once __DIR__ . '/form/create_penagihan.php'; ?>
                </div>
            </div>
        </div>

        <?php
        require_once __DIR__ . '/../config/javscript/script_create.php'; ?>
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