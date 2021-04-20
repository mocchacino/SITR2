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

		$sukses=false;	
		if ($_SERVER['REQUEST_METHOD']==='GET'){
			$nim=$_GET['nim'];
			$id=$_GET['id'];
			$show_nim_pendaftar_uji_kelayakan=mysqli_query($con, "SELECT uji_kelayakan.nim FROM uji_kelayakan inner join tr1 on tr1.nim=uji_kelayakan.nim where tr1.idlab_tr1='$idlab' and uji_kelayakan.is_lulus='0' and uji_kelayakan.id_uji_kelayakan='$id' ");
		}

		
		

		//$cek_nim=mysqli_query($con, "SELECT nim from penguji_kelayakan inner join bimbingan on bimbingan.id_bimbingan=penguji_kelayakan.id_bimbingan where bimbingan.nim='$nim' ");
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['edit'])) {
				$savenim=test_input($_POST['savenim']);
				$saveid=test_input($_POST['saveid']);
				$nim=test_input($_POST['nim']);
				$id=test_input($_POST['id']);
				$nipPenguji1=test_input($_POST['nipPenguji1']);
				$nipPenguji2=test_input($_POST['nipPenguji2']);
				$nipPenguji3=test_input($_POST['nipPenguji3']);
				$nipPenguji4=test_input($_POST['nipPenguji4']);

				$nilai_pembimbing_kelayakan1=test_input($_POST['nilai_pembimbing_kelayakan1']);
				$nilai_pembimbing_kelayakan2=test_input($_POST['nilai_pembimbing_kelayakan2']);
				$nilai_penguji_lab1=test_input($_POST['nilai_penguji_lab1']);
				$nilai_penguji_lab2=test_input($_POST['nilai_penguji_lab2']);
				$nilai_penguji_lab3=test_input($_POST['nilai_penguji_lab3']);
				$nilai_penguji_lab4=test_input($_POST['nilai_penguji_lab4']);

				$is_lulus=test_input($_POST['is_lulus']);
				
				$penguji = mysqli_query($con,"SELECT dosen.nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim='$nim' and penguji_kelayakan.id_uji_kelayakan='$id' "); 
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
					for($i =1; $i<=2; $i++){
						if($_POST['nilai_pembimbing_kelayakan'.$i]){
							$counter++;
							${'nipPenguji'.$counter}=test_input($_POST['nipPembimbing'.$i]);
						}
						if(${"nilai_pembimbing_kelayakan".$i} !=''){
							if (is_numeric(${"nilai_pembimbing_kelayakan".$i})){
								${"nilaiFloat".$counter}=floatval(${"nilai_pembimbing_kelayakan".$i});
								if ((${"nilaiFloat".$counter} >= 0) && (${"nilaiFloat".$counter} <= 100)){
									${"valid_nilai_pembimbing_kelayakan".$i}=$validNilai++;
								}else{
									${"error_nilai_pembimbing_kelayakan".$i} = 'isi dengan nilai diantara 0-100, nilai desimal dipisah dengan titik(.)';
								}
							}else{
								${"error_nilai_pembimbing_kelayakan".$i} = 'isi dengan angka';
							}
						}
					}
				}else{
					$error_is_lulus="harus diisi";
				}

				if(($counter==$validNilai)&&($counter==$jml_penguji)){
					for($j=1;$j<=$counter; $j++){
						$nilai=${'nilaiFloat'.$j};
						$nip=${'nipPenguji'.$j};
						$tambah_nilai=mysqli_query($con, "UPDATE penguji_kelayakan set nilai='".$nilai."' where nip_penguji_kelayakan='".$nip."'  and id_uji_kelayakan='".$id."'");
					}
					$ubah_is_lulus=mysqli_query($con, "UPDATE uji_kelayakan set is_lulus='".$is_lulus."' where nim='".$nim."'  and id_uji_kelayakan='".$id."'");
					if($tambah_nilai && $ubah_is_lulus){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data nilai & keterangan lulus/tidak";
					}elseif($ubah_is_lulus){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data lulus";
					}elseif($tambah_nilai){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data nilai";
					}else{
						$sukses=false;
						$pesan_gagal="tidak Berhasil memasukkan data";
					}
				}
				else{
					$sukses=false;
					$pesan_gagal="Data yang dimasukkan tidak sesuai";
				} 
			}
		}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Input Nilai</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="daftar_nilai.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Edit Nilai Uji Kelayakan
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  enctype="multipart/form-data">
								<?php
									if ($sukses){
										if(isset($pesan_sukses)) {
											echo '<div class="alert alert-success" role="alert">';
											echo $pesan_sukses; 
											echo '</div>';
										}
									}if (!$sukses){
										if(isset($pesan_gagal)){
											echo'<div class="alert alert-danger" role="alert">';
											echo $pesan_gagal;
											echo'</div>';
										}
									}
								?>
								<input type="text" name="savenim" hidden value="<?php echo $nim;?>">
								<input type="text" name="saveid" hidden value="<?php echo $id;?>">
								<input type="text" id='input_id' hidden name="id"  value="<?php if(isset($id)){echo $id;}else $saveid; ?>">
								<div class="form-group">
									<label>NIM</label>&nbsp;<span class="label label-warning">*</span>
									<input class="form-control" type='text' id='input_nim' name="nim" value="<?php if(isset($nim)){echo $nim;}else $savenim; ?>" readonly autofocus">
								</div>
								<input type="text" name="nipPembimbing1" id="nipPembimbing1" hidden>
								<input type="text" name="nipPembimbing2" id="nipPembimbing2" hidden>
								<input type="text" name="nipPenguji1" id="nipPenguji1" hidden>
								<input type="text" name="nipPenguji2" id="nipPenguji2" hidden>
								<input type="text" name="nipPenguji3" id="nipPenguji3" hidden>
								<input type="text" name="nipPenguji4" id="nipPenguji4" hidden>

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
										<label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_pembimbing_kelayakan1)) echo $error_nilai_pembimbing_kelayakan1;?></span>
										<input class="form-control" name="pembimbing_kelayakan1" id="pembimbing_kelayakan1" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_pembimbing_kelayakan1" id="nilai_pembimbing_kelayakan1">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Pembimbing 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_pembimbing_kelayakan2)) echo $error_nilai_pembimbing_kelayakan2;?></span>
										<input class="form-control" name="pembimbing_kelayakan2" id="pembimbing_kelayakan2" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_pembimbing_kelayakan2" id="nilai_pembimbing_kelayakan2">
									</div>
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
										<label>Mahasiswa dinyatakan LAYAK?</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_is_lulus)) echo $error_is_lulus;?></span>
										</div>
										<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input type="radio" name='is_lulus' id='is_lulus' value='1' required> Ya
										<input type="radio" name='is_lulus' id='is_lulus' value='0'> Tidak
									</div>

								</div>
								<br><br>
								<div class="form-group">
									<input class="form-control btn btn-primary" type="submit" name="edit" value="Submit Nilai" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
		$(document).ready(function(){
			$('#input_id').ready(function(){
				if($("#input_id").val()==undefined){
					var id="";
				}else{
					var id= $("#input_id").val();
				}
				$.ajax({
				    type: "POST",
				    data: 'id='+id,
					url:"autopopulate_edit_nilai.php",
					dataType:"json",
					cache: false,
				    success: function(response){
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
						$('#nipPembimbing1').val(response.nipPembimbing1);
						$('#nipPembimbing2').val(response.nipPembimbing2);
						$('#pembimbing_kelayakan1').val(response.pembimbing1);
						$('#pembimbing_kelayakan2').val(response.pembimbing2);
						if(!response.nipPembimbing2) document.getElementById('nilai_pembimbing_kelayakan2').readOnly=true;
					},
					error: function(response){
						console.log(response);
						alert('Mahasiswa tidak terdaftar dalam Laboratorium ini');
						
					}
			    });

			});
		});
	</script>
</html>

<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>