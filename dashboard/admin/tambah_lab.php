<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		$show_kbk = mysqli_query($con, "SELECT * FROM dosen");

		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			$nama=test_input($_POST['nama']);
			$email=test_input($_POST['email']);
			$kbk=test_input($_POST['kbk']);
			if(isset($_POST['submit'])){
				if($nama!=''){
					$validNama=true;
				}else{
					$errorNama='wajib diisi';
					$validNama=false;
				}

				if($email!=''){
					$validEmail=true;
				}else{
					$errorEmail='wajib diisi';
					$validEmail=false;
				}

				if($kbk!=''){
					$cek_kbk = mysqli_query($con, "SELECT * FROM dosen where nip = '$kbk' ");
					$rcek_kbk = $cek_kbk->fetch_object();
					if($rcek_kbk != '') $validKbk=true;
					else{
						$errorKbk='Dosen tidak terdaftar';
						$validKbk=false;
					}
				}else{
					$errorKbk='wajib diisi';
					$validKbk=false;
				}

				if($validNama && $validEmail && $validKbk){
					$tambahAdmin = mysqli_query($con,"INSERT INTO lab SET nama_lab='".$nama."', password='".md5('sip'.$email.'pis')."', admin='".$email."', nip='".$kbk."' ");
					if ($tambahAdmin) {
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
						Tambah Laboratorium
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
										<label>Nama Laboratorium</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNama)) echo $errorNama;?></span>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Kimia Analitik" maxlength="30" required autofocus value="<?php if(!$sukses&&$validNama){echo $nama;} ?>">
									</div>
									<div class="form-group">
										<label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmail)) echo $errorEmail;?></span>
										<input type="email" class="form-control" id="email" name="email" placeholder="contoh: wila@gmail.com" maxlength="50" required autofocus value="<?php if(!$sukses&&$validEmail){echo $email;} ?>">
									</div>
									<div class="form-group">
										<label>KBK</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorKbk)) echo $errorKbk;?></span>
										<input class="form-control" list="list_kbk" name="kbk" placeholder="--Masukkan Nama KBK--" value="<?php if(isset($kbk)) {echo $kbk;} ?>" required autofocus >
										<datalist id="list_kbk">
										<option></option>
										<?php
											while($rshow_kbk=$show_kbk->fetch_assoc()){ ?>
													<option value="<?php echo $rshow_kbk['nip'];?>"> <?php echo $rshow_kbk['nama_dosen'];?>
													</option>
												<?php 
											}
										?>
										</datalist>
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



