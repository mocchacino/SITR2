<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		if(!isset($_POST['edit'])){
			$nip=$_GET['nip'];
		 	$data=mysqli_query($con,"SELECT * from dosen where nip='".$nip."' ");
	 		while($rData=$data->fetch_object()){
	 			$nama=$rData->nama_dosen;
	 			$noTelp=$rData->no_telp;
	 			$alamat=$rData->alamat;
	 			$email=$rData->email;
	 			$topik=$rData->topik;
	 		}
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$savenip=test_input($_POST['savenip']);
					$nip=test_input($_POST['nip']);
					$nama=test_input($_POST['nama']);
					$noTelp=test_input($_POST['noTelp']);
					$alamat=test_input($_POST['alamat']);
					$email=test_input($_POST['email']);
					$topik=test_input($_POST['topik']);
					$idlab=test_input($_POST['idlab']);
					if($nama!=''){
						$validNama=true;
					}else{
						$errorNama='wajib diisi';
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
						$errorNoTelp='wajib diisi';
						$validNoTelp=false;
					}

					if($alamat!=''){
						$validAlamat=true;
					}else{
						$errorAlamat='wajib diisi';
						$validAlamat=false;
					}

					if($email!=''){
						$validEmail=true;
					}else{
						$errorEmail='wajib diisi';
						$validEmail=false;
					}

					if($topik!=''){
						$validTopik=true;
					}else{
						$errorTopik='wajib diisi';
						$validTopik=false;
					}

					if($idlab!=''){
						$validIdlab=true;
					}else{
						$errorIdlab='wajib diisi';
						$validIdlab=false;
					}

					if($validNama && $validAlamat && $validNoTelp && $validEmail && $validTopik && $validIdlab){
				  		$edit_pendaftar = mysqli_query($con,"UPDATE dosen SET nama_dosen='".$nama."', no_telp='".$noTelp."', email='".$email."', alamat='".$alamat."', topik='".$topik."', idlab='".$idlab."' where nip='$nip' ");
						
						if($edit_pendaftar){
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
			<title>Form Ubah Dosen</title>
			<script type="text/javascript">
			</script>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php
	                  		echo '<a href="daftar_dosen.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>';
	                  	?>
						Ubah Dosen
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
									<input type="text" name="savenip" hidden value="<?php echo $nip;?>">
									<div class="form-group">
										<label>NIP</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNip)) echo $errorNip;?></span>
										<input type="text" class="form-control" id="nip" name="nip" placeholder="contoh: 24010314120014" maxlength="14" readonly autofocus value="<?php if(isset($nip)){echo $nip;}?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNama)) echo $errorNama;?></span>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Wiladhianty Yulianova, S.Kom" maxlength="30" required autofocus value="<?php if(isset($nama)){echo $nama;}?>">
									</div>
									<div class="form-group">
										<label>Alamat</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorAlamat)) echo $errorAlamat;?></span>
										<input type="text" class="form-control" id="alamat" name="alamat" placeholder="contoh: Jalan Jend. Sudirman No.9" maxlength="100" required autofocus value="<?php if(isset($alamat)){echo $alamat;}?>">
									</div>
									<div class="form-group">
										<label>Laboratorium</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIdlab)) echo $errorIdlab;?></span>
										<input class="form-control" list="idlab" name="idlab" placeholder="--Masukkan Laboratorium--" required autofocus value="<?php if(isset($idlab)){echo $idlab;}?>">
										<datalist id="idlab">
											<option></option>
											<option value=1> Biokimia </option>
											<option value=2> Kimia Anorganik </option>
											<option value=3> Kimia Organik </option>
											<option value=4> Kimia Fisika </option>
											<option value=5> Kimia Analitik </option>
										</datalist>
									</div>
									<div class="form-group">
										<label>Topik</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorTopik)) echo $errorTopik;?></span>
										<textarea class="form-control" name="topik" id="topik" cols="26" rows="5" maxlength="150" ><?php if(isset($topik)){echo $topik;}?> </textarea>
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
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>



