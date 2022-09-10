<?php 

    $hostname = "localhost";
    $database = "forecasting";
    $username = "root";
    $password = "";

    $conn = mysqli_connect($hostname, $username, $password, $database);

    if(!$conn) {
        die("<script>alert('Connection Failed.')</script>");
    }
