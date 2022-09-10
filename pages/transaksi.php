<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
        header("Location: ../index.php");
    }   

    if(isset($_POST['addproduk'])) {
        $kode = $_POST['kode'];
        
        $query2 = "SELECT * FROM tbl_barang WHERE kode = '$kode'";
        $result2 = mysqli_query($conn, $query2);
        $row = mysqli_fetch_assoc($result2);

        $jumlah = $_POST['qty'];
        $tgl = $_POST['tgl'];
        $id = $_SESSION['id'];
        $harjul = $row['harga_jual'];
        $qty = (int)$jumlah * (int)$harjul;

        $data = mysqli_query($conn, "SELECT nonota FROM detail_nota ORDER BY id DESC LIMIT 1");
  
        $rows = mysqli_fetch_assoc($data);
        $row = $rows['nonota'];
        $ex = explode("BRG", $row);
        $no = (int) $ex[1] + 1;
        $format = 'BRG' . $no . '';

        $query3 = "INSERT INTO nota (nonota, tgl, kode, harga, jumlah, subtotal) VALUES ('$format', '$tgl', '$kode', '$harjul', '$jumlah', '$qty')";
        $result3 = mysqli_query($conn, $query3);

        if($result3) {
            header("Location: transaksi.php");
        }
    }

    if(isset($_POST['bayar'])) {
        $nonota = $_POST['nonota'];
        $kode = $_POST['kode'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
        $total = $_POST['total1'];
        $tgl = $_POST['tgl'];
        $jml = count($nonota);

        for($i = 0; $i < $jml; $i++) {
            $data = array(
                'nonota' => $nonota[$i], 
                'kode' => $kode[$i], 
                'jumlah' => $jumlah[$i], 
                'harga' => $harga[$i], 
                'tot' => $total[$i], 
                'tgl' => $tgl[$i]
            );



            $d1 = $data['nonota'];
            $d2 = $data['tgl'];
            $d3 = $data['kode'];
            $d4 = $data['harga'];
            $d5 = $data['jumlah'];
            $d6 = $data['tot'];

            $query2 = "SELECT * FROM tbl_barang WHERE kode = '$d3'";
            $result2 = mysqli_query($conn, $query2);
            $row = mysqli_fetch_assoc($result2);
            $stok = $row['stok'];
            if($d5 <= $stok) {

              $newStok = $stok - $d5;

              $query = "INSERT INTO detail_nota (nonota, tanggal, kode, harga, jumlah, subtotal) VALUES ('$d1', '$d2', '$d3', '$d4', '$d5', '$d6')";
              $result = mysqli_query($conn, $query);
              $query2 = "UPDATE tbl_barang SET stok = '$newStok' WHERE kode = '$d3'";
              $result2 = mysqli_query($conn, $query2);
  
              if($result && $result2) {
                  $quert = "DELETE FROM nota";
                  mysqli_query($conn, $quert);
              }
            }

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
                    <h4 class="card-title">Transaksi</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                            Order
                        </button>
                    </p>
                    <table class="table table-white">
                      <thead>
                        <tr>
                          <th> No </th>
                          <th> Kode </th>
                          <th> No Nota </th>
                          <th> Nama Barang </th>
                          <th> Jumlah </th>
                          <th> Harga </th>
                          <th> Total </th>
                          <th> Tanggal </th>
                        </tr>
                      </thead>
                      <tbody>
                            <?php
                                $i = 1;
                                $query = "SELECT * FROM nota n, tbl_barang b WHERE n.kode = b.kode";
                                $result = mysqli_query($conn, $query);

                                while($row = mysqli_fetch_assoc($result)) {
                            ?>  
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row['kode']; ?></td>
                            <td><?= $row['nonota']; ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['jumlah']; ?></td>
                            <td>Rp. <?= $row['harga']; ?></td>
                            <td>Rp. <?= $row['subtotal']; ?></td>
                            <td><?= $row['tgl']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <hr>
                <form method="POST">
                    <?php
                        $total = 0; $i = 1;
                        $query = "SELECT * FROM nota";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_assoc($result)) {
                            $total += $row['subtotal'];
                        }      
                        
                    ?>

                    <?php 
                        $queryTrans = "SELECT * FROM nota n, tbl_barang b WHERE n.kode = b.kode";
                        $resultTrans = mysqli_query($conn, $queryTrans);

                        while($row = mysqli_fetch_assoc($resultTrans)) {
                    ?>

                    <input type="hidden" name="nonota[]" value="<?= $row['nonota']; ?>">
                    <input type="hidden" name="kode[]" value="<?= $row['kode']; ?>">
                    <input type="hidden" name="jumlah[]" value="<?= $row['jumlah']; ?>">
                    <input type="hidden" name="harga[]" value="<?= $row['harga']; ?>">
                    <input type="hidden" name="total1[]" value="<?= $row['subtotal']; ?>">
                    <input type="hidden" name="tgl[]" value="<?= date('Y/m/d'); ?>">
                    
                    <?php } ?>  
                    <label class="col-sm-2 col-form-label">Total Harga</label>
                    <input type="text" value="<?= 'Rp. ' . $total ?>" required readonly><br>
                    <!-- <label class="col-sm-2 col-form-label">Uang</label>
                    <input type="text" ><br> -->
                    <!-- <label class="col-sm-2 col-form-label">Kembalian</label>
                    <input type="text" ><br> -->
                    <!-- <label class="col-sm-2 col-form-label mr-3">Jumlah Uang</label>
                    <input type="text" value><br> -->

                    <button type="submit" class="btn btn-success mt-3" name="bayar">Bayar</button>
                    

                </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
            <div class="modal fade" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <table class="table table-white">
                            <thead>
                                <tr>
                                <th> ID BARANG </th>
                                <th> Nama Barang </th>
                                <th> Harga Jual </th>
                                <th> Stok </th>
                                <th>Total</th>
                                <th> Opsi </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" name="total"></td>
                                    <td><button type="submit"><i class="mdi mdi mdi-cart-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table> -->
                        <form method="POST">
                        <label class="col-sm-3 col-form-label">Pilih Barang</label>
                        <select name="kode" class="form-control">

                            <?php

                                $query = "SELECT * FROM tbl_barang";
                                $result = mysqli_query($conn, $query);

                                while($row = mysqli_fetch_assoc($result)) {
                            ?>

                            <option value="<?= $row['kode']; ?>"><?= $row['nama']; ?> - Rp. <?= $row['harga_jual']; ?></option>

                            <?php } ?>
                            
                        </select>
                

                        <label class="col-sm-3 col-form-label mt-3">Jumlah</label>
                        <input type="number" name="qty" class="form-control">
                        <label class="col-sm-3 col-form-label mt-3">Tanggal</label>
                        <input type="text" name="tgl" class="form-control" required readonly value="<?= date("Y/m/d"); ?>">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="addproduk">Submit</button>
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
    <script src="../assets/js/file-upload.js"></script>
    <!-- End custom js for this page -->
</body>
</html>