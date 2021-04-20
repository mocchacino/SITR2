<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
			$sukses=FALSE;
			$validNim=''; $validKomulatif=''; $validPembimbing1=''; $validSks=''; $validPembimbing2=''; $validSemester=''; $validIpk='';
			ini_set('display_errors', 1);
			//if ($_SERVER['REQUEST_METHOD'] === 'GET'){
				$get_nim=mysqli_query($con,"SELECT nim from tr1 where tr1.nim not in (select nim from daftar_tugas_riset2)" );
				$qpembimbing1=mysqli_query($con,"SELECT distinct nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where idlab=idlab_tr1");
				$qpembimbing2=mysqli_query($con,"SELECT distinct nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab");
				$qpembimbing3=mysqli_query($con,"SELECT distinct nip,nama FROM pembimbing_luar");
				$tahunAjaran=mysqli_query($con,"SELECT deskripsi from misc where judul='tahun_ajaran' ");
				$rTahunAjaran=$tahunAjaran->fetch_assoc();

				$syaratIpk=mysqli_query($con,"SELECT syarat_ipk FROM waktu where nama='TR2' ");
				$rSyaratIpk=$syaratIpk->fetch_assoc();
			//}		
			

			// eksekusi tombol daftar
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				$get_nim=mysqli_query($con,"SELECT nim from tr1 where tr1.nim not in (select nim from daftar_tugas_riset2)" );
				$qpembimbing1=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where idlab=idlab_tr1");
				$qpembimbing2=mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab");
				$qpembimbing3=mysqli_query($con,"SELECT nip,nama FROM pembimbing_luar");
				$tahunAjaran=mysqli_query($con,"SELECT deskripsi from misc where judul='tahun_ajaran' ");
				$rTahunAjaran=$tahunAjaran->fetch_assoc();

				$syaratIpk=mysqli_query($con,"SELECT syarat_ipk FROM waktu where nama='TR2' ");
				$rSyaratIpk=$syaratIpk->fetch_assoc();
				if ((isset($_POST['daftar'])) && (isset($_POST['lulus_toefl'])) && (isset($_FILES['file']))) {
					$nim=test_input($_POST['nim']);
					$cekMhs=mysqli_num_rows(mysqli_query($con,"SELECT nim from tr1 where tr1.nim not in (select nim from daftar_tugas_riset2) and nim='".$nim."'"));
					$cekTemporary=mysqli_query($con,"SELECT tgl_lulus from tr1 where nim='".$nim."' and tgl_lulus is not null");
					$cekLulusTr1=mysqli_num_rows($cekTemporary);
					

					if($nim==''){
						$errorNim='NIM wajib diisi';
						$validNim=false;
					}else{
						if($cekMhs==0){
							$errorNim='Hanya mahasiswa yang belum terdaftar di Tugas Riset 2';
							$validNim=false;
						}else{
							$validNim=true;
						}
					}
					
					$pembimbing1=test_input($_POST['pembimbing1']);
					$cekPembimbing1=mysqli_num_rows(mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where idlab=idlab_tr1 and nip='".$pembimbing1."' "));
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
					$cekPembimbing2=mysqli_num_rows(mysqli_query($con,"SELECT nip,nama_dosen FROM dosen inner join tr1 on tr1.idlab_tr1=idlab where nip='".$pembimbing2."' "));
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
								$errorSks='sks berjalan min 7, max 24';
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
							$syarat=floatval($rSyaratIpk['syarat_ipk']);
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
					
					

					if($validNim&&$validPembimbing1&&$validPembimbing2&&$validNip&&$validJudul&&$validKomulatif&&$validKrs&&$validSks&&$validSemester&&$validIpk&&$validTranskrip){
						if($cekLulusTr1!=0){
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
							
				// 			die();
							if(move_uploaded_file($_FILES['file']['tmp_name'], $target.$path)){
								$insert_pendaftar = mysqli_query($con,"INSERT INTO daftar_tugas_riset2 (nim,nip1,nip2,nip3, sks_komulatif,sks_semester,tgl_krs,tgl_daftar, path_file,judul,tahun_ajaran,semester, ipk) VALUES ('".$nim."', '".$pembimbing1."', '".$pembimbing2."', '".$nipPembimbing3."','".$komulatif."','".$sks."', '".$krs."',NOW(), '".$path."', '".$judul."', '".$rTahunAjaran['deskripsi']."', '".$semester."', '".$ipk."' )");
							}
				// 			die(print_r(mysqli_error($con)));
							if($insert_pendaftar && $tambah_pembimbing3){
								$sukses=TRUE;
								$pesan_sukses="Berhasil menambahkan data Pendaftarabn TR2 dan data Dosen/Pembimbing dari Luar";
							}else if($insert_pendaftar){
								$sukses=TRUE;
								$pesan_sukses="Berhasil menambahkan data Pendaftaran TR2";
							}else if($tambah_pembimbing3){
								$sukses=TRUE;
								$pesan_sukses="Berhasil menambahkan data Dosen/Pembimbing dari Luar";
							}else{
							 //   die(print_r($insert_pendaftar));
								$sukses=FALSE;
								$pesan_gagal="Tidak berhasil menambahkan data";
							}
						}else{
							$sukses=FALSE;
							$pesan_gagal="Mahasiswa belum lulus Tugas Riset 1";
						}
					}else{
						$sukses=FALSE;
						$pesan_gagal="Data yang diinputkan tidak valid";
					}
						
				}else{
					$sukses=FALSE;
					$pesan_gagal="Silahkan lengkapi data yang dibutuhkan";
				}
			}
// 			$validNim&&$validPembimbing1&&$validPembimbing2&&$validNip&&$validJudul&&$validKomulatif&&$validKrs&&$validSks&&$validSemester&&$validIpk&&$validTranskrip)
// 			die($_FILES["file"]["error"]);
			
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
									<div class="form-group">
										<label>NIM</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNim)) echo $errorNim;?></span>
										<input class="form-control" list="nim" id='input_nim' name="nim" placeholder="--Masukkan NIM Mahasiswa--" required autofocus value="<?php if(!$sukses&&$validNim){echo $nim;} ?>">
										<datalist id="nim">
										<option></option>
										<?php
											while($fget_nim=$get_nim->fetch_object()){ ?>
													<option value="<?php echo $fget_nim->nim;?>"> <?php echo $fget_nim->nim;?>
													</option>
												<?php 
											}
										?>
										</datalist>
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;
										<input class="form-control" type="text" id="nama" name="nama" readonly>
									</div>
									<div class="form-group">
										<label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPembimbing1)) echo $errorPembimbing1;?></span>
										<input class="form-control" list="pembimbing1" id='input_pembimbing1' name="pembimbing1" placeholder="--Masukkan NIP--" required autofocus >
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
										<input class="form-control" list="pembimbing2" id='input_pembimbing2' name="pembimbing2" placeholder="--Masukkan NIP--" required autofocus >
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
										<input class="form-control" list="pembimbing3" id='input_pembimbing3' name="pembimbing3" placeholder="--Masukkan NIP--" autofocus >
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
										<label>Judul Tugas Riset 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorJudul)) echo $errorJudul;?></span>
										<textarea class="form-control" name="judul" id="judul" placeholder="masukan judul skripsi" cols="26" rows="5" required maxlength="150" value="<?php if(!$sukses&&$validJudul){echo $judul;} ?>"></textarea>
									</div>
									<div class="form-group">
										<label>SKS Komulatif</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKomulatif)) echo $errorKomulatif;?></span>
										<input class="form-control" type="text" name="komulatif" required autofocus pattern="^[0-9]{,3}$" value="<?php if(!$sukses&&$validKomulatif){echo $komulatif;} ?>">
									</div>
									<div class="form-group">
										<label>Jumlah SKS Semester Berjalan</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorSks)) echo $errorSks;?></span>
										<input class="form-control" type="text" name="sks" required autofocus pattern="^[0-9]{,2}$" value="<?php if(!$sukses&&$validSks){echo $sks;} ?>">
									</div>
									<div class="form-group">
										<label>Semester</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorSemester)) echo $errorSemester;?></span>
										<input class="form-control" type="text" name="semester" required autofocus pattern="^[0-9]{,2}$" value="<?php if(!$sukses&&$validSemester){echo $semester;} ?>">
									</div>
									<div class="form-group">
										<label>IPK</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIpk)) echo $errorIpk;?></span>
										<input class="form-control" type="text" name="ipk" required autofocus value="<?php if(!$sukses&&$validIpk){echo $ipk;} ?>">
									</div>
									<div class="form-group">
										<label>Tanggal Submit KRS</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKrs)) echo $errorKrs;?></span>
										<input class="form-control" type="date" name="krs" required autofocus value="<?php if(!$sukses&&!$validKrs){echo $krs;} ?>" max="<?php echo date('Y-m-d');?>" >
									</div>
									<div class="form-group">
										<input type="checkbox" name="lulus_toefl" value="check" id="lulus_toefl" required /> Mahasiswa sudah '<b>Lulus TOEFL</b> dengan <b>skor minimal 400</b>' <br>
									</div>
									<div class="form-group">
										<div class="form-group">
											<label>Upload Trasnkrip Lengkap (.pdf max 2MB)</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorTranskrip)) echo $errorTranskrip;?></span>
											<input type="file" name="file" size="50" accept="application/pdf" required autofocus value="<?php if(!$sukses&&!$validTranskrip){echo $mime;} ?>">
										</div>
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
			function autocomplete(nim){
				$.ajax({
				    type: "POST",
				    data: 'nim='+nim,
					url:"autopopulate_admin_pendaftaran_tr2.php",
					dataType:"json",
					cache: false,
				    success: function(response){
				    	console.log(response);
					    $('#nama').val(response.nama);  
					    $('#judul').val(response.judul);
					    $('#input_pembimbing1').val(response.pembimbing1);
					    $('#input_pembimbing2').val(response.pembimbing2);
					    $('#input_pembimbing3').val(response.pembimbing3);
						
					},
					error: function(response){
						alert('Mahasiswa belum terdaftar pada Tugas Riset 1');
					}
			    });
			}
			$(document).ready(function(){
				var nim= $("#input_nim").val();
				if(nim!=''){
					autocomplete(nim);
				}
				$('#input_nim').change(function(){
					if($("#input_nim").val()==undefined){
						var nim="";
					}else{
						var nim= $("#input_nim").val();
					}
					autocomplete(nim);

				});

			});
		</script>
		<?php
		include_once('../footer.php');
		$con->close();
}else{
	header("Location:./");
}
?>