<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php?menu=Dashboard" class="brand-link">
    <img src="assets/img/fukubistorelogo.png" alt="AdminLTE Logo" class="brand-image img-circle"
      style="opacity: .8; filter: invert(1);">
    <span class="brand-text font-weight-light">Fukubi Store</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">
          <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Vladimir Makarov'; ?>
          <?php if (isset($_SESSION['role'])): ?>
            <small>(<?php echo ucfirst($_SESSION['role']); ?>)</small>
          <?php endif; ?>
        </a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
        // Get current user role - default to 'stock' if not set
        $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'stock';

        // Menu visibility based on role
        $menuAccess = [
          'owner' => ['dashboard', 'stock', 'dies_natalis', 'account'],
          'marketing' => ['dashboard', 'dies_natalis', 'account'],
          'stock' => ['dashboard', 'stock', 'account']
        ];

        // Get current menu for highlighting active state
        $menu = isset($_GET['menu']) ? $_GET['menu'] : 'Dashboard';
        $submenu = isset($_GET['submenu']) ? $_GET['submenu'] : '';

        // Get accessible sections for current user
        $accessibleSections = isset($menuAccess[$userRole]) ? $menuAccess[$userRole] : [];
        ?>
        <li class="nav-item">
          <a href="index.php?menu=Dashboard" class="nav-link <?php if ($menu == "Dashboard")
                                                                echo "active"; ?>">
            <i class="fas fa-tachometer-alt nav-icon"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <?php if (in_array('stock', $accessibleSections)): ?>
          <li class="nav-header">Team Stock</li>
        <?php endif; ?>


        <?php if (in_array('stock', $accessibleSections)): ?>
          <li class="nav-item">
            <a href="index.php?menu=Barang&submenu=StockBarang" class="nav-link <?php if ($menu == "Barang")
                                                                                  echo "active"; ?>">
              <i class="nav-icon fas fa-box"></i>
              <p>Stock Barang</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if (in_array('dies_natalis', $accessibleSections)): ?>
          <li class="nav-header">Team Marketing</li>

          <li class="nav-item <?php if ($menu == "Tabel" || $menu == "Penagihan")
                                echo "menu-is-opening menu-open"; ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Data Tabel
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="index.php?menu=Tabel" class="nav-link <?php if ($menu == "Tabel" && empty($submenu))
                                                                  echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Dies Natalis</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?menu=Penagihan" class="nav-link <?php if ($menu == "Penagihan")
                                                                      echo "active"; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Penagihan</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="index.php?menu=Kalender" class="nav-link <?php if ($menu == "Kalender")
                                                                echo "active"; ?>">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Kalender Dies Natalis
                <?php
                // Show notification count if available
                if (isset($notificationCount) && $notificationCount > 0):
                ?>
                  <span class="badge badge-info right"><?php echo $notificationCount; ?></span>
                <?php endif; ?>
              </p>
            </a>
          </li>
        <?php endif; ?>

        <?php if (in_array('account', $accessibleSections)): ?>
          <li class="nav-header">Log Out Aplikasi</li>
          <li class="nav-item">
            <a href="config/proses_logout.php" class="nav-link bg-danger">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>