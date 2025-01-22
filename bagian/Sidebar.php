<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php?menu=Dashboard" class="brand-link">
    <img src="assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Fukubi</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Vladimir Makarov</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               
        <li class="nav-header">Database</li>
        <li class="nav-item">
          <a href="index.php?menu=Dashboard" class="nav-link <?php if ($menu == "Dashboard") {
                                                                echo "active";
                                                              } ?>">
            <i class="fas fa-tachometer-alt nav-icon"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item <?php if ($menu == "Create") {
                              echo "menu-is-opening menu-open";
                            } ?>">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
            <p>
              Tambah Data
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="index.php?menu=Create&submenu=ContactAdd" class="nav-link <?php if ($submenu == "ContactAdd") {
                                                                echo "active";
                                                              } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Dies Natalis</p>
              </a>
            </li>
            <li class="nav-item">
            <a href="index.php?menu=Create&submenu=Sekolah" class="nav-link <?php if ($submenu == "Sekolah") {
                                                                echo "active";
                                                              } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Sekolah</p>
              </a>
            </li>
            <li class="nav-item">
            <a href="index.php?menu=Create&submenu=Penagihan" class="nav-link <?php if ($submenu == "Penagihan") {
                                                                echo "active";
                                                              } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Penagihan</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item <?php if ($menu == "Tabel" || $menu == "Penagihan") {
                              echo "menu-is-opening menu-open";
                            } ?>">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>
              Data Tabel
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="index.php?menu=Tabel" class="nav-link <?php if ($menu == "Tabel") {
                                                                echo "active";
                                                              } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Dies Natalis</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?menu=Penagihan" class="nav-link <?php if ($menu == "Penagihan") {
                                                                    echo "active";
                                                                  } ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Penagihan</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">Kalender</li>
        <li class="nav-item">
          <a href="index.php?menu=Kalender" class="nav-link <?php if ($menu == "Kalender") {
                                                              echo "active";
                                                            } ?>">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Calendar
                                                          <!-- Tambahkan Nanti Setelah Fitur Ini Bekerja -->
              <!-- <span class="badge badge-info right">2</span> -->
            </p>
          </a>
        </li><li class="nav-header">Pengaturan Akun</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Edit Akun
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link bg-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              Logout
            </p>
          </a>
        </li>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>