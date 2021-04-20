<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=false;	
		if ($_SERVER['REQUEST_METHOD']==='GET'){
			$nim=$_GET['nim'];
			$id=$_GET['id'];
			$show_nim_pendaftar_uji_kelayakan=mysqli_query($con, "SELECT * FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim inner join uji_skripsi on uji_skripsi.nim=daftar_skripsi.nim where uji_skripsi.is_lulus='0' ");
			
			$show_pendaftar_uji_kelayakan=mysqli_query($con, "SELECT * FROM daftar_skripsi ");
			$fshow_pendaftar_uji_kelayakan=$show_pendaftar_uji_kelayakan->fetch_object();
		}
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['edit'])) {
				$savenim=test_input($_POST['savenim']);
				$nim=test_input($_POST['nim']);
				$nipPenguji1=test_input($_POST['nipPenguji1']);
				$nipPenguji2=test_input($_POST['nipPenguji2']);
				$nipPenguji3=test_input($_POST['nipPenguji3']);
				$nipPenguji4=test_input($_POST['nipPenguji4']);
				$nipPenguji5=test_input($_POST['nipPenguji5']);
				
				$nilai_pembimbing_skripsi1=test_input($_POST['nilai_pembimbing_skripsi1']);
				$nilai_pembimbing_skripsi2=test_input($_POST['nilai_pembimbing_skripsi2']);
				$nilai_penguji_skripsi1=test_input($_POST['nilai_penguji_skripsi1']);
				$nilai_penguji_skripsi2=test_input($_POST['nilai_penguji_skripsi2']);
				$nilai_penguji_skripsi3=test_input($_POST['nilai_penguji_skripsi3']);
				$nilai_penguji_skripsi4=test_input($_POST['nilai_penguji_skripsi4']);
				$nilai_penguji_skripsi5=test_input($_POST['nilai_penguji_skripsi5']);
				
				$tgl_lulus=test_input($_POST['tgl_lulus']);
				$is_lulus=test_input($_POST['is_lulus']);
				if($is_lulus == '0') $tgl_lulus = null;
				
				$penguji = mysqli_query($con,"SELECT dosen.nama_dosen FROM dosen inner join penguji_skripsi on penguji_skripsi.nip_penguji_skripsi=dosen.nip inner join uji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.nim='$nim' "); 
				$jml_penguji = mysqli_num_rows($penguji);
				$counter=0;
				$validNilai=0;
				if(isset($is_lulus)){
					for($i =1; $i<=5; $i++){
						if($_POST['nilai_penguji_skripsi'.$i]){
							$counter++;
						} 
						if(${"nilai_penguji_skripsi".$i} !=''){
							if (is_numeric(${"nilai_penguji_skripsi".$i})){
								${"nilaiFloat".$i}=floatval(${"nilai_penguji_skripsi".$i});
								if ((${"nilaiFloat".$i} >= 0) && (${"nilaiFloat".$i} <= 100)){
									${"valid_nilai_penguji_skripsi".$i}=$validNilai++;
								}else{
									${"error_nilai_penguji_skripsi".$i} = 'isi dengan nilai diantara 0-100, nilai desimal dipisah dengan titik(.)';
								}
							}else{
								${"error_nilai_penguji_skripsi".$i} = 'isi dengan angka';
							}
						}
					}
					for($i =1; $i<=2; $i++){
						if($_POST['nilai_pembimbing_skripsi'.$i]){
							$counter++;
							${'nipPenguji'.$counter}=test_input($_POST['nipPembimbing'.$i]);
						}
						if(${"nilai_pembimbing_skripsi".$i} !=''){
							if (is_numeric(${"nilai_pembimbing_skripsi".$i})){
								${"nilaiFloat".$counter}=floatval(${"nilai_pembimbing_skripsi".$i});
								if ((${"nilaiFloat".$counter} >= 0) && (${"nilaiFloat".$counter} <= 100)){
									${"valid_nilai_pembimbing_skripsi".$i}=$validNilai++;
								}else{
									${"error_nilai_pembimbing_skripsi".$i} = 'isi dengan nilai diantara 0-100, nilai desimal dipisah dengan titik(.)';
								}
							}else{
								${"error_nilai_pembimbing_skripsi".$i} = 'isi dengan angka';
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
						$tambah_nilai=mysqli_query($con, "UPDATE penguji_skripsi set nilai='".$nilai."' where nip_penguji_skripsi='".$nip."'");
					}
					$ubah_is_lulus=mysqli_query($con, "UPDATE uji_skripsi set is_lulus='".$is_lulus."' where nim='".$nim."'");
					$ubah_is_lulus_daftar_skripsi=mysqli_query($con, "UPDATE daftar_skripsi set is_lulus='".$is_lulus."', tgl_lulus='".$tgl_lulus."' where nim='".$nim."'");
					if($tambah_nilai && $ubah_is_lulus){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data nilai & keterangan lulus/tidak";
					}elseif($ubah_is_lulus){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data lulus";
					}elseif($tambah_nilai){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data nilai";
					}elseif($ubah_is_lulus_daftar_skripsi){
						$sukses=true;
						$pesan_sukses="Berhasil memasukkan data di seluruh mahasiswa skripsi";
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
					<a href="daftar_nilai_skripsi.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Edit Nilai Ujian Tugas Akhir
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
								<input type="text" name="nipPenguji5" id="nipPenguji5" hidden>

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
										<label>Pembimbing 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_pembimbing_skripsi1)) echo $error_nilai_pembimbing_skripsi1;?></span>
										<input class="form-control" name="pembimbing_skripsi1" id="pembimbing_skripsi1" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_pembimbing_skripsi1" id="nilai_pembimbing_skripsi1">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Pembimbing 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_pembimbing_skripsi2)) echo $error_nilai_pembimbing_skripsi2;?></span>
										<input class="form-control" name="pembimbing_skripsi2" id="pembimbing_skripsi2" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_pembimbing_skripsi2" id="nilai_pembimbing_skripsi2">
									</div>
								</div>	
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji 1</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_skripsi1)) echo $error_nilai_penguji_skripsi1;?></span>
										<input class="form-control" name="penguji_skripsi1" id="penguji_skripsi1" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_skripsi1" id="nilai_penguji_skripsi1">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji 2</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_skripsi2)) echo $error_nilai_penguji_skripsi2;?></span>
										<input class="form-control" name="penguji_skripsi2" id="penguji_skripsi2" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_skripsi2" id="nilai_penguji_skripsi2">
									</div>
								</div>
						
								
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji 3</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_skripsi3)) echo $error_nilai_penguji_skripsi3;?></span>
										<input class="form-control" name="penguji_skripsi3" id="penguji_skripsi3" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_skripsi3" id="nilai_penguji_skripsi3">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji 4</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_skripsi4)) echo $error_nilai_penguji_skripsi4;?></span>
										<input class="form-control" name="penguji_skripsi4" id="penguji_skripsi4" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_skripsi4" id="nilai_penguji_skripsi4">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Penguji 5</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_nilai_penguji_skripsi5)) echo $error_nilai_penguji_skripsi5;?></span>
										<input class="form-control" name="penguji_skripsi5" id="penguji_skripsi5" readonly>
									</div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input class="form-control" name="nilai_penguji_skripsi5" id="nilai_penguji_skripsi5">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Mahasiswa dinyatakan LULUS?</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_is_lulus)) echo $error_is_lulus;?></span>
										</div>
										<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input type="radio" class="is_lulus" name='is_lulus' id='is_lulus_yes' value='1' checked="checked" required> Ya
										<input type="radio" class="is_lulus" name='is_lulus' id='is_lulus_no' value='0'> Tidak
									</div>
								</div>
								<div class="row" id="tgl_lulus" >
									<div class="form-group col-md-8 col-sm-8 col-xs-12" >
										<label>Tanggal mahasiswa LULUS</label>&nbsp;<span class="label label-warning">*<?php if(isset($error_tgl)) echo $error_tgl;?></span>
										</div>
										<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<input class="form-control" type="date" name="tgl_lulus" value="<?php echo date('Y-m-d');?>" required>
										</div>
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
	<script>
		$(document).ready(function(){
			$(":radio").click(function(){
			    var value = $(this).val();
			    if (value == '1') {
			        $("#tgl_lulus").show();
			    } else if (value == '0') {
			        $("#tgl_lulus").hide();
			    }
			});
			$('#input_nim').ready(function(){
				if($("#input_nim").val()==undefined){
					var nim="";
				}else{
					var nim= $("#input_nim").val();
				}
				$.ajax({
				    type: "POST",
				    data: 'nim='+nim,
					url:"autopopulate_edit_nilai_skripsi.php",
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
					    $('#penguji_skripsi1').val(response.namaPenguji1);
					    $('#penguji_skripsi2').val(response.namaPenguji2);
					    $('#penguji_skripsi3').val(response.namaPenguji3);
					    $('#penguji_skripsi4').val(response.namaPenguji4);
					    $('#penguji_skripsi5').val(response.namaPenguji5);
					    $('#nipPenguji1').val(response.nipPenguji1);
					    $('#nipPenguji2').val(response.nipPenguji2);
					    $('#nipPenguji3').val(response.nipPenguji3);
					    $('#nipPenguji4').val(response.nipPenguji4);
					    $('#nipPenguji5').val(response.nipPenguji5);
						for(var i=5;i>response.jml_penguji;i--){
							document.getElementById('nilai_penguji_skripsi'+i).readOnly=true;
						}
						$('#nipPembimbing1').val(response.nipPembimbing1);
						$('#nipPembimbing2').val(response.nipPembimbing2);
						$('#pembimbing_skripsi1').val(response.pembimbing1);
						$('#pembimbing_skripsi2').val(response.pembimbing2);
						if(!response.nipPembimbing2) document.getElementById('nilai_pembimbing_skripsi2').readOnly=true;
					},
					error: function(response){
						console.log(response);
						alert('Mahasiswa tidak terdaftar dalam Laboratorium ini');
						
					}
			    });

			});
		});
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