<?php

    session_start();
    require_once 'koneksi.php';

    if(isset($_SESSION['login'])) {
        header('Location: pages/home.php');
    }

    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if(password_verify($password, $row['password'])) {
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['address'] = $row['address'];
                    $_SESSION['role'] = $row['role'];

                    if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Petugas'|| $_SESSION['role'] == 'Kasir') {
                        header("Location:pages/home.php");
                    }

                } else {
                    echo "<script>alert('Password salah!)</script>";
                }
            }
        } else {
            echo "User tidak ada!";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <style>
      /* .box {
        width: 100px;
        height: 100px;
        margin: 10px auto;
        background-image: url("assets/images/938.jpg");
        background-position: center;
        background-size: 100px 100px;
      } */
      body {
        background-image: url("assets/images/938.jpg");
        background-position: center;
        background-size: auto;
      }
    </style>
  </head>
  <body>
    <div class="box">
    </div>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <h3 style="color: #D84DEB;">TB. Jaya Agung</h3>
                </div>
                
                <h4>Login</h4>
                <h6 class="font-weight-light">Hallo! Silahkan Masuk.</h6>
                <form class="pt-3" method="POST" >
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" name="username" placeholder="Username">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="password" placeholder="Password">
                  </div>
                  <div class="mt-3">
                    <button type="submit" name="login" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>