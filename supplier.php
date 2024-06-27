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
        <a class="nav-link collapsed" href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="supplier.php">
          <i class="bi bi-person"></i>
          <span>Supplier</span>
        </a>
      </li>
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Data Supplier</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Supplier</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Supplier</h5>
              <?php
                  if(isset($_GET["id"])):
                      $sql = "DELETE FROM supplier WHERE id = ".$_GET["id"];
                      if (mysqli_query($conn, $sql)) :?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Hapus data supplier berhasil
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                      <?php else: ?>
                          <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              Hapus data supplier gagal
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>
                      <?php endif;
                  endif;
              ?>
              <div class="d-flex mb-2">
                <a class="btn btn-primary ms-auto" href="supplier-add.php">TAMBAH</a>
              </div>
              <!-- Table with stripped rows -->
              <table class="table datatable">
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
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                      $sql = "SELECT * FROM supplier";
                      $result = mysqli_query($conn, $sql);
                      while($row =mysqli_fetch_array($result)) :
                  ?>
                      <tr>
                          <th><?=$row['nama']?></th>
                          <td><?=$row['k1']?></td>
                          <td><?=$row['k2']?></td>
                          <td><?=$row['k3']?></td>
                          <td><?=$row['k4']?></td>
                          <td><?=$row['k5']?></td>
                          <td><?=$row['k6']?></td>
                          <td><?=$row['k7']?></td>
                          <td>
                              <a class="btn btn-warning" href="supplier-edit.php?id=<?=$row['id']?>">UBAH</a>
                              <a class="btn btn-danger" href="supplier.php?id=<?=$row['id']?>">HAPUS</a>
                          </td>
                      </tr>
                  <?php endwhile ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
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