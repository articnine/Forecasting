<?php

    session_start();
    require_once "../koneksi.php";

    $id = $_GET['id'];
    if(isset($id)) {
        $query = "DELETE FROM users WHERE id = '$id'";
        $result = mysqli_query($conn, $query);

        if($result) {
            header("Location: users.php");
        }
    }

?>