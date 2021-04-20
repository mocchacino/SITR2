<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'laboratorium'){
		include_once('../sidebar.php');
		switch ($idlab) {
			case '1':
				$namaLab='BIOKIMIA';
				break;
			case '2':
				$namaLab='KIMIA_ANALITIK';
				break;
			case '3':
				$namaLab='KIMIA_ANORGANIK';
				break;
			case '4':
				$namaLab='KIMIA_FISIK';
				break;
			case '5':
				$namaLab='KIMIA_ORGANIK';
				break;
			default:
				header("Location:../login/login.php");
				break;
		}

		$sukses=TRUE;
		if(!isset($_POST['submit'])){
			$nim=$_GET['nim'];
			$id=$_GET['id'];
			if(($nim=='') && ($id=='')){
		 		header('Location:./admin_daftar_uji_kelayakan.php');
		 	}else{
		 		$show_pendaftar_uji_kelayakan=mysqli_query($con, "SELECT * FROM daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim INNER JOIN daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where daftar_uji_kelayakan.id_daftar_uji_kelayakan='$id' and daftar_uji_kelayakan.nim = '$nim' and tr1.idlab_tr1='$idlab'");
				$fshow_pendaftar_uji_kelayakan=$show_pendaftar_uji_kelayakan->fetch_object();

				//daftar pembimbing
				$nama_pembimbing1=mysqli_query($con,"SELECT * FROM daftar_tugas_riset2 inner join dosen on dosen.nip=daftar_tugas_riset2.nip1 where daftar_tugas_riset2.nim='$nim' ");
				$nama_pembimbing2=mysqli_query($con,"SELECT * FROM daftar_tugas_riset2 inner join dosen on dosen.nip=daftar_tugas_riset2.nip2 where daftar_tugas_riset2.nim='$nim' ");
				$nama_pembimbing3=mysqli_query($con,"SELECT * FROM daftar_tugas_riset2 inner join pembimbing_luar on pembimbing_luar.nip=daftar_tugas_riset2.nip3 where daftar_tugas_riset2.nim='$nim' ");
				$fnama_pembimbing1=$nama_pembimbing1->fetch_object();
				$fnama_pembimbing2=$nama_pembimbing2->fetch_object();
				$fnama_pembimbing3=$nama_pembimbing3->fetch_assoc();

				$counter_penguji_lab=mysqli_query($con,"SELECT count(penguji_kelayakan.nip_penguji_kelayakan) as counter, dosen.nama_dosen, dosen.nip FROM dosen left join penguji_kelayakan on dosen.nip=penguji_kelayakan.nip_penguji_kelayakan where dosen.idlab='$idlab' group by dosen.nip order by counter");
				//cek sudah test
				$cekTesti=mysqli_query($con, "SELECT is_test,jadwal,tempat FROM uji_kelayakan inner join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan where uji_kelayakan.id_uji_kelayakan='$id'");
				$rCekTesti=$cekTesti->fetch_object();
				$cekPenguji=mysqli_query($con, "SELECT nip_penguji_kelayakan as nip FROM uji_kelayakan inner join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan where uji_kelayakan.id_uji_kelayakan='$id'");
				
				$nama='';
				$pembimbing1='';
				$pembimbing2='';
				$pembimbing3='';
				$judul='';
		 	}
			
		}else{
			// eksekusi
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if(isset($_POST['submit'])){
					// Cek Nama
					$nim=test_input($_POST['nim']);
					$savenim=test_input($_POST['savenim']);
					$saveid=test_input($_POST['saveid']);
					$id=test_input($_POST['id']);
					// $nama=$_POST['nama'];
					// $pembimbing1=$_POST['pembimbing1'];
					// $pembimbing2=$_POST['pembimbing2'];
					// $pembimbing3=$_POST['pembimbing3'];
					// $judul=$_POST['judul'];
					$show_pendaftar_uji_kelayakan=mysqli_query($con, "SELECT * FROM daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim INNER JOIN daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where daftar_uji_kelayakan.id_daftar_uji_kelayakan='$id' and daftar_uji_kelayakan.nim = '$nim' ");
					$fshow_pendaftar_uji_kelayakan=$show_pendaftar_uji_kelayakan->fetch_object();
					$nama_pembimbing1=mysqli_query($con,"SELECT * FROM daftar_tugas_riset2 inner join dosen on dosen.nip=daftar_tugas_riset2.nip1 where daftar_tugas_riset2.nim='$nim' ");
					$nama_pembimbing2=mysqli_query($con,"SELECT * FROM daftar_tugas_riset2 inner join dosen on dosen.nip=daftar_tugas_riset2.nip2 where daftar_tugas_riset2.nim='$nim' ");
					$nama_pembimbing3=mysqli_query($con,"SELECT * FROM daftar_tugas_riset2 inner join pembimbing_luar on pembimbing_luar.nip=daftar_tugas_riset2.nip3 where daftar_tugas_riset2.nim='$nim' ");
					$fnama_pembimbing1=$nama_pembimbing1->fetch_object();
					$fnama_pembimbing2=$nama_pembimbing2->fetch_object();
					$fnama_pembimbing3=$nama_pembimbing3->fetch_assoc();
					$cek=mysqli_query($con, "SELECT count(uji_kelayakan.id_uji_kelayakan) as jml from uji_kelayakan inner join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan where uji_kelayakan.id_uji_kelayakan='$id' ");
					$rCek=$cek->fetch_object();
					$cekTesti=mysqli_query($con, "SELECT is_test,jadwal,tempat FROM uji_kelayakan inner join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan where uji_kelayakan.id_uji_kelayakan='$id'");
					$rCekTesti=$cekTesti->fetch_object();
					$counter_penguji_lab=mysqli_query($con,"SELECT count(penguji_kelayakan.nip_penguji_kelayakan) as counter, dosen.nama_dosen, dosen.nip FROM dosen left join penguji_kelayakan on dosen.nip=penguji_kelayakan.nip_penguji_kelayakan where dosen.idlab='$fshow_pendaftar_uji_kelayakan->idlab_tr1' group by dosen.nip order by counter");
					if($nim==''){
						$errorNim='wajib ada';
						$validNim=false;
					}else $validNim=true;
					$penguji_lab1=test_input($_POST['penguji_lab1']);
					if ($penguji_lab1=='') {
						$errorPengujiLab1='wajib diisi';
						$validPengujiLab1=FALSE;
					}else{
						$validPengujiLab1=TRUE;
					}
					$penguji_lab2=test_input($_POST['penguji_lab2']);
					if ($penguji_lab2=='') {
						$errorPengujiLab2='wajib diisi';
						$validPengujiLab2=FALSE;
					}else{
						$validPengujiLab2=TRUE;
					}

					if(isset($_POST['penguji_lab3'])){
						$penguji_lab3=test_input($_POST['penguji_lab3']);
					}
					if(isset($_POST['penguji_lab4'])){
						$penguji_lab4=test_input($_POST['penguji_lab4']);
					}

					$jadwal=test_input($_POST['jadwal']);
					if ($jadwal=='') {
						$errorJadwal='wajib diisi';
						$validJadwal=FALSE;
					}else{
						$validJadwal=TRUE;
					}
					$tempat=test_input($_POST['tempat']);
					if ($tempat=='') {
						$errorTempat='wajib diisi';
						$validTempat=FALSE;
					}else{
						$validTempat=TRUE;
					}
					
					$counter=0;
					$validPenguji=0;
					for($i =1; $i<=4; $i++){
						if($_POST['penguji_lab'.$i]){
							$counter++;
						}if(${'penguji_lab'.$i}!=''){
							$penguji=${'penguji_lab'.$i};
							$cek_dosen = mysqli_query($con,"SELECT count(nip) as cek from dosen where nip = '".$penguji."' and idlab='".$idlab."' ");
							$rcek_dosen = $cek_dosen->fetch_object();
							if($rcek_dosen->cek != 0){
								${'validPengujiLab'.$i}=$validPenguji++;
							}else{
								${'errorPengujiLab'.$i} = 'dosen tidak terdaftar';
							}
						}else{
							if($i<=2) ${'errorPengujiLab'.$i} = 'wajib diisi';
						}
					}
					if(($counter==$validPenguji) && $validTempat && $validJadwal && $validNim){
						if($rCek->jml !=0){
							$deleteDaftar=mysqli_query($con, "DELETE FROM uji_kelayakan where id_uji_kelayakan='$id' ");
							$deletePenguji=mysqli_query($con, "DELETE FROM penguji_kelayakan where id_uji_kelayakan='$id' ");
							$tambahDaftar=mysqli_query($con, "INSERT INTO uji_kelayakan (id_uji_kelayakan,nim,jadwal,tempat) VALUES ('".$id."','".$nim."', '".$jadwal."', '".$tempat."') ");

							for($j=1;$j<=$counter;$j++){
								$penguji=${'penguji_lab'.$j};
								$tambahPenguji=mysqli_query($con, "INSERT into penguji_kelayakan (id_uji_kelayakan, nip_penguji_kelayakan, jabatan) values ('".$id."', '".$penguji."', 'penguji')");
							}
							if($fnama_pembimbing1->nip1!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_kelayakan (id_uji_kelayakan, nip_penguji_kelayakan, jabatan) values ('".$id."', '".$fnama_pembimbing1->nip1."', 'pembimbing')");
							}
							if($fnama_pembimbing2->nip2!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_kelayakan (id_uji_kelayakan, nip_penguji_kelayakan, jabatan) values ('".$id."', '".$fnama_pembimbing2->nip2."', 'pembimbing')");
							}
						}if($rCek->jml == 0){
							$tambahDaftar=mysqli_query($con, "INSERT INTO uji_kelayakan (id_uji_kelayakan,nim,jadwal,tempat) VALUES ('".$id."','".$nim."', '".$jadwal."', '".$tempat."') ");

							for($j=1;$j<=$counter;$j++){
								$penguji=${'penguji_lab'.$j};
								$tambahPenguji=mysqli_query($con, "INSERT into penguji_kelayakan (id_uji_kelayakan, nip_penguji_kelayakan, jabatan) values ('".$id."', '".$penguji."', 'penguji')");
							}
							if($fnama_pembimbing1->nip1!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_kelayakan (id_uji_kelayakan, nip_penguji_kelayakan, jabatan) values ('".$id."', '".$fnama_pembimbing1->nip1."', 'pembimbing')");
							}
							if($fnama_pembimbing2->nip2!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_kelayakan (id_uji_kelayakan, nip_penguji_kelayakan, jabatan) values ('".$id."', '".$fnama_pembimbing2->nip2."', 'pembimbing')");
							}
						}
						if($tambahPenguji && $tambahDaftar){
							$sukses=true;
							$pesan_sukses="Berhasil memasukkan data";
						}else{
							$sukses=false;
							$pesan_gagal="Tidak Berhasil memasukkan data";
						}
					}else{
						$sukses=false;
						$pesan_gagal="Data yang dimasukkan tidak sesuai";
					}

				}
			}
		}
		
	

	
		
?>

<!DOCTYPE html>
<html>
<head>
	<title>Tambah Penguji dari Laboratorium</title>
	
</head>
<body>
<div class="row">
	<div class="col-md-12">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="lab_daftar_uji_kelayakan.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali </a> Edit Penguji dari Laboratorium
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
							<?php
								if ($sukses && isset($pesan_sukses)){
									echo '<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>'; 
								} if (!$sukses && isset($pesan_gagal)){
									echo '<div class="alert alert-danger" role="alert">'. $pesan_gagal.'</div>';
								}
							?>

							<input type="hidden" name="savenim" value="<?php $nim;?>">
							<input type="hidden" name="saveid" value="<?php $id;?>">
							<input type="hidden" name="id" value="<?php if(isset($id)){echo $id;}else $saveid;?>">
							
							<div class="form-group">
								<label>NIM</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" type="text" name="nim" readonly required value="<?php if(isset($nim)){echo $nim;}else $savenim; ?>">
							</div>
							<div class="form-group">
								<label>Nama</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" type="text" name="nama" readonly required value="<?php echo $fshow_pendaftar_uji_kelayakan->nama; ?>">
							</div>
<?php 
						 if($rCekTesti && $rCekTesti->is_test=='sudah'){
						//if($rCekTesti->is_test=='sudah'){
							echo'<div class="form-group">
							<label>Tanggal & Waktu</label>&nbsp;<span class="label label-warning">*</span>
							<input class="form-control" type="text" name="jadwal" required autofocus readonly value=" ';
							echo $rCekTesti->jadwal;
							echo ' "></div>';

							echo '<div class="form-group">
							<label>Tempat</label>&nbsp;<span class="label label-warning">*</span>
							<input class="form-control" list="tempat" name="tempat" required autofocus readonly value=" ';
							echo $rCekTesti->tempat; 
							echo '"></div>';

							echo'<div class="form-group"><label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*</span>
							<input class="form-control" type="text" name="pembimbing1" readonly required value="';
							if(!$pembimbing1) {echo $fnama_pembimbing1->nama_dosen;}else echo $pembimbing1 ;
							echo'"></div>';

							echo'<div class="form-group"><label>Pembimbing 2</label>&nbsp;<span class="label label-warning">*</span>
							<input class="form-control" type="text" name="pembimbing2" readonly required value="';
							if(!$pembimbing2) {echo $fnama_pembimbing2->nama_dosen;}else echo $pembimbing2;
							echo'"></div>';

							echo'<div class="form-group"><label>Pembimbing 3</label>&nbsp;<span class="label label-warning">*</span>
							<input class="form-control" type="text" name="pembimbing3" readonly required value="';
							if(!$pembimbing3) {echo $fnama_pembimbing3['nama'];}else echo $pembimbing3; 
							echo'"></div>';

							echo'<div class="form-group"><label>Judul</label>&nbsp;<span class="label label-warning">*</span>
							<textarea class="form-control" name="judul" placeholder="masukan judul skripsi" cols="26" rows="5" required maxlength="150" readonly > ';
							if(!$judul){echo $fshow_pendaftar_uji_kelayakan->judul;} else echo $judul;
							echo'</textarea></div>';

							$nomor=1;
							while($rCekPenguji=$cekPenguji->fetch_object()){
								echo'<div class="form-group"><label>Penguji Lab '.$nomor.'</label><span class="label label-warning">*</span>
								<input class="form-control" list="penguji_lab" name="penguji_lab1" placeholder="--Masukkan Nama Penguji dari Laboratorium--" value="';
								echo $rCekPenguji->nip; 
								echo'" readonly required autofocus ></div>';
								$nomor++;
							}
								
							echo'<div class="form-group">
							<input class="form-control btn btn-primary" type="submit" name="submit" value="Submit Penguji" disabled />
							</div>';
													
						}else{

?> 
							<div class="form-group">
								<label>Tanggal & Waktu</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorJadwal)) echo $errorJadwal;?></span>
								<input class="form-control" type="datetime-local" name="jadwal" required autofocus value="<?php if(!$sukses&&!$validJadwal){echo $jadwal;} ?>" >
							</div>
							<div class="form-group">
								<label>Tempat</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorTempat)) echo $errorTempat;?></span>
								<input class="form-control" list="tempat" name="tempat" placeholder="--Masukkan Lokasi Pengujian--" value="<?php if(isset($tempat)) {echo $tempat;} ?>" required autofocus >
								<datalist id="tempat">
									<option></option>
									<option value='Ruang Sidang Biokimia'>
									<option value='Ruang Sidang Kimia Anorganik'>
									<option value='Ruang Sidang Kimia Organik'>
									<option value='Ruang Sidang Kimia Fisika'>
									<option value='Ruang Sidang Kimia Analitik'>
								</datalist>
							</div>
							<div class="form-group">
								<label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" type="text" name="pembimbing1" readonly required value="<?php echo $fnama_pembimbing1->nama_dosen;?>">
							</div>
							<div class="form-group">
								<label>Pembimbing 2</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" type="text" name="pembimbing2" readonly required value="<?php echo $fnama_pembimbing2->nama_dosen;?> ">
							</div>
							<div class="form-group">
								<label>Pembimbing 3</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" type="text" name="pembimbing3" readonly required value="<?php echo $fnama_pembimbing3['nama'];?> ">
							</div>
							<div class="form-group">
								<label>Judul</label>&nbsp;<span class="label label-warning">*</span>
								<textarea class="form-control" name="judul" placeholder="masukan judul skripsi" cols="26" rows="5" required maxlength="150" readonly > <?php echo $fshow_pendaftar_uji_kelayakan->judul;?> </textarea>
							</div>

							<div class="form-group">
								<label>Penguji Lab 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPengujiLab1)) echo $errorPengujiLab1;?></span>
								<input class="form-control" list="penguji_lab" name="penguji_lab1" placeholder="--Masukkan Nama Penguji dari Laboratorium--" value="<?php if(isset($penguji_lab1)) {echo $penguji_lab1;} ?>" required autofocus >
								<datalist id="penguji_lab">
								<option></option>
								<?php
									while($ncounter_penguji_lab=$counter_penguji_lab->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji_lab['nip'];?>"> <?php echo $ncounter_penguji_lab['nama_dosen'];echo '('; echo $ncounter_penguji_lab['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<label>Penguji Lab 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPengujiLab2)) echo $errorPengujiLab2;?></span>
								<input class="form-control" list="penguji_lab" name="penguji_lab2" placeholder="--Masukkan Nama Penguji dari Laboratorium--" value="<?php if(isset($penguji_lab2)) {echo $penguji_lab2;} ?>" required autofocus >
								<datalist id="penguji_lab">
								<option></option>
								<?php
									while($ncounter_penguji_lab=$counter_penguji_lab->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji_lab['nip'];?>"> <?php echo $ncounter_penguji_lab['nama_dosen'];echo '('; echo $ncounter_penguji_lab['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<label>Penguji Lab 3</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPengujiLab3)) echo $errorPengujiLab3;?></span>
								<input class="form-control" list="penguji_lab" name="penguji_lab3" placeholder="--Masukkan Nama Penguji dari Laboratorium--" value="<?php if(isset($penguji_lab3)) {echo $penguji_lab3;} ?>" autofocus">
								<datalist id="penguji_lab">
								<option></option>
								<?php
									while($ncounter_penguji_lab=$counter_penguji_lab->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji_lab['nip'];?>"> <?php echo $ncounter_penguji_lab['nama_dosen'];echo '('; echo $ncounter_penguji_lab['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<label>Penguji Lab 4</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPengujiLab4)) echo $errorPengujiLab4;?></span>
								<input class="form-control" list="penguji_lab" name="penguji_lab4" placeholder="--Masukkan Nama Penguji dari Laboratorium--" value="<?php if(isset($penguji_lab4)) {echo $penguji_lab4;} ?>" autofocus">
								<datalist id="penguji_lab">
								<option></option>
								<?php
									while($ncounter_penguji_lab=$counter_penguji_lab->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji_lab['nip'];?>"> <?php echo $ncounter_penguji_lab['nama'];echo '('; echo $ncounter_penguji_lab['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<input class="form-control btn btn-primary" type="submit" name="submit" value="Submit Penguji" />
							</div>
<?php
						}
?>
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