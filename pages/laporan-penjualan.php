<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
      header("Location: ../index.php");
    } elseif($_SESSION['role'] == 'Kasir') {
      header("Location: home.php");
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
                    <h4 class="card-title">Data Barang</h4>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                      <a class="btn btn-primary" href="cetak.php" target="_blank"> Cetak Laporan </a>
                    </div>
                    <table id="example1" class="table table-striped mb-4">
                      <thead>
                        <tr>
                          <th> No </th>
                          <th> Tanggal </th>
                          <th> Nama Barang </th>
                          <th> Harga </th>
                          <th> Jumlah </th>
                          <th> Total Harga </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                            $i = 1;
                            $query = "SELECT * FROM detail_nota d INNER JOIN tbl_barang b ON d.kode = b.kode ORDER BY tanggal ASC;";
                            $result = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($result)) {
                              // $id = $row['id'];
                          ?>
                        <tr>
                          <td> <?= $i++; ?> </td>
                          <td> <?= $row['tanggal']; ?> </td>
                          <td><?= $row['nama']; ?></td>
                          <td> <?= $row['harga']; ?> </td>
                          <td> <?= $row['jumlah']; ?> </td>
                          <td> <?= $row['subtotal']; ?> </td>
                        </tr>

                        <?php }?>
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

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- End custom js for this page -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
          $('#example1').DataTable({
            search: false
          });
      });
    </script>
  </body>
</html>