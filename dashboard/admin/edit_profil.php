<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		if(!isset($_POST['edit'])){
			$show_detail=mysqli_query($con, "SELECT * FROM petugas where idpetugas='".$_SESSION['sip_masuk_aja']."' ");	
			while($rshow_detail=$show_detail->fetch_object()){
				$nama=$rshow_detail->nama;
				$email=$rshow_detail->email;
			}
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$nama=test_input($_POST['nama']);
					$email=test_input($_POST['email']);
					if($nama!=''){
						$validNama=true;
					}else{
						$errorNama='wajib diisi';
						$validNama=false;
					}

					if($email!=''){
						$validEmail=true;
					}else{
						$errorEmail='wajib diisi';
						$validEmail=false;
					}

					if($validNama && $validEmail){
				  		$edit_profil = mysqli_query($con,"UPDATE petugas SET nama='".$nama."', email='".$email."' where idpetugas='".$_SESSION['sip_masuk_aja']."' ");
						
						if($edit_profil){
							$sukses=TRUE;
							$pesan_sukses="Berhasil menambahkan data.";	
						}else{
							$sukses=false;
							$pesan_gagal="Tidak berhasil menambahkan data.";	
						}
					}else{
						$sukses=FALSE;
						$pesan_gagal="Data yang diinputkan tidak valid";
					}

				}
			}
		}
		

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Profil</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="row">
		<div class="col-md-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Edit Profil
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
										<label>Nama</label>&nbsp;
										<input class="form-control" type="text" id="nama" name="nama" value="<?php if(isset($nama)){echo $nama;}?>" >
									</div>
									<div class="form-group">
										<label>Email</label>&nbsp;
										<input class="form-control" type='text' id='email' name="email" autofocus value="<?php if(isset($email)){echo $email;}?>" >
									</div>
									<div class="form-group">
										<input class="form-control btn btn-primary" type="submit" name="edit" value="Edit"/>
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