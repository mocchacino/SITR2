<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'mahasiswa'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			if (isset($_POST['submit'])) {
				$pass = mysqli_query($con, "SELECT * FROM mahasiswa where nim='".$_SESSION['sip_masuk_aja']."' ");
				$rpass=$pass->fetch_object();
				$new_pass=test_input($_POST['new_pass']);
				$re_new_pass=test_input($_POST['re_new_pass']);
				$old_pass=test_input($_POST['old_pass']);
				if($old_pass!=''){
					if(md5('sip'.$old_pass.'pis') == $rpass->password) $validPassLama=true;
					else{
						$errorPassLama='password lama salah';
						$validPassLama=false;
					}
				}else{
					$errorPassLama='wajib diisi';
					$validPassLama=false;
				}

				if($new_pass!=''){
					$validPassBaru=true;
				}else{
					$errorPassBaru='wajib diisi';
					$validPassBaru=false;
				}

				if($re_new_pass!=''){
					if($re_new_pass == $new_pass) $validPassBaruConfirm=true;
					else{
						$errorPassBaruConfirm='Konfirmasi password dengan password baru tidak sama';
						$validPassBaruConfirm=false;
					}
				}else{
					$errorPassBaruConfirm='wajib diisi';
					$validPassBaruConfirm=false;
				}

				if($validPassBaru && $validPassLama && $validPassBaruConfirm){
			  		$change_pass = mysqli_query($con,"UPDATE mahasiswa SET password='".md5('sip'.$new_pass.'pis')."' where nim='".$_SESSION['sip_masuk_aja']."' ");
					
					if($change_pass){
						$sukses=TRUE;
						$pesan_sukses="Berhasil merubah password.";	
					}else{
						$sukses=false;
						$pesan_gagal="Tidak berhasil merubah password.";	
					}
				}else{
					$sukses=FALSE;
					$pesan_gagal="Data yang diinputkan tidak valid";
				}

			}
		}
		
?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Ubah Password</title>
			<script type="text/javascript">
			</script>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Ubah Password
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
									<?php
										if ($sukses && isset($pesan_sukses)){
											?>
											<div class="alert alert-success"><?php echo $pesan_sukses;?></div>
											<?php
										} if (!$sukses && isset($pesan_gagal)){
											?>
											<div class="alert alert-danger"><?php echo $pesan_gagal;?></div>
											<?php
										}
									?>
									
									<div class="form-group">
										<label>Password Lama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPassLama)) echo $errorPassLama;?></span>
										<input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Password Lama" required autofocus >
									</div>
									<div class="form-group">
										<label>Password Baru</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPassBaru)) echo $errorPassBaru;?></span>
										<input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="Password Baru" required autofocus >
									</div>
									<div class="form-group">
										<label>Konfirmasi Password Baru</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorPassBaruConfirm)) echo $errorPassBaruConfirm;?></span>
										<input type="password" class="form-control" id="re_new_pass" name="re_new_pass" placeholder="Konfirmasi Password Baru" required autofocus >
									</div>
									<div class="form-group">
										<input class="form-control btn btn-primary" type="submit" name="submit" value="Submit"/>
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
	}else{
		header("Location:./");
	}
?>



