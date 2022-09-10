<?php

    session_start();
    include '../src/SimpleXLSX.php';

    $target = basename($_FILES['file']['name']);
    $dir = move_uploaded_file($_FILES['file']['tmp_name'], $target);
    

    $xlsx = new SimpleXLSX($target);

    try {
        $conn = new PDO( "mysql:host=localhost;dbname=forecasting", "root", "");
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     }
     catch(PDOException $e)
     {
         echo $sql . "<br>" . $e->getMessage();
     }
     $stmt = $conn->prepare( "INSERT INTO tbl_barang 
     (kode, nama, harga_beli, harga_jual, stok) VALUES (?, ?, ?, ?, ?)");
     $stmt->bindParam( 1, $kode);
     $stmt->bindParam( 2, $nama);
     $stmt->bindParam( 3, $harga_beli);
     $stmt->bindParam( 4, $harga_jual);
     $stmt->bindParam( 5, $stok);

    foreach($xlsx->rows() as $keys => $rows) {

        if($keys < 1) continue;
        $kode = $rows[0];
        $nama = $rows[1];
        $harga_beli = $rows[2];
        $harga_jual = $rows[3];
        $stok = $rows[4];

        $stmt->execute();
        
    }

    header("location: materials.php");
    unlink($_FILES['file']['name']);