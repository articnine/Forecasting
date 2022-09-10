<?php

    session_start();
    require_once "../koneksi.php";

    if(!$_SESSION['login']) {
        header("Location: ../index.php");
    } elseif($_SESSION['role'] === 'Kasir') {
      header("Location: home.php");
    } 

    $id = $_GET['produk'];
    $nmBarang = mysqli_fetch_array(mysqli_query($conn, "SELECT nama FROM tbl_barang WHERE kode = '$id'"));

    $jumlah_october  = mysqli_fetch_array(mysqli_query($conn, "select jumlah FROM laporan_bulanan WHERE bulan='October'"));
    $jumlah_november  = mysqli_fetch_array(mysqli_query($conn, "select jumlah FROM laporan_bulanan WHERE bulan='November'"));
    $jumlah_desember = mysqli_fetch_array(mysqli_query($conn, "select jumlah FROM laporan_bulanan WHERE bulan='December'"));
    $jumlah_januari = mysqli_fetch_array(mysqli_query($conn, "select jumlah FROM laporan_bulanan WHERE bulan='January'"));
    $jumlah_februari  = mysqli_fetch_array(mysqli_query($conn, "select jumlah FROM laporan_bulanan WHERE bulan='February'"));
    $jumlah_maret  = mysqli_fetch_array(mysqli_query($conn, "select jumlah FROM laporan_bulanan WHERE bulan='March'"));

    $october = $jumlah_october['jumlah']  ;
    $november = $jumlah_november['jumlah'] ;
    $desember = $jumlah_desember['jumlah'] ;
    $january = $jumlah_januari['jumlah'];
    $february = $jumlah_februari['jumlah'];
    $march = $jumlah_maret['jumlah'];

    $dataBulan = array('October', 'November', 'December', 'January', 'February', 'March', 'April');
    $data_jumlah = array($october, $november, $desember, $january, $february, $march);

    $ma_desember = (($october + $november ) /2);
    $ma_january = ($november + $desember ) /2;
    $ma_february = ($desember + $january ) /2;
    $ma_march = ($january + $february ) /2;
    $ma_april = ($february + $march ) /2;
    $ma_may = ($march + $ma_april) / 2;
    $ma_june = ($ma_april + $ma_may) / 2;

    $dataSma = array($ma_desember, $ma_january, $ma_february, $ma_march, $ma_april);

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
            <div class="container-fluid">
              <div class="col-lg-12 stretch-card">
                <div class="card flex-fill">
                  <div class="card-header">
                    <h3 class="card-title mb-0">Peramalan Barang <?= $nmBarang['nama']; ?></h2>
                  </div>
                  <div class="card-body">
                    <div id="curveChart" style="width: 100%; height: 500px "></div>
                  </div>
                </div>
              </div>
            <div class="row mt-4">
              <div class="col-lg-8 grid-margin stretch-card">
                <div class="card d-flex">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Hasil Peramalan</h5>
                  </div>
                  <div class="card-body">
                    <table class="table table-striped flex-fill">
                    <?php

                      $x = 1;
                      $jumlah_x = 0;
                      $jumlah_y = 0;
                      $jumlah_xx = 0;
                      $jumlah_xy = 0;
                      $no =1;
                      $query = mysqli_query($conn, "select * from laporan_bulanan");
                      while ($data = mysqli_fetch_array($query)){
                          $jumlah_x += $x ;
                          $jumlah_y += $data['jumlah'];
                          $jumlah_xx += $x * $x;
                          $jumlah_xy += $x * $data['jumlah'];
                          $x++; 
                      }
                    
                      // error MA
                      $p_desember = abs($desember - $ma_desember);
                      $p_january = abs($january - $ma_january);
                      $p_february = abs($february - $ma_february);
                      $p_march = abs($march - $ma_march);
                      
                      $mse_desember = $p_desember * $p_desember;
                      $mse_january = $p_january * $p_january;
                      $mse_february = $p_february * $p_february;
                      $mse_march = $p_march * $p_march;

                      $mape_desember = abs($p_desember / $desember * 100);
                      $mape_january = abs($p_january / $january * 100);
                      $mape_february = abs($p_february / $february * 100);
                      $mape_march = abs($p_march / $march * 100);

                      $total_mda = ($p_desember + $p_january + $p_february + $p_march) / 4;
                      $total_mse = ($mse_desember + $mse_january + $mse_february + $mse_march) / 4;
                      $total_mape = ($mape_desember + $mape_january + $mape_february + $mape_march) /4;

                      // RUMUS DOUBLE EXPONENTIAL SMOOTHING;
                    
                      $dataramal = array();
                      $alp = 0.6;
                      
                      $datatmp = array();
                      $datakonstanta = array('0');
                      $dataslope = array();
                      $dataforecast = array($data_jumlah[0]);

                      $tampung = array();
                      $tampung2 = array();

                      $error = array(); // menghitung nilai error perbulan
                      $error2 = array();

                      $nsb1 = 0;
                      $nsb2 = 0;

                      for($i = 0; $i < 7; $i++) {
                        if($i <= 1) {
                          $x1 = $data_jumlah[$i];
                          $peramalan1 = ($alp * $x1) + (1 - $alp) * $data_jumlah[0];
                          $peramalan2 = ($alp * $peramalan1) + (1 - $alp) * $data_jumlah[0];
                          array_push($tampung, $peramalan1);
                        } else {
                          if(isset($data_jumlah[$i])) {
                            $x1 = $data_jumlah[$i];
                            $peramalan1 = ($alp * $x1) + (1 - $alp) * $nsb1;
                            $peramalan2 = ($alp * $peramalan1) + (1 - $alp) * $nsb2;
                          }
                        }

                        $nsb1 = $peramalan1;
                        $nsb2 = $peramalan2;

                        $konstA = 2 * $peramalan1 - $peramalan2; // perhitungan konstanta A
                        $slope = ($alp / (1 - $alp)) * ($peramalan1 - $peramalan2); //perhitungan slope
                        $forecast = $konstA + $slope; // perhitungan forecash
                        array_push($datatmp, $peramalan1); //simpan data peramalan 1 ke array
                        array_push($dataramal, $peramalan2); //simpan data peramalan 2 ke array
                        array_push($datakonstanta, $konstA);
                        array_push($dataslope, $slope);
                        array_push($dataforecast, $forecast);
                        if ($i < count($data_jumlah)) {
                          $err = $data_jumlah[$i] - $dataforecast[$i + 1]; //menghitung nilai error pertahun
                          $err2 = pow($err, 2); //menghitung nilai error^2 pertahun
                          array_push($error, $err); //simpan nilai error ke array
                          array_push($error2, $err2); //simpan nilai error^2 ke array
                        }

                      }

                      $errorMAD = array();
                      $errorMSE = array();
                      $errorMAPE = array();

                      $j=3;
                      for($i = 2; $i < 6; $i++) {
                        $mad = abs($data_jumlah[$i] - $dataforecast[$j]);
                        $j++;
                        array_push($errorMAD, $mad);
                      }
                      for($i=0; $i<count($errorMAD); $i++) {
                        $mse = $errorMAD[$i] * $errorMAD[$i];
                        array_push($errorMSE, $mse);
                      }
                      $j = 2;
                      for($i=0; $i<count($errorMAD); $i++) {
                        $mape = $errorMAD[$i] / $data_jumlah[$j] * 100;
                        $j++;
                        array_push($errorMAPE, $mape);
                      }

                      $totErrorMad = array_sum($errorMAD) / 4;
                      $totErrorMse = array_sum($errorMSE) / 4;
                      $totErrorMape = array_sum($errorMAPE) / 4;
                    ?>
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Bulan</th>
                          <th>Jumlah</th> 
                          <th>Single Moving Average</th> 
                          <th>Double Exponential Smoothing</th> 
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td>October</td>
                          <td><?= $october; ?></td>
                          <td class="text-center">-</td>
                          <td class="text-center">-</td>
                        </tr>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td>November</td>
                          <td><?= $november; ?></td>
                          <td class="text-center">-</td>
                          <td class="text-center"><?= floor(abs($dataforecast[2])); ?></td>
                        </tr>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td>December</td>
                          <td><?= $desember; ?></td>
                          <td class="text-center"><?= floor($ma_desember); ?></td>
                          <td class="text-center"><?= floor(abs($dataforecast[3])); ?></td>
                        </tr>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td>January</td>
                          <td><?= $january; ?></td>
                          <td class="text-center"><?= floor($ma_january); ?></td>
                          <td class="text-center"><?= floor(abs($dataforecast[4])); ?></td>
                        </tr>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td>February</td>
                          <td><?= $february; ?></td>
                          <td class="text-center"><?= floor($ma_february); ?></td>
                          <td class="text-center"><?= floor(abs($dataforecast[5])); ?></td>
                        </tr>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td>March</td>
                          <td><?= $march; ?></td>
                          <td class="text-center"><?= floor($ma_march); ?></td>
                          <td class="text-center"><?= floor(abs($dataforecast[6])); ?></td>
                        </tr>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td>April</td>
                          <td>-</td>
                          <td class="text-center"><?= floor($ma_april); ?></td>
                          <td class="text-center"><?= floor(abs($dataforecast[6])); ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Hasil Error</h5>
                  </div>
                  <div class="card-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>SMA</th>
                          <th>DES</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>MAD = <?= floor($total_mda); ?></td>
                          <td>MAD = <?= floor($totErrorMad); ?></td>
                        </tr>
                        <tr>
                          <td>MSE = <?= floor($total_mse); ?></td>
                          <td>MSE = <?= floor($totErrorMse); ?></td>
                        </tr>
                        <tr>
                          <td>MAPE = <?= floor($total_mape); ?>%</td>
                          <td>MAPE = <?= floor($totErrorMape); ?>%</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-header">
                    <div class="row">
                      <div class="col">
                        <h5 class="card-title mt-2">Hasil Peramalan Double Exponential Smoothing</h5>
                      </div>
                      <div class="col">
                        <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#myModal">
                            Proses
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                  <table class="table table-striped">
                      <tr>
                        <th class="text-center" width="100">Periode (Bulan)</th>
                        <th class="text-center" width="100">Penjualan</th>
                        <th class="text-center" width="100">S't</th><!-- Peramalan pertama -->
                        <th class="text-center" width="100">S''t</th><!-- Peramalan Kedua -->
                        <th class="text-center" width="100">at</th><!-- konstanta -->
                        <th class="text-center" width="100">bt</th><!-- Slope -->
                        <th class="text-center" width="100">Forecast</th><!-- forecast -->
                      </tr>
                      <tr>
                      <?php
                        $jumlah = 0;
                        $jumlah2 = 0;
                        foreach ($dataramal as $key => $value) {
                            echo "<tr>";
                            echo "<td class='text-center'>" . $dataBulan[$key] . "</td>"; //data 1
                            if ($key >= count($data_jumlah)) {
                                echo "<td class='text-center'>-</td>"; //data 2
                            } else {
                                echo "<td class='text-center'>" . $data_jumlah[$key] . "</td>"; //data 2
                            }
                            echo "<td class='text-center'>" . round($datatmp[$key], 1) . "</td>"; //data 3
                            echo "<td class='text-center'>" . round($value, 1) . "</td>"; //data 4
                            if ($key == 0) {
                                echo "<td class='text-center'>-</td>"; //data 5
                                echo "<td class='text-center'>-</td>"; //data 6
                                echo "<td class='text-center'>-</td>"; //data 10
                                // echo "<td>-</td>"; //data 11
                            } else {
                                echo "<td class='text-center'>" . round($datakonstanta[$key + 1], 1) . "</td>"; //data 5
                                echo "<td class='text-center'>" . round($dataslope[$key], 1) . "</td>"; //data 5
                                if ($dataforecast[$key + 1] == $data_jumlah[0]) {
                                    echo "<td class='text-center'>-</td>";
                                } else {
                                    echo "<td class='text-center'>" . abs(round($dataforecast[$key + 1], 1)) . "</td>"; //data 6
                                }
                                // $d = (abs($error[$key - 1]) / $arrsementara[$key - 1]) * 100;
                            }
                            echo "</tr>";
                            if (isset($error2[$key - 2])) {
                                $jumlah = $error2[$key - 2] + $error2[$key - 1];
                            }
                            $rmse = $jumlah / 2;
                        }
                      ?>
                      </tr>
                    </table><br>
                    <!-- <p>Menentukan smoothing pertama :</p>
                    <p>S't = αDt + (1 - α) S't-1</p>
                    <p>Menentukan smoothing kedua :</p>
                    <p>S"t = αS't + (1 - α) S"t-1</p>
                    <p>Menentukan besarnya konstanta A :</p>
                    <p>at = S't + (S't - S"t) = 2 S't - S"t</p>
                    <p>menentukan besarnya slope :</p>
                    <p>bt = (α/(1 - α))(S't - S"t)</p>
                    <p>Menentukan besarnya forecast :</p>
                    <p>F t+m= at + btm</p> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-header">
                    <div class="row">
                      <div class="col">
                        <h5 class="card-title mt-2">Hasil Peramalan Single Moving Average</h5>
                      </div>
                      <div class="col">
                        <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#modal">Proses</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <table class="table table-striped">
                      <tr>
                        <th class="text-center" width="100">Periode (Bulan)</th>
                        <th class="text-center" width="100">Penjualan</th>
                        <th class="text-center" width="100">Forecast</th><!-- forecast -->
                      </tr>
                      <?php
                      $i = 0;
                        foreach($dataramal as $key => $value) {

                      ?>
                      <tr>
                        <td class="text-center"><?= $dataBulan[$key]; ?></td>
                        <?php
                          if($key >= count($data_jumlah)) {
                            echo '<td class="text-center">-</td>';
                          } else {
                            echo '<td class="text-center">' . $data_jumlah[$key] . '</td>';
                          }
                        ?>
                        <?php
                          if($key <= 1) {
                            echo '<td class="text-center">-</td>';
                          } else {
                            echo '<td class="text-center">' . $dataSma[$i++] . '</td>';
                          }
                        ?>
                      </tr>
                      <?php } ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="modal">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Proses Single Moving Average</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col">
                        <h5>Rumus Single Moving Average</h5>
                        <table class="table table-dark">
                          <thead>
                            <tr>
                              <td>No</td>
                              <td>Bulan</td>
                              <td>Single Moving Average</td>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>Desember</td>
                              <td>
                                <p>Ft+1 = (X_t+X_(t-1)+X_(t-2)+ … +X_(t-n))/n</p>
                                (<?= $october ?> + <?= $november ?> ) / 2) = <?= $ma_desember ?>;
                              </td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>Januari</td>
                              <td>
                                <p>Ft+1 = (X_t+X_(t-1)+X_(t-2)+ … +X_(t-n))/n</p>
                                (<?= $november ?> + <?= $desember ?> ) / 2) = <?= $ma_january ?>;
                              </td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td>Februari</td>
                              <td>
                                <p>Ft+1 = (X_t+X_(t-1)+X_(t-2)+ … +X_(t-n))/n</p>
                                (<?= $desember ?> + <?= $january ?> ) / 2) = <?= $ma_february ?>;
                              </td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td>Maret</td>
                              <td>
                                <p>Ft+1 = (X_t+X_(t-1)+X_(t-2)+ … +X_(t-n))/n</p>
                                (<?= $january ?> + <?= $february ?> ) / 2) = <?= $ma_march ?>;
                              </td>
                            </tr>
                            <tr>
                              <td>5</td>
                              <td>April</td>
                              <td>
                                <p>Ft+1 = (X_t+X_(t-1)+X_(t-2)+ … +X_(t-n))/n</p>
                                (<?= $february ?> + <?= $march ?> ) / 2) = <?= $ma_april ?>;
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="myModal">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Proses Double Exponential Smoothing</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col">
                        <h5>Menentukan Smoothing Pertama</h5>
                        <table class="table table-dark">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Bulan</th>
                              <th>Rumus Smoothing Pertama</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $i = 0;
                              $jumlah = 6;
                              for($j = 1; $j <= 5; $j++) {
                            ?>
                            <tr>
                              <td><?= $j ?></td>
                              <td><?= $dataBulan[$j]; ?></td>
                              <td>
                                <?php
                                  echo "<p>S't = αDt + (1 - α) S't-1</p>
                                    (0,6 *" . $data_jumlah[$j] . ") + (1 - 0,6) * " . $datatmp[$i] .  " = " . $datatmp[$j];
                                  $i++;
                                ?>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                      <div class="col">
                        <h5>Menentukan Smoothing Kedua</h5>
                        <table class="table table-dark">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Bulan</th>
                              <th>Rumus Smoothing Kedua</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $i = 0;
                              $jumlah = 1;
                              for($j = 1; $j <= 6; $j++) {
                            ?>
                            <tr>
                              <td><?= $j ?></td>
                              <td><?= $dataBulan[$j]; ?></td>
                              <td>
                                <?php
                                  echo "<p>S``t = αS't + (1 - α) S``t-1</p>
                                    (0,6 *" . $datatmp[$j] . ") + (1 - 0,6) * " . $dataramal[$i] .  " = " . $dataramal[$j];
                                  $i++;
                                ?>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row mt-5">
                      <div class="col">
                        <h5>Menentukan besarnya konstanta A :</h5>
                        <table class="table table-dark">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Bulan</th>
                              <th>Rumus Konstanta A :</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $i = 0;
                              $jumlah = 1;
                              for($j = 1; $j <= 6; $j++) {
                            ?>
                            <tr>
                              <td><?= $j ?></td>
                              <td><?= $dataBulan[$j]; ?></td>
                              <td>
                                <?php
                                  echo "<p>at = 2S't - S``t</p>
                                    2 *" . $datatmp[$j] . " - " . $dataramal[$j] . " = " . $datakonstanta[$j+1];
                                  $i++;
                                ?>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                      <div class="col">
                        <h5>Menentukan Pemulusan Tren</h5>
                        <table class="table table-dark">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Bulan</th>
                              <th>Rumus Pemulusan Tren</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $i = 0;
                              $jumlah = 1;
                              for($j = 1; $j <= 6; $j++) {
                            ?>
                            <tr>
                              <td><?= $j ?></td>
                              <td><?= $dataBulan[$j]; ?></td>
                              <td>
                                <?php
                                  echo "<p>(α/(1 - α))(S't - S``t)</p>
                                    (0,6 /(1 - 0,6))*(" . $datatmp[$j] . " - " . $dataramal[$i] .  ") = " . $dataslope[$j];
                                  $i++;
                                ?>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="row mt-5">
                      <div class="col">
                        <h5>Menentukan Hasil Forecasting</h5>
                        <table class="table table-dark">
                          <thead>
                            <tr>
                              <td>No</td>
                              <td>Bulan</td>
                              <td>Rumus Forecasting</td>
                            </tr>
                          </thead>
                          <tbody>
                          <?php
                            $i = 1;
                            $jumlah = 1;
                            for($j = 1; $j <= 6; $j++) {
                              ?>
                          <tr>
                            <td><?= $j; ?></td>
                            <td><?= $dataBulan[$j]; ?></td>
                            <td>
                              <?php
                                  echo "<p>Ft+m = at + btm</p>
                                  " . $datakonstanta[$i+1] . " + " . $dataslope[$j] .  " = " . $dataforecast[$j+1];
                                  $i++;
                                  ?>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
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
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- endinject -->
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Bulan', 'Pengeluaran Barang', 'Single Moving Average', 'Double Exponential Smoothing'],
                ['October', <?= $october; ?>, null, null],
                ['November', <?= $november; ?>, null, <?= abs(floor($dataforecast[2])); ?>],
                ['December', <?= $desember; ?>, <?= floor($ma_desember); ?>, <?= abs(ceil($dataforecast[3])); ?>],
                ['January', <?= $january; ?>, <?= floor($ma_january); ?>, <?= abs(floor($dataforecast[4])); ?>],
                ['February', <?= $february; ?>, <?= floor($ma_february); ?>, <?= abs(floor($dataforecast[5])); ?>],
                ['March', <?= $march; ?>, <?= floor($ma_march); ?>, <?= abs(floor($dataforecast[6])); ?>],
                ['April', null, <?= floor($ma_april); ?>, <?= abs(floor($dataforecast[6])); ?>],
            ]);

            var options = {
                title: 'TB. JAYA AGUNG',
                curveType: 'function'
            };

            var chart = new google.visualization.LineChart(document.getElementById('curveChart'))

            chart.draw(data, options);
        }

    </script>
    
  </body>
</html>