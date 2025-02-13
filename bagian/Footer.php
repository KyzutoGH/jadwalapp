<!-- REQUIRED SCRIPTS -->
<script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
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
  // Configuration Objects
  const CONFIG = {
    toastr: {
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
    },
    dataTable: {
      language: {
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
      },
      defaultOptions: {
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        deferRender: true
      }
    },
    chart: {
      defaultOptions: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1, precision: 0 }
          }
        },
        plugins: {
          legend: { display: true, position: 'top' }
        }
      }
    }
  };

  // Chart Management
  class ChartManager {
    static init(canvasId, data) {
      const canvas = document.getElementById(canvasId);
      if (!canvas) {
        console.warn(`Canvas ${canvasId} not found`);
        return null;
      }

      // Destroy existing chart
      const existingChart = Chart.getChart(canvas);
      if (existingChart) {
        existingChart.destroy();
      }

      return new Chart(canvas, {
        type: 'bar',
        data: data || this.getDefaultData(),
        options: CONFIG.chart.defaultOptions
      });
    }

    static getDefaultData() {
      return {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
          label: 'Jumlah Dies Natalis',
          data: new Array(12).fill(0),
          backgroundColor: 'rgba(60,141,188,0.9)',
          borderColor: 'rgba(60,141,188,0.8)',
          borderWidth: 1
        }]
      };
    }
  }

  // DataTable Management
  class DataTableManager {
    static tables = new Map();

    static init(selector, customOptions = {}) {
      if (!$(selector).length) return null;

      const options = {
        ...CONFIG.dataTable.defaultOptions,
        ...customOptions,
        language: CONFIG.dataTable.language
      };

      // Destroy if exists
      if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
      }

      const table = $(selector).DataTable(options);
      this.tables.set(selector, table);
      return table;
    }

    static refresh(selector) {
      const table = this.tables.get(selector);
      if (table) {
        table.ajax.reload();
      }
    }

    static initializeAll() {
      const tableConfigs = {
        '#example1': {
          lengthChange: false,
          buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        },
        '#tabelPenagihan': {
          order: [[0, 'desc']],
          pageLength: 25
        }
      };

      Object.entries(tableConfigs).forEach(([selector, options]) => {
        const table = this.init(selector, options);
        if (table && selector === '#example1') {
          table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }
      });
    }
  }

  // Filter Management
  class FilterManager {
    static createDateRangeFilter() {
      return (settings, data, dataIndex) => {
        if (settings.nTable.id !== 'tabelPenagihan') return true;

        const startDate = $('#tgl_mulai').val();
        const endDate = $('#tgl_akhir').val();
        if (!startDate && !endDate) return true;

        const dateInTable = moment(data[0], 'DD/MM/YYYY', true);
        if (!dateInTable.isValid()) return false;

        const start = startDate ? moment(startDate) : null;
        const end = endDate ? moment(endDate) : null;

        if (start && end) return dateInTable.isBetween(start, end, 'day', '[]');
        if (start) return dateInTable.isSameOrAfter(start, 'day');
        if (end) return dateInTable.isSameOrBefore(end, 'day');

        return true;
      };
    }

    static setupFilters() {
      const debounce = (fn, delay) => {
        let timeoutId;
        return (...args) => {
          clearTimeout(timeoutId);
          timeoutId = setTimeout(() => fn(...args), delay);
        };
      };

      $('#tgl_mulai, #tgl_akhir').on('change', debounce(() => {
        this.saveFilters();
        DataTableManager.tables.get('#tabelPenagihan')?.draw();
      }, 300));

      $('#filter_status').on('change', debounce(() => {
        this.saveFilters();
        const table = DataTableManager.tables.get('#tabelPenagihan');
        if (table) {
          table.column(5).search($('#filter_status').val()).draw();
        }
      }, 300));
    }

    static saveFilters() {
      try {
        const filters = {
          startDate: $('#tgl_mulai').val(),
          endDate: $('#tgl_akhir').val(),
          status: $('#filter_status').val()
        };
        localStorage.setItem('tabelPenagihanFilters', JSON.stringify(filters));
      } catch (error) {
        console.error('Error saving filters:', error);
      }
    }

    static loadSavedFilters() {
      try {
        const savedFilters = JSON.parse(localStorage.getItem('tabelPenagihanFilters'));
        if (savedFilters) {
          $('#tgl_mulai').val(savedFilters.startDate);
          $('#tgl_akhir').val(savedFilters.endDate);
          $('#filter_status').val(savedFilters.status).trigger('change');
        }
      } catch (error) {
        console.error('Error loading saved filters:', error);
      }
    }
  }

  // Initialize everything when DOM is ready
  document.addEventListener('DOMContentLoaded', () => {
    try {
      // Initialize Chart
      if (window.Chart) {
        ChartManager.init('diesNatalisChart');

        // Add resize handler
        const debouncedResize = _.debounce(() => {
          ChartManager.init('diesNatalisChart');
        }, 250);
        window.addEventListener('resize', debouncedResize);
      }

      // Initialize DataTables
      DataTableManager.initializeAll();

      // Setup Filters
      FilterManager.setupFilters();
      FilterManager.loadSavedFilters();

      // Initialize Select2
      $('.select2bs4').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: {
          noResults: () => "Data tidak ditemukan",
          searching: () => "Mencari..."
        }
      });

      console.log('All initializations completed successfully');
    } catch (error) {
      console.error('Error during initialization:', error);
    }
  });
  document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded');

    if (typeof $.fn.DataTable !== 'undefined') {
      try {
        $('.tabelBarang').each(function () {
          $(this).DataTable({
            processing: true,
            pageLength: 10,
            responsive: true,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
              "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
              "sProcessing": "Sedang memproses...",
              "sLengthMenu": "Tampilkan _MENU_ entri",
              "sZeroRecords": "Tidak ditemukan data yang sesuai",
              "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
              "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
              "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
              "sInfoPostFix": "",
              "sSearch": "Cari:",
              "oPaginate": {
                "sFirst": "Pertama",
                "sPrevious": "Sebelumnya",
                "sNext": "Selanjutnya",
                "sLast": "Terakhir"
              }
            }
          });
        });
        console.log('DataTable initialized');
      } catch (e) {
        console.error('Error initializing DataTable:', e);
      }
    } else {
      console.error('DataTables not loaded');
    }
  });
</script>
</body>

</html>