<script>
    // Function to handle school deletion
    function hapusSekolah(id) {
        document.getElementById('hapusID').value = id;
        var modal = new bootstrap.Modal(document.getElementById('modalHapus'));
        modal.show();
    }

    // Wait for document to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize datepickers for all modals when they're shown
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function () {
                const modalId = this.id;
                const id = modalId.replace('modalEdit', '');
                setupDatePicker(id);
            });
        });
    });

    // Function to set up date picker for a specific modal
    function setupDatePicker(id) {
        // Get elements by ID instead of class to ensure uniqueness
        const tanggalSelect = document.getElementById('dn_tanggal_' + id);
        const bulanSelect = document.getElementById('dn_bulan_' + id);
        const hiddenInput = document.getElementById('tanggal_dn_' + id);
        const displayText = document.getElementById('display_tanggal_dn_' + id);

        // Log for debugging
        console.log('Setting up date picker for ID:', id);
        console.log('Found tanggal select:', tanggalSelect !== null);
        console.log('Found bulan select:', bulanSelect !== null);

        // Skip if elements don't exist
        if (!tanggalSelect || !bulanSelect || !hiddenInput || !displayText) {
            console.error('Required elements not found for ID:', id);
            return;
        }

        // Data bulan dengan nama
        const dataBulan = [
            { key: '01', nama: 'Januari' },
            { key: '02', nama: 'Februari' },
            { key: '03', nama: 'Maret' },
            { key: '04', nama: 'April' },
            { key: '05', nama: 'Mei' },
            { key: '06', nama: 'Juni' },
            { key: '07', nama: 'Juli' },
            { key: '08', nama: 'Agustus' },
            { key: '09', nama: 'September' },
            { key: '10', nama: 'Oktober' },
            { key: '11', nama: 'November' },
            { key: '12', nama: 'Desember' }
        ];

        // Function to update the hidden input and display text
        function updateDateValue() {
            const tanggal = tanggalSelect.value;
            const bulan = bulanSelect.value;

            if (tanggal && bulan) {
                // Update hidden input with DD-MM format
                hiddenInput.value = `${tanggal}-${bulan}`;

                // Find the month name
                const bulanObj = dataBulan.find(b => b.key === bulan);
                const bulanNama = bulanObj ? bulanObj.nama : '';

                // Update display text
                displayText.textContent = `Dipilih: ${tanggal} ${bulanNama}`;
            } else {
                hiddenInput.value = '';
                displayText.textContent = '';
            }
        }

        // Add event listeners
        tanggalSelect.addEventListener('change', updateDateValue);
        bulanSelect.addEventListener('change', updateDateValue);

        // Form validation
        const form = hiddenInput.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                // Check if date is selected
                if (!hiddenInput.value) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Silakan pilih tanggal Dies Natalis!');

                    // Highlight fields
                    if (!tanggalSelect.value) tanggalSelect.classList.add('is-invalid');
                    if (!bulanSelect.value) bulanSelect.classList.add('is-invalid');
                }
            });

            // Remove invalid class on change
            tanggalSelect.addEventListener('change', function () {
                this.classList.remove('is-invalid');
            });

            bulanSelect.addEventListener('change', function () {
                this.classList.remove('is-invalid');
            });
        }

        // If there's already a value, update the display
        if (hiddenInput.value) {
            // Make sure the selects have the right values
            updateDateValue();
        }
    }
</script>