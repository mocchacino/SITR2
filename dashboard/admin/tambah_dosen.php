<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			$nama=test_input($_POST['nama']);
			$nip=test_input($_POST['nip']);
			$alamat=test_input($_POST['alamat']);
			$noTelp=test_input($_POST['noTelp']);
			$email=test_input($_POST['email']);
			$idlab=test_input($_POST['idlab']);
			$topik=test_input($_POST['topik']);

			if(isset($_POST['submit'])){
				if($nama!=''){
					$validNama=true;
				}else{
					$errorNama='wajib diisi';
					$validNama=false;
				}

				if($nip!=''){
					$validNip=true;
				}else{
					$errorNip='wajib diisi';
					$validNip=false;
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

				if($validNama && $validNip && $validAlamat && $validNoTelp && $validEmail && $validTopik && $validIdlab){
					$tambahDosen = mysqli_query($con,"INSERT INTO dosen SET nip='".$nip."', nama_dosen='".$nama."', password='".md5('sip'.$nip.'pis')."', no_telp='".$noTelp."', email='".$email."', alamat='".$alamat."', topik='".$topik."', idlab='".$idlab."' ");
					if ($tambahDosen) {
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
			<title>Form Tambah Dosen</title>
			<script type="text/javascript">
			</script>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Tambah Dosen
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
										<label>NIP</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNip)) echo $errorNip;?></span>
										<input type="text" class="form-control" id="nip" name="nip" placeholder="contoh: 24010314120014" maxlength="14" required autofocus value="<?php if(!$sukses&&$validNip){echo $nip;} ?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNama)) echo $errorNama;?></span>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Wiladhianty Yulianova, S.Kom" maxlength="30" required autofocus value="<?php if(!$sukses&&$validNama){echo $nama;} ?>">
									</div>
									<div class="form-group">
										<label>Alamat</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorAlamat)) echo $errorAlamat;?></span>
										<input type="text" class="form-control" id="alamat" name="alamat" placeholder="contoh: Jalan Jend. Sudirman No.9" maxlength="100" required autofocus value="<?php if(!$sukses&&$validAlamat){echo $alamat;} ?>">
									</div>
									<div class="form-group">
										<label>Laboratorium</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIdlab)) echo $errorIdlab;?></span>
										<input class="form-control" list="idlab" name="idlab" placeholder="--Masukkan Laboratorium--" required autofocus value="<?php if(!$sukses&&!$validIdlab){echo $idlab;} ?>">
										<datalist id="idlab">
											<option></option>
											<option value=1>Biokimia</option>
											<option value=2>Kimia Anorganik</option>
											<option value=3>Kimia Organik</option>
											<option value=4>Kimia Fisika</option>
											<option value=5>Kimia Analitik</option>
										</datalist>
									</div>
									<div class="form-group">
										<label>Topik</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorTopik)) echo $errorTopik;?></span>
										<textarea class="form-control" name="topik" id="topik" cols="26" rows="5" maxlength="150" ><?php if(!$sukses&&!$validTopik) echo $topik;?> </textarea>
									</div>
									<div class="form-group">
										<label>Nomor Telepon</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNoTelp)) echo $errorNoTelp;?></span>
										<input type="tel" class="form-control" id="noTelp" name="noTelp" pattern="[0-9]{10,}" title="Nomor Telepon" placeholder="contoh: 081234567890" maxlength="20" required autofocus value="<?php if(!$sukses&&$validNoTelp){echo $noTelp;} ?>">
									</div>
									<div class="form-group">
										<label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmail)) echo $errorEmail;?></span>
										<input type="email" class="form-control" id="email" name="email" placeholder="contoh: wila@gmail.com" maxlength="50" required autofocus value="<?php if(!$sukses&&$validEmail){echo $email;} ?>">
									</div>
									<div class="form-group">
										<input class="form-control btn btn-primary" type="submit" name="submit" value="Submit"/>
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



