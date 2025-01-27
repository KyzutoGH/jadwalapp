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
  $(document).ready(function () {
    // Inisialisasi DataTables untuk semua tabel yang ada
    ['#tabelSekolah', '#tabelContact', '#tabelPenagihan'].forEach(function (tableId) {
      if ($(tableId).length) {
        $(tableId).DataTable({
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
      }
    });
  });
  $(document).ready(function () {
    // When modal is about to be shown
    $('#modalEdit').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var modal = $(this);

      // Debug the data attributes
      console.log('Data from button:', {
        id: button.data('id'),
        nama_sekolah: button.data('nama_sekolah'),
        alamat: button.data('alamat'),
        nomor: button.data('nomor_kontak'),
        pemilik_kontak: button.data('pemilik_kontak'),
        jabatan: button.data('jabatan'),
        tanggal_dn: button.data('tanggal_dn'),
        status_kontak: button.data('status_kontak')
      });

      // Set values to form fields
      modal.find('input[name="nama_sekolah"]').val(button.data('nama_sekolah'));
      modal.find('input[name="alamat"]').val(button.data('alamat'));
      modal.find('input[name="nomor_kontak"]').val(button.data('nomor_kontak'));
      modal.find('input[name="pemilik_kontak"]').val(button.data('pemilik_kontak'));
      modal.find('input[name="jabatan"]').val(button.data('jabatan'));
      modal.find('input[name="tanggal_dn"]').val(button.data('tanggal_dn'));
      modal.find('select[name="status_kontak"]').val(button.data('status_kontak'));
    });

    // Add form submission handling
    $('#editForm').on('submit', function (e) {
      e.preventDefault();

      // Get form data
      var formData = $(this).serialize();

      // Submit using AJAX
      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function (response) {
          $('#modalEdit').modal('hide');
          Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil diperbarui',
            icon: 'success'
          }).then((result) => {
            location.reload();
          });
        },
        error: function (xhr, status, error) {
          Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menyimpan data',
            icon: 'error'
          });
        }
      });
    });
  });
  // Fungsi-fungsi CRUD
  function editSekolah(id) {
    document.getElementById('editContact').submit();
  }

  function hapusSekolah(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data sekolah ini?')) {
      document.getElementById('hapusSekolahForm').submit();
    }
  }
  function showBatalkanModal(index) {
    $('#custIdBatal').val(index);
    $('#alasanBatal').val('');
    $('#modalBatalkan').modal('show');
  }

  function batalkanPesanan() {
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
        // Here you would normally update the database
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
  }

  function showAlasanBatal(alasan) {
    Swal.fire({
      title: 'Alasan Pembatalan',
      text: alasan,
      icon: 'info'
    });
  }
  $(function () {
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
  $(function () {
    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function () {
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
    $('.mailbox-star').click(function (e) {
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
  $(function () {
    // Inisialisasi Select2
    $('.select2bs4').select2({
      theme: 'bootstrap4',
      width: '100%',
      language: {
        noResults: function () {
          return "Data tidak ditemukan";
        },
        searching: function () {
          return "Mencari...";
        }
      }
    })
  });
</script>
<script>
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right", // Ubah posisi jika perlu
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };
</script>

</body>

</html>