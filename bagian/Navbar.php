<?php
// Base Notification Class
abstract class BaseNotification
{
  protected $db;
  protected $iconPath;
  protected $debug = true;  // Set false for production
  protected $useTimer = false;  // Set true for production
  protected $checkInterval;

  public function __construct($db)
  {
    $this->db = $db;
  }

  abstract protected function fetchNotifications();
  abstract protected function getNotificationStatus($days);
  abstract protected function createNotificationMessage($notifications);
  abstract protected function getStorageKey();
  abstract protected function getNotificationTitle();

  public function getActiveNotifications()
  {
    $notifications = $this->fetchNotifications();

    if (!empty($notifications) && $this->debug) {
      error_log('Debug - Active notifications: ' . print_r($notifications, true));
    }

    if (!empty($notifications)) {
      echo $this->generateNotificationScript($notifications);
    }

    return $notifications;
  }

  public function getNotificationCount()
  {
    return count($this->getActiveNotifications());
  }

  protected function generateNotificationScript($notifications)
  {
    $message = $this->createNotificationMessage($notifications);
    $debugMode = $this->debug ? 'true' : 'false';
    $useTimer = $this->useTimer ? 'true' : 'false';
    $storageKey = $this->getStorageKey();

    return <<<SCRIPT
            <script>
                class NotificationHandler {
                    constructor(storageKey, checkInterval, debugMode, useTimer) {
                        this.storageKey = storageKey;
                        this.checkInterval = checkInterval;
                        this.debugMode = debugMode;
                        this.useTimer = useTimer;
                    }
                    
                    debugLog(message) {
                        if (this.debugMode) {
                            console.log(`Debug [\${this.storageKey}] -`, message);
                        }
                    }
                    
                    saveNotificationTime() {
                        try {
                            localStorage.setItem(this.storageKey, Date.now().toString());
                            this.debugLog('Saved notification time');
                        } catch (e) {
                            this.debugLog('Error saving to localStorage: ' + e);
                        }
                    }
                    
                    shouldShowNotification() {
                        if (!this.useTimer) return true;
                        
                        try {
                            const lastNotification = localStorage.getItem(this.storageKey);
                            if (!lastNotification) {
                                this.debugLog('No previous notification found');
                                return true;
                            }
                            
                            const timeSinceLastNotification = Date.now() - parseInt(lastNotification);
                            const shouldShow = timeSinceLastNotification >= this.checkInterval;
                            
                            this.debugLog(`Time since last: \${Math.floor(timeSinceLastNotification / 1000 / 60)} minutes`);
                            this.debugLog(`Should show: \${shouldShow}`);
                            
                            return shouldShow;
                        } catch (e) {
                            this.debugLog('Error checking notification time: ' + e);
                            return true;
                        }
                    }
                    
                    async showNotification(title, message, icon) {
                        if (!this.shouldShowNotification()) {
                            this.debugLog('Skipping notification due to time constraint');
                            return;
                        }

                        if (!('Notification' in window)) {
                            this.debugLog('Browser does not support notifications');
                            return;
                        }

                        try {
                            if (Notification.permission === 'granted') {
                                this.debugLog('Showing notification');
                                new Notification(title, { body: message, icon });
                                this.saveNotificationTime();
                            } else if (Notification.permission !== 'denied') {
                                this.debugLog('Requesting permission');
                                const permission = await Notification.requestPermission();
                                if (permission === 'granted') {
                                    new Notification(title, { body: message, icon });
                                    this.saveNotificationTime();
                                }
                            }
                        } catch (e) {
                            this.debugLog('Error showing notification: ' + e);
                        }
                    }
                }

                // Initialize handler
                const handler = new NotificationHandler(
                    '{$storageKey}',
                    {$this->checkInterval},
                    {$debugMode},
                    {$useTimer}
                );

                // Initial check
                handler.showNotification(
                    '{$this->getNotificationTitle()}',
                    `{$message}`,
                    '{$this->iconPath}'
                );

                // Set up timer if enabled
                if ({$useTimer}) {
                    handler.debugLog('Setting up timer');
                    setInterval(() => {
                        handler.showNotification(
                            '{$this->getNotificationTitle()}',
                            `{$message}`,
                            '{$this->iconPath}'
                        );
                    }, {$this->checkInterval});
                }
            </script>
        SCRIPT;
  }
}

// Billing Notification Implementation
class BillingNotification extends BaseNotification
{
  protected $iconPath = 'assets/img/billing-icon.png';
  protected $checkInterval = 60 * 60 * 1000; // 1 hour

  protected function getStorageKey()
  {
    return 'lastBillingNotification';
  }

  protected function getNotificationTitle()
  {
    return 'Pengingat Penagihan';
  }

  protected function fetchNotifications()
  {
    $notifications = [];
    $current_date = new DateTime();

    $query = "SELECT 
                p.*,
                COALESCE(dp1_nominal, 0) + COALESCE(dp2_nominal, 0) + COALESCE(dp3_nominal, 0) as total_dibayar
            FROM penagihan p 
            WHERE status IN ('1', '2') 
            AND (
                (dp1_tenggat IS NOT NULL AND dp1_status = '0') OR
                (dp2_tenggat IS NOT NULL AND dp2_status = '0') OR
                (dp3_tenggat IS NOT NULL AND dp3_status = '0') OR
                (pelunasan > 0 AND tgllunas IS NULL)
            )";

    try {
      $result = $this->db->query($query);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $deadlines = $this->calculateDeadlines($row);

          foreach ($deadlines as $deadline) {
            $deadline_date = new DateTime($deadline['date']);
            $interval = $current_date->diff($deadline_date);
            $days_until = $interval->days;

            if ($days_until <= 7 && $deadline_date > $current_date) {
              $notifications[] = [
                'id' => $row['id'],
                'customer' => $row['customer'],
                'jenis' => $deadline['type'],
                'nominal' => $deadline['amount'],
                'tanggal' => $deadline_date->format('d-m-Y'),
                'sisa_hari' => $days_until,
                'status' => $this->getNotificationStatus($days_until)
              ];
            }
          }
        }
      }
    } catch (Exception $e) {
      if ($this->debug) {
        error_log("Error fetching billing notifications: " . $e->getMessage());
      }
    }

    return $notifications;
  }

  private function calculateDeadlines($row)
  {
    $deadlines = [];
    $total_pembayaran = $row['total'];
    $sisa = $total_pembayaran - $row['total_dibayar'];

    if ($row['dp1_tenggat'] && $row['dp1_status'] == '0') {
      $deadlines[] = [
        'date' => $row['dp1_tenggat'],
        'type' => 'DP Pertama',
        'amount' => $row['dp1_nominal']
      ];
    }

    if ($row['dp2_tenggat'] && $row['dp2_status'] == '0') {
      $deadlines[] = [
        'date' => $row['dp2_tenggat'],
        'type' => 'DP Kedua',
        'amount' => $row['dp2_nominal']
      ];
    }

    if ($row['dp3_tenggat'] && $row['dp3_status'] == '0') {
      $deadlines[] = [
        'date' => $row['dp3_tenggat'],
        'type' => 'DP Ketiga',
        'amount' => $row['dp3_nominal']
      ];
    }

    if ($sisa > 0 && isset($row['tgllunas'])) {
      $deadlines[] = [
        'date' => $row['tgllunas'],
        'type' => 'Pelunasan',
        'amount' => $sisa
      ];
    }

    return $deadlines;
  }

  protected function getNotificationStatus($days)
  {
    if ($days <= 2)
      return 'urgent';
    if ($days <= 5)
      return 'warning';
    return 'info';
  }

  protected function createNotificationMessage($notifications)
  {
    $count = count($notifications);
    $urgentNotifications = array_filter($notifications, function ($n) {
      return $n['status'] === 'urgent';
    });

    if (!empty($urgentNotifications)) {
      $urgent = array_shift($urgentNotifications);
      return "SEGERA: Pembayaran {$urgent['jenis']} untuk {$urgent['customer']} sebesar Rp " .
        number_format($urgent['nominal'], 0, ',', '.') .
        " jatuh tempo dalam {$urgent['sisa_hari']} hari!";
    }

    return "Terdapat {$count} pembayaran yang akan jatuh tempo dalam waktu dekat. Silakan cek daftar penagihan.";
  }
}

// Dies Natalis Notification Implementation
class DiesNatalisNotification extends BaseNotification
{
  protected $iconPath = 'assets/img/date-of-birth.png';
  protected $checkInterval = 30 * 60 * 1000; // 30 minutes

  protected function getStorageKey()
  {
    return 'lastDNNotification';
  }

  protected function getNotificationTitle()
  {
    return 'Pengingat Dies Natalis';
  }

  protected function fetchNotifications()
  {
    $notifications = [];
    $current_date = new DateTime();
    $current_year = (int) $current_date->format('Y');

    $query = "SELECT id, nama_sekolah, tanggal_dn FROM datadn WHERE tanggal_dn IS NOT NULL";

    try {
      $result = $this->db->query($query);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $dn_date = $row['tanggal_dn'];
          $dn_with_year = DateTime::createFromFormat('d-m-Y', $dn_date . '-' . $current_year);

          if (!$dn_with_year) {
            if ($this->debug) {
              error_log("Debug - Failed parsing date: " . $dn_date);
            }
            continue;
          }

          if ($dn_with_year < $current_date) {
            $dn_with_year = DateTime::createFromFormat('d-m-Y', $dn_date . '-' . ($current_year + 1));
          }

          $interval = $current_date->diff($dn_with_year);
          $days_until = $interval->days;

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
      }
    } catch (Exception $e) {
      if ($this->debug) {
        error_log("Error fetching DN notifications: " . $e->getMessage());
      }
    }

    return $notifications;
  }

  protected function getNotificationStatus($days)
  {
    if ($days <= 7)
      return 'urgent';
    if ($days <= 14)
      return 'warning';
    return 'info';
  }

  protected function createNotificationMessage($notifications)
  {
    $count = count($notifications);

    if ($count === 1) {
      $notification = $notifications[0];
      return "Dies Natalis {$notification['nama_sekolah']} akan berlangsung dalam {$notification['sisa_hari']} hari pada tanggal {$notification['tanggal_dn']}.";
    }

    return "Terdapat {$count} Dies Natalis yang akan berlangsung dalam waktu dekat. Silakan cek daftar Dies Natalis.";
  }
}

// Usage
try {
  // Initialize notifications
  $billingNotification = new BillingNotification($db);
  $dnNotification = new DiesNatalisNotification($db);

  // Get notifications
  $billingNotifications = $billingNotification->getActiveNotifications();
  $dnNotifications = $dnNotification->getActiveNotifications();

  // Get counts
  $billingCount = $billingNotification->getNotificationCount();
  $dnCount = $dnNotification->getNotificationCount();

} catch (Exception $e) {
  if ($billingNotification->debug) {
    error_log("Error in notification system: " . $e->getMessage());
  }
}
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