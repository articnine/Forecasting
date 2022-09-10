<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
        header("Location: ../index.php");
    } elseif($_SESSION['role'] === 'Kasir') {
      header("Location: home.php");
    }

    if(isset($_POST['simpan'])) {
      $pilihan = $_POST['selected'];
      $hapus_data = mysqli_query($conn, "DELETE FROM laporan_bulanan");
      $reset = mysqli_query($conn, "ALTER TABLE laporan_bulanan AUTO_INCREMENT=0");

      if($hapus_data && $reset) {
        $tambahlaporan = mysqli_query($conn, "INSERT INTO laporan_bulanan (bulan, jumlah) SELECT DATE_FORMAT(tanggal, '%M') AS bulan, SUM(jumlah) FROM detail_nota WHERE kode = '$pilihan' GROUP BY YEAR(tanggal), MONTH(tanggal) ORDER BY YEAR(tanggal), MONTH(tanggal)");

        echo "<meta http-equiv='refresh' content='1; url=hitung.php?produk=$pilihan'/>";
      } 
      else {
            echo "<meta http-equiv='refresh' content='1; url=forecast.php'/>"; 
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
                  <div class="card-body">
                    <h4 class="card-title">Pilih Data Barang Ramalan</h4>
                    <form class="forms-sample" method="POST">
                      <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Barang</label>
                        <div class="col-lg">
                          <select id="inputState" name="selected" class="form-select">
                              <option selected>Pilih Barang</option>
                              <?php
                                $query = "SELECT * FROM tbl_barang";
                                
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_array($queryBulan);
                                
                                
                                while($data = mysqli_fetch_array($result)) {
                                  echo "<option value='$data[kode]'>$data[nama]</option>";
                                }
                              ?>
                          </select>
                        </div>
                      </div>
                      <button class="btn btn-primary" name="simpan" type="submit">Proses Peramalan</button>
                    </form>
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