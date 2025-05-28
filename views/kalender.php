<style>
    /* Custom CSS untuk mempercantik UI */
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        padding: 20px;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }

    .external-event {
        padding: 15px 18px;
        margin: 10px 0;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .external-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
    }

    .external-event:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        background: linear-gradient(135deg, #20c997, #28a745);
    }

    .external-event i {
        opacity: 0.9;
    }

    .external-event small {
        font-size: 0.75rem;
        opacity: 0.9;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 6px;
        border-radius: 10px;
    }

    .legend-card {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: 1px solid #dee2e6;
    }

    .legend-item {
        padding: 10px 15px;
        margin: 5px 0;
        border-radius: 8px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .legend-item:hover {
        transform: translateX(3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .calendar-card {
        background: white;
        border-radius: 15px;
    }

    #calendar {
        padding: 20px;
    }

    /* FullCalendar custom styling */
    .fc-toolbar-title {
        font-weight: 700;
        color: #495057;
    }

    .fc-button-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }

    .fc-button-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    .fc-daygrid-event {
        border-radius: 6px;
        padding: 2px 8px;
        font-weight: 500;
    }

    /* Animasi fade in */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animated-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .animated-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .animated-card:nth-child(3) {
        animation-delay: 0.4s;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="sticky-top mb-3">
                <!-- Card Dies Natalis Bulan Ini -->
                <div class="card animated-card">
                    <div class="card-header bg-gradient-primary">
                        <?php $bulan_ini = $formatter->format(new DateTime()); ?>
                        <h4 class="card-title text-white">
                            <i class="fas fa-birthday-cake mr-2"></i>
                            Dies Natalis Bulan <?php echo $bulan_ini; ?>
                        </h4>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        <div id="external-events">
                            <?php
                            $query = "SELECT * FROM datadn ORDER BY tanggal_dn ASC";
                            $result = $db->query($query);
                            $current_month = date('m');
                            $count = 0;

                            while ($row = $result->fetch_assoc()) {
                                $bulan_event = date('m', strtotime($row['tanggal_dn']));
                                if ($bulan_event === $current_month) {
                                    $count++;
                                    $tanggal_format = date('d M', strtotime($row['tanggal_dn']));
                                    ?>
                                    <div class="external-event text-white position-relative" data-toggle="tooltip"
                                        title="Dies Natalis - <?php echo $tanggal_format; ?>"
                                        style="background: linear-gradient(135deg, #28a745, #20c997); border: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="fas fa-graduation-cap mr-2"></i>
                                                <strong><?php echo $row['nama_sekolah']; ?></strong>
                                            </div>
                                            <div class="text-right">
                                                <small class="badge badge-light text-dark">
                                                    <i class="fas fa-calendar-day mr-1"></i>
                                                    <?php echo $tanggal_format; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }

                            if ($count == 0) {
                                echo '<div class="text-center text-muted py-3">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p>Tidak ada dies natalis bulan ini</p>
                                      </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Card Legenda -->
                <div class="card legend-card shadow-sm animated-card">
                    <div class="card-header bg-gradient-info">
                        <h4 class="card-title text-white mb-0">
                            <i class="fas fa-palette mr-2"></i>
                            Legenda Kategori Pendidikan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #FFEB3B;"></i>
                                    <strong>SD</strong>
                                    <small class="text-muted d-block">Sekolah Dasar</small>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #8BC34A;"></i>
                                    <strong>SMP</strong>
                                    <small class="text-muted d-block">Sekolah Menengah Pertama</small>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #2196F3;"></i>
                                    <strong>SMA</strong>
                                    <small class="text-muted d-block">Sekolah Menengah Atas</small>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #FF5722;"></i>
                                    <strong>SMK</strong>
                                    <small class="text-muted d-block">Sekolah Menengah Kejuruan</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #FFC107;"></i>
                                    <strong>MI</strong>
                                    <small class="text-muted d-block">Madrasah Ibtidaiyah</small>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #009688;"></i>
                                    <strong>MTs</strong>
                                    <small class="text-muted d-block">Madrasah Tsanawiyah</small>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #3F51B5;"></i>
                                    <strong>MA</strong>
                                    <small class="text-muted d-block">Madrasah Aliyah</small>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-circle mr-2" style="color: #9C27B0;"></i>
                                    <strong>MAK</strong>
                                    <small class="text-muted d-block">Madrasah Aliyah Kejuruan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="col-md-9">
            <div class="card calendar-card animated-card">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Kalender Dies Natalis
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool text-white" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$events = [];

if ($result) {
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $backgroundColor = '';
        $textColor = '#ffffff';

        // Improved color detection with better logic
        $nama_sekolah = strtoupper($row['nama_sekolah']);

        if (strpos($nama_sekolah, 'SD ') !== false || strpos($nama_sekolah, 'SDN') !== false) {
            $backgroundColor = '#FFEB3B';
            $textColor = '#333333';
        } elseif (strpos($nama_sekolah, 'SMP') !== false) {
            $backgroundColor = '#8BC34A';
        } elseif (strpos($nama_sekolah, 'SMA') !== false) {
            $backgroundColor = '#2196F3';
        } elseif (strpos($nama_sekolah, 'SMK') !== false) {
            $backgroundColor = '#FF5722';
        } elseif (strpos($nama_sekolah, 'MI ') !== false || strpos($nama_sekolah, 'MIN') !== false) {
            $backgroundColor = '#FFC107';
            $textColor = '#333333';
        } elseif (strpos($nama_sekolah, 'MTS') !== false) {
            $backgroundColor = '#009688';
        } elseif (strpos($nama_sekolah, 'MA ') !== false || strpos($nama_sekolah, 'MAN') !== false) {
            $backgroundColor = '#3F51B5';
        } elseif (strpos($nama_sekolah, 'MAK') !== false) {
            $backgroundColor = '#9C27B0';
        } else {
            $backgroundColor = '#6c757d'; // Default gray
        }

        $tanggal_dn = date('Y-m-d', strtotime($row['tanggal_dn']));

        $events[] = [
            'title' => $row['nama_sekolah'],
            'start' => $tanggal_dn,
            'end' => $tanggal_dn,
            'backgroundColor' => $backgroundColor,
            'borderColor' => $backgroundColor,
            'textColor' => $textColor,
            'extendedProps' => [
                'description' => 'Dies Natalis ' . $row['nama_sekolah'],
                'tanggal' => date('d F Y', strtotime($row['tanggal_dn']))
            ]
        ];
    }
}

$event_json = json_encode($events);
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function formatTanggalIndonesia(tanggalString) {
            const date = new Date(tanggalString);

            if (isNaN(date)) return 'Tanggal tidak valid';

            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const hari = date.getDate();
            const namaBulan = bulan[date.getMonth()];
            const tahun = date.getFullYear();

            return `${hari} ${namaBulan} ${tahun}`;
        }
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        const diesnatalis = <?php echo $event_json; ?>;

        // Initialize calendar
        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            initialView: 'dayGridMonth',
            height: 'auto',
            locale: 'id',
            themeSystem: 'bootstrap5',
            editable: false,
            droppable: false,
            events: diesnatalis,
            eventClick: function (info) {
                // Show event details in Bootstrap modal
                const event = info.event;
                const modalHtml = `
                <div class="modal fade" id="eventModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-birthday-cake mr-2"></i>
                                    Detail Dies Natalis
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card border-left-primary">
                                            <div class="card-body">
                                                <h6 class="text-primary font-weight-bold">
                                                    <i class="fas fa-school mr-2"></i>Nama Sekolah
                                                </h6>
                                                <p class="mb-3">${event.title}</p>
                                                
                                                <h6 class="text-primary font-weight-bold">
                                                    <i class="fas fa-calendar mr-2"></i>Tanggal Dies Natalis
                                                </h6>
                                                <p class="mb-3">${formatTanggalIndonesia(event.extendedProps.tanggal)}</p>
                                                <h6 class="text-primary font-weight-bold">
                                                    <i class="fas fa-info-circle mr-2"></i>Keterangan
                                                </h6>
                                                <p class="mb-0">${event.extendedProps.description}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="index.php?menu=Tabel" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt mr-2"></i>Lihat Detail Dies Natalis
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                // Remove existing modal if any
                $('#eventModal').remove();

                // Add modal to body and show
                $('body').append(modalHtml);
                $('#eventModal').modal('show');
            },
            eventMouseEnter: function (info) {
                // Add hover effect
                info.el.style.transform = 'scale(1.05)';
                info.el.style.zIndex = '999';
            },
            eventMouseLeave: function (info) {
                // Remove hover effect
                info.el.style.transform = 'scale(1)';
                info.el.style.zIndex = 'auto';
            }
        });

        calendar.render();

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Add loading animation
        $('.animated-card').each(function (index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
    });
</script>