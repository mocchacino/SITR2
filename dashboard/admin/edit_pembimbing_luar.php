<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			$nipLama=$_GET['nip'];
		 	if($nipLama==''){
		 		header('Location:../daftar_pembimbing_luar.php');
		 	}else{
		 		$data=mysqli_query($con,"SELECT * from pembimbing_luar where nip='".$nipLama."' ");
		 		while($rData=$data->fetch_object()){
		 			$nip=$rData->nip;
		 			$nama=$rData->nama;
		 			$namaInstansi=$rData->instansi;
		 			$noTelpPembimbing3=$rData->no_telp;
		 			$emailPembimbing3=$rData->email;
		 			$alamatPembimbing3=$rData->alamat;
		 		}
		 	}
		}
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			if (isset($_POST['edit'])) {
				$nip=test_input($_POST['nip']);
				$nama=test_input($_POST['nama']);
				$namaPembimbing3=test_input($_POST['namaPembimbing3']);
				$nipPembimbing3=test_input($_POST['nipPembimbing3']);
				$namaInstansi=test_input($_POST['namaInstansi']);
				$alamatPembimbing3=test_input($_POST['alamatPembimbing3']);
				$noTelpPembimbing3=test_input($_POST['noTelpPembimbing3']);
				$emailPembimbing3=test_input($_POST['emailPembimbing3']);

				if($nipPembimbing3!=''){
					$validNip=true;
				}else{
					$errorNipPembimbing3='NIP harus diisi';
					$validNip=false;
				}

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
					$editPembimbingLuar = mysqli_query($con,"UPDATE pembimbing_luar SET nip='".$nipPembimbing3."', nama='".$namaPembimbing3."', instansi='".$namaInstansi."', no_telp='".$noTelpPembimbing3."', email='".$emailPembimbing3."', alamat='".$alamatPembimbing3."' where nip='$nip' and nama='$nama' ");
					if ($editPembimbingLuar) {
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
			<title>Edit Pembimbing Luar</title>
			<script type="text/javascript">
			</script>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading ">
						<a href="daftar_pembimbing_luar.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
						Edit Pembimbing Luar
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
									<?php
										if ($sukses && isset($pesan_sukses)){
											echo '<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>';
										} if (!$sukses && isset($pesan_gagal)){
											echo '<div class="alert alert-danger" role="alert">'.$pesan_gagal.'</div>';
										}
									?>
									<input type="text" name="nip" hidden value="<?php echo $nip;?>">
									<input type="text" name="nama" hidden value="<?php echo $nama;?>">
									<div class="form-group">
										<label>NIP</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNipPembimbing3)) echo $errorNipPembimbing3;?></span>
										<input type="text" class="form-control" id="nipPembimbing3" name="nipPembimbing3" placeholder="contoh: 1234567890" maxlength="18" required autofocus value="<?php if(!isset($nipPembimbing3)){echo $nip;}else{echo $nipPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNamaPembimbing3)) echo $errorNamaPembimbing3;?></span>
										<input type="text" class="form-control" id="namaPembimbing3" name="namaPembimbing3" placeholder="contoh: Hadi Kusuma, S.Kom" maxlength="30" required autofocus value="<?php if(!isset($namaPembimbing3)){echo $nama;} else{$namaPembimbing3;}?>">
									</div>
									<div class="form-group">
										<label>Nama Instansi</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNamaInstansi)) echo $errorNamaInstansi;?></span>
										<input type="text" class="form-control" id="namaInstansi" name="namaInstansi" placeholder="contoh: Universitas A" maxlength="30" required autofocus value="<?php if(isset($namaInstansi)){echo $namaInstansi;} ?>">
									</div>
									<div class="form-group">
										<label>Alamat</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorAlamatPembimbing3)) echo $errorAlamatPembimbing3;?></span>
										<input type="text" class="form-control" id="alamatPembimbing3" name="alamatPembimbing3" placeholder="contoh: Jalan Jend. Sudirman No.9" maxlength="100" required autofocus value="<?php if(isset($alamatPembimbing3)){echo $alamatPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<label>Nomor Telepon</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNoTelpPembimbing3)) echo $errorNoTelpPembimbing3;?></span>
										<input type="tel" class="form-control" id="noTelpPembimbing3" name="noTelpPembimbing3" pattern="[0-9]{10,}" placeholder="contoh: 081234567890" title="Nomor Telepon" maxlength="20" required autofocus value="<?php if(isset($noTelpPembimbing3)){echo $noTelpPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmailPembimbing3)) echo $errorEmailPembimbing3;?></span>
										<input type="email" class="form-control" id="emailPembimbing3" name="emailPembimbing3" placeholder="contoh: hadi@gmail.com" maxlength="50" required autofocus value="<?php if(isset($emailPembimbing3)){echo $emailPembimbing3;} ?>">
									</div>
									<div class="form-group">
										<input class="form-control btn btn-primary" type="submit" name="edit" value="Edit Pembimbing Luar"/>
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