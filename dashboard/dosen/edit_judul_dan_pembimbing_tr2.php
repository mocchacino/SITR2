<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);
		if ($_SERVER['REQUEST_METHOD']==='GET'){
			$nim=$_GET['nim'];
			$data=mysqli_query($con,"SELECT * from daftar_tugas_riset2 inner join mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim where daftar_tugas_riset2.nim='".$nim."' ");
		 		while($rData=$data->fetch_object()){
		 			$nim=$rData->nim;
		 			$nama=$rData->nama;
		 			$pembimbing1=$rData->nip1;
		 			$pembimbing2=$rData->nip2;
		 			$pembimbing3=$rData->nip3;
		 			$judul=$rData->judul;
		 			$komulatif=$rData->sks_komulatif;
		 			$sks=$rData->sks_semester;
		 			$ipk=$rData->ipk;
		 			$semester=$rData->semester; 
		 		}
		 		$qpembimbing1=mysqli_query($con,"SELECT distinct nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where idlab=idlab_tr1");
		 		$qpembimbing2=mysqli_query($con,"SELECT distinct nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab");
				$qpembimbing3=mysqli_query($con,"SELECT distinct nip,nama FROM pembimbing_luar");
		}
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['edit'])) {
				$savenim=test_input($_POST['savenim']);
				$nim=test_input($_POST['nim']);
				$data=mysqli_query($con,"SELECT * from daftar_tugas_riset2 inner join mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim where daftar_tugas_riset2.nim='".$nim."' ");
				while($rData=$data->fetch_object()){
		 			$nim=$rData->nim;
		 			$nama=$rData->nama;
		 			$komulatif=$rData->sks_komulatif;
		 			$sks=$rData->sks_semester;
		 			$ipk=$rData->ipk;
		 			$semester=$rData->semester; 
		 		}
				$qpembimbing1=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where idlab=idlab_tr1");
		 		$qpembimbing2=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab");
				$qpembimbing3=mysqli_query($con,"SELECT nip,nama FROM pembimbing_luar");
				$pembimbing1=test_input($_POST['pembimbing1']);
				if ($pembimbing1=='') {
					$validPembimbing1=false;
					$errorPembimbing1='wajib diisi';
				}else{
					$cekPembimbing1=mysqli_num_rows(mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where idlab=idlab_tr1 and nip='".$pembimbing1."' "));
					if($cekPembimbing1==0){
						$validPembimbing1=false;
						$errorPembimbing1='Dosen tidak terdaftar';
					}else{
						$validPembimbing1=true;
					}
				}

				$pembimbing2=test_input($_POST['pembimbing2']);
				if ($pembimbing2!='') {
					$cekPembimbing2=mysqli_num_rows(mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where idlab=idlab_tr1 and nip='".$pembimbing2."' "));
					if($cekPembimbing2==0){
						$validPembimbing2=false;
						$errorPembimbing2='Dosen tidak terdaftar';
					}else{
						$validPembimbing2=true;
					}
				}else $validPembimbing2=true;

				$pembimbing3=test_input($_POST['pembimbing3']);
				if($pembimbing3!=''){
					$cekPembimbing3=mysqli_num_rows(mysqli_query($con,"SELECT nip from pembimbing_luar where nip='".$pembimbing3."' "));
					if($cekPembimbing3==0){
						$errorPembimbing3='Dosen/Pembimbing belum terdaftar';
						$validPembimbing3 =false;
					}else{
						$validPembimbing3 =true;
					}
				}else $validPembimbing3 =true;

				$judul=test_input($_POST['judul']);
				if($judul==''){
					$validJudul=false;
					$errorJudul='wajib diisi';
				}else{
					$validJudul=true;
				}
						
				
				if($validPembimbing1 && $validPembimbing2 && $validPembimbing3 && $validJudul){
						$edit_pendaftar = mysqli_query($con,"UPDATE daftar_tugas_riset2 SET nip1='".$pembimbing1."',nip2='".$pembimbing2."',nip3='".$pembimbing3."',judul='".$judul."' where  nim = '".$nim."' " );
						
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
			
			

			
			
?>

		<!DOCTYPE html>
		<html>
		<head>
			<title>Edit Mahasiswa TR II</title>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<a href="dosen_daftar_tr2.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
						Edit Judul dan Pembimbing Mahasiswa TR II
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
									<?php
										if ($sukses && isset($pesan_sukses)){
											echo '<div class="alert alert-success" role="alert">';
											echo $pesan_sukses;
											echo '</div>';
										}if (!$sukses && isset($pesan_gagal)){
											echo '<div class="alert alert-danger" role="alert">';
											echo $pesan_gagal;
											echo '</div>';
										}
									?>
									<input type="text" name="savenim" hidden value="<?php echo $nim;?>">
									<div class="form-group">
										<label>NIM</label>&nbsp;
										<input class="form-control" type='text' id='input_nim' name="nim" value="<?php if(isset($nim)){echo $nim;}else $savenim; ?>" readonly autofocus">
										
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;
										<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php if(isset($nama))echo $nama; ?>" readonly autofocus">
									</div>
									<div class="form-group">
										<label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPembimbing1)) echo $errorPembimbing1;?></span>
										<input class="form-control" list="pembimbing1" id='input_pembimbing1' name="pembimbing1" placeholder="--Masukkan NIP--" required autofocus value="<?php if(isset($pembimbing1)){echo $pembimbing1;}?>">
										<datalist id="pembimbing1">
										<option></option>
										<?php
											while($rPembimbing1=$qpembimbing1->fetch_object()){ ?>
													<option value="<?php echo $rPembimbing1->nip;?>"> <?php echo $rPembimbing1->nama_dosen;?>
													</option>
												<?php 
											}
										?>
										</datalist>
									</div>
									<div class="form-group">
										<label>Pembimbing 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPembimbing2)) echo $errorPembimbing2;?></span>
										<input class="form-control" list="pembimbing2" id='input_pembimbing2' name="pembimbing2" placeholder="--Masukkan NIP--" autofocus value="<?php if(isset($pembimbing2)){echo $pembimbing2;}?>" >
										<datalist id="pembimbing2">
										<option></option>
										<?php
											while($rPembimbing2=$qpembimbing2->fetch_object()){ ?>
													<option value="<?php echo $rPembimbing2->nip;?>"> <?php echo $rPembimbing2->nama_dosen;?>
													</option>
												<?php 
											}
										?>
										</datalist>
									</div>
									<div class="form-group">
										<label>Pembimbing 3</label>&nbsp;<span class="label label-warning"><?php if(isset($errorPembimbing3)) echo $errorPembimbing3;?></span>
										<input class="form-control" list="pembimbing3" id='input_pembimbing3' name="pembimbing3" placeholder="--Masukkan NIP--" autofocus value="<?php if(isset($pembimbing3)){echo $pembimbing3;}?>" >
										<datalist id="pembimbing3">
										<option></option>
										<?php
											while($rPembimbing3=$qpembimbing3->fetch_object()){ ?>
													<option value="<?php echo $rPembimbing3->nip;?>"> <?php echo $rPembimbing3->nama;?>
													</option>
												<?php 
											}
										?>
										</datalist>
									</div>
									<div class="form-group">
										<label>Judul Tugas Riset 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorJudul)) echo $errorJudul;?></span>
										<textarea class="form-control" name="judul" id="judul" placeholder="masukan judul skripsi" cols="26" rows="5" required maxlength="150"><?php if(isset($judul)){echo $judul;}?></textarea>
									</div>
									<div class="form-group">
										<label>Komulatif</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKomulatif)) echo $errorKomulatif;?></span>
										<input class="form-control" type="text" name="komulatif" readonly value="<?php if(isset($komulatif)){echo $komulatif;}?>">
									</div>
									<div class="form-group">
										<label>Jumlah SKS Semester Berjalan</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorSks)) echo $errorSks;?></span>
										<input class="form-control" type="text" name="sks" readonly value="<?php if(isset($sks)){echo $sks;}?>">
									</div>
									<div class="form-group">
										<label>Semester</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorSemester)) echo $errorSemester;?></span>
										<input class="form-control" type="text" name="semester" readonly value="<?php if(isset($semester)){echo $semester;}?>">
									</div>
									<div class="form-group">
										<label>IPK</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIpk)) echo $errorIpk;?></span>
										<input class="form-control" type="text" name="ipk" readonly value="<?php if(isset($ipk)){echo $ipk;}?>">
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