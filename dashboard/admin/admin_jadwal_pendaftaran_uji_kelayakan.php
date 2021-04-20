<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		

	$sukses=TRUE;



	// eksekusi
	if(isset($_POST['submit'])){
		
		// Cek Nama
		$jadwal_buka=test_input($_POST['jadwal_buka']);
		if ($jadwal_buka=='') {
			$errorjadwal_buka='wajib diisi';
			$validjadwal_buka=FALSE;
		}else{
			$validjadwal_buka=TRUE;
		}
		$jadwal_tutup=test_input($_POST['jadwal_tutup']);
		if ($jadwal_tutup=='') {
			$errorjadwal_tutup='wajib diisi';
			$validjadwal_tutup=FALSE;
		}else{
			if($jadwal_tutup > $jadwal_buka) $validjadwal_tutup=TRUE;
			else{
				$errorjadwal_tutup='jadwal tutup harus lebih dari jadwal buka';
				$validjadwal_tutup=FALSE;
			}
		}
		$min_ipk=test_input($_POST['min_ipk']);
		if($min_ipk == ''){
			$erromin_ipk='wajib diisi';
			$validmin_ipk= false;
		}else{
			if(is_numeric($min_ipk)){
				$min_ipk=floatval($min_ipk);
				if(($min_ipk>=0)&&($min_ipk<=4)){
					$validmin_ipk=true;
				}else{
					$erromin_ipk='nilai IPK tidak valid';
					$validmin_ipk=false;
				}
			}else{
				$erromin_ipk='hanya angka dan menggunakan titik(.) untuk bilangan desimal';
				$validmin_ipk=false;
			}
		}
		// jika tidak ada kesalahan input
		if ($validjadwal_buka && $validjadwal_tutup && $validmin_ipk) {
			$jadwal_buka=$con->real_escape_string($jadwal_buka);
			$jadwal_tutup=$con->real_escape_string($jadwal_tutup);
			$min_ipk=$con->real_escape_string($min_ipk);
			$insert_jadwal=mysqli_query($con,"UPDATE waktu SET awal='$jadwal_buka', akhir='$jadwal_tutup', syarat_ipk='$min_ipk' where nama='UK' ");
			if (!$insert_jadwal) {
				die("Tidak dapat menjalankan query database: <br>".$con->error);
			}else{
				$sukses=TRUE;
				$pesan_sukses="Berhasil menambah Jadwal";
			}
		}
		else{
			$sukses=FALSE;
			$pesan_sukses="Tidak dapat menambah Jadwal";
		}
		
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Jadwal Pendaftaran Uji Kelayakan</title>
	
</head>
<body>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				Jadwal Pendaftaran Uji Kelayakan
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<div class="col-md-12 col-sm-12 col-xs-12">
							<?php
								if ($sukses && isset($pesan_sukses)){
									echo '<div class="alert alert-success" role="alert">';echo $pesan_sukses;
									echo '</div>';
								} if (!$sukses && isset($pesan_gagal)){
									echo '<div class="alert alert-danger" role="alert">';
									echo $pesan_gagal;
									echo '</div>';
								}
								
							?>
							</div>
							<div class="col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Jadwal dibuka</label>&nbsp;<span class="label label-warning">*<?php if(!$sukses&&(isset($errorjadwal_buka))){echo $errorjadwal_buka;} ?></span>
									<input class="form-control" type="date" name="jadwal_buka" required autofocus>
								</div>
							</div>

							<div class="col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Jadwal ditutup</label>&nbsp;<span class="label label-warning">*<?php if(!$sukses&&(isset($errorjadwal_tutup))){echo $errorjadwal_tutup;} ?></span>
									<input class="form-control" type="date" name="jadwal_tutup" required autofocus>
								</div>
							</div>

							<div class="col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Syarat Minimal IPK</label>&nbsp;<span class="label label-warning">*<?php if(!$sukses&&(isset($erromin_ipk))){echo $erromin_ipk;} ?></span>
									<input class="form-control" type="text" name="min_ipk" required autofocus placeholder="contoh: 2.725" >
								</div>
							</div>
							
							<div class="col-md-12 col-sm-12 col-xs-12">			
								<div class="form-group">
									<input class="form-control btn-primary" type="submit" name="submit" value="Submit Jadwal" />
								</div>
							</div>
						</form>
					</div>
				</div>
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