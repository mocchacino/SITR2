<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'mahasiswa'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);

		$show_detail=mysqli_query($con, "SELECT * FROM mahasiswa where nim='".$_SESSION['sip_masuk_aja']."' ");	
		$rshow_detail=$show_detail->fetch_assoc();
		

?>
<!DOCTYPE html>
<html>
<head>
	<title>Profil</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Profil
<?php
					echo '<a href="edit_profil.php" class="btn btn-primary btn-sm pull-right"><i class="fa fa-pencil-square-o"></i> Edit</a>';
?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form>
								<div class="form-group">
									<label>NIM</label>&nbsp;
									<input type="text" class="form-control" id="nip" name="nip" placeholder="contoh: 24010314120014" maxlength="14" readonly autofocus value="<?php echo $rshow_detail['nim'];?>">
								</div>
								<div class="form-group">
									<label>Nama</label>&nbsp;
									<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php echo $rshow_detail['nama'];?>"" >
								</div>
								<div class="form-group">
									<label>Email</label>&nbsp;
									<input class="form-control" type='text' id='email' name="email" readonly autofocus value="<?php echo $rshow_detail['email'];?>"" >
								</div>
								<div class="form-group">
									<label>Nomor Telepon</label>&nbsp;
									<input type="tel" class="form-control" id="noTelp" name="noTelp" pattern="[0-9]{10,}" title="Nomor Telepon" placeholder="contoh: 081234567890" maxlength="20" readonly autofocus value="<?php echo $rshow_detail['no_telp'];?>"">
								</div>
								<div class="form-group">
									<label>Alamat</label>&nbsp;
									<input type="text" class="form-control" id="alamat" name="alamat" placeholder="contoh: Jalan Jend. Sudirman No.9" maxlength="100" readonly autofocus value="<?php echo $rshow_detail['alamat'];?>"">
								</div>
								<div class="form-group">
									<label>Kota Lahir</label>&nbsp;
									<input type="text" class="form-control" id="kotaLahir" name="kotaLahir" placeholder="contoh: Jakarta" maxlength="20" readonly autofocus value="<?php echo $rshow_detail['tempat_lahir'];?>"">
								</div>
								<div class="form-group">
									<label>Tanggal Lahir</label>&nbsp;
									<input type="date" class="form-control" id="tanggalLahir" name="tanggalLahir" readonly autofocus value="<?php echo $rshow_detail['tgl_lahir'];?>">
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