<?php
$jadwalController = new JadwalController($database);
$jadwalData = $jadwalController->read();
?>
<div id="calendar"></div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: <?php echo json_encode($jadwalData); ?>,
        eventClick: function(info) {
            showEventDetails(info.event);
        }
    });
    calendar.render();
});
</script>
