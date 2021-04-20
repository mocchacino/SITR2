<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		
		$sukses=TRUE;
		ini_set('display_errors', 1);
		$get_nim=mysqli_query($con,"SELECT nim from daftar_tugas_riset2 where daftar_tugas_riset2.nim not in (SELECT daftar_uji_kelayakan.nim from daftar_uji_kelayakan inner JOIN uji_kelayakan on uji_kelayakan.nim=daftar_uji_kelayakan.nim where is_lulus='1' and jadwal is not null)" );
		$tahunAjaran=mysqli_query($con,"SELECT deskripsi from misc where judul='tahun_ajaran' ");
		$rTahunAjaran=$tahunAjaran->fetch_assoc();
		$syarat_ipk=mysqli_query($con,"SELECT syarat_ipk FROM waktu where nama='UK' ");
		$rSyaratIpk=$syarat_ipk->fetch_assoc();
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			if (isset($_POST['daftar'])){
				if((isset($_POST['lembar_pengesahan'])) && (isset($_POST['draft_skripsi'])) && (isset($_POST['no_nilai_d'])) && (isset($_FILES['file']))) {
					if(isset($_POST['lembar_pengesahan']) || $_POST['lembar_pengesahan'] == 'lembar_pengesahan') {
						if(isset($_POST['draft_skripsi']) || $_POST['draft_skripsi'] == 'draft_skripsi') {
							if(isset($_POST['no_nilai_d']) || $_POST['no_nilai_d'] == 'no_nilai_d'){
								if(isset($_POST['toefl']) || $_POST['toefl'] == 'toefl'){
									$nim=test_input($_POST['nim']);
									$cekMhs=mysqli_num_rows(mysqli_query($con,"SELECT nim, tahun_ajaran from daftar_tugas_riset2 where daftar_tugas_riset2.nim not in (SELECT daftar_uji_kelayakan.nim from daftar_uji_kelayakan inner JOIN uji_kelayakan on uji_kelayakan.nim=daftar_uji_kelayakan.nim where is_lulus='1' and jadwal is not null) and nim='$nim' "));
									//$cekLulusTr2=mysqli_num_rows(mysqli_query($con,"SELECT tgl_lulus from daftar_tugas_riset2 where nim='".$nim."' and tgl_lulus is not null "));
									$tahun_ajaran=mysqli_query($con,"SELECT distinct max(tahun_ajaran) as tahun_ajaran from daftar_uji_kelayakan where nim='".$nim."' ");
									$rTahun_ajaran=$tahun_ajaran->fetch_assoc();
									$cekdaftarUjiKelayakan=mysqli_query($con,"SELECT * FROM daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.nim=daftar_uji_kelayakan.nim WHERE daftar_uji_kelayakan.nim = ".$nim." and uji_kelayakan.is_lulus='0' order by tahun_ajaran desc limit 1");
									$rCekDaftarUjiKelayakan=$cekdaftarUjiKelayakan->fetch_assoc();

									if($nim==''){
										$errorNim='NIM wajib diisi';
										$validNim=false;
									}else{
										if($cekMhs==0){
											$errorNim='Hanya mahasiswa yang sudah terdaftar di Tugas Riset 2';
											$validNim=false;
										}else{
											$validNim=true;
										}
									}
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
		
									$krs=test_input($_POST['krs']);
									if ($krs=='') {
										$errorKrs='wajib diisi';
										$validKrs=FALSE;
									}else{
										$validKrs=TRUE;
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
									
									// $ipk=test_input($_POST['ipk']);
									// if($ipk == ''){
									// 	$errorIpk='wajib diisi';
									// 	$validIpk= false;
									// }else{
									// 	if(is_numeric($ipk)){
									// 		$ipk=floatval($ipk);
									// 		$syarat=floatval($rSyaratIpk['syarat_ipk']);
									// 		//$minIpk=$fSyaratDaftar->syarat_ipk;
									// 		if(($ipk>=$syarat)&&($ipk<=4)){
									// 			$validIpk=true;
									// 		}else{
									// 			$errorIpk='Nilai IPK tidak valid';
									// 			$validIpk=false;
									// 		}
									// 	}else{
									// 		$errorIpk='hanya angka dan menggunakan titik(.) untuk bilangan desimal';
									// 		$validIpk=false;
									// 	}
									// }
		
									$temp = explode(".", $_FILES["file"]["name"]);
									$extension = end($temp);
									$validTranskrip = false;
									$target="../transkrip_uji_kelayakan/";
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
		
									if($validNim&&$validKomulatif&&$validKrs&&$validSks&&$validSemester&&$validTranskrip){
										if(move_uploaded_file($_FILES['file']['tmp_name'], $target.$path)){
											if($rCekDaftarUjiKelayakan==0){
												$insert_pendaftar = $con->query("INSERT INTO daftar_uji_kelayakan (nim, sks_komulatif,sks_semester,tgl_krs,tgl_daftar, path_file,tahun_ajaran) VALUES ('".$nim."','".$komulatif."','".$sks."', '".$krs."',NOW(), '".$path."','".$rTahunAjaran['deskripsi']."' )" );
												// $id=mysqli_query($con,"SELECT max(id_daftar_uji_kelayakan) as id from daftar_uji_kelayakan where '$nim' ");
												// $rId=$id->fetch_assoc();
												// $insert_id = $con->query("INSERT INTO uji_kelayakan (id_uji_kelayakan,nim) VALUES ('".$rId['id']."','".$nim."')" );
											}else{
												if($rTahun_ajaran['tahun_ajaran']!=$rTahunAjaran['deskripsi']){
													$insert_pendaftar = $con->query("INSERT INTO daftar_uji_kelayakan (nim, sks_komulatif,sks_semester,tgl_krs,tgl_daftar, path_file,tahun_ajaran) VALUES ('".$nim."','".$komulatif."','".$sks."', '".$krs."',NOW(), '".$path."','".$rTahunAjaran['deskripsi']."' )" );
													// $id=mysqli_query($con,"SELECT max(id_daftar_uji_kelayakan) as id from daftar_uji_kelayakan where '$nim' ");
													// $rId=$id->fetch_assoc();
													// $insert_id = $con->query("INSERT INTO uji_kelayakan (id_uji_kelayakan,nim) VALUES ('".$rId['id']."','".$nim."')" );
												}else{
													$insert_pendaftar = $con->query("UPDATE daftar_uji_kelayakan SET nim = '".$nim."', sks_komulatif = '".$komulatif."',sks_semester = '".$sks."',tgl_krs = '".$krs."',tgl_daftar = NOW() ,path_file = '".$path."' where tahun_ajaran='".$rTahunAjaran['deskripsi']."' " );
												}
											}
											if ($insert_pendaftar) {
												$sukses=TRUE;
												$pesan_sukses="Berhasil menambahkan data.";	
											}else{
												$sukses=FALSE;
												$pesan_gagal="Tidak Berhasil menambahkan data.";
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
									$pesan_gagal='Harus memiliki TOEFL minimal skor 400';
								}
							}else{
								$sukses=FALSE;
								$pesan_gagal='Tidak disarankan untuk mendaftarkan diri';
							}
						}else{
						    $sukses=FALSE;
							$pesan_gagal='Silahkan melengkapi draft skripsi terlebih dahulu';
						}
					}else{
						$sukses=FALSE;
						$pesan_gagal='Silahkan melengkapi lembar pengesahan terlebih dahulu';
					}	
				}else{
					$sukses=FALSE;
					$pesan_gagal='Silahkan melengkapi dokumen yang dibutuhkan';
				}		
			}
		}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Form Pendaftaran Uji Kelayakan</title>
</head>
<body>
<div class="row">
	<div class="col-md-12">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				Pendaftaran Uji Kelayakan
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
								<div class="form-group">
									<label>Nama</label>&nbsp;
									<input class="form-control" type="text" id="nama" name="nama" readonly>
								</div>
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
							<!-- <div class="form-group">
								<label>IPK</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIpk)) echo $errorIpk;?></span>
								<input class="form-control" type="text" name="ipk" required autofocus value="<?php if(!$sukses&&$validIpk){echo $ipk;} ?>">
							</div> -->
							<div class="form-group">
								<label>Tanggal Submit KRS</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKrs)) echo $errorKrs;?></span>
								<input class="form-control" type="date" name="krs" required autofocus value="<?php if(!$sukses&&!$validKrs){echo $krs;} ?>" max="<?php echo date('Y-m-d');?>">
							</div>
							<div class="form-group">
								<input type="checkbox" name="no_nilai_d" value="check" id="no_nilai_d" required /> Tidak memiliki '<b> nilai < C</b>' <br>

								<input type="checkbox" name="toefl" value="check" id="toefl" required /> Skor TOEFL sudah mencapai '<b> 400</b>' <br>

								<input type="checkbox" name="lembar_pengesahan" value="check" id="lembar_pengesahan" required /> Saya sudah menyiapkan '<b>Lembar Pengesahan</b>' <br>

								<input type="checkbox" name="draft_skripsi" value="check" id="draft_skripsi" required /> Saya sudah menyiapkan '<b>Draft Skripsi</b> yang sudah ditandatangani Pembimbing' <br>
							</div>
							<div class="form-group">
								<div class="form-group">
									<label>Upload Transkrip Lengkap (.pdf max 2MB)</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorTranskrip)) echo $errorTranskrip;?></span>
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
		if(nim!='') autocomplete(nim);
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