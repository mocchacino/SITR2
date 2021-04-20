<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);

		$show_detail=mysqli_query($con, "SELECT * FROM petugas where idpetugas='".$_SESSION['sip_masuk_aja']."' ");	
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
								<div class="form-group col-md-12 col-sm-12 col-xs-12">
									<label>Nama</label>&nbsp;
									<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php echo $rshow_detail['nama'];?>" >
								</div>
								<div class="form-group col-md-12 col-sm-12 col-xs-12">
									<label>Email</label>&nbsp;
									<input class="form-control" type='text' id='email' name="email" readonly autofocus value="<?php echo $rshow_detail['email'];?>" >
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