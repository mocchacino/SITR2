<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			$namaPembimbing3=test_input($_POST['namaPembimbing3']);
			$nipPembimbing3=test_input($_POST['nipPembimbing3']);
			$namaInstansi=test_input($_POST['namaInstansi']);
			$alamatPembimbing3=test_input($_POST['alamatPembimbing3']);
			$noTelpPembimbing3=test_input($_POST['noTelpPembimbing3']);
			$emailPembimbing3=test_input($_POST['emailPembimbing3']);

			if(isset($_POST['submit'])){
				if($namaPembimbing3!=''){
					$validNama=true;
				}else{
					$errorNamaPembimbing3='Nama harus diisi';
					$validNama=false;
				}

				if($nipPembimbing3!=''){
					$validNip=true;
				}else{
					$errorNipPembimbing3='NIP harus diisi';
					$validNip=false;
				}

				if($namaInstansi!=''){
					$validInstansi=true;
				}else{
					$errorNamaInstansi='Nama Instansi harus diisi';
					$validInstansi=false;
				}

				if($noTelpPembimbing3!=''){
					if(is_numeric($noTelpPembimbing3)){
						$validNoTelp=true;
					}else{
						$errorNoTelpPembimbing3='No Telp hanya berisi angka';
						$validNoTelp=false; 
					}
				}else{
					$errorNoTelpPembimbing3='No Telp harus diisi';
					$validNoTelp=false;
				}

				if($alamatPembimbing3!=''){
					$validAlamat=true;
				}else{
					$errorAlamatPembimbing3='Alamat harus diisi';
					$validAlamat=false;
				}

				if($emailPembimbing3!=''){
					$validEmail=true;
				}else{
					$errorEmailPembimbing3='E-mail harus diisi';
					$validEmail=false;
				}

				if($validNama && $validNip && $validInstansi && $validAlamat && $validNoTelp && $validEmail){
					$tambahPembimbingLuar = mysqli_query($con,"INSERT INTO pembimbing_luar SET nip='".$nipPembimbing3."', nama='".$namaPembimbing3."', instansi='".$namaInstansi."', no_telp='".$noTelpPembimbing3."', email='".$emailPembimbing3."', alamat='".$alamatPembimbing3."' ");
					if ($tambahPembimbingLuar) {
						$sukses=TRUE;
						$pesan_sukses="Berhasil menambahkan data.";	
					}else{
						$sukses=FALSE;
						$pesan_gagal="Harap lengkapi form yang ditandai";
					}
				}
			}
		}
?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Form Pendaftaran TR II</title>
			<script type="text/javascript">
			</script>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Tambah Pembimbing Luar
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
									<?php
										if ($sukses){
											?>
											<span class="label label-success"><?php if(isset($pesan_sukses)) echo $pesan_sukses;?></span>
											<?php
										} if (!$sukses){
											?>
											<span class="label label-danger"><?php if(isset($pesan_gagal)) echo $pesan_gagal;?></span>
											<?php
										}
									?>
									<div class="form-group">
										<label>NIP</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNipPembimbing3)) echo $errorNipPembimbing3;?></span>
										<input type="text" class="form-control" id="nipPembimbing3" name="nipPembimbing3" placeholder="contoh: 1234567890" maxlength="18" required autofocus value="<?php if(!$sukses&&$validNip){echo $nipPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNamaPembimbing3)) echo $errorNamaPembimbing3;?></span>
										<input type="text" class="form-control" id="namaPembimbing3" name="namaPembimbing3" placeholder="contoh: Hadi Kusuma, S.Kom" maxlength="30" required autofocus value="<?php if(!$sukses&&$validNama){echo $namaPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<label>Nama Instansi</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNamaInstansi)) echo $errorNamaInstansi;?></span>
										<input type="text" class="form-control" id="namaInstansi" name="namaInstansi" placeholder="contoh: Universitas A" maxlength="30" required autofocus value="<?php if(!$sukses&&$validInstansi){echo $namaInstansi;} ?>">
									</div>
									<div class="form-group">
										<label>Alamat</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorAlamatPembimbing3)) echo $errorAlamatPembimbing3;?></span>
										<input type="text" class="form-control" id="alamatPembimbing3" name="alamatPembimbing3" placeholder="contoh: Jalan Jend. Sudirman No.9" maxlength="100" required autofocus value="<?php if(!$sukses&&$validAlamat){echo $alamatPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<label>Nomor Telepon</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNoTelpPembimbing3)) echo $errorNoTelpPembimbing3;?></span>
										<input type="tel" class="form-control" id="noTelpPembimbing3" name="noTelpPembimbing3" pattern="[0-9]{10,}" title="Nomor Telepon" placeholder="contoh: 081234567890" maxlength="20" required autofocus value="<?php if(!$sukses&&$validNoTelp){echo $noTelpPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmailPembimbing3)) echo $errorEmailPembimbing3;?></span>
										<input type="email" class="form-control" id="emailPembimbing3" name="emailPembimbing3" placeholder="contoh: hadi@gmail.com" maxlength="50" required autofocus value="<?php if(!$sukses&&$validEmail){echo $emailPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<input class="form-control btn btn-primary" type="submit" name="submit" value="Tambahkan Pembimbing Luar"/>
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



