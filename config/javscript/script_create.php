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