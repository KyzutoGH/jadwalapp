  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="#">Fukubi</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 0.1
    </div>
  </footer>
  </div>
  <!-- ./wrapper -->

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
  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
  <script>
    $(function() {
      //Enable check and uncheck all functionality
      $('.checkbox-toggle').click(function() {
        var clicks = $(this).data('clicks')
        if (clicks) {
          //Uncheck all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
          $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
        } else {
          //Check all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
          $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
        }
        $(this).data('clicks', !clicks)
      })

      //Handle starring for font awesome
      $('.mailbox-star').click(function(e) {
        e.preventDefault()
        //detect type
        var $this = $(this).find('a > i')
        var fa = $this.hasClass('fa')

        //Switch states
        if (fa) {
          $this.toggleClass('fa-star')
          $this.toggleClass('fa-star-o')
        }
      })
    })
  </script>
  <script>
    $(function() {
      // Inisialisasi Select2
      $('.select2bs4').select2({
        theme: 'bootstrap4',
        width: '100%',
        language: {
          noResults: function() {
            return "Data tidak ditemukan";
          },
          searching: function() {
            return "Mencari...";
          }
        }
      });

      // Variabel untuk menyimpan data sekolah
      let schoolsData = [];

      // Fungsi untuk mengambil data sekolah dari API
      async function fetchSchools(wilayah = '') {
        try {
          const response = await fetch('https://api-sekolah-indonesia.vercel.app/sekolah?page=1&perPage=100');
          const data = await response.json();

          if (data.dataSekolah) {
            schoolsData = data.dataSekolah.filter(school =>
              !wilayah || school.kabupaten_kota.trim() === wilayah
            );
            updateSchoolDropdown();
          }
        } catch (error) {
          console.error('Error fetching schools:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal mengambil data sekolah'
          });
        }
      }

      // Fungsi untuk memperbarui dropdown sekolah
      function updateSchoolDropdown() {
        const schoolDropdown = $('#sekolah');
        schoolDropdown.empty().append(new Option('Pilih Sekolah', ''));

        schoolsData
          .sort((a, b) => a.sekolah.localeCompare(b.sekolah))
          .forEach(school => {
            schoolDropdown.append(new Option(
              school.sekolah + ' (' + school.bentuk + ')',
              school.npsn
            ));
          });

        schoolDropdown.trigger('change');
      }

      schoolDropdown.trigger('change');
    },

    // Event listener untuk perubahan wilayah
    $('#wilayah').on('change', function() {
      const selectedWilayah = $(this).val();
      if (selectedWilayah) {
        fetchSchools(selectedWilayah);
      } else {
        $('#sekolah').empty().append(new Option('Pilih Sekolah', '')).trigger('change');
      }
    }),

    // Handle form submission
    $('#contactForm').on('submit', function(e) {
      e.preventDefault();

      const formData = {
        nama: $('#nama').val(),
        email: $('#email').val(),
        telepon: $('#telepon').val(),
        wilayah: $('#wilayah').val(),
        sekolah: $('#sekolah').val(),
        alamat: $('#alamat').val()
      };

      // Simulate AJAX request
      setTimeout(() => {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Data kontak berhasil disimpan',
          showConfirmButton: false,
          timer: 1500
        }).then(() => {
          $('#contactForm')[0].reset();
          $('#wilayah, #sekolah').val(null).trigger('change');
        });
      }, 500);
    }),

    // Batal button handler
    $('button[type="button"]').on('click', function() {
      $('#contactForm')[0].reset();
      $('#wilayah, #sekolah').val(null).trigger('change');
    }));
  </script>
  </body>

  </html>