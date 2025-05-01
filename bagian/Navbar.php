<?php

class DiesNatalisNotification
{
  private $db;
  private $iconPath = 'assets/img/date-of-birth.png';
  private $debug = true; // Set false for production
  private $useTimer = false; // Set true for production

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getActiveNotifications()
  {
    $notifications = [];
    $current_date = new DateTime();
    $current_year = (int) $current_date->format('Y');

    // Query untuk mengambil semua tanggal DN - sekarang tanggal_dn adalah DATE
    $query = "SELECT id, nama_sekolah, tanggal_dn FROM datadn WHERE tanggal_dn IS NOT NULL";
    $result = $this->db->query($query);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Tanggal dari database sekarang dalam format YYYY-MM-DD
        $db_date = $row['tanggal_dn'];

        // Buat objek DateTime dari tanggal database
        $dn_date_obj = new DateTime($db_date);

        // Ekstrak hanya tanggal dan bulan (DD-MM format) untuk notifikasi
        $dn_date = $dn_date_obj->format('d-m');

        // Buat tanggal dengan tahun saat ini untuk perbandingan
        $dn_with_year = DateTime::createFromFormat('d-m-Y', $dn_date . '-' . $current_year);

        // Cek jika gagal parse tanggal
        if (!$dn_with_year) {
          if ($this->debug)
            error_log("Debug - Gagal parsing tanggal: " . $dn_date);
          continue;
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
        }
      }
    } else {
      if ($this->debug)
        error_log('Debug - Query error atau tidak ada data!');
    }

    if (!empty($notifications) && $this->debug) {
      error_log('Debug - Notifikasi aktif: ' . print_r($notifications, true));
    }

    // Generate notification script jika ada notifikasi aktif
    if (!empty($notifications)) {
      echo $this->generateNotificationScript($notifications);
    }

    return $notifications;
  }

  private function getNotificationStatus($days)
  {
    if ($days <= 7) {
      return 'urgent';
    } elseif ($days <= 14) {
      return 'warning';
    } else {
      return 'info';
    }
  }

  public function getNotificationCount()
  {
    return count($this->getActiveNotifications());
  }

  private function generateNotificationScript($notifications)
  {
    $notificationCount = count($notifications);
    $message = $this->createNotificationMessage($notifications);
    $debugMode = $this->debug ? 'true' : 'false';
    $useTimer = $this->useTimer ? 'true' : 'false';

    return <<<SCRIPT
            <script>
                const debugMode = {$debugMode};
                const useTimer = {$useTimer};
                
                function debugLog(message) {
                    if (debugMode) {
                        console.log('Debug -', message);
                    }
                }

                function saveLastNotificationTime() {
                    localStorage.setItem('lastDNNotification', Date.now());
                    debugLog('Saved notification time');
                }

                function shouldShowNotification() {
                    if (!useTimer) return true;
                    
                    const lastNotification = localStorage.getItem('lastDNNotification');
                    if (!lastNotification) {
                        debugLog('No previous notification found');
                        return true;
                    }
                    
                    const timeSinceLastNotification = Date.now() - parseInt(lastNotification);
                    const thirtyMinutes = 30 * 60 * 1000;
                    
                    const shouldShow = timeSinceLastNotification >= thirtyMinutes;
                    debugLog(`Time since last notification: \${Math.floor(timeSinceLastNotification / 1000 / 60)} minutes`);
                    debugLog(`Should show notification: \${shouldShow}`);
                    
                    return shouldShow;
                }

                function showNotification(title, message) {
                    if (!shouldShowNotification()) {
                        debugLog('Skipping notification due to time constraint');
                        return;
                    }

                    if (Notification.permission === 'granted') {
                        debugLog('Showing notification with granted permission');
                        new Notification(title, {
                            body: message,
                            icon: '{$this->iconPath}'
                        });
                        saveLastNotificationTime();
                    } else if (Notification.permission !== 'denied') {
                        debugLog('Requesting notification permission');
                        Notification.requestPermission().then(function (permission) {
                            if (permission === 'granted') {
                                debugLog('Permission granted, showing notification');
                                new Notification(title, {
                                    body: message,
                                    icon: '{$this->iconPath}'
                                });
                                saveLastNotificationTime();
                            } else {
                                debugLog('Permission denied');
                            }
                        });
                    } else {
                        debugLog('Notifications are denied by user');
                    }
                }

                function checkAndNotify() {
                    debugLog('Checking notifications');
                    const title = 'Pengingat Dies Natalis';
                    const message = `{$message}`;
                    
                    if ('Notification' in window) {
                        debugLog('Browser supports notifications');
                        showNotification(title, message);
                    } else {
                        debugLog('Browser does not support notifications');
                    }
                }

                // Initial notification check
                debugLog('Initial notification check');
                checkAndNotify();

                // Set up timer if enabled
                if (useTimer) {
                    debugLog('Setting up 30-minute timer');
                    setInterval(checkAndNotify, 30 * 60 * 1000);
                }
            </script>
        SCRIPT;
  }

  private function createNotificationMessage($notifications)
  {
    $count = count($notifications);

    if ($count === 1) {
      $notification = $notifications[0];
      return "Dies Natalis {$notification['nama_sekolah']} akan berlangsung dalam {$notification['sisa_hari']} hari pada tanggal {$notification['tanggal_dn']}.";
    } else {
      return "Terdapat {$count} Dies Natalis yang akan berlangsung dalam waktu dekat. Silakan cek daftar Dies Natalis.";
    }
  }
}

// Contoh penggunaan
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
              <?php echo $notif['nama_sekolah']; ?>
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