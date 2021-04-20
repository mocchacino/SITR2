<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'mahasiswa'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$sukses=TRUE;
		$nim=$rMahasiswa->nim;
		$nama=$rMahasiswa->nama;

		$cekLulusUjiKelayakan = mysqli_query($con,"SELECT MAX(is_lulus)as sudah_lulus FROM uji_kelayakan WHERE nim = '$nim' ");
		$rcekLulusUjiKelayakan=$cekLulusUjiKelayakan->fetch_assoc();
		
		$cekSudahDaftarSkripsi = mysqli_query($con,"SELECT * FROM daftar_skripsi WHERE nim = ".$nim." order by tahun_ajaran desc limit 1 ");
		$rcekSudahDaftarSkripsi=$cekSudahDaftarSkripsi->fetch_assoc();
		$syaratDaftar=mysqli_query($con,"SELECT * FROM waktu where nama='Skr' ");
		$rsyaratDaftar=$syaratDaftar->fetch_assoc();
		$tahunAjaran=mysqli_query($con,"SELECT deskripsi from misc where judul='tahun_ajaran' ");
		$rTahunAjaran=$tahunAjaran->fetch_assoc();
		$show_detail=mysqli_query($con, "SELECT mahasiswa.nama, nip1, nip2, nip3, judul from daftar_tugas_riset2 inner join mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim where daftar_tugas_riset2.nim='$nim' ");	
		$rshow_detail=$show_detail->fetch_assoc();
		$pembimbing1=mysqli_query($con, "SELECT nip, nama_dosen from dosen where nip='".$rshow_detail['nip1']."' ");
		$rpembimbing1=$pembimbing1->fetch_assoc();
		$pembimbing2=mysqli_query($con, "SELECT nip, nama_dosen from dosen where nip='".$rshow_detail['nip2']."' ");
		$rpembimbing2=$pembimbing2->fetch_assoc();
		$pembimbing3=mysqli_query($con, "SELECT nip, nama from pembimbing_luar where nip='".$rshow_detail['nip3']."' ");
		$rpembimbing3=$pembimbing3->fetch_assoc();
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			if (isset($_POST['daftar'])){
				if(($rcekLulusUjiKelayakan['sudah_lulus']== '1')&&(isset($_FILES['file']))) {	
					$pembimbing1=test_input($_POST['pembimbing1']);	
					$pembimbing2=test_input($_POST['pembimbing2']);	
					$pembimbing3=test_input($_POST['pembimbing3']);
					$judul=test_input($_POST['judul']);
					$komulatif=test_input($_POST['komulatif']);
					if ($komulatif==''){
						$errorKomulatif='wajib diisi';
						$validKomulatif=FALSE;
					}else{
						if (is_numeric($komulatif)){
							if ($komulatif >= 141){
								$validKomulatif=TRUE;
							}else{
								$errorKomulatif='sks komulatif minimal 141';
								$validKomulatif=FALSE;
							}
						}else{
							$errorKomulatif='isi dengan angka';
							$validKomulatif=FALSE;
						}
					}

					$ipk=test_input($_POST['ipk']);
					if($ipk == ''){
						$errorIpk='wajib diisi';
						$validIpk= false;
					}else{
						if(is_numeric($ipk)){
							$ipk=floatval($ipk);
							$syarat=floatval($rsyaratDaftar['syarat_ipk']);
							//$minIpk=$fSyaratDaftar->syarat_ipk;
							if(($ipk>=$syarat)&&($ipk<=4)){
								$validIpk=true;
							}else{
								$errorIpk='Nilai IPK tidak memenuhi syarat minimal '.$syarat;
								$validIpk=false;
							}
						}else{
							$errorIpk='hanya angka dan menggunakan titik(.) untuk bilangan desimal';
							$validIpk=false;
						}
					}

					$sks=test_input($_POST['sks']);
					if ($sks !=''){
						if (is_numeric($sks)){
							if(($sks >= 2)&&(($sks <= 24))){
								$validSks=TRUE;
							}else{
								$errorSks='sks berjalan min 2, max 24';
								$validSks=FALSE;
							}
						}else{
							$errorSks='isi dengan angka';
							$validSks=FALSE;
						}
					}else{
						$errorSks='wajib diisi';
						$validSks=FALSE;
					}
					
					$krs=test_input($_POST['krs']);
					if ($krs=='') {
						$errorKrs='wajib diisi';
						$validKrs=FALSE;
					}else{
						$validKrs=TRUE;
					}
					

					$temp = explode(".", $_FILES["file"]["name"]);
					$extension = end($temp);
					$validTranskrip = false;
					$target="../transkrip_skripsi/";
					$path=md5(microtime()).'.'.$extension;
					if (($_FILES["file"]["size"] < 2000000)){
						if ($_FILES["file"]["error"] > 0){
						   switch ($_FILES["file"]["error"]) {
								case 1:
									$errorTranskrip='Ukuran file melebihi ukuran maksimum 2MB';
									break;
								case 2: $errorTranskrip='The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
									break;
								case 3: $errorTranskrip='The uploaded file was only partially uploaded';
									break;
								case 4: $errorTranskrip='Tidak ada file yang diupload';
									break;
								case 5: $errorTranskrip='The uploaded file exceeds the upload_max_filesize directive in php.ini';
									break;
								case 6: $errorTranskrip='Missing a temporary folder';
									break;
								case 7: $errorTranskrip='Failed to write file to disk.';
									break;
								case 8: $errorTranskrip='A PHP extension stopped the file upload.';
									break;
							}   
						}else{
							$finfo = finfo_open(FILEINFO_MIME_TYPE);
							$mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
							
							if ($mime == 'application/pdf') {
								$validTranskrip = true;
							}else{
							    $errorTranskrip="Unknown/not permitted file type";
							    $validTranskrip = false;
							}

						}
					}else{
						$errorTranskrip= "Ukuran file melebihi ukuran maksimum 2MB";
					}
					
					$tempToefl = explode(".", $_FILES["fileToefl"]["name"]);
					$extensionToefl = end($tempToefl);
					$validToefl = false;
					$targetToefl="../toefl/";
					$pathToefl=md5(microtime()).'.'.$extensionToefl;
					if (($_FILES["fileToefl"]["size"] < 2000000)){
						if ($_FILES["fileToefl"]["error"] > 0){
						   switch ($_FILES["fileToefl"]["error"]) {
								case 1:
									$errorToefl='Ukuran file melebihi ukuran maksimum 2MB';
									break;
								case 2: $errorToefl='The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
									break;
								case 3: $errorToefl='The uploaded file was only partially uploaded';
									break;
								case 4: $errorToefl='Tidak ada file yang diupload';
									break;
								case 5: $errorToefl='The uploaded file exceeds the upload_max_filesize directive in php.ini';
									break;
								case 6: $errorToefl='Missing a temporary folder';
									break;
								case 7: $errorToefl='Failed to write file to disk.';
									break;
								case 8: $errorToefl='A PHP extension stopped the file upload.';
									break;
							}   
						}else{
							$finfoToefl = finfo_open(FILEINFO_MIME_TYPE);
							$mimeToefl = finfo_file($finfoToefl, $_FILES['fileToefl']['tmp_name']);
							
							if ($mimeToefl == 'application/pdf') {
								$validToefl = true;
							}else{
							    $errorToefl="Unknown/not permitted file type";
							    $validToefl = false;
							}
						}
					}else{
						$errorToefl= "Ukuran file melebihi ukuran maksimum 2MB";
					}
					if($validKomulatif && $validKrs && $validSks && $validTranskrip && $validToefl&&$validIpk){
						if((move_uploaded_file($_FILES['file']['tmp_name'], $target.$path))&&(move_uploaded_file($_FILES['fileToefl']['tmp_name'], $targetToefl.$pathToefl))){
							if($rcekSudahDaftarSkripsi==''){
								if(($rsyaratDaftar['awal'] <= date('Y-m-d'))&&($rsyaratDaftar['akhir'] >= date('Y-m-d') )){
									$insert_pendaftar = mysqli_query($con, "INSERT INTO daftar_skripsi (nim, nip1, nip2, nip3, judul, sks_komulatif,sks_semester, tgl_krs, tgl_daftar, path_file, tahun_ajaran, path_toefl, ipk) VALUES ('".$nim."', '".$rpembimbing1['nip']."', '".$rpembimbing2['nip']."', '".$rpembimbing3['nip']."', '".$rshow_detail['judul']."', '".$komulatif."','".$sks."', '".$krs."',NOW(), '".$path."','".$rTahunAjaran['deskripsi']."', '".$pathToefl."', '".$ipk."' )" );
									// $id=mysqli_query($con,"SELECT max(id_daftar_skripsi) as id from daftar_skripsi where '$nim' ");
									// $rId=$id->fetch_assoc();
									// $insert_id = $con->query("INSERT INTO uji_skripsi (id_uji_skripsi,nim) VALUES ('".$rId['id']."','".$nim."')" );
								}else{
				    				$sukses=FALSE;
									$pesan_gagal= 'Mohon Maaf Tidak didalam Periode Pendaftaran ';
				    			
								}	
							}else{
								if(($rsyaratDaftar['awal'] <= date('Y-m-d'))&&($rsyaratDaftar['akhir'] >= date('Y-m-d') )){
									if(($rcekSudahDaftarSkripsi['tgl_lulus'] == null) && ($rcekSudahDaftarSkripsi['is_lulus']=='0') && ($rcekSudahDaftarSkripsi['tahun_ajaran']!=$rTahunAjaran['deskripsi'])){

										$insert_pendaftar = mysqli_query($con,"INSERT INTO daftar_skripsi (nim, nip1, nip2, nip3, judul, sks_komulatif,sks_semester, tgl_krs, tgl_daftar, path_file, tahun_ajaran, path_toefl, ipk) VALUES ('".$nim."', '".$rpembimbing1['nip']."', '".$rpembimbing2['nip']."', '".$rpembimbing3['nip']."', '".$rshow_detail['judul']."', '".$komulatif."','".$sks."', '".$krs."',NOW(), '".$path."','".$rTahunAjaran['deskripsi']."', '".$pathToefl."', '".$ipk."' )" );
										
										// $insert_id = $con->query("INSERT INTO uji_skripsi (id_uji_skripsi,nim) VALUES ('".$rId['id']."','".$nim."')" );
									}else{
										$id=mysqli_query($con,"SELECT max(id_daftar_skripsi) as id from daftar_skripsi where '".$nim."' ");
										$rId=$id->fetch_assoc();
										$insert_pendaftar = $con->query("UPDATE daftar_skripsi SET nim = '".$nim."', nip1='".$rpembimbing1['nip']."', nip2='".$rpembimbing2['nip']."', nip3='".$rpembimbing3['nip']."', judul='".$rshow_detail['judul']."', sks_komulatif = '".$komulatif."',sks_semester = '".$sks."',tgl_krs = '".$krs."',tgl_daftar = NOW() ,path_file = '".$path."',path_toefl = '".$pathToefl."', ipk = '".$ipk."' where tahun_ajaran='".$rTahunAjaran['deskripsi']."' and id_daftar_skripsi='".$rId['id']."' " );
									}
								}else{
									$sukses=FALSE;
									$pesan_gagal= 'Mohon Maaf Tidak didalam Periode Pendaftaran ';
								}
							}
							if ($insert_pendaftar) {
								$sukses=TRUE;
								$pesan_sukses="Berhasil menambahkan data.";	
							}
						}else{
							$sukses=FALSE;
							$pesan_gagal="Gagal mengupload file.";
						}	
					}else{
						$sukses=FALSE;
						$pesan_gagal="Harap lengkapi form yang ditandai";
					}		
				}else{
					$sukses=FALSE;
					$pesan_gagal='Silahkan melengkapi dokumen/syarat pendaftaran yang dibutuhkan';
				}		
			}
		}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Form Pendaftaran Skripsi</title>
</head>
<body>
<div class="row">
	<div class="col-md-12">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				Pendaftaran Skripsi
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
							<?php
								if ($sukses && isset($pesan_sukses)){
									echo '<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>';
								} if (!$sukses && isset($pesan_gagal)){
									echo '<div class="alert alert-danger" role="alert">'.$pesan_gagal.'</div>';
								}
							?>
							<div class="form-group">
								<label>NIM</label>
								<input class="form-control" type="text" name="nim" <?php if($status=="mahasiswa") {echo 'readonly';} ?> required value="<?php if($status=="mahasiswa"){echo $nim; } ?>">
							</div>
							<div class="form-group">
								<label>Nama</label>
								<input class="form-control" type="text" name="nama"<?php if($status=="mahasiswa") {echo 'readonly';} ?> required value="<?php if($status=="mahasiswa"){echo $nama;} ?>">
							</div>
							<div class="form-group">
								<label>Pembimbing 1</label>&nbsp;
								<input class="form-control" type="text" name="pembimbing1" id="pembimbing1" readonly value="<?php echo $rpembimbing1['nama_dosen'];?>">
							</div>
							<div class="form-group">
								<label>Pembimbing 2</label>&nbsp;
								<input class="form-control" type="text" name="pembimbing2" id="pembimbing2" readonly value="<?php echo $rpembimbing2['nama_dosen'];?>">
							</div>
							<div class="form-group">
								<label>Pembimbing 3</label>&nbsp;
								<input class="form-control" type="text" name="pembimbing3" id="pembimbing3" readonly value="<?php echo $rpembimbing3['nama'];?>">
							</div>
							<div class="form-group">
									<label>Judul</label>&nbsp;
									<textarea class="form-control" name="judul" id="judul" cols="26" rows="5" maxlength="150" readonly ><?php echo $rshow_detail['judul'];?> </textarea>
								</div>
							<div class="form-group">
								<label>SKS Komulatif</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKomulatif)) echo $errorKomulatif;?></span>
								<input class="form-control" type="number" name="komulatif" required autofocus pattern="^[0-9]{,3}$" value="<?php if(!$sukses&&$validKomulatif){echo $komulatif;} ?>">
							</div>
							<div class="form-group">
								<label>Jumlah SKS Semester Berjalan</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorSks)) echo $errorSks;?></span>
								<input class="form-control" type="number" name="sks" required autofocus pattern="^[0-9]{,2}$" value="<?php if(!$sukses&&$validSks){echo $sks;} ?>">
							</div>
							<div class="form-group">
								<label>IPK</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIpk)) echo $errorIpk;?></span>
								<input class="form-control" type="number" step="any" name="ipk" required autofocus value="<?php if(!$sukses&&$validIpk){echo $ipk;} ?>">
							</div>
							<div class="form-group">
								<label>Tanggal Submit KRS</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKrs)) echo $errorKrs;?></span>
								<input class="form-control" type="date" name="krs" required autofocus value="<?php if(!$sukses&&!$validKrs){echo $krs;} ?>" max="<?php echo date('Y-m-d');?>" >
							</div>
							<div class="form-group">
								<div class="form-group">
									<label>Upload Trasnkrip Lengkap (.pdf max 2MB)</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorTranskrip)) echo $errorTranskrip;?></span>
									<input type="file" name="file" size="50" accept="application/pdf" required autofocus value="<?php if(!$sukses&&!$validTranskrip){echo $mime;} ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="form-group">
									<label>Upload TOEFL (.pdf max 2MB)</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorToefl)) echo $errorTranskrip;?></span>
									<input type="file" name="fileToefl" size="50" accept="application/pdf" required autofocus value="<?php if(!$sukses&&!$validToefl){echo $mimeToefl;} ?>">
								</div>
							</div>
							<div class="form-group">
								<input class="form-control btn-primary" type="submit" name="daftar" value="Daftar"/>
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