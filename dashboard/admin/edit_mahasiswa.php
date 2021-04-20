<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		if(!isset($_POST['edit'])){
			$nim=$_GET['nim'];
		 	$data=mysqli_query($con,"SELECT * from mahasiswa where nim='".$nim."' ");
	 		while($rData=$data->fetch_object()){
	 			$nama=$rData->nama;
	 			$alamat=$rData->alamat;
	 			$kotaLahir=$rData->tempat_lahir;
	 			$tanggalLahir=$rData->tgl_lahir;
	 			$email=$rData->email;
	 			$noTelp=$rData->no_telp;
	 		}
	 		$dataTr1 = mysqli_query($con, "SELECT * FROM tr1 where nim = '".$nim."'");
	 		while($rdataTr1=$dataTr1->fetch_object()){
	 			$judul=$rdataTr1->judul;
	 			$idlab=$rdataTr1->idlab_tr1;
	 			$pembimbing1=$rdataTr1->nip1;
	 			$pembimbing2=$rdataTr1->nip2;
	 			$tanggalLulusTR1=$rdataTr1->tgl_lulus;
	 		}
	 		$pembimbing=mysqli_query($con, "SELECT * FROM dosen");	
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$pembimbing=mysqli_query($con, "SELECT * FROM dosen");
					$savenim=test_input($_POST['savenim']);
					$nim=test_input($_POST['nim']);
					$nama=test_input($_POST['nama']);
					$noTelp=test_input($_POST['noTelp']);
					$alamat=test_input($_POST['alamat']);
					$email=test_input($_POST['email']);
					$kotaLahir=test_input($_POST['kotaLahir']);
					$tanggalLahir=test_input($_POST['tanggalLahir']);
					$tanggalLulusTR1=test_input($_POST['tanggalLulusTR1']);
					$idlab=test_input($_POST['idlab']);
					$judul=test_input($_POST['judul']);
					$pembimbing1=test_input($_POST['pembimbing1']);
					$pembimbing2=test_input($_POST['pembimbing2']);

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

					if($judul!=''){
						$validJudul=true;
					}else{
						$errorJudul='wajib diisi';
						$validJudul=false;
					}

					if($idlab!=''){
						$validIdlab=true;
					}else{
						$errorIdlab='wajib diisi';
						$validIdlab=false;
					}

					if($pembimbing1!=''){
						$validPembimbing1=true;
					}else{
						$errorPembimbing1='wajib diisi';
						$validPembimbing1=false;
					}

					if($pembimbing2!=''){
						$validPembimbing2=true;
					}else{
						$errorPembimbing2='wajib diisi';
						$validPembimbing2=false;
					}

					if($tanggalLulusTR1!=''){
						$validTanggalLulusTR1=true;
					}else{
						$errorTanggalLulusTR1='wajib diisi';
						$validTanggalLulusTR1=false;
					}

					if($validNama && $validKotaLahir && $validTanggalLahir && $validAlamat && $validNoTelp && $validEmail && $validJudul && $validPembimbing1 && $validPembimbing2 && $validIdlab && $validTanggalLulusTR1){
				  		$edit_pendaftar = mysqli_query($con,"UPDATE mahasiswa SET nama='".$nama."', no_telp='".$noTelp."', email='".$email."', alamat='".$alamat."', tempat_lahir='".$kotaLahir."', tgl_lahir='".$tanggalLahir."' where nim='$nim' ");
						$edit_tr1= mysqli_query($con, "UPDATE tr1 SET nip1='".$pembimbing1."', nip2='".$pembimbing2."', judul='".$judul."', tgl_lulus='".$tanggalLulusTR1."', idlab_tr1='".$idlab."' where nim='".$nim."' ");
						if($edit_pendaftar && $edit_tr1){
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
			<title>Form Ubah Mahasiswa</title>
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
	                  	echo '<a href="daftar_mahasiswa.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>';
	                  	?>
						Ubah Mahasiswa
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
									<input type="text" name="savenim" hidden value="<?php echo $nim;?>">
									<div class="form-group">
										<label>NIM</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNim)) echo $errorNim;?></span>
										<input type="text" class="form-control" id="nim" name="nim" placeholder="contoh: 24010314120014" maxlength="14" readonly required autofocus value="<?php if(isset($nim)){echo $nim;}?>">
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
										<label>Judul Tugas Riset 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmail)) echo $errorJudul;?></span>
										<textarea class="form-control" name="judul" placeholder="masukan judul tugas riset 1" cols="26" rows="5" required maxlength="150"><?php if(isset($judul)){echo $judul;}?></textarea>
									</div>
									<div class="form-group">
										<label>Laboratorium</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIdlab)) echo $errorIdlab;?></span>
										<input class="form-control" list="idlab" name="idlab" placeholder="--Masukkan Laboratorium--" required autofocus value="<?php if(isset($idlab)){echo $idlab;}?>">
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
										<label>Tanggal Lulus Tugas Riset 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorTanggalLulusTR1)) echo $errorTanggalLulusTR1;?></span>
										<input type="date" class="form-control" id="tanggalLulusTR1" name="tanggalLulusTR1" required autofocus value="<?php if(isset($tanggalLulusTR1)){echo $tanggalLulusTR1;}?>">
									</div>
									<div class="form-group">
										<label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPembimbing1)) echo $errorPembimbing1;?></span>
										<input class="form-control" list="pembimbing" name="pembimbing1" placeholder="--Masukkan Nama Pembimbing--" value="<?php if(isset($pembimbing1)){echo $pembimbing1;}?>" required autofocus >
										<datalist id="pembimbing">
										<option></option>
										<?php
											while($rPembimbing=$pembimbing->fetch_assoc()){ ?>
													<option value="<?php echo $rPembimbing['nip'];?>"> <?php echo $rPembimbing['nama_dosen'];?>
													</option>
												<?php 
											}
										?>
										</datalist>
									</div>
									<div class="form-group">
										<label>Pembimbing 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPembimbing2)) echo $errorPembimbing2;?></span>
										<input class="form-control" list="pembimbing" name="pembimbing2" placeholder="--Masukkan Nama Pembimbing--" value="<?php if(isset($pembimbing2)){echo $pembimbing2;}?>" required autofocus >
										<datalist id="pembimbing">
										<option></option>
										<?php
											while($rPembimbing=$pembimbing->fetch_assoc()){ ?>
													<option value="<?php echo $rPembimbing['nip'];?>"> <?php echo $rPembimbing['nama_dosen'];?>
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



