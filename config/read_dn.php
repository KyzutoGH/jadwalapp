<?php
require_once('koneksi.php');

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, nama_sekolah, tanggal_dn, jenis, status FROM your_table_name");
    $events = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Map database fields to FullCalendar event format
        $event = [
            'id' => $row['id'],
            'title' => $row['nama_sekolah'] . ' - ' . $row['jenis'],
            'start' => $row['tanggal_dn'], // Assumes date is in a format FullCalendar accepts
            'backgroundColor' => $row['status'] == 1 ? '#28a745' : '#dc3545', // Green for active, red for inactive
            'borderColor' => $row['status'] == 1 ? '#28a745' : '#dc3545'
        ];
        $events[] = $event;
    }

    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>