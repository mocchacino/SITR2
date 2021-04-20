<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			$nama=test_input($_POST['nama']);
			$email=test_input($_POST['email']);

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

				if($validNama && $validEmail){
					$tambahAdmin = mysqli_query($con,"INSERT INTO petugas SET nama='".$nama."', password='".md5('sip'.$email.'pis')."', email='".$email."' ");
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
						Tambah Admin
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
										<label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNama)) echo $errorNama;?></span>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Wiladhianty Yulianova, S.Kom" maxlength="30" required autofocus value="<?php if(!$sukses&&$validNama){echo $nama;} ?>">
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



