<?php
	date_default_timezone_set("Asia/Bangkok"); 
	setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$nim=$_GET['nim'];
		$id=$_GET['id'];

?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Print Surat Permohonan Pengantar</title>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	</head>
	<body>
		<div class="panel-body">
<?php
			echo '<a href="daftar_uji_skripsi.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Mahasiswa Ujian Tugas Akhir</a><br><br>';
 			echo '<div class="well clearfix"><label>Print Form Ujian Sarjana</label>';
 				echo '<form id="catatan" method="POST" role="form" action="form_ujian_sarjana.php?nim='.$nim.'&id='.$id.'" enctype="multipart/form-data">';
 				echo '<button type="submit" class="btn btn-primary pull-right" form="catatan" value="submit"><i class="fa fa-pencil-square-o"></i> Submit</button><br>';
				echo '<div class="form-group">Catatan:&nbsp;';
				echo '<textarea class="form-control" name="catatan" id="catatan" cols="26" rows="5" maxlength="150" placeholder="Isi Catatan ini untuk kolom catatan yang ada di dalam Form Ujian Sarjana " ></textarea>';
				echo '</form></div>';
			echo '</div>';

			echo '<div class="well clearfix"><label>Print Berita Acara Ujian Sarjana</label>';
				echo '<a href="berita_acara_ujian_sarjana.php?nim='.$nim.'&id='.$id.'" role="button" class="btn btn-primary pull-right"><i class="fa fa-print"></i> | Print</a>';
			echo '</div>';
			echo '<div class="well clearfix"><label>Print Lembar Penilaian Sidang Tugas Riset</label>';
				echo '<a href="lembar_penilaian_sidang_tr.php?nim='.$nim.'&id='.$id.'" role="button" class="btn btn-primary pull-right"><i class="fa fa-print"></i> | Print</a>';
			echo '</div>';
			echo '<div class="well clearfix"><label>Print Formulir Review</label>';
				echo '<a href="formulir_review.php?nim='.$nim.'&id='.$id.'" role="button" class="btn btn-primary pull-right"><i class="fa fa-print"></i> | Print</a>';
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