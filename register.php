<?php

    session_start();
    require_once 'koneksi.php';

    if(isset($_POST['register'])) {
        $username = $_POST['username'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $role = $_POST['role'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO users(username, password, name, address, role) VALUES('$username', '$password', '$name', '$address', '$role')";
        if(mysqli_query($conn, $query)) {
            echo "Registrasi Berhasil";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form method="POST">
        <label class="form-label">Username</label>
        <input class="form-control form-control-lg" type="text" name="username" placeholder="Enter your username" />
        <br>
        <label>Password</label>
        <input type="password" name="password">
        <br>
        <label for="">name</label>
        <input type="text" name="name">
        <br>
        <label for="">address</label>
        <input type="text" name="address">
        <br>
        <label for="">role</label>
        <input type="text" name="role">
        <br>
        <button type="submit" name="register">Sign Up</button>
    </form>
</body>
</html>