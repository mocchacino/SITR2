<?php
	date_default_timezone_set("Asia/Bangkok"); 
	setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);

		switch ($_SESSION['admin_sebagai_lab']) {
			case 'biokimia':
				$nama_lab='Laboratorium Biokimia';
				break;
			case 'analitik':
				$nama_lab='Laboratorium Analitik';
				break;
			case 'fisik':
				$nama_lab='Laboratorium Fisik';
				break;
			case 'organik':
				$nama_lab='Laboratorium Organik';
				break;
			case 'anorganik':
				$nama_lab='Laboratorium Anorganik';
				break;
			
			default:
				header("Location:./");
				break;
		}
?>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		<button class="btn btn-danger pull-right" onclick="window.location.href='index.php'">Logout Laboratorium</button>
<?php
		echo '<h1 align="center">'.$nama_lab.'</h1>';
?>
	</div></div>
<style type="text/css">
	.noti-box{
		min-height: 133px;
	}
</style>
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
				<span class="icon-box bg-color-green set-icon">
					<i class="fa fa-book"></i>
				</span>
				<div class="text-box" >
					<div class="main-text">Daftar Mahasiswa</div>
					<button class="btn btn-primary" onclick="window.location.href='daftar_mahasiswa_uji_kelayakan_lab.php'">Kelola</button>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
				<span class="icon-box bg-color-green set-icon">
					<i class="fa fa-book"></i>
				</span>
				<div class="text-box" >
					<div class="main-text">Daftar Nilai</div>
					<button class="btn btn-primary" onclick="window.location.href='daftar_nilai_uji_kelayakan_lab.php'">Kelola</button>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-6">           
			<div class="panel panel-back noti-box">
				<span class="icon-box bg-color-green set-icon">
					<i class="fa fa-pencil"></i>
				</span>
				<div class="text-box" >
					<div class="main-text">Input Nilai</div>
					<button class="btn btn-primary" onclick="window.location.href='input_nilai_uji_kelayakan_lab.php'">Kelola</button>
				</div>
			</div>
		</div>
	</div>
<?php

    include_once('../footer.php');
    $con->close();
  }else{
    header("Location:./");
  }
?>