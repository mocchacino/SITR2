<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$sukses=false;
		$kadep=mysqli_query($con,"SELECT distinct nip,nama_dosen FROM dosen ");
		// $pesan_gagal='';
		// $errorStatus='';
		// eksekusi tombol daftar
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			//$kadep=mysqli_query($con,"SELECT distinct nip,nama_dosen FROM dosen ");
			if(isset($_POST['submit'])){
				if (isset($_POST['kadep'])){
					$fkadep=$_POST['kadep'];
					$cek=mysqli_query($con,"SELECT count(nip) as cek FROM dosen where nip='$fkadep'");
					$rCek=$cek->fetch_object();
					if($rCek->cek == 0){
						$validKadep=false;
						$errorKadep='NIP tidak terdaftar';
					}else{
						$validKadep=TRUE;
					}
				
					if($validKadep){
						$ubah=mysqli_query($con,"UPDATE misc set deskripsi='".$fkadep."' where judul='kepala_departemen'");
						$pesan_sukses='berhasil mengubah Ketua Departemen';
						$sukses=true;
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
	<title>Admin|Ubah Ketua Departemen</title>
	
</head>
<body>
<div class="row">
	<div class="col-md-8">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				Ubah Ketua Departemen
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<?php
								if ($sukses && isset($pesan_sukses)){
									echo'<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>';
								} if (!$sukses && isset($pesan_gagal)){
									echo'<div class="alert alert-danger" role="alert">'.$pesan_gagal.'</div>';
								}
							?>

							<div class="form-group">
								<label>Ketua Departemen</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorKadep)) echo $errorKadep;?></span>
								<input class="form-control" list="kadep" id='input_kadep' name="kadep" placeholder="--Masukkan NIP--" required autofocus >
								<datalist id="kadep">
								<option></option>
								<?php
									while($rKadep=$kadep->fetch_object()){ ?>
											<option value="<?php echo $rKadep->nip;?>"> <?php echo $rKadep->nama_dosen;?>
											</option>
										<?php 
									}
								?>
								</datalist>
							</div>
							<div class="form-group">
								<input class="form-control btn btn-primary" type="submit" name="submit" value="Submit" />
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