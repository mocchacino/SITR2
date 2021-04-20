<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'mahasiswa'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		if(!isset($_POST['edit'])){
			$show_detail=mysqli_query($con, "SELECT * FROM mahasiswa where nim='".$_SESSION['sip_masuk_aja']."' ");	
			while($rshow_detail=$show_detail->fetch_object()){
				$nama=$rshow_detail->nama;
	 			$noTelp=$rshow_detail->no_telp;
	 			$alamat=$rshow_detail->alamat;
	 			$email=$rshow_detail->email;
	 			$kotaLahir=$rshow_detail->tempat_lahir;
	 			$tanggalLahir=$rshow_detail->tgl_lahir;
	 			
	 		}
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$nama=test_input($_POST['nama']);
					$noTelp=test_input($_POST['noTelp']);
					$alamat=test_input($_POST['alamat']);
					$email=test_input($_POST['email']);
					$kotaLahir=test_input($_POST['kotaLahir']);
					$tanggalLahir=test_input($_POST['tanggalLahir']);
					
					if($nama!=''){
						$validNama=true;
					}else{
						$errorNama='Nama harus diisi';
						$validNama=false;
					}

					if($noTelp!=''){
						if(is_numeric($noTelp)){
							$validNoTelp=true;
						}else{
							$errorNoTelp='Nomor Telepon hanya berisi angka';
							$validNoTelp=false; 
						}
					}else{
						$errorNoTelp='Nomor Telepon harus diisi';
						$validNoTelp=false;
					}

					if($alamat!=''){
						$validAlamat=true;
					}else{
						$errorAlamat='Alamat harus diisi';
						$validAlamat=false;
					}

					if($email!=''){
						$validEmail=true;
					}else{
						$errorEmail='E-mail harus diisi';
						$validEmail=false;
					}

					if($kotaLahir!=''){
						$validKotaLahir=true;
					}else{
						$errorKotaLahir='wajib diisi';
						$validKotaLahir=false;
					}

					if($tanggalLahir!=''){
						$validTanggalLahir=true;
					}else{
						$errorTanggalLahir='wajib diisi';
						$validTanggalLahir=false;
					}

					
					if($validNama && $validKotaLahir && $validTanggalLahir && $validAlamat && $validNoTelp && $validEmail){
				  		$edit_profil = mysqli_query($con,"UPDATE mahasiswa SET nama='".$nama."', no_telp='".$noTelp."', email='".$email."', alamat='".$alamat."', tempat_lahir='".$kotaLahir."', tgl_lahir='".$tanggalLahir."' where nim='".$_SESSION['sip_masuk_aja']."' ");
						
						if($edit_profil){
							$sukses=TRUE;
							$pesan_sukses="Berhasil mengubah data.";	
						}else{
							$sukses=false;
							$pesan_gagal="Tidak berhasil mengubah data.";	
						}
					}else{
						$sukses=FALSE;
						$pesan_gagal="Data yang diinputkan tidak valid";
					}

				}
			}
		}
		

?>
<!DOCTYPE html>
<html>
<head>
	<title>Profil</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="row">
		<div class="col-md-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Profil
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
									<?php
										if ($sukses && isset($pesan_sukses)){
											?>
											<div class="alert alert-success"><?php echo $pesan_sukses;?></div>
											<?php
										} if (!$sukses && isset($pesan_gagal)){
											?>
											<div class="alert alert-danger"><?php echo $pesan_gagal;?></div>
											<?php
										}
									?>
									<div class="form-group">
										<label>NIM</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNim)) echo $errorNim;?></span>
										<input type="text" class="form-control" id="nim" name="nim" placeholder="contoh: 24010314120014" maxlength="14" readonly required autofocus value="<?php echo $_SESSION['sip_masuk_aja'];?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNama)) echo $errorNama;?></span>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Wiladhianty Yulianova" maxlength="30" required autofocus value="<?php if(isset($nama)){echo $nama;}?>">
									</div>
									<div class="form-group">
										<label>Alamat</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorAlamat)) echo $errorAlamat;?></span>
										<input type="text" class="form-control" id="alamat" name="alamat" placeholder="contoh: Jalan Jend. Sudirman No.9" maxlength="100" required autofocus value="<?php if(isset($alamat)){echo $alamat;}?>">
									</div>
									<div class="form-group">
										<label>Kota Lahir</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorKotaLahir)) echo $errorAlamat;?></span>
										<input type="text" class="form-control" id="kotaLahir" name="kotaLahir" placeholder="contoh: Jakarta" maxlength="20" required autofocus value="<?php if(isset($kotaLahir)){echo $kotaLahir;}?>">
									</div>
									<div class="form-group">
										<label>Tanggal Lahir</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorTanggalLahir)) echo $errorTanggalLahir;?></span>
										<input type="date" class="form-control" id="tanggalLahir" name="tanggalLahir" required autofocus value="<?php if(isset($tanggalLahir)){echo $tanggalLahir;}?>">
									</div>
									<div class="form-group">
										<label>Nomor Telepon</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNoTelp)) echo $errorNoTelp;?></span>
										<input type="tel" class="form-control" id="noTelp" name="noTelp" pattern="[0-9]{10,}" title="Nomor Telepon" placeholder="contoh: 081234567890" maxlength="20" required autofocus value="<?php if(isset($noTelp)){echo $noTelp;}?>">
									</div>
									<div class="form-group">
										<label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmail)) echo $errorEmail;?></span>
										<input type="email" class="form-control" id="email" name="email" placeholder="contoh: wila@gmail.com" maxlength="50" required autofocus value="<?php if(isset($email)){echo $email;}?>">
									</div>
									
									
									<div class="form-group">
										<input class="form-control btn btn-primary" type="submit" name="edit" value="Edit"/>
									</div>
								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>