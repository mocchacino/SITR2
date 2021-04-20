<?php
    require_once('functions.php');
    // date_default_timezone_set("Asia/Jakarta");
    // setlocale(LC_ALL, 'id_ID');
    ini_set('display_errors', 1);

    
    $endTugasRiset2=mysqli_query($con, "SELECT awal, akhir FROM waktu where nama='TR2' ORDER BY akhir DESC");
    if(mysqli_num_rows($endTugasRiset2) > 0){
        $waktuSelesaiTugasRiset2 = mysqli_fetch_assoc($endTugasRiset2);
        $endDateTugasRiset2 = date('Y-m-d',strtotime($waktuSelesaiTugasRiset2['akhir']));
        $startDateTugasRiset2 = date('Y-m-d',strtotime($waktuSelesaiTugasRiset2['awal']));
        if(($endDateTugasRiset2 >= date("Y-m-d"))&&($startDateTugasRiset2 <= date("Y-m-d")))
            $noteTR2=" dibuka sampai tanggal ".strftime("%e %B %Y",strtotime($endDateTugasRiset2));
        else if(($endDateTugasRiset2 < date("Y-m-d"))&&($startDateTugasRiset2 > date("Y-m-d")))
            $noteTR2=" dimulai dari tanggal ".strftime("%e %B %Y",strtotime($startDateTugasRiset2));
        else $noteTR2="Tidak Ada";
    }
    $endUjiKelayakan=mysqli_query($con, "SELECT awal, akhir FROM waktu where nama='UK' ORDER BY akhir DESC");
    if(mysqli_num_rows($endUjiKelayakan) > 0){
        $waktuSelesaiUjiKelayakan = mysqli_fetch_assoc($endUjiKelayakan);
        $endDateUjiKelayakan = $waktuSelesaiUjiKelayakan['akhir'];
        $startDateUjiKelayakan = $waktuSelesaiUjiKelayakan['awal'];
        if(($endDateUjiKelayakan >= date("Y-m-d"))&&($startDateUjiKelayakan <= date("Y-m-d")))
            $noteUK=" dibuka sampai tanggal ".strftime("%e %B %Y",strtotime($endDateUjiKelayakan));
        else if(($endDateTugasRiset2 < date("Y-m-d"))&&($startDateTugasRiset2 > date("Y-m-d")))
            $noteUK=" dimulai dari tanggal ".strftime("%e %B %Y",strtotime($startDateUjiKelayakan));
        else $noteUK="Tidak Ada";
    }
    $endSkripsi=mysqli_query($con, "SELECT awal, akhir FROM waktu where nama='Skr' ORDER BY akhir DESC");
    if(mysqli_num_rows($endSkripsi) > 0){
        $waktuSelesaiSkripsi = mysqli_fetch_assoc($endSkripsi);
        $endDateSkripsi = $waktuSelesaiSkripsi['akhir'];
        $startDateSkripsi = $waktuSelesaiSkripsi['awal'];
        if(($endDateSkripsi >= date("Y-m-d"))&&($startDateSkripsi <= date("Y-m-d")))
            $noteSkr=" dibuka sampai tanggal ".strftime("%e %B %Y",strtotime($endDateSkripsi));
        else if(($endDateTugasRiset2 < date("Y-m-d"))&&($startDateTugasRiset2 > date("Y-m-d")))
            $noteSkr=" dimulai dari tanggal ".strftime("%e %B %Y",strtotime($startDateSkripsi));
        else $noteSkr="Tidak Ada";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Sistem Informasi Tugas Riset 2</title>
<link rel="stylesheet" type="text/css" href="assets/css/table.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="../assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../assets/css/main.css" type="text/css">
    <link rel="stylesheet" href="../assets/extras/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="../assets/extras/owl.theme.css" type="text/css">    
    <!-- Responsive CSS Styles -->
    <link rel="stylesheet" href="../assets/css/responsive.css" type="text/css">
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="../assets/css/bootstrap-select.min.css">
    <!-- Bootstrap Core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme CSS -->
    <link href="../assets/css/freelancer.min.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .item h4 {
            font-size: 19px;
            line-height: 1.375em;
            font-weight: 400;
            font-style: italic;
            margin: 70px 0;
        }
        .carousel-control.right, .carousel-control.left {
            background-image: none;
        }
        .bg-image {
          /* Add the blur effect */
          filter: blur(5px);
          -webkit-filter: blur(5px);

          /* Center and scale the image nicely */
          background-position: center;
          background-repeat: no-repeat;
          background-size: cover;
        }
    </style>
</head>

<body id="page-top" class="index">

    <?php
        include_once('header.php');
    ?>

    
    <!-- Header -->
    <header id='home'>
                    <div class="container container-fluid text-center ">
                    <div id="myCarousel" class="carousel slide text-center" data-ride="carousel" >
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                          <li data-target="#myCarousel" data-slide-to="1"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                              <img src="../assets/img/bg/wp.jpg" class="bg-image" style="height: 400px;">
                              <div class="carousel-caption">
                                <h3>SITR2</h3>
                                <p>Sistem Informasi Tugas Riset 2</p>
                              </div> 
                            </div>

                            <div class="item">
                              <img src="../assets/img/bg/front.jpg" class="bg-image" style="height: 400px;">
                              <div class="carousel-caption">
                                <p>Membantu dalam mengelola kegiatan dalam Tugas Riset 2</p>
                              </div> 
                            </div>
                        </div>

                        <!-- Left and right controls -->
                        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                          <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                          <span class="sr-only">Next</span>
                        </a>
                      </div> 
                      </div>
                
            
    </header>

    <!-- Portfolio Grid Section -->
    <div id="jadwal" class="wrapper">
      <!-- Featured Listings Start -->
      <section class="featured-lis" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Jadwal</h2>
                    <hr style="border-color: black" class="star-light">
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h4>Pendaftaran Tugas Riset 2</h4>
                        </div>
                        <div class="panel-body">
                        <?php
                            if (isset($noteTR2)){
                                echo $noteTR2;
                            }
                        ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-xs-12">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h4>Pendaftaran Uji Kelayakan</h4>
                        </div>
                        <div class="panel-body">
                        <?php
                            if (isset($noteUK)){
                                echo $noteUK;
                            }
                        ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-xs-12">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h4>Pendaftaran Ujian Tugas Akhir</h4>
                        </div>
                        <div class="panel-body">
                        <?php
                            if (isset($noteSkr)){
                                echo $noteSkr;
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Featured Listings End -->
    </div>

    <!-- About Section -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>About</h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row" align="justify">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <p>Sistem Informasi Tugas Riset II adalah sistem yang dibuat untuk memudahkan petugas administrasi dalam mengelola pendaftaran dan pengelolaan Tugas Riset II hingga Tugas Akhir. </p>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-6">
                        <h3>Location</h3>
                        <p>Gedung Departemen Kimia
                            <br>Fakultas Sains dan Matematika
                            <br>Universitas Diponegoro</p>
                    </div>
                    <div class="footer-col col-md-6">
                        <h3>Around the Web</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-github"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; 2018 Sistem Informasi Tugas Riset 2 Departemen Kimia Universitas Diponegoro & script is create with Bootstrap theme <a href="http://startbootstrap.com">Start Bootstrap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

   
    <!-- jQuery -->
    <script src="../assets/js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="../assets/js/jquery.easing.min.js"></script>
    <!-- Theme JavaScript -->
    <script src="../assets/js/freelancer.min.js"></script>
    <script type="text/javascript" src="../assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="../assets/js/wow.js"></script>
    <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../assets/js/main.js"></script>
</body>
</html>
