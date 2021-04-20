<?php
  ob_start();
  error_reporting(0);
  
    // Include our login information
	require_once('connect.php');
	
	// Connect
	$con = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	if(mysqli_connect_errno()){
		die('Could not connect to database : <br/>'.$mysqli_connect_error());
	}
	
	define("BASE_URL","http://sitr2.kimia.fsm.undip.ac.id/");
	$site_name="Sistem Informasi Tugas Riset 2";

	session_start();
	date_default_timezone_set('Asia/Jakarta');

	if(isset($_SESSION['sip_masuk_aja'])){
		if($_SESSION['sip_status']=='petugas'){
			$admin=mysqli_query($con,"SELECT * from petugas where idpetugas=".$_SESSION['sip_masuk_aja']);
			$rAdmin=$admin->fetch_object();
			$status=$_SESSION['sip_status'];
		}elseif($_SESSION['sip_status']=='mahasiswa'){
			$mahasiswa=mysqli_query($con,"SELECT * from mahasiswa where nim=".$_SESSION['sip_masuk_aja']);
			$rMahasiswa=$mahasiswa->fetch_object();
			$status=$_SESSION['sip_status'];
		}elseif($_SESSION['sip_status']=='dosen'){
			$dosen=mysqli_query($con,"SELECT * from dosen where nip=".$_SESSION['sip_masuk_aja']);
			$rDosen=$dosen->fetch_object();
			$status=$_SESSION['sip_status'];
		}elseif($_SESSION['sip_status']=='laboratorium'){
			$laboratorium=mysqli_query($con,"SELECT * from lab where idlab=".$_SESSION['sip_masuk_aja']);
			$rLaboratorium=$laboratorium->fetch_object();
			$status=$_SESSION['sip_status'];
		}
	}
	
	function base_url($string=''){
		return BASE_URL.$string;
	}
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
?>
