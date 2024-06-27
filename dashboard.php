<?php 
    require_once 'connection.php'; 
    session_start();
    if(!isset($_SESSION["logged"])){
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT * FROM user WHERE id=".$_SESSION["logged"];
    $result = mysqli_query($conn, $sql);
    $user =mysqli_fetch_array($result);
    
    function convertNilai($nilai) {
        if ($nilai >= 80 && $nilai <= 100) {
            return 5;
        } elseif ($nilai >= 60 && $nilai < 80) {
            return 4;
        } elseif ($nilai >= 40 && $nilai < 60) {
            return 3;
        } elseif ($nilai >= 20 && $nilai < 40) {
            return 2;
        } elseif ($nilai >= 0 && $nilai < 20) {
            return 1;
        } else {
            return 0;
        }
    }

    function sumNilai($nilai){
        return $nilai['k1']+$nilai['k2']+$nilai['k3']+$nilai['k4']+$nilai['k5']+$nilai['k6']+$nilai['k7'];
    }

    $sql = "SELECT * FROM supplier";
    $result = mysqli_query($conn, $sql);
    $data = array();
    
    $minK1 = 5;
    $maxK2 = 1;
    $maxK3 = 1;
    $maxK4 = 1;
    $maxK5 = 1;
    $maxK6 = 1;
    $minK7 = 5;

    $k1 = 0.2;
    $k2 = 0.2;
    $k3 = 0.15;
    $k4 = 0.15;
    $k5 = 0.15;
    $k6 = 0.10;
    $k7 = 0.05;

    while($row =mysqli_fetch_array($result)) {
        $row['bobot']['k1'] = convertNilai($row['k1']);
        $row['bobot']['k2'] = convertNilai($row['k2']);
        $row['bobot']['k3'] = convertNilai($row['k3']);
        $row['bobot']['k4'] = convertNilai($row['k4']);
        $row['bobot']['k5'] = convertNilai($row['k5']);
        $row['bobot']['k6'] = convertNilai($row['k6']);
        $row['bobot']['k7'] = convertNilai($row['k7']);
        $minK1 = ($row['bobot']['k1']<$minK1?$row['bobot']['k1']:$minK1);
        $maxK2 = ($row['bobot']['k2']>$maxK2?$row['bobot']['k2']:$maxK2);
        $maxK3 = ($row['bobot']['k3']>$maxK3?$row['bobot']['k3']:$maxK3);
        $maxK4 = ($row['bobot']['k4']>$maxK4?$row['bobot']['k4']:$maxK4);
        $maxK5 = ($row['bobot']['k5']>$maxK5?$row['bobot']['k5']:$maxK5);
        $maxK6 = ($row['bobot']['k6']>$maxK6?$row['bobot']['k6']:$maxK6);
        $minK7 = ($row['bobot']['k7']<$minK7?$row['bobot']['k7']:$minK7);
        $data[] = $row;
    }
    for ($i=0; $i < count($data); $i++) { 
        $data[$i]['normalisasi']['k1'] = $minK1/$data[$i]['bobot']['k1'];
        $data[$i]['normalisasi']['k2'] = $data[$i]['bobot']['k2']/$maxK2;
        $data[$i]['normalisasi']['k3'] = $data[$i]['bobot']['k3']/$maxK3;
        $data[$i]['normalisasi']['k4'] = $data[$i]['bobot']['k4']/$maxK4;
        $data[$i]['normalisasi']['k5'] = $data[$i]['bobot']['k5']/$maxK5;
        $data[$i]['normalisasi']['k6'] = $data[$i]['bobot']['k6']/$maxK6;
        $data[$i]['normalisasi']['k7'] = $minK7/$data[$i]['bobot']['k7'];

        $data[$i]['preferensi']['k1'] = $data[$i]['normalisasi']['k1']*$k1;
        $data[$i]['preferensi']['k2'] = $data[$i]['normalisasi']['k2']*$k2;
        $data[$i]['preferensi']['k3'] = $data[$i]['normalisasi']['k3']*$k3;
        $data[$i]['preferensi']['k4'] = $data[$i]['normalisasi']['k4']*$k4;
        $data[$i]['preferensi']['k5'] = $data[$i]['normalisasi']['k5']*$k5;
        $data[$i]['preferensi']['k6'] = $data[$i]['normalisasi']['k6']*$k6;
        $data[$i]['preferensi']['k7'] = $data[$i]['normalisasi']['k7']*$k7;
        $data[$i]['preferensi']['total'] = sumNilai($data[$i]['preferensi']);
    }
    $rank = array();
    foreach($data as $row){
        $rank[] = [
            'nama'=>$row['nama'],
            'total'=>$row['preferensi']['total']
        ];
    }
    $n = count($rank);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($rank[$j]['total'] < $rank[$j + 1]['total']) {
                $temp = $rank[$j];
                $rank[$j] = $rank[$j + 1];
                $rank[$j + 1] = $temp;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SPK Supplier Masakan Bude</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">SPK Supplier</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?=$user['username']?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?=$user['username']?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link " href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="supplier.php">
          <i class="bi bi-person"></i>
          <span>Supplier</span>
        </a>
      </li>
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <div class="row">

        <div class="col-12">
        <div class="card">
              <div class="card-body">
                <h5 class="card-title">Data Kriteria</h5>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Kode</th>
                      <th>Kriteria</th>
                      <th>Bobot</th>
                      <th>Sifat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>K1</td>
                      <td>Harga</td>
                      <td>0.20</td>
                      <td>Cost</td>
                    </tr>
                    <tr>
                      <td>K2</td>
                      <td>Kualitas Bahan Pangan</td>
                      <td>0.20</td>
                      <td>Benefit</td>
                    </tr>
                    <tr>
                      <td>K3</td>
                      <td>Pengalaman dan Reputasi Supplier</td>
                      <td>0.15</td>
                      <td>Benefit</td>
                    </tr>
                    <tr>
                      <td>K4</td>
                      <td>Ketersediaan Bahan Pangan</td>
                      <td>0.15</td>
                      <td>Benefit</td>
                    </tr>
                    <tr>
                      <td>K5</td>
                      <td>Kecepatan dan Ketepatan Waktu Pengiriman</td>
                      <td>0.15</td>
                      <td>Benefit</td>
                    </tr>
                    <tr>
                      <td>K6</td>
                      <td>Fleksibitas dalam Penanganan Pesanan</td>
                      <td>0.1</td>
                      <td>Benefit</td>
                    </tr>
                    <tr>
                      <td>K7</td>
                      <td>Lokasi dan Logistik </td>
                      <td>0.05</td>
                      <td>Cost</td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
        <div class="card">
              <div class="card-body">
                <h5 class="card-title">Bobot</h5>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Nilai</th>
                      <th>Bobot</th>
                      <th>Sifat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>80-100</td>
                      <td>5</td>
                      <td>Sangat Baik</td>
                    </tr>
                    <tr>
                      <td>60-79</td>
                      <td>4</td>
                      <td>Baik</td>
                    </tr>
                    <tr>
                      <td>40-59</td>
                      <td>3</td>
                      <td>Cukup</td>
                    </tr>
                    <tr>
                      <td>20-39</td>
                      <td>2</td>
                      <td>Buruk</td>
                    </tr>
                    <tr>
                      <td>0-19</td>
                      <td>1</td>
                      <td>Sangat Buruk</td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Supplier</h5>
              <!-- Table with stripped rows -->
              <table class="table">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>K1</th>
                    <th>K2</th>
                    <th>K3</th>
                    <th>K4</th>
                    <th>K5</th>
                    <th>K6</th>
                    <th>K7</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                        foreach($data as $row):
                  ?>
                      <tr>
                          <td><?=$row['nama']?></td>
                          <td><?=$row['k1']?></td>
                            <td><?=$row['k2']?></td>
                            <td><?=$row['k3']?></td>
                            <td><?=$row['k4']?></td>
                            <td><?=$row['k5']?></td>
                            <td><?=$row['k6']?></td>
                            <td><?=$row['k7']?></td>
                      </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
        </div>
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Bobot Supplier</h5>
              <!-- Table with stripped rows -->
              <table class="table">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>K1</th>
                    <th>K2</th>
                    <th>K3</th>
                    <th>K4</th>
                    <th>K5</th>
                    <th>K6</th>
                    <th>K7</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                        foreach($data as $row):
                  ?>
                      <tr>
                            <td><?=$row['nama']?></td>
                            <td><?=$row['bobot']['k1']?></td>
                            <td><?=$row['bobot']['k2']?></td>
                            <td><?=$row['bobot']['k3']?></td>
                            <td><?=$row['bobot']['k4']?></td>
                            <td><?=$row['bobot']['k5']?></td>
                            <td><?=$row['bobot']['k6']?></td>
                            <td><?=$row['bobot']['k7']?></td>
                            
                      </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
        </div>
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Normalisasi Supplier</h5>
              <!-- Table with stripped rows -->
              <table class="table">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>K1</th>
                    <th>K2</th>
                    <th>K3</th>
                    <th>K4</th>
                    <th>K5</th>
                    <th>K6</th>
                    <th>K7</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                        foreach($data as $row):
                  ?>
                      <tr>
                            <td><?=$row['nama']?></td>
                            <td><?=$row['normalisasi']['k1']?></td>
                            <td><?=$row['normalisasi']['k2']?></td>
                            <td><?=$row['normalisasi']['k3']?></td>
                            <td><?=$row['normalisasi']['k4']?></td>
                            <td><?=$row['normalisasi']['k5']?></td>
                            <td><?=$row['normalisasi']['k6']?></td>
                            <td><?=$row['normalisasi']['k7']?></td>
                            
                      </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
        </div>
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Preferensi Supplier</h5>
              <!-- Table with stripped rows -->
              <table class="table">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>K1</th>
                    <th>K2</th>
                    <th>K3</th>
                    <th>K4</th>
                    <th>K5</th>
                    <th>K6</th>
                    <th>K7</th>
                    <th>Hasil</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                        foreach($data as $row):
                  ?>
                      <tr>
                            <td><?=$row['nama']?></td>
                            <td><?=$row['preferensi']['k1']?></td>
                            <td><?=$row['preferensi']['k2']?></td>
                            <td><?=$row['preferensi']['k3']?></td>
                            <td><?=$row['preferensi']['k4']?></td>
                            <td><?=$row['preferensi']['k5']?></td>
                            <td><?=$row['preferensi']['k6']?></td>
                            <td><?=$row['preferensi']['k7']?></td>
                            <td><?=$row['preferensi']['total']?></td>
                            
                      </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
        </div>
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Rank Supplier</h5>
              <!-- Table with stripped rows -->
              <table class="table">
                <thead>
                  <tr>
                    <th>Rank</th>
                    <th>Nama</th>
                    <th>Hasil</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                        $i = 1;
                        foreach($rank as $row):
                  ?>
                      <tr>
                            <td><?=$i++?></td>
                            <td><?=$row['nama']?></td>
                            <td><?=$row['total']?></td>
                      </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
        </div>
    </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Masakan Bude</span></strong>. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>