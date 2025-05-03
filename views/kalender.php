<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="sticky-top mb-3">
                <div class="card">
                    <div class="card-header"><?php
                    $bulan_ini = $formatter->format(new DateTime()); ?>
                        <h4 class="card-title">Dies Natalis Bulan <?php echo $bulan_ini; ?></h4>
                    </div>
                    <div class="card-body">
                        <!-- the events -->
                        <div id="external-events">
                            <?php
                            $query = "SELECT * FROM datadn";
                            $result = $db->query($query);
                            $current_month = date('m');
                            while ($row = $result->fetch_assoc()) {
                                $bulan_event = date('m', strtotime($row['tanggal_dn']));
                                if ($bulan_event === $current_month) {

                                    ?>
                                    <div class="external-event bg-success"><?php echo $row['nama_sekolah']; ?></div>
                                    <?php
                                }
                            } ?>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="card shadow-lg rounded">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Legenda Kategori Pendidikan</h4>
                    </div>
                    <div class="card-body">
                        <!-- Legenda Warna -->
                        <div id="external-events">
                            <ul class="fc-color-picker list-unstyled">
                                <li class="mb-2">
                                    <a style="color: #FFEB3B; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> SD
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a style="color: #8BC34A; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> SMP
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a style="color: #2196F3; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> SMA
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a style="color: #FF5722; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> SMK
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a style="color: #FFC107; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> MI
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a style="color: #009688; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> MTs
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a style="color: #3F51B5; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> MA
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a style="color: #9C27B0; font-size: 1.1rem;" href="#">
                                        <i class="fas fa-square me-2"></i> MAK
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->

<?php

$events = [];

if ($result) {
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $backgroundColor = '';
        if (strpos($row['nama_sekolah'], 'SD') !== false) {
            $backgroundColor = '#FFEB3B'; // Warna kuning untuk SD
        } elseif (strpos($row['nama_sekolah'], 'SMP') !== false) {
            $backgroundColor = '#8BC34A'; // Warna hijau untuk SMP
        } elseif (strpos($row['nama_sekolah'], 'SMA') !== false) {
            $backgroundColor = '#2196F3'; // Warna biru untuk SMA
        } elseif (strpos($row['nama_sekolah'], 'SMK') !== false) {
            $backgroundColor = '#FF5722'; // Warna oranye untuk SMK
        } elseif (strpos($row['nama_sekolah'], 'MI') !== false) {
            $backgroundColor = '#FFC107'; // Warna kuning untuk MI
        } elseif (strpos($row['nama_sekolah'], 'MTs') !== false) {
            $backgroundColor = '#009688'; // Warna teal untuk MTs
        } elseif (strpos($row['nama_sekolah'], 'MA') !== false) {
            $backgroundColor = '#3F51B5'; // Warna biru tua untuk MA
        } elseif (strpos($row['nama_sekolah'], 'MAK') !== false) {
            $backgroundColor = '#9C27B0'; // Warna ungu untuk MAK
        }
        $tanggal_dn = date('Y-m-d', strtotime($row['tanggal_dn']));
    }
    $events[] = [
        'title' => 'Dies Natalis ' . $row['nama_sekolah'],
        'start' => $tanggal_dn,
        'end' => $tanggal_dn,
        'backgroundColor' => $backgroundColor,
        'borderColor' => '#000000'
    ];
}

$event_json = json_encode($events);
?>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;
        const diesnatalis = <?php echo $event_json; ?>;

        // Inisialisasi kalender
        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap',
            editable: true,
            droppable: true,
            events: diesnatalis
        });

        calendar.render();
    });

</script>