<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		//$sukses=false;

		$show_nim_pendaftar_uji_kelayakan=mysqli_query($con, "SELECT DISTINCT uji_kelayakan.nim, uji_kelayakan.id_uji_kelayakan, tahun_ajaran from  daftar_uji_kelayakan INNER join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join uji_kelayakan on daftar_uji_kelayakan.id_daftar_uji_kelayakan=uji_kelayakan.id_uji_kelayakan  where uji_kelayakan.is_lulus='0' ");
		
		$show_pendaftar_uji_kelayakan=mysqli_query($con, "SELECT * FROM daftar_uji_kelayakan ");
		$fshow_pendaftar_uji_kelayakan=$show_pendaftar_uji_kelayakan->fetch_object();

		
		

		//$cek_nim=mysqli_query($con, "SELECT nim from penguji_kelayakan inner join bimbingan on bimbingan.id_bimbingan=penguji_kelayakan.id_bimbingan where bimbingan.nim='$nim' ");
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['submit'])) {
				$nim=test_input($_POST['nim']);
				$id=test_input($_POST['id']);
				$cek_id=mysqli_query($con, "SELECT id_uji_kelayakan from uji_kelayakan where id_uji_kelayakan='$id' ");
				$rcek_id = $cek_id->fetch_object();
				if($cek_id->id_uji_kelayakan){
					$validId = true;
				}else {
					$validId = false;
					$errorId = 'Mahasiswa tidak terdaftar';
				}
				$nipPenguji1=test_input($_POST['nipPenguji1']);
				$nipPenguji2=test_input($_POST['nipPenguji2']);
				$nipPenguji3=test_input($_POST['nipPenguji3']);
				$nipPenguji4=test_input($_POST['nipPenguji4']);

				$nilai_penguji_lab1=test_input($_POST['nilai_penguji_lab1']);
				$nilai_penguji_lab2=test_input($_POST['nilai_penguji_lab2']);
				$nilai_penguji_lab3=test_input($_POST['nilai_penguji_lab3']);
				$nilai_penguji_lab4=test_input($_POST['nilai_penguji_lab4']);
				// if (is_numeric($nilai_penguji_lab4)){
				// 	$nilaiFloat4=floatval($nilai_penguji_lab4);
				// 	if (($nilaiFloat4 >= 0) && ($nilaiFloat4 <= 100)){
				// 		$valid_nilai_penguji_lab4=TRUE;
				// 	}else{
				// 		$error_nilai_penguji_lab4='isi dengan nilai diantara 0-100, nilai desimal dipisah dengan titik(.)';
				// 		$valid_nilai_penguji_lab4=FALSE;
				// 	}
				// }else{
				// 	$error_nilai_penguji_lab4='isi dengan nilai diantara 0-100, nilai desimal dipisah dengan titik(.)';
				// 	$valid_nilai_penguji_lab4=FALSE;
				// }
				$is_lulus=test_input($_POST['is_lulus']);

				$penguji = mysqli_query($con,"SELECT dosen.nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan where penguji_kelayakan.id_uji_kelayakan='$id' "); 
				$jml_penguji = mysqli_num_rows($penguji);
				$counter=0;
				$validNilai=0;
				if(isset($is_lulus)){
					for($i =1; $i<=4; $i++){
						if($_POST['nilai_penguji_lab'.$i]){
							$counter++;
						} 
						if(${"nilai_penguji_lab".$i} !=''){
							if (is_numeric(${"nilai_penguji_lab".$i})){
								${"nilaiFloat".$i}=floatval(${"nilai_penguji_lab".$i});
								if ((${"nilaiFloat".$i} >= 0) && (${"nilaiFloat".$i} <= 100)){
									${"valid_nilai_penguji_lab".$i}=$validNilai++;
								}else{
									${"error_nilai_penguji_lab".$i} = 'isi dengan nilai diantara 0-100, nilai desimal dipisah dengan titik(.)';
								}
							}else{
								${"error_nilai_penguji_lab".$i} = 'isi dengan angka';
							}
						}
					}
				}else{
					$error_is_lulus="harus diisi";
				}

				if(($counter==$validNilai)&&($counter==$jml_penguji) && $validId){
					for($j=1;$j<=$counter; $j++){
						$nilai=${'nilaiFloat'.$j};
						$nip=$_POST['nipPenguji'.$j];
						$tambah_nilai=mysqli_query($con, "UPDATE penguji_kelayakan set nilai='".$nilai."' where nip_penguji_kelayakan='".$nip."' and id_uji_kelayakan='".$id."' ");
						$ubah_is_lulus=mysqli_query($con, "UPDATE uji_kelayakan set is_lulus='".$is_lulus."', is_test='sudah' where nim='".$nim."' and id_uji_kelayakan='".$id."' ");
					}
					if($tambah_nilai && $ubah_is_lulus){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data nilai & keterangan lulus/tidak";
					}else{
						$sukses=false;
						$pesan_gagal="tidak Berhasil memasukkan data";
					}
				}
				else{
					$sukses=false;
					$pesan_gagal="Data yang dimasukkan tidak sesuai";
				}
/*
			if($valid_nilai_penguji_lab1 && $valid_nilai_penguji_lab2){
				$insert_nilai1 = $con->query("UPDATE penguji_kelayakan SET nilai= '$nilai_penguji_lab1' WHERE   ")
				if()

					if ($fcek_sudah_daftar_tr1==1){
						if(move_uploaded_file($_FILES['file']['tmp_name'], $target.$path)){
							if ($fcek_sudah_daftar_tr2==0){
				    			$insert_pendaftar = $con->query("INSERT INTO daftar_tugas_riset2 (nim, sks_komulatif,sks_semester,tgl_krs,tgl_daftar, path_file) VALUES ('".$_SESSION['user']."','".$komulatif."','".$sks."', '".$krs."',CURDATE(), '".$path."' )" );
								$insert_judul_tr2 = $con->query("UPDATE judul SET judul_tr2='".$fjudul_tr1->judul_tr1."' WHERE judul.nim='".$_SESSION['user']."' ");
				    		}else{
				    			$insert_pendaftar = $con->query("UPDATE daftar_tugas_riset2 SET nim = '".$_SESSION['user']."', sks_komulatif = '".$komulatif."',sks_semester = '".$sks."',tgl_krs = '".$krs."',tgl_daftar = CURDATE() ,path_file = '".$path."' " );
				    		}
				    		if (!$insert_pendaftar) {
								if(!$insert_judul_tr2){
									die("Tidak dapat menjalankan query database: <br>".$con->error);
								}else{
									die("Tidak dapat menjalankan query database: <br>".$con->error);
								}
							}else{
								$sukses=TRUE;
								$pesan_sukses="Berhasil menambahkan data.";	
							}
						}else {
			    			$sukses=FALSE;
							$pesan_gagal="Gagal mengupload file.";
						}
					}else{
						$sukses=FALSE;
						$pesan_gagal="Anda belum mendaftar Tugas Riset 1.";
					}
				}else{
					$sukses=FALSE;
					$pesan_gagal="Data yang diinputkan tidak valid";
				}
				*/
			}
		}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Input Nilai</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$('#input_id').change(function(){
				if($("#input_id").val()==undefined){
					var id="";
				}else{
					var id= $("#input_id").val();
				}
				$.ajax({
				    type: "POST",
				    data: "id="+ id,
					url:"autopopulate_input_nilai_uji_kelayakan.php",
					dataType:"json",
					cache: false,
				    success: function(response){
				    	$('#nim').val(response.nim);
					    $('#nama').val(response.nama);  
					    $('#jadwal').val(response.jadwal);
					    $('#tempat').val(response.tempat);
					    $('#pembimbing1').val(response.pembimbing1);
					    $('#pembimbing2').val(response.pembimbing2);
					    $('#pembimbing3').val(response.pembimbing3);
					    $('#judul').val(response.judul);
					    $('#penguji_lab1').val(response.namaPenguji1);
					    $('#penguji_lab2').val(response.namaPenguji2);
					    $('#penguji_lab3').val(response.namaPenguji3);
					    $('#penguji_lab4').val(response.namaPenguji4);
					    $('#nipPenguji1').val(response.nipPenguji1);
					    $('#nipPenguji2').val(response.nipPenguji2);
					    $('#nipPenguji3').val(response.nipPenguji3);
					    $('#nipPenguji4').val(response.nipPenguji4);
						for(var i=4;i>response.jml_penguji;i--){
							document.getElementById('nilai_penguji_lab'+i).readOnly=true;
						}
					},
					error: function(response){
						console.log(response);
						alert('Mahasiswa tidak terdaftar dalam Laboratorium ini');
						
					}
			    });

			});
		});
	</script>
</head>
<body>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="home_lab.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Input Nilai Uji Kelayakan
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
								<?php
									if ((isset($sukses))&&($sukses)){
								?>
										<div class="alert alert-success"><?php if(isset($pesan_sukses)) echo $pesan_sukses;?></div>
								<?php
									} if ((isset($sukses))&&(!$sukses)){
								?>
										<div class="alert alert-danger"><?php if(isset($pesan_gagal)) echo $pesan_gagal;?></div>
								<?php
									}
								?>
								
								<div class="form-group"><span class="label label-warning"><?php if(isset($errorId)) echo $errorId;?></span>
									<input class="form-control" list="id" id='input_id' name="id" placeholder="-- Masukkan NIM Mahasiswa--" required autofocus">
									<datalist id="id">
									<option></option>		
<?php
										while($fshow_nim_pendaftar_uji_kelayakan=$show_nim_pendaftar_uji_kelayakan->fetch_object()){ 
?>
												<option value="<?php echo $fshow_nim_pendaftar_uji_kelayakan->id_uji_kelayakan?>">
<?php 	
												echo $fshow_nim_pendaftar_uji_kelayakan->nim.' (';

												$periode=$fshow_nim_pendaftar_uji_kelayakan->tahun_ajaran;
												for ($i=0; $i <9 ; $i++) { 
													echo $periode[$i];
												}
												echo ( $periode[10] =='1')?' Ganjil)':' Genap)';
?>
												</option>
<?php 
										}
?>
									</datalist>
								</div>
								
								<input type="text" name="nipPenguji1" id="nipPenguji1" hidden>
								<input type="text" name="nipPenguji2" id="nipPenguji2" hidden>
								<input type="text" name="nipPenguji3" id="nipPenguji3" hidden>
								<input type="text" name="nipPenguji4" id="nipPenguji4" hidden>
								<div class="form-group">
								<label>NIM</label>&nbsp;
									<input class="form-control" type="text" id="nim" name="nim" readonly>
								</div>
								<div class="form-group">
									<label>Nama</label>&nbsp;
									<input class="form-control" type="text" id="nama" name="nama" readonly>
								</div>
								<div class="form-group">
									<label>Tanggal & Waktu</label>&nbsp;
									<input class="form-control" name="jadwal" id="jadwal" readonly>
								</div>
								<div class="form-group">
									<label>Tempat</label>&nbsp;
									<input class="form-control" name="tempat" id="tempat" readonly>
								</div>
								<div class="form-group">
									<label>Pembimbing 1</label>&nbsp;
									<input class="form-control" type="text" name="pembimbing1" id="pembimbing1" readonly>
								</div>
								<div class="form-group">
									<label>Pembimbing 2</label>&nbsp;
									<input class="form-control" type="text" name="pembimbing2" id="pembimbing2" readonly>
								</div>
								<div class="form-group">
									<label>Pembimbing 3</label>&nbsp;
									<input class="form-control" type="text" name="pembimbing3" id="pembimbing3" readonly>
								</div>
								<div class="form-group">
									<label>Judul</label>&nbsp;
									<textarea class="form-control" name="judul" id="judul" cols="26" rows="5" maxlength="150" readonly > </textarea>
								</div>

									
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji Lab 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_lab1)) echo $error_nilai_penguji_lab1;?></span>
										<input class="form-control" name="penguji_lab1" id="penguji_lab1" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_lab1" id="nilai_penguji_lab1">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji Lab 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_lab2)) echo $error_nilai_penguji_lab2;?></span>
										<input class="form-control" name="penguji_lab2" id="penguji_lab2" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_lab2" id="nilai_penguji_lab2">
									</div>
								</div>
						
								
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji Lab 3</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_lab3)) echo $error_nilai_penguji_lab3;?></span>
										<input class="form-control" name="penguji_lab3" id="penguji_lab3" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_lab3" id="nilai_penguji_lab3">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji Lab 4</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_lab4)) echo $error_nilai_penguji_lab4;?></span>
										<input class="form-control" name="penguji_lab4" id="penguji_lab4" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_lab4" id="nilai_penguji_lab4">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Mahasiswa dinyatakan LULUS?</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_is_lulus)) echo $error_is_lulus;?></span>
										</div>
										<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input type="radio" name='is_lulus' id='is_lulus' value='1' required> Ya
										<input type="radio" name='is_lulus' id='is_lulus' value='0'> Tidak
									</div>

								</div>
								<br><br>
								<div class="form-group">
									<input class="form-control btn-primary" type="submit" name="submit" value="Submit Nilai" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>