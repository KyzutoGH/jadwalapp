<script>
    function showDetailModal(el) {
        // Format angka ke format mata uang
        const formatCurrency = (amount) => {
            return 'Rp ' + parseFloat(amount).toLocaleString('id-ID');
        };

        // Persiapan data tanggal dan cek jatuh tempo
        const today = new Date();
        const formatDate = (dateStr) => {
            if (!dateStr || dateStr === '-') return '-';
            const parts = dateStr.split('/');
            if (parts.length !== 3) return dateStr;
            return `${parts[0]}/${parts[1]}/${parts[2]}`;
        };

        // Parse tanggal jatuh tempo
        const parseDate = (dateStr) => {
            if (!dateStr || dateStr === '-') return null;
            const parts = dateStr.split('/');
            if (parts.length !== 3) return null;
            return new Date(parts[2], parts[1] - 1, parts[0]);
        };

        const dp1_tenggat = parseDate(el.dataset.tenggat1);
        const dp2_tenggat = parseDate(el.dataset.tenggat2);
        const dp3_tenggat = parseDate(el.dataset.tenggat3);

        // Cek jatuh tempo
        const isDp1Overdue = dp1_tenggat && today > dp1_tenggat && el.dataset.dp1status === '0';
        const isDp2Overdue = dp2_tenggat && today > dp2_tenggat && el.dataset.dp2status === '0';
        const isDp3Overdue = dp3_tenggat && today > dp3_tenggat && el.dataset.dp3status === '0';
        const hasOverdue = isDp1Overdue || isDp2Overdue || isDp3Overdue;

        // Status badge dengan warna
        const getStatusBadge = (status) => {
            let badgeClass = 'badge ';
            if (status.includes('Belum Lunas')) {
                badgeClass += 'badge-warning';
            } else if (status.includes('Siap Diambil')) {
                badgeClass += 'badge-success';
            } else if (status.includes('Proses')) {
                badgeClass += 'badge-info';
            } else if (status.includes('Selesai')) {
                badgeClass += 'badge-secondary';
            } else if (status.includes('Dibatalkan')) {
                badgeClass += 'badge-danger';
            } else {
                badgeClass += 'badge-primary';
            }
            return `<span class="${badgeClass}">${status}</span>`;
        };

        // Peringatan pembayaran jatuh tempo
        let overdueWarnings = '';
        if (hasOverdue) {
            overdueWarnings = '<div class="alert alert-danger mb-4">';
            overdueWarnings += '<i class="fas fa-exclamation-triangle mr-2"></i><strong>Peringatan:</strong> Ada pembayaran yang melewati jatuh tempo!';
            overdueWarnings += '<ul class="mb-0 mt-2">';

            if (isDp1Overdue) {
                overdueWarnings += `<li>DP1 seharusnya dibayar sebelum ${formatDate(el.dataset.tenggat1)}</li>`;
            }
            if (isDp2Overdue) {
                overdueWarnings += `<li>DP2 seharusnya dibayar sebelum ${formatDate(el.dataset.tenggat2)}</li>`;
            }
            if (isDp3Overdue) {
                overdueWarnings += `<li>DP3 seharusnya dibayar sebelum ${formatDate(el.dataset.tenggat3)}</li>`;
            }

            overdueWarnings += '</ul></div>';
        }

        // Kartu informasi dasar
        let basicInfo = `
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Customer:</strong> ${el.dataset.customer}</p>
                        <p><strong>Tanggal Pesan:</strong> ${el.dataset.tanggal}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>ID Pesanan:</strong> #${el.dataset.id}</p>
                        <p><strong>Status:</strong> ${getStatusBadge(el.dataset.status)}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Kontak:</strong> 0${el.dataset.kontak || '-'}</p>
                </div>
            </div>
        </div>`;

        // Kartu detail pembayaran
        let paymentDetails = `
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Detail Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="text-center border rounded p-3 h-100 bg-light">
                            <p class="mb-1"><strong>Total Tagihan</strong></p>
                            <h4 class="mb-0">${formatCurrency(el.dataset.total)}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center border rounded p-3 h-100 bg-light">
                            <p class="mb-1"><strong>Total Dibayar</strong></p>
                            <h4 class="mb-0">${formatCurrency(el.dataset.totalDibayar)}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center border rounded p-3 h-100 ${parseFloat(el.dataset.sisaTagihan) > 0 ? 'bg-warning' : 'bg-success text-white'}">
                            <p class="mb-1"><strong>Sisa Tagihan</strong></p>
                            <h4 class="mb-0">${parseFloat(el.dataset.sisaTagihan) > 0 ? formatCurrency(el.dataset.sisaTagihan) : 'LUNAS'}</h4>
                        </div>
                    </div>
                </div>`;

        // Rincian DP
        if (el.dataset.dp1 || el.dataset.dp2 || el.dataset.dp3) {
            paymentDetails += '<h6 class="mt-4 mb-3 border-bottom pb-2"><strong>Rincian Pembayaran</strong></h6>';
            paymentDetails += '<div class="table-responsive"><table class="table table-sm table-bordered table-hover">';
            paymentDetails += `<thead class="thead-light">
                <tr>
                    <th>Pembayaran</th>
                    <th>Nominal</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>`;

            // DP1
            if (el.dataset.dp1) {
                const dp1Status = el.dataset.dp1status === '1' ?
                    '<span class="badge badge-success">Dibayar</span>' :
                    (isDp1Overdue ? '<span class="badge badge-danger">Terlambat</span>' : '<span class="badge badge-warning">Belum Dibayar</span>');

                paymentDetails += `
                <tr>
                    <td>DP1</td>
                    <td>${formatCurrency(el.dataset.dp1)}</td>
                    <td>${formatDate(el.dataset.tenggat1)}</td>
                    <td>${dp1Status}</td>
                </tr>`;
            }

            // DP2
            if (el.dataset.dp2) {
                const dp2Status = el.dataset.dp2status === '1' ?
                    '<span class="badge badge-success">Dibayar</span>' :
                    (isDp2Overdue ? '<span class="badge badge-danger">Terlambat</span>' : '<span class="badge badge-warning">Belum Dibayar</span>');

                paymentDetails += `
                <tr>
                    <td>DP2</td>
                    <td>${formatCurrency(el.dataset.dp2)}</td>
                    <td>${formatDate(el.dataset.tenggat2)}</td>
                    <td>${dp2Status}</td>
                </tr>`;
            }

            // DP3
            if (el.dataset.dp3) {
                const dp3Status = el.dataset.dp3status === '1' ?
                    '<span class="badge badge-success">Dibayar</span>' :
                    (isDp3Overdue ? '<span class="badge badge-danger">Terlambat</span>' : '<span class="badge badge-warning">Belum Dibayar</span>');

                paymentDetails += `
                <tr>
                    <td>DP3</td>
                    <td>${formatCurrency(el.dataset.dp3)}</td>
                    <td>${formatDate(el.dataset.tenggat3)}</td>
                    <td>${dp3Status}</td>
                </tr>`;
            }

            // PELUNASAN
            if (el.dataset.pelunasan) {
                const pelunasan = Number(el.dataset.pelunasan);
                const status = el.dataset.status;
                const lunasStatus = ['2', '3', '4'];
                let pelunasanStatus = '';

                if (lunasStatus.includes(status)) {
                    pelunasanStatus = '<span class="badge badge-success">Sudah lunas duluan</span>';
                } else if (pelunasan > 0) {
                    pelunasanStatus = '<span class="badge badge-success">Dibayar</span>';
                } else {
                    pelunasanStatus = '<span class="badge badge-danger">Belum Dibayar</span>';
                }

                paymentDetails += `
                <tr>
                    <td>Pelunasan</td>
                    <td>${formatCurrency(el.dataset.pelunasan)}</td>
                    <td>${formatDate(el.dataset.tglpelunasan)}</td>
                    <td>${pelunasanStatus}</td>
                </tr>`;
            }

            paymentDetails += '</tbody></table></div>';
        }

        paymentDetails += '</div></div>';

        // Kartu detail produk
        let productDetails = `
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-box mr-2"></i>Detail Produk</h5>
            </div>
            <div class="card-body">`;

        if (el.dataset.produk && el.dataset.produk !== '-') {
            const produkLines = el.dataset.produk.split('\n');
            productDetails += '<ul class="list-group">';
            produkLines.forEach(line => {
                productDetails += `<li class="list-group-item">${line}</li>`;
            });
            productDetails += '</ul>';
        } else {
            productDetails += '<p class="text-muted">Tidak ada detail produk.</p>';
        }

        if (el.dataset.keterangan && el.dataset.keterangan !== '-') {
            productDetails += `
            <div class="mt-3">
                <h6><strong>Catatan:</strong></h6>
                <div class="p-3 bg-light rounded">${el.dataset.keterangan}</div>
            </div>`;
        }

        productDetails += '</div></div>';

        // Susun modal
        const html = `
            ${overdueWarnings}
            ${basicInfo}
            ${paymentDetails}
            ${productDetails}
        `;

        // Update modal content
        document.getElementById('detailContent').innerHTML = html;

        // Set WhatsApp link
        const phoneNumber = el.dataset.kontak || '';
        if (phoneNumber) {
            $('#whatsappCustomerBtn').attr('href', `https://wa.me/62${phoneNumber}`).show();
        } else {
            $('#whatsappCustomerBtn').hide();
        }

        // Tampilkan modal
        $('#modalDetail').modal('show');
    }

    // Fungsi untuk mencetak detail
    document.getElementById('printDetailBtn').addEventListener('click', function () {
        const printContent = document.getElementById('detailContent').innerHTML;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Struk Pemesanan</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body {
                    padding: 40px;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: #f8f9fa;
                }
                .receipt-container {
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 8px;
                    padding: 30px;
                    max-width: 700px;
                    margin: auto;
                    box-shadow: 0 0 15px rgba(0,0,0,0.1);
                }
                .store-info {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .store-info img {
                    width: 100px;
                    margin-bottom: 10px;
                }
                .store-info h5 {
                    margin-bottom: 5px;
                    font-weight: bold;
                }
                .store-info p {
                    margin: 0;
                    font-size: 13px;
                    color: #6c757d;
                }
                .receipt-header {
                    border-top: 2px dashed #6c757d;
                    border-bottom: 2px dashed #6c757d;
                    margin: 20px 0;
                    padding: 10px 0;
                    text-align: center;
                }
                .receipt-content {
                    font-size: 15px;
                }
                .receipt-footer {
                    border-top: 2px dashed #6c757d;
                    margin-top: 30px;
                    padding-top: 10px;
                    text-align: center;
                    font-size: 13px;
                    color: #6c757d;
                }
                .btn-print {
                    margin-top: 30px;
                    text-align: center;
                }
                @media print {
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="store-info">
                    <img src="assets/img/fukubistorelogo.png" alt="Fubuki Konveksi">
                    <h5>Fubuki Konveksi</h5>
                    <p>Dadapan, Kedung Bunder, Kec. Sutojayan, Kabupaten Blitar, Jawa Timur 66172</p>
                    <p>WA: 0856-4962-3058</p>
                </div>

                <div class="receipt-header">
                    <h4>Detail Pemesanan</h4>
                </div>

                <div class="receipt-content">
                    ${printContent}
                </div>

                <div class="receipt-footer">
                    Terima kasih telah memesan di Fubuki Konveksi.
                </div>

                <div class="btn-print no-print">
                    <button onclick="window.print()" class="btn btn-primary">Print</button>
                    <button onclick="window.close()" class="btn btn-secondary ml-2">Tutup</button>
                </div>
            </div>
        </body>
        </html>
    `);
        printWindow.document.close();
    });
    function updateStatus(id, newStatus) {
        // Set judul dan pesan berdasarkan status yang akan diubah
        let title = newStatus === 3 ? 'Konfirmasi Barang Siap' : 'Konfirmasi Pengambilan';
        let text = newStatus === 3 ?
            'Apakah barang sudah selesai diproduksi dan siap diambil?' :
            'Apakah barang sudah diambil oleh customer?';

        // Update modal content
        $('#statusModalLabel').text(title);
        $('#statusModalBody').text(text);

        // Set link konfirmasi
        $('#konfirmasiStatusBtn').attr('href', `config/update_status.php?id=${id}&status=${newStatus}`);

        // Tampilkan modal
        $('#statusModal').modal('show');
    }

    function showCicilanModal(id, cicilanKe, totalCicilan, nominal, tenggat) {
        console.log('Parameters received:', id, cicilanKe, totalCicilan, nominal, tenggat);

        // Convert nominal ke number jika perlu
        nominal = parseFloat(nominal) || 0;

        // Set nilai ke form
        $('#penagihan_id').val(id);
        $('#cicilan_ke').val(cicilanKe);
        $('#total_cicilan').val(totalCicilan);
        // Format tampilan
        var infoText = '';
        if (cicilanKe <= totalCicilan) {
            infoText = 'Pembayaran DP ' + cicilanKe + ' dari ' + totalCicilan +
                '<br>Nominal: Rp ' + nominal.toLocaleString('id-ID') +
                '<br>Jatuh Tempo: ' + tenggat;
        } else {
            infoText = 'Pelunasan Pembayaran' +
                '<br>Nominal: Rp ' + nominal.toLocaleString('id-ID');
        }
        $('#infoCicilan').html(infoText);

        // Debugging
        console.log('Values set:', {
            id: $('#penagihan_id').val(),
            cicilanKe: $('#cicilan_ke').val(),
            totalCicilan: $('#total_cicilan').val(),
            nominal: $('#nominal').val()
        });

        $('#cicilanModal').modal('show');
    }
    function showBatalkanModal(id) {
        $('#custIdBatal').val(id);
        $('#modalBatalkan').modal('show');
    }

    function tampilkanAlasan(alasan) {
        document.getElementById('alasanText').innerText = alasan;
    }

    // Payment form validation
    $('#formCicilan').on('submit', function (e) {
        e.preventDefault(); // Mencegah pengiriman form secara default

        const cicilanKe = parseInt($('#cicilanKe').val());
        const totalCicilan = parseInt($(this).data('total-cicilan'));
        const $jumlahBayar = $('#jumlahBayar');
        const jumlahBayar = parseFloat($jumlahBayar.val());

        // Validasi form
        if (cicilanKe > totalCicilan) {
            const sisaPembayaran = parseFloat($jumlahBayar.attr('min'));

            if (jumlahBayar !== sisaPembayaran) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah pelunasan harus sesuai dengan sisa pembayaran'
                });
                return false;
            }
        } else if (!jumlahBayar || jumlahBayar <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Jumlah pembayaran harus lebih dari 0'
            });
            return false;
        }

        // Jika validasi sukses, kirim form secara manual
        this.submit(); // Kirim form jika validasi sukses
    });

    // Cancel form validation
    $('#formBatalkan').on('submit', function (e) {
        const alasanBatal = $('#alasanBatal').val().trim();

        if (!alasanBatal) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Alasan pembatalan harus diisi'
            });
            return false;
        }
    });
</script>