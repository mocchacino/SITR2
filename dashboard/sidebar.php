<?php
	require_once('functions.php');
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	if(!isset($_SESSION['sip_masuk_aja'])){
	  header("Location:login/login.php");
	}                          
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $site_name; ?></title>
	<!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
   
   <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
							
</head>
<body>
    <div id="wrapper">
		<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php">SITR2</a> 
            </div>
			<div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
				<span class="dropdown">
					<a style="color:#fff; font-size:12px;" data-toggle="dropdown"><i class="fa fa-user" class="dropdown-toggle"></i>	
					<?php if($status=='petugas') echo $rAdmin->nama; elseif ($status=='mahasiswa') echo $rMahasiswa->nama; elseif ($status=='dosen') echo $rDosen->nama_dosen; elseif ($status=='laboratorium') echo $rLaboratorium->nama_lab; 
					?> </a>
					<ul class="dropdown-menu">
					    <li><a href="profil.php">Profil</a></li>
					    <?php if($status=='mahasiswa') echo '<li><a href="data_tr1.php">Ubah Data TR1</a></li>';?>
					    <li><a href="change_pass.php">Ubah Password</a></li>
					</ul>
				  
				</span>
				<a style="text-decoration: none; color:#fff; font-size:12px;"> <?php
					echo " ( ".date("D, Y-m-d") . " ". date("h:ia")." )&nbsp;&nbsp;";
				?>
				</a>
				<a href="../login/logout.php" class="btn btn-danger square-btn-adjust">Logout</a>
			</div>
        </nav>   
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
					<img src="../assets/img/lambang_undip.png" class="user-image img-responsive"/>
				</li>
				<li>
					<a class="active-menu" href="index.php"><i class="fa fa-dashboard "></i> Dashboard</a>
				</li>
				<?php
				if($status=='petugas'){
					echo '
					<li>
						<a href="#"><i class="fa fa-file"></i>Tugas Riset 2<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="admin_pendaftaran_tr2.php">Pendaftaran Tugas Riset 2</a></li>
							<li><a href="admin_daftar_tr2.php">Daftar Mahasiswa TR2</a></li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-flask"></i>Uji Kelayakan<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="admin_pendaftaran_uji_kelayakan.php">Pendaftaran Uji Kelayakan</a></li>
							<li><a href="admin_daftar_uji_kelayakan.php">Daftar Mahasiswa Uji Kelayakan</a></li>
							<li><a href="daftar_nilai_uji_kelayakan.php">Daftar Nilai Mahasiswa</a></li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-book"></i>Tugas Akhir<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="admin_pendaftaran_skripsi.php">Pendaftaran Tugas Akhir</a></li>
							<li><a href="daftar_uji_skripsi.php">Daftar Mahasiswa Ujian Tugas Akhir</a></li>
							<li><a href="daftar_nilai_skripsi.php">Daftar Nilai Mahasiswa</a></li>
							<li><a href="input_nilai_skripsi.php">Input Nilai Ujian Tugas Akhir</a></li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-users"></i> Kelola Eksekutif Departemen<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="ubah_kadep.php">Tetapkan Ketua Departemen</a>
							</li>
							
							<li><a href="ubah_sekdep.php">Tetapkan Sekretaris Departemen</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-calendar"></i> Kelola Periode<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="ubah_tahun_ajaran.php">Tetapkan Tahun Ajaran</a>
							</li>
							<li><a href="admin_jadwal_pendaftaran_tr2.php">Tetapkan Jadwal Pendaftaran Tugas Riset 2</a>
							</li>
							<li><a href="admin_jadwal_pendaftaran_uji_kelayakan.php">Tetapkan Jadwal Uji Kelayakan</a>
							</li>
							<li><a href="admin_jadwal_pendaftaran_skripsi.php">Tetapkan Jadwal Skripsi</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-user"></i>Pembimbing Luar<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="daftar_pembimbing_luar.php">Daftar Pembimbing dari Luar Departemen</a>
							</li>
							<li><a href="admin_tambah_pembimbing_luar.php">Tambah Pembimbing dari Luar Departemen</a>
							</li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-user"></i>Admin<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="daftar_admin.php">Daftar Admin</a>
							</li>
							<li><a href="tambah_admin.php">Tambah Admin</a>
							</li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-user"></i>Laboratorium<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="daftar_lab.php">Daftar Laboratorium</a>
							</li>
							<li><a href="tambah_lab.php">Tambah Laboratorium</a>
							</li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-user"></i>Dosen<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="daftar_dosen.php">Daftar Dosen</a>
							</li>
							<li><a href="tambah_dosen.php">Tambah Dosen</a>
							</li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-user"></i>Mahasiswa<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="daftar_mahasiswa.php">Daftar Mahasiswa</a>
							</li>
							<li><a href="tambah_mahasiswa.php">Tambah Mahasiswa</a>
							</li>
							
						</ul>
					</li>
					';
				}elseif ($status=='mahasiswa') {
					echo '<li>
						<a href="#"><i class="fa fa-calendar"></i>Jadwal Seminar/ Sidang<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="jadwal_uji_kelayakan.php">Seminar Uji Kelayakan</a></li>
							<li><a href="jadwal_skripsi.php">Sidang Tugas Akhir</a></li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-file"></i>Tugas Riset 2<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="pendaftaran_tr2.php">Pendaftaran Tugas Riset 2</a></li>
							<li><a href="daftar_tr2.php">Daftar Mahasiswa Tugas Riset 2</a></li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-flask"></i>Uji Kelayakan<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="pendaftaran_uji_kelayakan.php">Pendaftaran Uji Kelayakan</a></li>
							<li><a href="daftar_uji_kelayakan.php">Daftar Mahasiswa Uji Kelayakan</a></li>
							<li><a href="surat_undangan_uji_kelayakan.php">Print Surat Undangan Uji Kelayakan</a></li>
							<li><a href="hasil_uji_kelayakan.php">Hasil Uji Kelayakan</a></li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-book"></i>Skripsi<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="pendaftaran_skripsi.php">Pendaftaran Tugas Akhir</a></li>
							<li><a href="daftar_skripsi.php">Daftar Mahasiswa Tugas Akhir</a></li>
							<li><a href="surat_ujian_skripsi.php">Surat Ujian Tugas Akhir</a></li>
							<li><a href="hasil_uji_skripsi.php">Hasil Ujian Tugas Akhir</a></li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-folder-open"></i>Pasca-Tugas Akhir<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="permohonan_pengantar.php">Permohonan Pengantar</a></li>
							<li><a href="skl.php">Surat Keterangan Lulus</a></li>
							<li><a href="tanda_terima_skripsi.php">Tanda Terima Penyerahan Tugas Akhir</a></li>	
						</ul>
					</li>';
				}elseif ($status=='dosen') {
					echo '<li>
						<a href="#"><i class="fa fa-calendar"></i>Jadwal Seminar/ Sidang<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="jadwal_uji_kelayakan.php">Seminar Ujian Kelayakan</a></li>
							<li><a href="jadwal_skripsi.php">Sidang Tugas Akhir</a></li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-file"></i>Tugas Riset 2<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="dosen_daftar_tr2.php">Daftar Mahasiswa Tugas Riset 2</a></li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-flask"></i>Uji Kelayakan<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="dosen_daftar_uji_kelayakan.php">Daftar Mahasiswa Ujian Kelayakan</a></li>
							
						</ul>
					</li>
					<li>
						<a href="#"><i class="fa fa-book"></i>Skripsi<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="daftar_uji_skripsi.php">Daftar Mahasiswa Ujian Tugas Akhir</a></li>
							<li><a href="daftar_nilai_skripsi.php">Daftar Nilai Mahasiswa Ujian Tugas Akhir</a></li>
							<li><a href="input_nilai_skripsi.php">Input Nilai Ujian Tugas Akhir</a></li>
							
						</ul>
					</li>';
				}elseif ($status=="laboratorium") {
					echo '<li>
						<a href="#"><i class="fa fa-flask"></i>Uji Kelayakan<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="pendaftaran_uji_kelayakan.php">Pendaftaran Mahasiswa Uji Kelayakan</a></li>
							<li><a href="lab_daftar_uji_kelayakan.php">Daftar Mahasiswa Uji Kelayakan</a></li>
							<li><a href="daftar_nilai.php">Daftar Nilai Mahasiswa</a></li>
							<li><a href="lab_input_nilai.php">Input Nilai Uji Kelayakan</a></li>
							
						</ul>
					</li>';
				}
				?>				
				
				
				
					<!-- <li>
                        <a  href="http://localhost/sip/index.php#about"><i class="fa fa-square-o fa-2x"></i> About</a>
					</li>	 -->
                </ul>
            </div>
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">