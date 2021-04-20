<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$sukses=TRUE;
		if(!isset($_POST['submit'])){
			$nim=$_GET['nim'];
			$id=$_GET['id'];
			if(($id=='')&&($nim=='')){
		 		header('Location:./daftar_uji_skripsi.php');
		 	}else{
		 		$show_pendaftar_uji_skripsi=mysqli_query($con, "SELECT nama, daftar_skripsi.* FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.nim = '$nim' and daftar_skripsi.id_daftar_skripsi='$id' ");
				$fshow_pendaftar_uji_skripsi=$show_pendaftar_uji_skripsi->fetch_object();

				//daftar pembimbing
				$nama_pembimbing1=mysqli_query($con,"SELECT * FROM daftar_skripsi inner join dosen on dosen.nip=daftar_skripsi.nip1 where daftar_skripsi.nim='$nim' ");
				$nama_pembimbing2=mysqli_query($con,"SELECT * FROM daftar_skripsi inner join dosen on dosen.nip=daftar_skripsi.nip2 where daftar_skripsi.nim='$nim' ");
				$nama_pembimbing3=mysqli_query($con,"SELECT * FROM daftar_skripsi inner join pembimbing_luar on pembimbing_luar.nip=daftar_skripsi.nip3 where daftar_skripsi.nim='$nim' ");
				$fnama_pembimbing1=$nama_pembimbing1->fetch_object();
				$fnama_pembimbing2=$nama_pembimbing2->fetch_object();
				$fnama_pembimbing3=$nama_pembimbing3->fetch_assoc();

				
				$counter_penguji=mysqli_query($con,"SELECT count(penguji_skripsi.nip_penguji_skripsi) as counter, dosen.nama_dosen, dosen.nip FROM dosen left join penguji_skripsi on dosen.nip=penguji_skripsi.nip_penguji_skripsi group by dosen.nip order by counter");

				//cek sudah test
				$cekTesti=mysqli_query($con, "SELECT is_test,jadwal,tempat FROM uji_skripsi inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='$id'");
				$rCekTesti=$cekTesti->fetch_object();
				$cekPenguji=mysqli_query($con, "SELECT nip_penguji_skripsi as nip FROM uji_skripsi inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='$id'");

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
					$show_pendaftar_uji_skripsi=mysqli_query($con, "SELECT nama, daftar_skripsi.* FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.nim = '$nim' and daftar_skripsi.id_daftar_skripsi='$id' ");
					$fshow_pendaftar_uji_skripsi=$show_pendaftar_uji_skripsi->fetch_object();

					//daftar pembimbing
					$nama_pembimbing1=mysqli_query($con,"SELECT * FROM daftar_skripsi inner join dosen on dosen.nip=daftar_skripsi.nip1 where daftar_skripsi.nim='$nim' ");
					$nama_pembimbing2=mysqli_query($con,"SELECT * FROM daftar_skripsi inner join dosen on dosen.nip=daftar_skripsi.nip2 where daftar_skripsi.nim='$nim' ");
					$nama_pembimbing3=mysqli_query($con,"SELECT * FROM daftar_skripsi inner join pembimbing_luar on pembimbing_luar.nip=daftar_skripsi.nip3 where daftar_skripsi.nim='$nim' ");
					$fnama_pembimbing1=$nama_pembimbing1->fetch_object();
					$fnama_pembimbing2=$nama_pembimbing2->fetch_object();
					$fnama_pembimbing3=$nama_pembimbing3->fetch_assoc();

					
					$counter_penguji=mysqli_query($con,"SELECT count(penguji_skripsi.nip_penguji_skripsi) as counter, dosen.nama_dosen, dosen.nip FROM dosen left join penguji_skripsi on dosen.nip=penguji_skripsi.nip_penguji_skripsi group by dosen.nip order by counter");

					//cek sudah test
					$cekTesti=mysqli_query($con, "SELECT is_test,jadwal,tempat FROM uji_skripsi inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='$id'");
					$rCekTesti=$cekTesti->fetch_object();
					$cekPenguji=mysqli_query($con, "SELECT nip_penguji_skripsi as nip FROM uji_skripsi inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='$id' and penguji_skripsi.jabatan != 'sekretaris' ");
					if($nim==''){
						$errorNim='wajib ada';
						$validNim=false;
					}else $validNim=true;
					$penguji1=test_input($_POST['penguji1']);
					if ($penguji1=='') {
						$errorPenguji1='wajib diisi';
						$validPenguji1=FALSE;
					}else{
						$validPenguji1=TRUE;
					}
					$penguji2=test_input($_POST['penguji2']);
					if ($penguji2=='') {
						$errorPenguji2='wajib diisi';
						$validPenguji2=FALSE;
					}else{
						$validPenguji2=TRUE;
					}

					if(isset($_POST['penguji3'])){
						$penguji3=test_input($_POST['penguji3']);
					}
					if(isset($_POST['penguji4'])){
						$penguji4=test_input($_POST['penguji4']);
					}
					if(isset($_POST['penguji5'])){
						$penguji5=test_input($_POST['penguji5']);
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
					for($i =1; $i<=5; $i++){
						if($_POST['penguji'.$i]){
							$counter++;
						}if(${'penguji'.$i}!=''){
							$penguji=${'penguji'.$i};
							$cek_dosen = mysqli_query($con,"SELECT count(nip) as cek from dosen where nip = '".$penguji."' ");
							$rcek_dosen = $cek_dosen->fetch_object();
							if($rcek_dosen->cek != 0){
								${'validPenguji'.$i}=$validPenguji++;
							}else{
								${'errorPenguji'.$i} = 'dosen tidak terdaftar';
							}
						}else{
							if($i<=2) ${'errorPenguji'.$i} = 'wajib diisi';
						}
					}
					if(($counter==$validPenguji) && $validTempat && $validJadwal && $validNim){
						$cek=mysqli_query($con,"SELECT count(uji_skripsi.id_uji_skripsi) as jml from uji_skripsi inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='".$id."' ");
						$rCek=$cek->fetch_object();
						if($rCek->jml !=0){
							$deleteDaftar=mysqli_query($con, "DELETE FROM uji_skripsi where id_uji_skripsi='$id' ");
							$deletePenguji=mysqli_query($con, "DELETE FROM penguji_skripsi where id_uji_skripsi='$id' ");
							$tambahDaftar=mysqli_query($con, "INSERT INTO uji_skripsi (id_uji_skripsi,nim, jadwal, tempat) VALUES ('".$id."','".$nim."','".$jadwal."','".$tempat."') ");

							for($j=1;$j<=$counter;$j++){
								$penguji=${'penguji'.$j};
								if($j==1) $jabatan='ketua';
								else $jabatan='anggota';
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_skripsi (id_uji_skripsi, nip_penguji_skripsi, jabatan) values ('".$id."', '".$penguji."', '".$jabatan."')");
							}
							if($fnama_pembimbing1->nip1!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_skripsi (id_uji_skripsi, nip_penguji_skripsi, jabatan) values ('".$id."', '".$fnama_pembimbing1->nip1."', 'sekretaris')");
							}
							if($fnama_pembimbing2->nip2!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_skripsi (id_uji_skripsi, nip_penguji_skripsi, jabatan) values ('".$id."', '".$fnama_pembimbing2->nip2."', 'sekretaris')");
							}
							if($fnama_pembimbing3->nip3!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_skripsi (id_uji_skripsi, nip_penguji_skripsi, jabatan) values ('".$id."', '".$fnama_pembimbing2->nip3."', 'sekretaris')");
							}
						}
						if($rCek->jml ==0){
							$tambahDaftar=mysqli_query($con, "INSERT INTO uji_skripsi (id_uji_skripsi,nim, jadwal, tempat) VALUES ('".$id."','".$nim."','".$jadwal."','".$tempat."') ");

							for($j=1;$j<=$counter;$j++){
								$penguji=${'penguji'.$j};
								if($j==1) $jabatan='ketua';
								else $jabatan='anggota';
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_skripsi (id_uji_skripsi, nip_penguji_skripsi, jabatan) values ('".$id."', '".$penguji."', '".$jabatan."')");
							}
							if($fnama_pembimbing1->nip1!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_skripsi (id_uji_skripsi, nip_penguji_skripsi, jabatan) values ('".$id."', '".$fnama_pembimbing1->nip1."', 'sekretaris')");
							}
							if($fnama_pembimbing2->nip2!=''){
								$tambahPenguji=mysqli_query($con, "INSERT INTO penguji_skripsi (id_uji_skripsi, nip_penguji_skripsi, jabatan) values ('".$id."', '".$fnama_pembimbing2->nip2."', 'sekretaris')");
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
	<title>Tambah Penguji</title>
	
</head>
<body>
<div class="row">
	<div class="col-md-12">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="daftar_uji_skripsi.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
				Tambah Penguji dan Jadwal Ujian Tugas Akhir
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
<?php
							if ($sukses && isset($pesan_sukses)){
								echo '<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>'; 
								//echo '</span><br><span class="label label-info">Silahkan Meninggalkan Halaman Ini.</span>';
							} if (!$sukses && isset($pesan_gagal)){
								echo '<div class="alert alert-danger" role="alert">'.$pesan_gagal.'</div>';
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
								<input class="form-control" type="text" name="nama" readonly required value="<?php echo $fshow_pendaftar_uji_skripsi->nama; ?>">
							</div>
<?php 
						//if($rCekTesti && $rCekTesti->is_test=='sudah'){
						// //if($rCekTesti->is_test=='sudah'){
						// 	echo'<div class="form-group">
						// 	<label>Tanggal & Waktu</label>&nbsp;<span class="label label-warning">*</span>
						// 	<input class="form-control" type="text" name="jadwal" required autofocus readonly value=" ';
						// 	echo $rCekTesti->jadwal;
						// 	echo ' "></div>';

						// 	echo '<div class="form-group">
						// 	<label>Tempat</label>&nbsp;<span class="label label-warning">*</span>
						// 	<input class="form-control" list="tempat" name="tempat" required autofocus readonly value=" ';
						// 	echo $rCekTesti->tempat; 
						// 	echo '"></div>';

						// 	echo'<div class="form-group"><label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*</span>
						// 	<input class="form-control" type="text" name="pembimbing1" readonly required value="';
						// 	if(!$pembimbing1) {echo $fnama_pembimbing1->nama_dosen;}else echo $pembimbing1 ;
						// 	echo'"></div>';

						// 	echo'<div class="form-group"><label>Pembimbing 2</label>&nbsp;<span class="label label-warning">*</span>
						// 	<input class="form-control" type="text" name="pembimbing2" readonly required value="';
						// 	if(!$pembimbing2) {echo $fnama_pembimbing2->nama_dosen;}else echo $pembimbing2;
						// 	echo'"></div>';

						// 	echo'<div class="form-group"><label>Pembimbing 3</label>&nbsp;<span class="label label-warning">*</span>
						// 	<input class="form-control" type="text" name="pembimbing3" readonly required value="';
						// 	if(!$pembimbing3) {echo $fnama_pembimbing3['nama'];}else echo $pembimbing3; 
						// 	echo'"></div>';

						// 	echo'<div class="form-group"><label>Judul</label>&nbsp;<span class="label label-warning">*</span>
						// 	<textarea class="form-control" name="judul" placeholder="masukan judul skripsi" cols="26" rows="5" required maxlength="150" readonly > ';
						// 	if(!$judul){echo $fshow_pendaftar_uji_skripsi->judul;} else echo $judul;
						// 	echo'</textarea></div>';

						// 	$nomor=1;
						// 	while($rCekPenguji=$cekPenguji->fetch_object()){
						// 		echo'<div class="form-group"><label>Penguji Lab '.$nomor.'</label><span class="label label-warning">*</span>
						// 		<input class="form-control" list="penguji_lab" name="penguji_lab1" placeholder="--Masukkan Nama Penguji dari Laboratorium--" value="';
						// 		echo $rCekPenguji->nip; 
						// 		echo'" readonly required autofocus ></div>';
						// 		$nomor++;
						// 	}
								
						// 	echo'<div class="form-group">
						// 	<input class="form-control btn btn-primary" type="submit" name="submit" value="Submit Penguji" disabled />
						// 	</div>';
													
						//}else{

?> 
							<div class="form-group">
								<label>Tanggal & Waktu</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorJadwal)) echo $errorJadwal;?></span>
								<input class="form-control" type="datetime-local" name="jadwal" required autofocus value="<?php if(!$sukses&&!$validJadwal){echo $jadwal;} ?>">
							</div>
							<div class="form-group">
								<label>Tempat</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorTempat)) echo $errorTempat;?></span>
								<input class="form-control" list="tempat" name="tempat" placeholder="--Masukkan Lokasi Pengujian--" required autofocus value="<?php if(!$sukses&&!$validTempat){echo $tempat;} ?>">
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
								<textarea class="form-control" name="judul" placeholder="masukan judul skripsi" cols="26" rows="5" required maxlength="150" readonly > <?php echo $fshow_pendaftar_uji_skripsi->judul;?> </textarea>
							</div>

							<div class="form-group">
								<label>Penguji 1 sebagai Ketua</label>&nbsp;<span class="label label-warning"><?php if(isset($errorPenguji1)) echo $errorPenguji1;?>*</span>
								<input class="form-control" list="penguji" name="penguji1" placeholder="--Masukkan Nama Penguji--" value="<?php if(isset($penguji1)) {echo $penguji1;} ?>" required autofocus">
								<datalist id="penguji">
								<option></option>
								<?php
									while($ncounter_penguji=$counter_penguji->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji['nip'];?>"> <?php echo $ncounter_penguji['nama_dosen'];echo '('; echo $ncounter_penguji['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<label>Penguji 2 sebagai Anggota</label>&nbsp;<span class="label label-warning"><?php if(isset($errorPenguji2)) echo $errorPenguji2;?>*</span>
								<input class="form-control" list="penguji" name="penguji2" placeholder="--Masukkan Nama Penguji--" value="<?php if(isset($penguji2)) {echo $penguji2;} ?>" required autofocus">
								<datalist id="penguji">
								<option></option>
								<?php
									while($ncounter_penguji=$counter_penguji->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji['nip'];?>"> <?php echo $ncounter_penguji['nama_dosen'];echo '('; echo $ncounter_penguji['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<label>Penguji 3 sebagai Anggota</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" list="penguji" name="penguji3" placeholder="--Masukkan Nama Penguji--" value="<?php if(isset($penguji3)) {echo $penguji3;} ?>" autofocus">
								<datalist id="penguji">
								<option></option>
								<?php
									while($ncounter_penguji=$counter_penguji->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji_lab['nip'];?>"> <?php echo $ncounter_penguji['nama_dosen'];echo '('; echo $ncounter_penguji['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<label>Penguji 4 sebagai Anggota</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" list="penguji" name="penguji4" placeholder="--Masukkan Nama Penguji--" value="<?php if(isset($penguji4)) {echo $penguji4;} ?>" autofocus">
								<datalist id="penguji">
								<option></option>
								<?php
									while($ncounter_penguji=$counter_penguji->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji['nip'];?>"> <?php echo $ncounter_penguji['nama'];echo '('; echo $ncounter_penguji['counter']; echo')';?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<label>Penguji 5 sebagai Anggota</label>&nbsp;<span class="label label-warning">*</span>
								<input class="form-control" list="penguji" name="penguji5" placeholder="--Masukkan Nama Penguji--" value="<?php if(isset($penguji5)) {echo $penguji5;} ?>" autofocus">
								<datalist id="penguji">
								<option></option>
								<?php
									while($ncounter_penguji=$counter_penguji->fetch_assoc()){ ?>
											<option value="<?php echo $ncounter_penguji['nip'];?>"> <?php echo $ncounter_penguji['nama'];echo '('; echo $ncounter_penguji['counter']; echo')';?>
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
						//}
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