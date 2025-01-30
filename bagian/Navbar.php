<?php
class DiesNatalisNotification
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getActiveNotifications()
  {
    $notifications = [];
    $current_date = new DateTime();
    $current_year = (int) $current_date->format('Y');

    // Query untuk mengambil semua tanggal DN
    $query = "SELECT id, nama_sekolah, tanggal_dn FROM datadn WHERE tanggal_dn IS NOT NULL";
    $result = $this->db->query($query);

    $showNotificationScript = false;

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Format tanggal dari database (DD-MM)
        $dn_date = $row['tanggal_dn'];

        // Buat objek DateTime untuk tanggal DN dengan tahun sekarang
        $dn_with_year = DateTime::createFromFormat('d-m-Y', $dn_date . '-' . $current_year);

        // Cek jika gagal parse tanggal
        if (!$dn_with_year) {
          error_log("Gagal memparsing tanggal: " . $dn_date);
          continue; // Lewati iterasi ini jika parsing gagal
        }

        // Jika tanggal sudah lewat, gunakan tahun depan
        if ($dn_with_year < $current_date) {
          $dn_with_year = DateTime::createFromFormat('d-m-Y', $dn_date . '-' . ($current_year + 1));
        }

        // Hitung selisih hari
        $interval = $current_date->diff($dn_with_year);
        $days_until = $interval->days;

        // Jika dalam rentang 30 hari dan belum lewat
        if ($days_until <= 30 && $dn_with_year > $current_date) {
          $notifications[] = [
            'id' => $row['id'],
            'nama_sekolah' => $row['nama_sekolah'],
            'tanggal_dn' => $dn_with_year->format('d-m-Y'),
            'sisa_hari' => $days_until,
            'status' => $this->getNotificationStatus($days_until)
          ];

          // Aktifkan flag notifikasi jika ada setidaknya satu Dies Natalis
          $showNotificationScript = true;
        }
      }
    } else {
      error_log('Query error!');
    }

    // Tampilkan script notifikasi hanya sekali jika ada data
    if ($showNotificationScript) {
      echo $this->generateNotificationScript();
    }

    return $notifications;
  }

  private function getNotificationStatus($days)
  {
    if ($days <= 7) {
      return 'urgent'; // Merah
    } elseif ($days <= 14) {
      return 'warning'; // Kuning
    } else {
      return 'info'; // Biru
    }
  }

  public function getNotificationCount()
  {
    return count($this->getActiveNotifications());
  }

  private function generateNotificationScript()
  {
    return <<<SCRIPT
      <script>
        function showNotification(title, message) {
          if (Notification.permission === 'granted') {
            new Notification(title, { body: message });
          } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(function (permission) {
              if (permission === 'granted') {
                new Notification(title, { body: message });
              }
            });
          }
        }

        function checkAndNotify() {
          title = 'Penting';
          message = 'Ada Dies Natalis yang sudah dekat!';
          if ('Notification' in window) {
            showNotification(title, message);
          }
        }

        // Jalankan notifikasi pertama kali
        checkAndNotify();

        // Jalankan notifikasi setiap 30 menit
        setInterval(checkAndNotify, 30 * 60 * 1000);
      </script>
    SCRIPT;
  }
}

// Contoh penggunaan di halaman
$dnNotification = new DiesNatalisNotification($db);
$notifications = $dnNotification->getActiveNotifications();
$notificationCount = $dnNotification->getNotificationCount();
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge"><?php echo $notificationCount; ?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header"><?php echo $notificationCount; ?> Dies Natalis Sudah Dekat</span>

        <?php foreach ($notifications as $notif): ?>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-calendar mr-2"></i>
            <?php
            $statusClass = match ($notif['status']) {
              'urgent' => 'text-danger',
              'warning' => 'text-warning',
              'info' => 'text-info'
            };
            ?>
            <span class="<?php echo $statusClass; ?>">
              <?php echo htmlspecialchars($notif['nama_sekolah']); ?>
            </span>
            <br>
            <small class="text-muted">
              H-<?php echo $notif['sisa_hari']; ?> (<?php echo $notif['tanggal_dn']; ?>)
            </small>
          </a>
        <?php endforeach; ?>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
      </div>
    </li>
  </ul>
  <script>
    // Fungsi untuk mengecek notifikasi sistem
    function checkSystemNotifications() {
      if (!("Notification" in window)) {
        console.log("Browser tidak mendukung notifikasi sistem");
        return;
      }

      if (Notification.permission !== "granted") {
        Notification.requestPermission();
      }
    }

    // Fungsi untuk memperbarui notifikasi
    function updateDNNotifications() {
      fetch('?check_dn_notifications=1')
        .then(response => response.json())
        .then(data => {
          // Update badge
          document.querySelector('.navbar-badge').textContent = data.count;

          // Kirim notifikasi sistem untuk yang urgent (H-7)
          if (Notification.permission === "granted") {
            data.notifications.forEach(notif => {
              if (notif.sisa_hari <= 30) {
                new Notification(`Pengingat Dies Natalis ${notif.nama_sekolah}`, {
                  body: `Dies Natalis akan berlangsung dalam ${notif.sisa_hari} hari pada tanggal ${notif.tanggal_dn}`,
                  icon: "../assets/img/date-of-birth.png" // Sesuaikan path
                });
              }
            });
          }
        });
    }

    // Inisialisasi
    document.addEventListener('DOMContentLoaded', function () {
      checkSystemNotifications();
      updateDNNotifications();

      // Update setiap 5 menit
      setInterval(updateDNNotifications, 5 * 60 * 1000);
    });
  </script>
</nav>
<!-- /.navbar -->