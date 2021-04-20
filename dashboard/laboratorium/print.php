<?php
	date_default_timezone_set("Asia/Jakarta"); 
	setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'laboratorium'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$nim=$_GET['nim'];
		$id=$_GET['id'];

?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Print</title>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	</head>
	<body>
		<div class="panel-body">
<?php
			echo '<a href="lab_daftar_uji_kelayakan.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Mahasiswa Uji Kelayakan</a><br><br>';
			echo '<div class="well clearfix"><label>Print Berita Acara Seminar Kelayakan Skripsi</label>';
				echo '<a href="berita_acara_ujian_sarjana.php?nim='.$nim.'&id='.$id.'" role="button" class="btn btn-primary pull-right"><i class="fa fa-print"></i> | Print</a>';
			echo '</div>';
			echo '<div class="well clearfix"><label>Print Lembar Penilaian Seminar Kelayakan Skripsi</label>';
				echo '<a href="lembar_penilaian.php?nim='.$nim.'&id='.$id.'" role="button" class="btn btn-primary pull-right"><i class="fa fa-print"></i> | Print</a>';
			echo '</div>';
?>
			
		</div>
	</body>

    
<?php
    include_once('../footer.php');
    $con->close();
  }else{
    header("Location:./");
  }
?>