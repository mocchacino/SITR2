<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$sukses=TRUE;
		$pesan_gagal='';
		$errorStatus='';
		// eksekusi tombol daftar
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			if(isset($_POST['submit'])){
				if ((isset($_POST['awal'])) && (isset($_POST['akhir'])) &&  (isset($_POST['ganjilgenap']))) {
					$awal=test_input($_POST['awal']);
					if ($awal!=''){
						if (is_numeric($awal)){
							$awal=(int)$awal;
							if ($awal >= 2000){
								$validAwal=TRUE;
							}else{
								$errorStatus.='awal tahun ajaran min tahun 2000. ';
								$validAwal=FALSE;
							}
						}else{
							$errorStatus.='isi dengan angka. ';
							$validAwal=FALSE;
						}
					}else{
						$errorStatus.='wajib diisi';
						$validAwal=FALSE;
					}
	
					$akhir=test_input($_POST['akhir']);
					if ($akhir!=''){
						if (is_numeric($akhir)){
							$akhir=(int)$akhir;
							$selisih=$akhir - $awal;
							if ($selisih == 1){
								$validAkhir=TRUE;
							}else{
								$errorStatus.='akhir tahun ajaran lebih 1 tahun dari awal tahun ajaran. ';
								$validAkhir=FALSE;
							}
						}else{
							$errorStatus='isi dengan angka. ';
							$validAkhir=FALSE;
						}
					}else{
						$errorStatus='wajib diisi. ';
						$validAkhir=FALSE;
					}
	
					$ganjilgenap=test_input($_POST['ganjilgenap']);
					if(!empty($ganjilgenap)){
						$validGanjilGenap=true;
					}else{
						$errorStatus.='harus diisi. ';
						$validGanjilGenap=false;
					}
					
					if(($validAkhir)&&($validAwal)&&($validGanjilGenap)){
						$isi=$awal.'/'.$akhir.'-'.$ganjilgenap;
						$ubah=mysqli_query($con,"UPDATE misc set deskripsi='".$isi."' where judul='tahun_ajaran'");
						$pesan_sukses='berhasil mengubah tahun ajaran';
					}else{
						$pesan_gagal='lengkapi form';
					}
				}else{
					$pesan_gagal='lengkapi form';
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin|Ubah Tahun Ajaran</title>
	
</head>
<body>
<div class="row">
	<div class="col-md-8">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				Ubah Tahun Ajaran
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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
								<label>Periode Ajaran</label>&nbsp;<span class="label label-warning">*
								<?php if(isset($errorStatus)) echo $errorStatus; ?></span>
							</div>
							<div class="form-group col-md-3">
								<input class="form-control" type="text" name="awal" required placeholder="contoh: 2014" pattern= '[0-9]{4}' maxlength='4' value="<?php if(!$sukses && !$validAwal){echo $awal;} ?>">
							</div>
							<div class="form-group col-md-1">/</div>
							<div class="form-group col-md-3">
								<input class="form-control" type="text" name="akhir" required placeholder='contoh: 2015' pattern= '[0-9]{4}' maxlength='4' value="<?php if(!$sukses && !$validAkhir){echo $akhir;} ?>">
							</div>
							<div class="form-group col-md-3">
								<input type="radio" name="ganjilgenap" value="1" id="ganjilgenap" required /> Ganjil
								<input type="radio" name="ganjilgenap" value="2" id="ganjilgenap" required /> Genap <br>
							</div>
							<div class="form-group">
								<input class="form-control btn btn-primary" type="submit" name="submit" value="Submit Tahun Ajaran" />
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
?>