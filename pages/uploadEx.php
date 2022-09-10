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

     $stmt = $conn->prepare("INSERT INTO detail_nota(nonota, tanggal, kode, harga, jumlah, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
     $stmt->bindParam( 1, $nonota);
     $stmt->bindParam( 2, $tgl);
     $stmt->bindParam( 3, $kode);
     $stmt->bindParam( 4, $harga);
     $stmt->bindParam( 5, $jumlah);
     $stmt->bindParam( 6, $subtotal);

    foreach($xlsx->rows() as $keys => $rows) {

        if($keys < 1) continue;

        $nonota = $rows[0];
        $tanggal = $rows[1];
        $kode = $rows[2];
        $harga = $rows[3];
        $jumlah = $rows[4];
        $subtotal = $rows[5];

        
        $tgl = rtrim($tanggal, ' 00:00:00');
        
        $stmt->execute();

    }

    header("location: materials.php");
    unlink($_FILES['file']['name']);
