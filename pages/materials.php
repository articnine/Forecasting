<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
        header("Location: ../index.php");
    }

    $halaman = 10;
    $page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    $mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;

  //   function barang_id() {
  //     global $conn;
  //     $query = "SELECT * FROM materials ORDER BY id DESC";
  //     $result = mysqli_query($conn, $query);

  //     $row = mysqli_fetch_assoc($result);

  //     $urut = substr($row['id_barang'], 2, 3);
  //     $tambah = (int) $urut + 1;
  //     if(strlen($tambah) == 1){
  //         $format = 'BR00'.$tambah.'';
  //     }else if(strlen($tambah) == 2){
  //         $format = 'BR0'.$tambah.'';
  //     }else{
  //        $ex = explode('BR',$row['id_barang']);
  //        $no = (int) $ex[1] + 1;
  //        $format = 'BR'.$no.'';
  //         }
  //    return $format;
  // }

  if(isset($_POST['delete'])) {
    $query = "DELETE FROM tbl_barang";
    $result = mysqli_query($conn, $query);

    if($result) {
      header("location: materials.php");
    }
  }

  if(isset($_POST['tambah'])) {
      $kode = $_POST['kode'];
      $nama = $_POST['nama'];
      $harga_beli = $_POST['harga_beli'];
      $harga_jual = $_POST['harga_jual'];
      $stok = $_POST['stok'];

      $query = "INSERT INTO tbl_barang (kode, nama, harga_beli, harga_jual, stok) VALUES ('$kode', '$nama', '$harga_beli', '$harga_jual', '$stok')";

      $result = mysqli_query($conn, $query);

      if($result) {
          header("Location: materials.php");
      }
  }

  if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];

    $query = "UPDATE tbl_barang SET kode = '$kode', nama = '$nama', harga_beli = '$harga_beli', harga_jual = '$harga_jual', stok = '$stok' WHERE id = '$id'";

    $result = mysqli_query($conn, $query);

    if($result) {
        header("Location: materials.php");
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
                    <h4 class="card-title">Data Barang</h4>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah"> Tambah </button>
                      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete">Delete</button>
                    </div>
                    <table id="example1" class="table table-striped mb-4">
                      <thead>
                        <tr>
                          <th> No </th>
                          <th> Kode </th>
                          <th> Nama Barang </th>
                          <th> Harga Beli </th>
                          <th> Harga Jual </th>
                          <th> Stok </th>
                          <th> Opsi </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                            $i = 1;
                            $query = "SELECT * FROM tbl_barang ORDER BY nama ASC;";
                            $result = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($result)) {
                              // $id = $row['id'];
                          ?>
                        <tr>
                          <td> <?= $i++; ?> </td>
                          <td> <?= $row['kode']; ?> </td>
                          <td> <?= $row['nama']; ?> </td>
                          <td> <?= $row['harga_beli']; ?> </td>
                          <td> <?= $row['harga_jual']; ?> </td>
                          <td> <?= $row['stok']; ?> </td>
                          <td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id']; ?>">Edit</button> <a class="btn btn-danger" href="delete_material.php?id=<?= $row['id']; ?>">Delete</a></td>
                        </tr>

                        <div class="modal fade" id="edit<?= $row['id']; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <form class="forms-sample" method="POST">
                                  <div class="form-group row">
                                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Kode Barang</label>
                                      <div class="col-sm-9">
                                      <input type="text" required readonly value="<?= $row['kode']; ?>" readonly name="kode" class="form-control" id="exampleInputUsername2" placeholder="Kode Barang">
                                      </div>
                                  </div>
                                  <input type="hidden" value="<?= $row['id']; ?>" name="id">
                                  <div class="form-group row">
                                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nama Barang</label>
                                      <div class="col-sm-9">
                                      <input type="text" name="nama" class="form-control" value="<?= $row['nama']; ?>" placeholder="Nama Barang">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Stok</label>
                                      <div class="col-sm-9">
                                      <input type="number" name="stok" class="form-control" value="<?= $row['stok']; ?>" placeholder="Stok">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Harga Beli</label>
                                      <div class="col-sm-9">
                                      <input type="number" name="harga_beli" value="<?= $row['harga_beli']; ?>" class="form-control" placeholder="Harga Beli">
                                      </div>
                                  </div>
                                  <div class="form-group row">
                                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Harga Jual</label>
                                      <div class="col-sm-9">
                                      <input type="number" name="harga_jual" value="<?= $row['harga_jual']; ?>" class="form-control" placeholder="Harga Jual">
                                      </div>
                                  </div>
                                  
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="update">Submit</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>

                        <?php }?>
                      </tbody>
                    </table>

                    <form action="upload_excel.php" class="forms-sample" method="POST" enctype="multipart/form-data">
                      <div class="form-group-row">
                        <div class="col-lg-6 mt-4">
                          <input type="file" name="file" class="form-control">
                          <button type="submit" name="upload" class="btn btn-success mt-2">Upload</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="tambah">
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
          <div class="modal fade" id="delete">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Semua Data Materials</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p>Apakah anda yakin?</p>
                  <div class="modal-footer justify-content-center">
                    <form class="forms-sample" method="POST">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                      <button type="submit" class="btn btn-primary" name="delete">Ya</button>
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