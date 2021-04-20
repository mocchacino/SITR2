<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		if(!isset($_POST['edit'])){
			$idlab=$_GET['idlab'];
		 	$data=mysqli_query($con,"SELECT * from lab where idlab='".$idlab."' ");
	 		while($rData=$data->fetch_object()){
	 			$nama=$rData->nama_lab;
	 			$email=$rData->admin;
	 			$kbk=$rData->nip;
	 		}
	 		$show_kbk = mysqli_query($con, "SELECT * FROM dosen");
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$show_kbk = mysqli_query($con, "SELECT * FROM dosen");
					$saveidlab=test_input($_POST['saveidlab']);
					$idlab=test_input($_POST['idlab']);
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
				  		$edit_pendaftar = mysqli_query($con,"UPDATE lab SET nama_lab='".$nama."', admin='".$email."', nip='".$kbk."' where idlab='$idlab' ");
						
						if($edit_pendaftar){
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
			<title>Form Ubah Laboratorium</title>
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
	                  		echo '<a href="daftar_lab.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>';
	                  	?>
						Ubah Laboratorium
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
									<input type="text" name="saveidlab" hidden value="<?php echo $idlab;?>">
									<input type="text" name="idlab" hidden value="<?php echo $idlab;?>">
									
									<div class="form-group">
										<label>Nama Laboratorium</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNama)) echo $errorNama;?></span>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Kimia Analitik" maxlength="30" required autofocus value="<?php if(isset($nama)){echo $nama;}?>">
									</div>
									<div class="form-group">
										<label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmail)) echo $errorEmail;?></span>
										<input type="email" class="form-control" id="email" name="email" placeholder="contoh: wila@gmail.com" maxlength="50" required autofocus value="<?php if(isset($email)){echo $email;}?>">
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
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>



