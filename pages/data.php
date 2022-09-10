<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
        header("Location: ../index.php");
    }   

    $totalBulanan = mysqli_query($conn, "SELECT SUM(jumlah) AS total, DATE_FORMAT(tanggal, '%M') AS bulan FROM detail_nota GROUP BY YEAR(tanggal), MONTH(tanggal)");

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
      <div class="container-fl<?php

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
        <div class="page-header">
            <h3 class="page-title"> Dataset </h3>
        </div>
        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Hasil dataset setiap bulan</h4>
                <table id="example1" class="table table-striped mb-4">
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
                        $query = "SELECT jumlah, DATE_FORMAT(tanggal, '%M %Y') as bulan FROM detail_nota ORDER BY tanggal";
                        $result = mysqli_query($conn, $query);
                        $row = mysqli_fetch_assoc($result);
                        while($row = mysqli_fetch_assoc($result)) {
                        
                          // $id = $row['id'];
                      ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $row['bulan']; ?></td>
                        <td><?= $row['jumlah']; ?></td>
                    </tr>

                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-lg grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Total Dataset Setiap Bulan</h4>
                        <canvas id="barChart" style="height:230px"></canvas>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal fade" id="proses">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="forms-sample" method="POST">
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Kode</label>
                        <div class="col-sm-9">
                        <input type="text" required name="kode" class="form-control" id="exampleInputUsername2" placeholder="Kode Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nama Barang</label>
                      <div class="col-sm-9">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Barang">
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Harga Beli</label>
                        <div class="col-sm-9">
                        <input type="number" name="harga_beli" class="form-control" placeholder="Harga Beli">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Harga Jual</label>
                        <div class="col-sm-9">
                        <input type="number" name="harga_jual" class="form-control" placeholder="Harga Jual">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Stok</label>
                        <div class="col-sm-9">
                        <input type="number" name="stok" class="form-control" placeholder="Stok">
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Tanggal Beli</label>
                        <div class="col-sm-9">
                        <input type="text" name="tgl" class="form-control" required readonly value="<?= date('j F Y'); ?>">
                        </div>
                    </div> -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" name="tambah">Submit</button>
                    </div>
                  </form>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

  const labels = [
    'Oktober',
    'November',
    'Desember',
    'Januari',
    'Februari',
    'Maret',
  ];

  const data = {
    labels: labels,
    datasets: [{
        label: 'Jumlah Dataset',
        backgroundColor: [
            'rgb(255, 99,132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)'
        ],
        borderColor: 'rgb(255, 99, 132)',
        data: [
            <?php
                while($row = mysqli_fetch_assoc($totalBulanan)) {
            ?>
                <?= $row['total']; ?>,
            
            <?php } ?>

        ],
    }],
  };

  const config = {
    type: 'bar',
    data: data,
    options: {}
  };

  const myChart = new Chart(
    document.getElementById('barChart'),
    config
  )
</script>
</body>
</html>