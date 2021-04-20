<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'laboratorium'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		if(!isset($_POST['edit'])){
			$show_detail=mysqli_query($con, "SELECT * FROM lab where idlab='".$_SESSION['sip_masuk_aja']."' ");	
			while($rshow_detail=$show_detail->fetch_object()){
				$nama=$rshow_detail->nama_lab;
	 			$email=$rshow_detail->admin;
	 			$kbk=$rshow_detail->nip;
	 		}
	 		$show_kbk = mysqli_query($con, "SELECT * FROM dosen");
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$show_kbk = mysqli_query($con, "SELECT * FROM dosen");
					$nama=test_input($_POST['nama']);
					$email=test_input($_POST['email']);
					$kbk=test_input($_POST['kbk']);
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
				  		$edit_profil = mysqli_query($con,"UPDATE lab SET nama_lab='".$nama."', admin='".$email."', nip='".$kbk."' where idlab='".$_SESSION['sip_masuk_aja']."' ");
						
						if($edit_profil){
							$sukses=TRUE;
							$pesan_sukses="Berhasil menambahkan data.";	
						}else{
							$sukses=false;
							$pesan_gagal="Tidak berhasil menambahkan data.";	
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
										<label>Nama</label>&nbsp;
										<input class="form-control" type="text" id="nama" name="nama" value="<?php if(isset($nama)){echo $nama;}?>" >
									</div>
									<div class="form-group">
										<label>Email</label>&nbsp;
										<input class="form-control" type='text' id='email' name="email" autofocus value="<?php if(isset($email)){echo $email;}?>" >
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