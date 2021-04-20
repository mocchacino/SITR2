<?php
  ob_start();
  error_reporting(0);
  
	require_once('connect.php');
	
	$con = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	if(mysqli_connect_errno()){
		die('Could not connect to database : <br/>'.$mysqli_connect_error());
	}

	$site_name="SITR";

	date_default_timezone_set("Asia/Jakarta");
					
	session_start();

	if(isset($_SESSION['user'])){
		
			$q = mysqli_query($con,"SELECT * FROM user JOIN profil ON user.kode_profil = profil.id_profil WHERE user.username = '".$_SESSION['user']."' "); 
			$user=mysqli_fetch_assoc($q);
			$status=$user['status'];
	}
	
	
	
?>
