<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
        header("Location: ../index.php");
    } elseif($_SESSION['role'] === 'Kasir') {
      header("Location: home.php");
    }

    if(isset($_POST['simpan'])) {
        $pilihan = $_GET['produk'];
        $data = mysqli_query($conn, "SELECT COUNT(id) AS jumlah FROM laporan_bulanan");
        $count = mysqli_fetch_array($data);
        $cek = $count['jumlah'];

        if($cek == 6) {
            echo "<meta http-equiv='refresh' content='1; url=hasil_ramal.php?produk=$pilihan'/>";
        } else {
            echo "
            <div class='alert alert-success' role='alert'>
                Data Material masih kurang untuk diproses!
            </div>
            <meta http-equiv='refresh' content='1; url=forecast.php'/>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
      <?php include '../partials/_navbar.php'; ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        <?php include '../partials/_sidebar.php' ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body col-lg-9">
                        <h4 class="card-title">Data Material 6 Bulan Terakhir</h4>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th> No </th>
                          <th> Bulan </th>
                          <th> Jumlah </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                            $i = 1;
                            $query = "SELECT * FROM laporan_bulanan";
                            $result = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td> <?= $row['bulan']; ?> </td>
                          <td> <?= $row['jumlah']; ?> </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>

                    <form method="post" class="form-control">
                        <button class="btn btn-primary" name="simpan" type="submit">Proses Peramalan</button>
                    </form>

                    <table class="table table-striped"> 
                      <thead>
                        <tr>
                          <td>Tanggal</td>
                          <td>Jam</td>
                          <td>Jumlah</td>
                          <td>Nama</td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                          $id = $_GET['produk'];
                          $nmBarang = mysqli_fetch_array(mysqli_query($conn, "SELECT nama FROM tbl_barang WHERE kode = '$id'"));
                          $data = mysqli_query($conn, "SELECT * FROM laporan_bulanan");
                          date_default_timezone_set('Asia/Jakarta');

                          while($row = mysqli_fetch_assoc($data)) {

                  
                        ?>
                        <tr>
                          <td><?= date('Y/m/d') ?></td>
                          <td><?= date('H:i:s'); ?></td>
                          <td><?= $row['jumlah']; ?></td>
                          <td><?= $nmBarang[0]; ?></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>



          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
            <?php include '../partials/_footer.php' ?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../assets/js/file-upload.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>