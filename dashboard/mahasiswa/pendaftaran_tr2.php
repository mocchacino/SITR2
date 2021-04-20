<?php
require_once('../functions.php');	
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'mahasiswa'){
	include_once('../sidebar.php');
	ini_set('display_errors', 0);
	$sukses=TRUE;
	$nim=$rMahasiswa->nim;
	$nama=$rMahasiswa->nama;
	$cek_lulus_tr1 = mysqli_query($con,"SELECT nip1, nip2, nip3, tgl_lulus, judul FROM tr1 WHERE tr1.nim = '".$nim."' ");
	$dariTr1=$cek_lulus_tr1->fetch_assoc();
	if($dariTr1){
		$cek_sudah_daftar_tr2 = mysqli_query($con,"SELECT * FROM daftar_tugas_riset2 WHERE daftar_tugas_riset2.nim = ".$nim);
		$fcek_sudah_daftar_tr2=mysqli_num_rows($cek_sudah_daftar_tr2); 

		$pembimbing1 = mysqli_query($con, "SELECT * FROM dosen WHERE nip =".$dariTr1['nip1']);
		$fpembimbing1=$pembimbing1->fetch_assoc();

		if($dariTr1['nip2']){
			$pembimbing2 = mysqli_query($con, "SELECT * FROM dosen WHERE nip =".$dariTr1['nip2']);
			$adaPembimbing2=mysqli_num_rows($pembimbing2);
			if ($adaPembimbing2!=0){
				$fpembimbing2=$pembimbing2->fetch_assoc();
			}
		}else{
			$fpembimbing2['nama_dosen']='';
		}

		if($dariTr1['nip3']){
			$pembimbing3 = mysqli_query($con, "SELECT * FROM pembimbing_luar WHERE nip =".$dariTr1['nip3']);
			if (mysqli_num_rows($pembimbing3)){
				$fpembimbing3=$pembimbing3->fetch_assoc();
			}
		}else{
			$fpembimbing3['nama']='';
		}

		$syaratDaftar=mysqli_query($con,"SELECT * FROM waktu where nama='TR2' ");
		$fSyaratDaftar=$syaratDaftar->fetch_assoc();
		$tahunAjaran=mysqli_query($con,"SELECT deskripsi from misc where judul='tahun_ajaran' ");
		$rTahunAjaran=$tahunAjaran->fetch_assoc();
		// $cek_mulai=mysqli_query($con,"SELECT waktu_mulai FROM jadwal_tugas_riset2 ORDER BY id_jadwal_tugas_riset2 DESC LIMIT 1");
		// $fcek_mulai=$cek_mulai->fetch_assoc();

		// $cek_kadaluarsa=mysqli_query($con,"SELECT waktu_selesai FROM jadwal_tugas_riset2 ORDER BY id_jadwal_tugas_riset2 DESC LIMIT 1");
		// $fcek_kadaluarsa=$cek_kadaluarsa->fetch_assoc();



		// eksekusi tombol daftar
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			if ((isset($_POST['daftar'])) && (isset($_POST['lulus_toefl'])) && (isset($_FILES['file']))) {
				$komulatif=test_input($_POST['komulatif']);
				if ($komulatif!=''){
					if (is_numeric($komulatif)){
						if ($komulatif >= 120){
							$validKomulatif=TRUE;
						}else{
							$errorKomulatif='sks komulatif minimal 120';
							$validKomulatif=FALSE;
						}
					}else{
						$errorKomulatif='isi dengan angka';
						$validKomulatif=FALSE;
					}
				}else{
					$errorKomulatif='wajib diisi';
					$validKomulatif=FALSE;
				}

				$sks=test_input($_POST['sks']);
				if ($sks !=''){
					if (is_numeric($sks)){
						if(($sks >= 3)&&(($sks <= 24))){
							$validSks=TRUE;
						}else{
							$errorSks='sks berjalan min 3, max 24';
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
				
				$semester=test_input($_POST['semester']);
				if ($semester !=''){
					if (is_numeric($semester)){
						if($semester >= 4){
							$validSemester=TRUE;
						}else{
							$errorSemester='Semester minimal 4';
							$validSemester=FALSE;
						}
					}else{
						$errorSemester='isi dengan angka';
						$validSemester=FALSE;
					}
				}else{
					$errorSemester='wajib diisi';
					$validSemester=FALSE;
				}
				
				$krs=test_input($_POST['krs']);
				if ($krs=='') {
					$errorKrs='wajib diisi';
					$validKrs=FALSE;
				}else{
					$validKrs=TRUE;
				}
				
				$ipk=test_input($_POST['ipk']);
				if($ipk == ''){
					$errorIpk='wajib diisi';
					$validIpk= false;
				}else{
					if(is_numeric($ipk)){
						$ipk=floatval($ipk);
						$syarat=floatval($fSyaratDaftar['syarat_ipk']);
						//$minIpk=$fSyaratDaftar->syarat_ipk;
						if(($ipk>=$syarat)&&($ipk<=4)){
							$validIpk=true;
						}else{
							$errorIpk='Nilai IPK harus minimal'.$syarat;
							$validIpk=false;
						}
					}else{
						$errorIpk='hanya angka dan menggunakan titik(.) untuk bilangan desimal';
						$validIpk=false;
					}
				}

				
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				$validTranskrip = false;
				$target="../transkrip_tr2/";
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
						if($validKomulatif && $validKrs && $validSks && $validTranskrip && $validIpk){
							if ($dariTr1['tgl_lulus'] != 0){
								if(move_uploaded_file($_FILES['file']['tmp_name'], $target.$path)){
									if ($fcek_sudah_daftar_tr2==0){
										if(($fSyaratDaftar['awal'] <= date('Y-m-d'))&&($fSyaratDaftar['akhir'] >= date('Y-m-d') )){
							    			$insert_pendaftar = $con->query("INSERT INTO daftar_tugas_riset2 (nim, nip1, nip2, nip3,sks_komulatif,sks_semester,tgl_krs,tgl_daftar, path_file, judul, tahun_ajaran, semester, ipk) VALUES ('".$nim."', '".$dariTr1['nip1']."' , '".$dariTr1['nip2']."', '".$dariTr1['nip3']."','".$komulatif."','".$sks."', '".$krs."',NOW(), '".$path."', '".$dariTr1['judul']."', '".$rTahunAjaran['deskripsi']."', '".$semester."', '".$ipk."' )" );
							    			
						    			}else{
						    				$sukses=FALSE;
											$pesan_gagal= 'Mohon Maaf Tidak didalam Periode Pendaftaran ';
						    			}

						    		}else{
						    			if(($fSyaratDaftar['awal'] <= date('Y-m-d h:i:sa'))&&($fSyaratDaftar['akhir'] >= date('Y-m-d h:i:sa') )){
						    				$id=mysqli_query($con,"SELECT max(id_tr2) as id from daftar_tugas_riset2 where '".$nim."' ");
											$rId=$id->fetch_assoc();
						    				$insert_pendaftar = $con->query("UPDATE daftar_tugas_riset2 SET nim = '".$nim."', sks_komulatif = '".$komulatif."',sks_semester = '".$sks."',tgl_krs = '".$krs."',tgl_daftar = NOW() ,path_file = '".$path."',semester='".$semester."', ipk='".$ipk."' where id_tr2 ='".$rId['id']."' " );
						    			}else{
						    				$sukses=FALSE;
											$pesan_gagal= 'Mohon Maaf Tidak didalam Periode Pendaftaran ';
						    			}
						    		}
						    		if ($insert_pendaftar) {
										$sukses=TRUE;
										$pesan_sukses="Berhasil menambahkan data.";	
									}
								}else {
					    			$sukses=FALSE;
									$pesan_gagal="Harap lengkapi form yang ditandai";
								}
							}else{
								$sukses=FALSE;
								$pesan_gagal="Anda belum lulus Tugas Riset 1";
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
			<title>Form Pendaftaran TR II</title>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Pendaftaran Tugas Riset II
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" >
									<?php
										if ($sukses && isset($pesan_sukses)){
											echo '<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>';
										} if (!$sukses && isset($pesan_gagal)){
											echo '<div class="alert alert-danger" role="alert">'.$pesan_gagal.'</div>';
										}
									?>
									<div class="form-group">
										<label>NIM</label>&nbsp;
										<input class="form-control" type="text" name="nim" <?php if($status=="mahasiswa") {echo 'readonly';} ?> autofocus value="<?php if($status=="mahasiswa"){echo $nim; } ?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;
										<input class="form-control" type="text" name="nama" <?php if($status=="mahasiswa"){echo 'readonly';} ?> value="<?php if($status=="mahasiswa"){echo $nama;} ?>">
									</div>
									<div class="form-group">
										<label>Pembimbing 1</label>&nbsp;
										<input class="form-control" type="text" name="pembimbing1" <?php if($status=="mahasiswa") {echo 'readonly';} ?> value="<?php echo $fpembimbing1['nama_dosen']; ?>">
									</div>
									<div class="form-group">
										<label>Pembimbing 2</label>&nbsp;
										<input class="form-control" type="text" name="pembimbing2" <?php if($status=="mahasiswa"){echo 'readonly';} ?> value="<?php echo $fpembimbing2['nama_dosen']; ?>">
									</div>

									<div class="form-group">
										<label>Pembimbing 3</label>&nbsp;
										<input class="form-control" type="text" name="pembimbing3" <?php if($status=="mahasiswa"){echo 'readonly';} ?> value="<?php echo $fpembimbing3['nama']; ?>">
									</div>
									<div class="form-group">
										<label>Judul Tugas Riset 2</label>&nbsp;
										<textarea class="form-control" name="judul" placeholder="masukan judul skripsi" cols="26" rows="5" maxlength="150" <?php if($status=="mahasiswa") {echo 'readonly';} ?> ><?php if($status=="mahasiswa"){echo $dariTr1['judul'];} ?></textarea>
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
										<label>Semester</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorSemester)) echo $errorSemester;?></span>
										<input class="form-control" type="number" name="semester" required autofocus value="<?php if(!$sukses&&$validSemester){echo $semester;} ?>">
									</div>
									<div class="form-group">
										<label>IPK</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIpk)) echo $errorIpk;?></span>
										<input class="form-control" type="number" step="any" name="ipk" required autofocus value="<?php if(!$sukses&&$validIpk){echo $ipk;} ?>">
									</div>
									<div class="form-group">
										<label>Tanggal Submit KRS</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKrs)) echo $errorKrs;?></span>
										<input class="form-control" type="date" name="krs" required autofocus value="<?php if(!$sukses&&!$validKrs){echo $krs;} ?>" max="<?php echo date('Y-m-d');?>">
									</div>
									<div class="form-group">
										<input type="checkbox" name="lulus_toefl" value="check" id="lulus_toefl" required /> Saya sudah '<b>Lulus TOEFL</b> dengan <b>skor minimal 400</b>' <br>
									</div>
									<div class="form-group">
										<label>Upload Trasnkrip Lengkap (.pdf max 2MB)</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorTranskrip)) echo $errorTranskrip;?></span>
										<input type="file" name="file" size="50" accept="application/pdf" required autofocus value="<?php if(!$sukses&&!$validTranskrip){echo $mime;} ?>">
									</div>
									<div class="form-group">
										<input class="form-control btn btn-primary" type="submit" name="daftar" value="Daftar"/>
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
		echo '<div class="alert alert-danger" role="alert">ANDA BELUM MENDAFTAR TUGAS RISET 1</div>';
		include_once('../footer.php');
		$con->close();
	}	
}else{
	header("Location:./");
}
?>