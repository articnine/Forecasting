<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2"><?= $_SESSION['name']; ?></span>
          <span class="text-secondary text-small"><?= $_SESSION['role']; ?></span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="home.php">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    <?php
      if($_SESSION['role'] === 'Admin') {
    ?>
    <li class="nav-item">
      <a class="nav-link" href="users.php">
        <span class="menu-title">Akun</span>
        <i class="mdi mdi-account-circle menu-icon"></i>
      </a>
    </li>
    <?php } ?>
    <li class="nav-item">
      <a class="nav-link" href="materials.php">
        <span class="menu-title">Master Barang</span>
        <i class="mdi mdi-folder-multiple menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="transaksi.php">
        <span class="menu-title">Transaksi</span>
        <i class="mdi mdi-cart menu-icon"></i>
      </a>
    </li>
    <?php
      if($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Petugas') {
    ?>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">Laporan</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-laptop-mac menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="laporan-penjualan.php">Laporan Penjualan</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="data.php">
        <span class="menu-title">Data Set</span>
        <i class="mdi mdi-archive menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="forecast.php">
        <span class="menu-title">Peramalan</span>
        <i class="mdi mdi-key menu-icon"></i>
      </a>
    </li>
  </ul>
  <?php } ?>
</nav>