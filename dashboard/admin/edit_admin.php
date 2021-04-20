<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$sukses=TRUE;
		ini_set('display_errors', 1);

		if(!isset($_POST['edit'])){
			$id=$_GET['nip'];
		 	$data=mysqli_query($con,"SELECT * from petugas where idpetugas='".$id."' ");
	 		while($rData=$data->fetch_object()){
	 			$nama=$rData->nama;
	 			$email=$rData->email;
	 		}
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$saveid=test_input($_POST['saveid']);
					$id=test_input($_POST['id']);
					$nama=test_input($_POST['nama']);
					if($nama==''){
						$validNama=false;
						$errorNama='wajib diisi';
					}else{
						$validNama=true;
					}

					$email=test_input($_POST['email']);
					if($email!=''){
						$validEmail=true;
					}else{
						$errorEmail='wajib diisi';
						$validEmail=false;
					}

					if($validNama && $validEmail){
				  		$edit_pendaftar = mysqli_query($con,"UPDATE petugas SET nama='".$nama."', email='".$email."' where idpetugas='$id' ");
						
						if($edit_pendaftar){
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
			<title>Form Ubah Admin</title>
			<script type="text/javascript">
			</script>
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php
	                  		echo '<a href="daftar_admin.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>';
	                  	?>
						Ubah Admin
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
									<?php
										if ($sukses && isset($pesan_sukses)){
											echo '<div class="alert alert-success">';
											echo $pesan_sukses;
											echo '</div>';
										} if (!$sukses && isset($pesan_gagal)){
											echo '<div class="alert alert-danger">';
											echo $pesan_gagal;
											echo '</div>';
										}
									?>
									<input type="text" name="saveid" hidden value="<?php echo $id;?>">
									<input type="text" id='input_id' hidden name="id"  value="<?php if(isset($id)){echo $id;}else $saveid; ?>">
									<div class="form-group">
										<label>Nama</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorNama)) echo $errorNama;?></span>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Wiladhianty Yulianova" maxlength="30" required autofocus value="<?php if(isset($nama)){echo $nama;} ?>">
									</div>
									
									<div class="form-group">
										<label>E-mail</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorEmail)) echo $errorEmail;?></span>
										<input type="email" class="form-control" id="email" name="email" placeholder="contoh: wila@gmail.com" maxlength="50" required autofocus value="<?php if(isset($email)){echo $email;} ?>">
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
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>



