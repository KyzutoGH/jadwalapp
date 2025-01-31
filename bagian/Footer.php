<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="assets/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="assets/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<!-- SweetAlert2 -->
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="assets/js/pages/dashboard2.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/fullcalendar/main.js"></script>
<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="assets/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="assets/plugins/dropzone/min/dropzone.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>
<script>
  // Global configuration objects
  const toastrConfig = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: true,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
  };

  const dataTableLanguage = {
    emptyTable: "Tidak ada data yang tersedia",
    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
    infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
    infoFiltered: "(difilter dari _MAX_ total entri)",
    lengthMenu: "Tampilkan _MENU_ entri",
    loadingRecords: "Memuat...",
    processing: "Memproses...",
    search: "Pencarian:",
    zeroRecords: "Tidak ditemukan data yang sesuai",
    paginate: {
      first: "Pertama",
      last: "Terakhir",
      next: "Selanjutnya",
      previous: "Sebelumnya"
    }
  };

  $(document).ready(function () {
    // Custom date range filter function
    const dateRangeFilter = (settings, data, dataIndex) => {
      if (settings.nTable.id !== 'tabelPenagihan') return true;

      const startDate = $('#tgl_mulai').val();
      const endDate = $('#tgl_akhir').val();

      if (!startDate && !endDate) return true;

      const dateInTable = moment(data[0], 'DD/MM/YYYY', true);

      if (!dateInTable.isValid()) return false;

      const start = startDate ? moment(startDate, 'YYYY-MM-DD', true) : null;
      const end = endDate ? moment(endDate, 'YYYY-MM-DD', true) : null;

      if (start && end) {
        return dateInTable.isBetween(start, end, 'day', '[]');
      } else if (start) {
        return dateInTable.isSameOrAfter(start, 'day');
      } else if (end) {
        return dateInTable.isSameOrBefore(end, 'day');
      }

      return true;
    };

    // Remove any existing filter before adding new one
    $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(filter =>
      filter.toString() !== dateRangeFilter.toString()
    );

    // Add the optimized date range filter
    $.fn.dataTable.ext.search.push(dateRangeFilter);

    // Initialize DataTable with optimized configuration
    let penaginhanTable;
    if ($('#tabelPenagihan').length) {
      penaginhanTable = $('#tabelPenagihan').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        language: dataTableLanguage,
        stateSave: true,
        deferRender: true,
        initComplete: function (settings, json) {
          $('.dataTables_filter input, .dataTables_length select').addClass('form-control');

          if (!$('#reset_filter').length) {
            const resetButton = $('<button>', {
              id: 'reset_filter',
              class: 'btn btn-secondary mb-3',
              html: '<i class="fas fa-sync-alt"></i> Reset Filter'
            });

            $('.card-body').prepend(resetButton);
          }

          const savedFilters = localStorage.getItem('tabelPenagihanFilters');
          if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            $('#tgl_mulai').val(filters.startDate);
            $('#tgl_akhir').val(filters.endDate);
            $('#filter_status').val(filters.status).trigger('change');
          }
        }
      });

      let dateFilterTimeout;
      $('#tgl_mulai, #tgl_akhir').on('change', function () {
        const filters = {
          startDate: $('#tgl_mulai').val(),
          endDate: $('#tgl_akhir').val(),
          status: $('#filter_status').val()
        };
        localStorage.setItem('tabelPenagihanFilters', JSON.stringify(filters));

        clearTimeout(dateFilterTimeout);
        dateFilterTimeout = setTimeout(() => {
          penaginhanTable.draw();
        }, 300);
      });

      let statusFilterTimeout;
      $('#filter_status').on('change', function () {
        const searchTerm = $(this).val();

        const filters = {
          startDate: $('#tgl_mulai').val(),
          endDate: $('#tgl_akhir').val(),
          status: searchTerm
        };
        localStorage.setItem('tabelPenagihanFilters', JSON.stringify(filters));

        clearTimeout(statusFilterTimeout);
        statusFilterTimeout = setTimeout(() => {
          penaginhanTable
            .column(5)
            .search(searchTerm)
            .draw();
        }, 300);
      });

      $(document).on('click', '#reset_filter', function () {
        $('#tgl_mulai, #tgl_akhir').val('');
        $('#filter_status').val('').trigger('change');

        localStorage.removeItem('tabelPenagihanFilters');

        penaginhanTable
          .search('')
          .columns()
          .search('')
          .draw();

        toastr.success('Filter berhasil direset');
      });
    }

    // Initialize Select2
    $('.select2bs4').select2({
      theme: 'bootstrap4',
      width: '100%',
      language: {
        noResults: () => "Data tidak ditemukan",
        searching: () => "Mencari..."
      }
    });

    // Initialize DataTables
    function initializeDataTable(tableId, options = {}) {
      if (!$(tableId).length) return null;

      const defaultOptions = {
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        language: dataTableLanguage
      };

      try {
        return $(tableId).DataTable({ ...defaultOptions, ...options });
      } catch (error) {
        console.error(`Error initializing DataTable for ${tableId}:`, error);
        return null;
      }
    }

    // Initialize specific DataTables
    const tables = {
      '#example1': {
        lengthChange: false,
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
      },
      '#tabelSekolah': {},
      '#tabelContact': {},
      '#tabelBarang': {},
      '#tabelBarangKeluar': {},
      '#tabelBarangMasuk': {}
    };

    Object.entries(tables).forEach(([tableId, options]) => {
      const table = initializeDataTable(tableId, options);
      if (table && tableId === '#example1') {
        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }
    });

    // Modal handling
    $('#modalEdit').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const modal = $(this);
      const fields = [
        'nama_sekolah', 'alamat', 'nomor_kontak',
        'pemilik_kontak', 'jabatan', 'tanggal_dn', 'status_kontak'
      ];

      fields.forEach(field => {
        modal.find(`[name="${field}"]`).val(button.data(field));
      });
    });

    // Form submission handling
    $('#editForm').on('submit', function (e) {
      e.preventDefault();
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: () => {
          $('#modalEdit').modal('hide');
          Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil diperbarui',
            icon: 'success'
          }).then(() => location.reload());
        },
        error: () => {
          Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menyimpan data',
            icon: 'error'
          });
        }
      });
    });
  });

  // Standalone functions for order cancellation
  const showBatalkanModal = (index) => {
    $('#custIdBatal').val(index);
    $('#alasanBatal').val('');
    $('#modalBatalkan').modal('show');
  };

  const batalkanPesanan = () => {
    const alasan = $('#alasanBatal').val().trim();
    if (!alasan) {
      Swal.fire({
        title: 'Error!',
        text: 'Alasan pembatalan harus diisi',
        icon: 'error'
      });
      return;
    }

    Swal.fire({
      title: 'Konfirmasi Pembatalan',
      text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Batalkan!',
      cancelButtonText: 'Tidak'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire(
          'Dibatalkan!',
          'Pesanan telah dibatalkan.',
          'success'
        ).then(() => {
          $('#modalBatalkan').modal('hide');
          location.reload();
        });
      }
    });
  };

  const showAlasanBatal = (alasan) => {
    Swal.fire({
      title: 'Alasan Pembatalan',
      text: alasan,
      icon: 'info'
    });
  };
</script>
</body>

</html>