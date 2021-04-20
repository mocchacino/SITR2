<?php
	require_once('../../functions.php');
	if(!isset($_SESSION['sip_masuk_aja'])){
		if(isset($_POST['login'])){
			if((!empty($_POST['email']))&&(!empty($_POST['password']))){
				$email = mysqli_real_escape_string($con, $_POST['email']);
				$password = mysqli_real_escape_string($con, $_POST['password']);
				//$password = md5($password);
				$password = md5('sip'.$password.'pis');

				$cekLoginAdmin=mysqli_query($con,"SELECT idpetugas from petugas where email='$email' and password='$password'");
				$cekLoginMahasiswa=mysqli_query($con,"SELECT nim from mahasiswa where email='$email' and password='$password' and terverifikasi='1'");
				$cekLoginDosen=mysqli_query($con,"SELECT nip from dosen where email='$email' and password='$password'");
				$cekLoginLaboratorium=mysqli_query($con,"SELECT idlab from lab where admin='$email' and password='$password'");

				if(mysqli_num_rows($cekLoginAdmin)!=0){
					$fetch_user_id=mysqli_fetch_assoc($cekLoginAdmin);
					$_SESSION['sip_masuk_aja']=$fetch_user_id['idpetugas'];
					$_SESSION['sip_status']='petugas';
					header("Location:../admin/index.php");
				}elseif(mysqli_num_rows($cekLoginMahasiswa)!=0){
					$fetch_user_id=mysqli_fetch_assoc($cekLoginMahasiswa);
					$_SESSION['sip_masuk_aja']=$fetch_user_id['nim'];
					$_SESSION['sip_status']='mahasiswa';
					header("Location:../mahasiswa/index.php");
				}elseif(mysqli_num_rows($cekLoginDosen)!=0){
					$fetch_user_id=mysqli_fetch_assoc($cekLoginDosen);
					$_SESSION['sip_masuk_aja']=$fetch_user_id['nip'];
					$_SESSION['sip_status']='dosen';
					header("Location:../dosen/index.php");
				}elseif(mysqli_num_rows($cekLoginLaboratorium)!=0){
					$fetch_user_id=mysqli_fetch_assoc($cekLoginLaboratorium);
					$_SESSION['sip_masuk_aja']=$fetch_user_id['idlab'];
					$_SESSION['sip_status']='laboratorium';
					header("Location:../laboratorium/index.php");
				}else{
				  	$gagal = "Tidak dapat login. Silahkan cek EMAIL dan PASSWORD anda kembali";			
				}
		  	}else{
				$gagal = "email dan password harus di isi!";
				if(!empty($_POST['sip_masuk_aja'])){
					$username = mysqli_real_escape_string($con, $_POST['sip_masuk_aja']);
				}
		  	}
		}
    }else{	  
		header("Location:../");
		exit;
	}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login<?php $site_name ?></title>
	<!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   <style>
	body{
		background-color: #076960;
	}

	.vertical-offset-100{
		padding-top:100px;
	}
	</style>
</head>
<body>
<div class="container">
    <div class="row vertical-offset-100">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Silakan Masuk</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form accept-charset="UTF-8" role="form" method="POST">
                    <fieldset>
						<?php if(isset($gagal)) echo '<div class="form-group"><span class="label label-warning">'.$gagal.'</span></div>' ?>
			    	  	<div class="form-group">
			    		    <input class="form-control" placeholder="E-mail" name="email" type="email" value="<?php if(isset($email)) echo $email; ?>"/>
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" placeholder="Password" name="password" type="password"/>
			    		</div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" name="login" value="login"/>
			    		<br>
			    		<div class="form-group" align="left">
			    			<font color='red' size='1px'>* Please use Google Chrome for better experience.</font>
			    		</div>
			    		<div class="form-group" align="right">
			    			<a href="../registrasi/registrasi.php">Tidak punya akun?</a>
			    		</div>
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>
</body>
</html>