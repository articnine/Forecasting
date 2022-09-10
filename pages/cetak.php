<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>TB. JAYA AGUNG</title>
  </head>
  <body>
    <div class="container mt-5">
        <h2 class="text-center">TB. JAYA AGUNG</h2>
        <p class="text-center">Jalan KRT Doktor Radjiman Wijodiningrat Rt. 009/Rw. 007 Kelurahan Jatinegara,</p> 
        <p class="text-center">Kecamatan Cakung, Jatinegara, Jakarta Timur, DKI Jakarta, Daerah Khusus Ibukota Jakarta 13930.</p>

        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
                <?php
                require_once '../koneksi.php';

                    $i = 1;
                    $query = "SELECT * FROM detail_nota b INNER JOIN tbl_barang d ON b.kode = d.kode ORDER BY b.tanggal ASC";
                    $result = mysqli_query($conn, $query);

                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr class="text-center">
                    <td><?= $i++; ?></td>
                    <td><?= $row['tanggal']; ?></td> 
                    <td><?= $row['nama']; ?></td>       
                    <td><?= $row['jumlah']; ?></td>       
                    <td><?= $row['subtotal']; ?></td>       
                </tr>
                <?php } ?>
            </thead>
        </table>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        window.print();
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>


