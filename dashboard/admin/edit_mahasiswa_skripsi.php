<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$sukses=false;
		if(!isset($_POST['edit'])){
			$nim=$_GET['nim'];
			$id=$_GET['id'];
		 	if(($nim=='')&&($id=='')){
		 		header('Location:./daftar_uji_skripsi.php');
		 	}else{
		 		$data=mysqli_query($con,"SELECT daftar_skripsi.*, mahasiswa.nama FROM daftar_skripsi INNER JOIN mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.id_daftar_skripsi='$id' and daftar_skripsi.nim='$nim' ORDER BY daftar_skripsi.tgl_daftar ");
		 		while($rData=$data->fetch_object()){
		 			$nim=$rData->nim;
		 			$nama=$rData->nama;
		 			$pembimbing1=$rData->nip1;
		 			$pembimbing2=$rData->nip2;
		 			$pembimbing3=$rData->nip3;
		 			$judul=$rData->judul; 
		 			$tgl_lulus=$rData->tgl_lulus;
		 			$tahun_ajaran=$rData->tahun_ajaran;
					$tgl_krs=$rData->tgl_krs;
					$tgl_daftar=$rData->tgl_daftar;
					$sks_semester=$rData->sks_semester;
					$sks_komulatif=$rData->sks_komulatif;
		 		}
				$qpembimbing1=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen ");
		 		$qpembimbing2=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen ");
				$qpembimbing3=mysqli_query($con,"SELECT nip,nama FROM pembimbing_luar");
		 	}
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])){
					$qpembimbing1=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen ");
			 		$qpembimbing2=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen ");
					$qpembimbing3=mysqli_query($con,"SELECT nip,nama FROM pembimbing_luar");
					$id=test_input($_POST['id']);
					$saveid=test_input($_POST['saveid']);
					$savenim=test_input($_POST['savenim']);
					$nim=test_input($_POST['nim']);
					$savenama=test_input($_POST['savenama']);
					$nama=test_input($_POST['nama']);
					$pembimbing1=test_input($_POST['pembimbing1']);
					$cekPembimbing1=mysqli_num_rows(mysqli_query($con,"SELECT nip,nama_dosen FROM dosen where nip='".$pembimbing1."' "));
					if($pembimbing1==''){
						$errorPembimbing1='Pembimbing harus diisi';
						$validPembimbing1=true;
					}else{
						if($cekPembimbing1==0){
							$errorPembimbing1='Dosen tidak terdaftar';;
							$validPembimbing1=false;
						}else{
							$validPembimbing1=true;
						}
					}
					
					$pembimbing2=test_input($_POST['pembimbing2']);
					$cekPembimbing2=mysqli_num_rows(mysqli_query($con,"SELECT nip,nama_dosen FROM dosen where nip='".$pembimbing2."' "));
					if($pembimbing2==''){
						$errorPembimbing2='Pembimbing harus diisi';
						$validPembimbing2=true;
					}else{
						if($cekPembimbing2==0){
							$errorPembimbing2='Dosen tidak terdaftar';;
							$validPembimbing2=false;
						}else{
							$validPembimbing2=true;
						}
					}
					
					$judul=test_input($_POST['judul']);
					if($judul==''){
						$errorJudul='Judul wajib diisi';
						$validJudul=false;
					}else{
						$validJudul=true;
					}
					
					$sks_komulatif=test_input($_POST['sks_komulatif']);
					if ($sks_komulatif!=''){
						if (is_numeric($sks_komulatif)){
							if ($sks_komulatif >= 120){
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
	
					$sks_semester=test_input($_POST['sks_semester']);
					if ($sks_semester !=''){
						if (is_numeric($sks_semester)){
							if(($sks_semester >= 7)&&($sks_semester <= 24)){
								$validSks=TRUE;
							}else{
								$errorSks='sks berjalan minimal 7, maksimal 24';
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
					
					// $semester=test_input($_POST['semester']);
					// if ($semester !=''){
					// 	if (is_numeric($semester)){
					// 		if($semester >= 4){
					// 			$validSemester=TRUE;
					// 		}else{
					// 			$errorSemester='Semester minimal 4';
					// 			$validSemester=FALSE;
					// 		}
					// 	}else{
					// 		$errorSemester='isi dengan angka';
					// 		$validSemester=FALSE;
					// 	}
					// }else{
					// 	$errorSemester='wajib diisi';
					// 	$validSemester=FALSE;
					// }
					
					// $ipk=test_input($_POST['ipk']);
					// if($ipk == ''){
					// 	$errorIpk='wajib diisi';
					// 	$validIpk= false;
					// }else{
					// 	if(is_numeric($ipk)){
					// 		$ipk=floatval($ipk);
					// 		//$syarat=floatval($rSyaratIpk['syarat_ipk']);
					// 		//$minIpk=$fSyaratDaftar->syarat_ipk;
					// 		// if(($ipk>=$syarat)&&($ipk<=4)){
					// 		// 	$validIpk=true;
					// 		// }
					// 		$validIpk=true;
					// 	}else{
					// 		$errorIpk='hanya angka dan menggunakan titik(.) untuk bilangan desimal';
					// 		$validIpk=false;
					// 	}
					// }
					
					$tgl_krs=test_input($_POST['tgl_krs']);
					$tgl_lulus=test_input($_POST['tgl_lulus']);
					$tgl_daftar=test_input($_POST['tgl_daftar']);
					$tahun_ajaran=test_input($_POST['periode']);

					$temp = explode(".", $_FILES["file"]["name"]);
					$extension = end($temp);
					$validTranskrip = true;
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

					$tempToefl = explode(".", $_FILES["toefl"]["name"]);
					$extensionToefl = end($temp);
					$validToefl = true;
					$targetToefl="../toefl/";
					$pathToefl=md5(microtime()).'.'.$extension;

					
					if (($_FILES["toefl"]["size"] < 2000000)){
						if ($_FILES["toefl"]["error"] > 0){
						   switch ($_FILES["toefl"]["error"]) {
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
							$mimeToefl = finfo_file($finfo, $_FILES['toefl']['tmp_name']);
							
							if ($mimeToefl == 'application/pdf') {
								$validTranskrip = true;
							}else{
							    $errorToefl="Unknown/not permitted file type";
							    $validToefl = false;
							}

						}
					}else{
						$errorToefl= "Ukuran file melebihi ukuran maksimum 2MB";
					}

					$pembimbing3=test_input($_POST['pembimbing3']);
					if($pembimbing3!=''){
						$cekPembimbing3=mysqli_num_rows(mysqli_query($con,"SELECT nip from pembimbing_luar where nip='".$pembimbing3."' "));

						if($cekPembimbing3==0){
							$errorPembimbing3='Dosen Pembimbing belum terdaftar';
							$validNip=false;
						}else{
							$nipPembimbing3=$pembimbing3;
							$validNip=true;
						}
					}else{
						$validNip=true;
					}
					
					if($validPembimbing1&&$validPembimbing2&&$validNip&&$validJudul&&$validKomulatif&&$validSks&&$validTranskrip){				
						if(!isset($_POST['nipPembimbing3'])){
							$errorNipPembimbing3='NIP wajib diisi';
							$validNip=false;
						}else{
							$validNip=true;
						}

						if(!isset($_POST['namaPembimbing3'])){
							$errorNamaPembimbing3='Nama wajib diisi';
							$validNama=false;
						}else{
							$validNama=true;
						}

						if(!isset($_POST['namaInstansi'])){
							$errorNamaInstansi='Nama Instansi wajib diisi';
							$validInstansi=false;
						}else{
							$validInstansi=true;
						}

						if(!isset($_POST['noTelpPembimbing3'])){
							$errorNoTelpPembimbing3='Nomor Telepon wajib diisi';
							$validNoTelp=false;
						}else{
							if(!is_numeric($noTelpPembimbing3)){
								$errorNoTelpPembimbing3='Hanya menerima angka';
								$validNoTelp=false;
							}else{
								$validNoTelp=true;
							}
						}
						if(!isset($_POST['emailPembimbing3'])){
							$errorEmailPembimbing3='E-mail wajib diisi';
							$validEmail=false;
						}else{
							$validEmail=true;
						}

						if(!isset($_POST['alamatPembimbing3'])){
							$errorEmailPembimbing3='Alamat wajib diisi';
							$validAlamat=false;
						}else{
							$validAlamat=true;
						}

						if($validNip&&$validNama&&$validAlamat&&$validInstansi&&$validEmail&&$validNoTelp){
							$nipPembimbing3=test_input($_POST['nipPembimbing3']);
							$namaPembimbing3=test_input($_POST['namaPembimbing3']);
							$namaInstansi=test_input($_POST['namaInstansi']);
							$noTelpPembimbing3=test_input($_POST['noTelpPembimbing3']);
							$emailPembimbing3=test_input($_POST['emailPembimbing3']);
							$alamatPembimbing3=test_input($_POST['alamatPembimbing3']);
							$tambah_pembimbing3=mysqli_query($con,"INSERT INTO pembimbing_luar (nip, nama, instansi, alamat, no_telp, email) VALUES ('".$nipPembimbing3."', '".$namaPembimbing3."', '".$namaInstansi."', '".$alamatPembimbing3."', '".$noTelpPembimbing3."', '".$emailPembimbing3."') ");
						}
						if(move_uploaded_file($_FILES['file']['tmp_name'], $target.$path)){
							$edit_pendaftar = mysqli_query($con,"UPDATE daftar_skripsi SET nip1='".$pembimbing1."',nip2='".$pembimbing2."',nip3='".$pembimbing3."',judul='".$judul."', sks_komulatif = '".$sks_komulatif."',sks_semester = '".$sks_semester."', path_file='".$path."', path_toefl='".$pathToefl."' where  nim = '".$nim."' and id_daftar_skripsi='".$id."' " );
						
						}else {
							$edit_pendaftar = mysqli_query($con,"UPDATE daftar_skripsi SET nip1='".$pembimbing1."',nip2='".$pembimbing2."',nip3='".$pembimbing3."',judul='".$judul."', sks_komulatif = '".$sks_komulatif."',sks_semester = '".$sks_semester."' where  nim = '".$nim."' and id_daftar_skripsi='".$id."' " );
						}
								
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
			<title>Form Edit Tugas Akhir</title>
			
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="row panel-heading" style="margin-left: 0px;
    margin-right: 0px;">
    					<a href="daftar_uji_skripsi.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
						Edit Mahasiswa Tugas Akhir
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
								<?php
									if ($sukses && isset($pesan_sukses)){
										echo '<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>';
									} if (!$sukses && isset($pesan_gagal)){
										echo'<div class="alert alert-danger" role="alert">'.$pesan_gagal.'</div>';
									}
								?>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>NIM</label>&nbsp;
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>Nama</label>&nbsp;
									</div>
								</div>
								<div class="row">
									<input type="text" name="id" id="id" hidden value="<?php echo $id;?>">
									<input type="text" name="saveid" hidden value="<?php if(isset($id)){echo $id;}else $saveid; ?>">
									<input type="text" name="savenim" hidden value="<?php echo $nim;?>">
									<input type="text" name="savenama" hidden value="<?php echo $nama;?>">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" list="nim" id='input_nim' name="nim" value="<?php if(isset($nim)){echo $nim;}else{echo $savenim;} ?>" readonly autofocus">
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php if(isset($nama)){echo $nama;}else{echo $savenama;} ?>" >
									</div>
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
									<label>Pembimbing 2</label>&nbsp;<?php if(isset($errorPembimbing2)) echo $errorPembimbing2;?></span>
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
								<div class="form-group" id="field_pembimbing3">
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
								</div>
								<div id="tambah_pembimbing"></div>
									<div class="icon_tambah form-group">
       									<button class="btn btn-success" type="button" value="tambah pembimbing" onclick="tambah_pembimbing();"> Tambah Pembimbing 3  <span class="glyphicon glyphicon-plus"></span> </button>
      								</div>
								<div class="form-group">
									<label>Judul</label>&nbsp;
									<textarea class="form-control" name="judul" id="judul" placeholder="masukan judul skripsi" cols="26" rows="5" required maxlength="150"><?php if(isset($judul)){echo $judul;}?></textarea>
								</div>
								<div class="row">
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Periode Pendaftaran</label>&nbsp;
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Tanggal Daftar KRS</label>&nbsp;
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Tanggal Daftar Tugas Akhir</label>&nbsp;
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input class="form-control" name="periode" id="periode" value="<?php if(isset($tahun_ajaran)){echo $tahun_ajaran;}?>" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input class="form-control" name="tgl_krs" id="tgl_krs" value="<?php if(isset($tgl_krs)){echo $tgl_krs;}?>" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input class="form-control" name="tgl_daftar" id="tgl_daftar" value="<?php if(isset($tgl_daftar)){echo $tgl_daftar;}?>" readonly>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Jumlah SKS Semester</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorSks)) echo $errorSks;?></span>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Jumlah SKS Komulatif</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKomulatif)) echo $errorKomulatif;?></span>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Tanggal Lulus Tugas Akhir</label>&nbsp;
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input class="form-control" name="sks_semester" id="sks_semester" value="<?php if(isset($sks_semester)){echo $sks_semester;}?>">
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input class="form-control" name="sks_komulatif" id="sks_komulatif" value="<?php if(isset($sks_komulatif)){echo $sks_komulatif;}?>">
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input class="form-control" name="tgl_lulus" id="tgl_lulus" value="<?php if(!is_null($tgl_lulus)) { echo $tgl_lulus;}else{echo "Belum/Tidak Lulus";}?>" readonly>
									</div>
									<br><br>
								</div>
								<div class="form-group">
									<label>Upload Trasnkrip Lengkap (.pdf)</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorTranskrip)) echo $errorTranskrip;?></span>
									<input type="file" name="file" size="50" accept="application/pdf" autofocus value="<?php if(!$sukses&&!$validTranskrip){echo $mime;} ?>">
								</div>
								<div class="form-group">
									<label>Upload TOEFL (.pdf)</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorToefl)) echo $errorToefl;?></span>
									<input type="file" name="toefl" size="50" accept="application/pdf" autofocus value="<?php if(!$sukses&&!$validToefl){echo $mimeToefl;} ?>">
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
	<script type="text/javascript">
			function tambah_pembimbing() {
				// pembimbing3++;
				var cpembimbing3 = 1;
				var tempat_tujuan = document.getElementById('tambah_pembimbing')
				var add_div = document.createElement("div");
				add_div.setAttribute("class", "form-group removeclass"+cpembimbing3);
				// var rdiv = 'removeclass'+pembimbing3;
				add_div.innerHTML = '<div class="form-group"><label> Tambah Pembimbing 3 Baru</label>&nbsp;<span class="label label-warning"></span></div><div class="col-sm-12 nopadding"><div class="form-group"><label>NIP</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNipPembimbing3)) echo $errorNipPembimbing3;?></span><input type="text" class="form-control" id="nipPembimbing3" name="nipPembimbing3" placeholder="contoh: 1234567890" maxlength="18" required autofocus ></div><div class="form-group"><label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNamaPembimbing3)) echo $errorNamaPembimbing3;?></span><input type="text" class="form-control" id="namaPembimbing3" name="namaPembimbing3" placeholder="contoh: Hadi Kusuma, S.Kom" maxlength="30" required autofocus ></div><div class="form-group"><label>Nama Instansi</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNamaInstansi)) echo $errorNamaInstansi;?></span><input type="text" class="form-control" id="namaInstansi" name="namaInstansi" placeholder="contoh: Universitas A" maxlength="30" required autofocus ></div><div class="form-group"><label>Alamat</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorAlamatPembimbing3)) echo $errorAlamatPembimbing3;?></span><input type="text" class="form-control" id="alamatPembimbing3" name="alamatPembimbing3" placeholder="contoh: Jalan Jend. Sudirman No.9" maxlength="100" required autofocus ></div><div class="form-group"><label>Nomor Telepon</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNoTelpPembimbing3)) echo $errorNoTelpPembimbing3;?></span><input type="text" class="form-control" id="noTelpPembimbing3" name="noTelpPembimbing3" placeholder="contoh: 081234567890" maxlength="20" required autofocus ></div><div class="form-group"><label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmailPembimbing3)) echo $errorEmailPembimbing3;?></span><input type="email" class="form-control" id="emailPembimbing3" name="emailPembimbing3" placeholder="contoh: hadi@gmail.com" maxlength="50" required autofocus></div></div><div class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_fields('+ cpembimbing3 +');">Hapus Pembimbing 3  <span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div><div class="clear"></div>';
				$('.icon_tambah').hide();
				$('#field_pembimbing3').hide();
				tempat_tujuan.appendChild(add_div);
			}
			function remove_fields(rid) {
				$('.icon_tambah').show();
				$('#field_pembimbing3').show();
				$('.removeclass'+rid).remove();
			}
			
		</script>
</body>
</html>

<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>