<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
        header("Location: ../index.php");
    } elseif($_SESSION['role'] === 'Kasir' || $_SESSION['role'] === 'Petugas') {
      header("location: home.php");
    }

    if(isset($_POST['tambah'])) {
      $username = $_POST['username'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $name = $_POST['name'];
      $address = $_POST['address'];
      $role = $_POST['role'];

      $query = "INSERT INTO users (username, password, name, address, role) VALUES ('$username', '$password', '$name', '$address', '$role')";

      $result = mysqli_query($conn, $query);

      if($result) {
          header("Location: users.php");
      }
  }

  if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    $query = "UPDATE users SET username = '$username', password = '$password', name = '$name', address = '$address', role = '$role' WHERE id = '$id'";

    $result = mysqli_query($conn, $query);

    if($result) {
        header("Location: users.php");
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
      <?php include '../partials/_navbar.php' ?>
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
                    <h4 class="card-title">Akun</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah"> Tambah </button>
                    
                    <table class="table table-white">
                      <thead>
                        <tr>
                          <th> No </th>
                          <th> Username </th>
                          <th> Nama </th>
                          <th> Alamat </th>
                          <th> Role </th>
                          <th> Opsi </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                            $i = 1;
                            $query = "SELECT * FROM users";
                            $result = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_assoc($result)) {
                          ?>
                        <tr>
                          <td> <?= $i++; ?> </td>
                          <td> <?= $row['username']; ?> </td>
                          <td> <?= $row['name']; ?> </td>
                          <td> <?= $row['address']; ?> </td>
                          <td> <?= $row['role']; ?> </td>
                          <td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id']; ?>">Edit</button> <a class="btn btn-danger" href="delete_user.php?id=<?= $row['id']; ?>">Delete</a></td>
                        </tr>

                        <div class="modal fade" id="edit<?= $row['id']; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <form class="forms-sample" method="POST">
                                  <input type="hidden" value="<?= $row['id']; ?>" name="id">
                                <div class="form-group row">
                                  <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Username</label>
                                  <div class="col-sm-9">
                                      <input type="text" name="username" class="form-control" id="exampleInputUsername2" placeholder="Username" value="<?= $row['username'] ?>">
                                  </div>
                                </div>
                                <div class="form-group row">
                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Password</label>
                                    <div class="col-sm-9">
                                    <input type="password" name="password" class="form-control" id="exampleInputUsername2" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" id="exampleInputUsername2" value="<?= $row['name']; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-9">
                                    <input type="text" name="address" class="form-control" id="exampleInputUsername2" value="<?= $row['address'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Role</label>
                                    <div class="col-lg">
                                    <select id="inputState" name="role" class="form-select">
                                        <option selected>Pilih Role</option>
                                        <option>Admin</option>
                                        <option>Petugas</option>
                                        <option>Owner</option>
                                        <option>Kasir</option>
                                    </select>
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
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="tambah">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="forms-sample" method="POST">
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                        <input type="text" name="username" class="form-control" id="exampleInputUsername2" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                        <input type="password" name="password" class="form-control" id="exampleInputUsername2" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" id="exampleInputUsername2" placeholder="Nama">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                        <input type="text" name="address" class="form-control" id="exampleInputUsername2" placeholder="Alamat">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Role</label>
                        <div class="col-lg">
                        <select id="inputState" name="role" class="form-select">
                            <option selected>Pilih Role</option>
                            <option>Admin</option>
                            <option>Petugas</option>
                            <option>Kasir</option>
                            <option>Owner</option>
                        </select>
                        </div>
                    </div>
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
    <script src="../assets/js/file-upload.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>