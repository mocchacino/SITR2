<?php
	require_once('../../functions.php');
	if(!isset($_SESSION['sip_masuk_aja'])){
		if(isset($_POST['registrasi'])){
			if((!empty($_POST['email']))&&(!empty($_POST['password']))&&(!empty($_POST['nim']))&&(!empty($_POST['nama']))&&(!empty($_POST['alamat']))&&(!empty($_POST['tempat_lahir']))&&(!empty($_POST['tanggal_lahir']))&&(!empty($_POST['nomor_telepon']))){
        $recaptcha=$_POST['g-recaptcha-response'];
        $email = mysqli_real_escape_string($con, $_POST['email']);
				$password = mysqli_real_escape_string($con, $_POST['password']);
        $password = md5('sip'.$password.'pis');
        $nim = mysqli_real_escape_string($con, $_POST['nim']); 
        $nama = mysqli_real_escape_string($con, $_POST['nama']);
        $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
        $tanggal_lahir = mysqli_real_escape_string($con, $_POST['tanggal_lahir']);
        $tempat_lahir = mysqli_real_escape_string($con, $_POST['tempat_lahir']);
        $nomor_telepon = mysqli_real_escape_string($con, $_POST['nomor_telepon']);
        $terverifikasi = '0';

        // Cek NIM
        $cekNim = mysqli_query($con,"SELECT nim from mahasiswa where email='$email' or nim='$nim'");
        if(mysqli_num_rows($cekNim)!=0){
          $pesan_gagal = "Alamat email atau NIM telah terdaftar";	
        }else {
          if(!$recaptcha){
            $pesan_gagal = "Harap lewati verifikasi Recaptcha";
          }else{
            $ch = curl_init();
            $ip = $_SERVER['REMOTE_ADDR'];
            $secretkey = "6Lf7gJkUAAAAAM3gmbeIIKfeEKV3W0-mT3BSvlXr";	
	          $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".$recaptcha."&remoteip=".$ip;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            echo $contents;
            $responseKeys = json_decode($response,true);
            

            if(intval($responseKeys["success"]) !== 1) {
              $pesan_gagal = "Harap ulangi Recaptcha";
            }else {
              if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $pesan_gagal = "Alamat Email Tidak Valid";	
              } else {
                if(!(is_numeric($nim) && is_numeric($nomor_telepon))){
                  if (!is_numeric($nim)) $pesan_gagal = "NIM tidak valid";	
                  if (!is_numeric($nomor_telepon)) $pesan_gagal = "Nomor Telepon tidak valid";
                } else {
                  if($con->query("INSERT INTO mahasiswa (nim, nama, password, alamat, tempat_lahir, tgl_lahir, email, no_telp, terverifikasi) VALUES ('".$nim."', '".$nama."', '".$password."', '".$alamat."', '".$tempat_lahir."', '".$tanggal_lahir."', '".$email."', '".$nomor_telepon."', '".$terverifikasi."')" )){
                    $pesan_sukses = "Pendaftaran berhasil silahkan hubungi Administrator untuk mengaktifkan akun anda";
                  } else {
                    $pesan_gagal = "Data yang dimasukkan tidak valid";	
                  }
                }
              }
            }    
          }
        }
      }else{
        $pesan_gagal = "Harap lengkapi form tersedia";
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
    <title>Registrasi Mahasiswa<?php $site_name ?></title>
	<!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
  <link href="../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   <!-- RECAPTCHA -->
   <script src='https://www.google.com/recaptcha/api.js'></script>
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
			    	<h3 class="panel-title">Registrasi Akun Mahasiswa</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form accept-charset="UTF-8" role="form" method="POST">
                    <fieldset>
              <?php
								if (isset($pesan_sukses)){
											echo '<div class="alert alert-success" role="alert">'.$pesan_sukses.'</div>';
                } if (isset($pesan_gagal)){
											echo '<div class="alert alert-danger" role="alert">'.$pesan_gagal.'</div>';
                }
							?>
              <div class="form-group">
			    		    <input class="form-control" placeholder="NIM" name="nim" type="number" value="<?php if(isset($nim)) echo $nim; ?>"/>
			    		</div>
              <div class="form-group">
			    		    <input class="form-control" placeholder="Nama" name="nama" type="text" value="<?php if(isset($nama)) echo $nama; ?>"/>
			    		</div>
              <div class="form-group">
			    		    <input class="form-control" placeholder="E-mail" name="email" type="email" value="<?php if(isset($email)) echo $email; ?>"/>
			    		</div>
			    		<div class="form-group">
			    			<textarea class="form-control" placeholder="Alamat" name="alamat"><?php if(isset($alamat)) echo $alamat; ?></textarea>
			    		</div>
              <div class="form-group">
			    			<input class="form-control" placeholder="Tanggal Lahir" name="tanggal_lahir" type="date" value="<?php if(isset($tanggal_lahir)) echo $tanggal_lahir; ?>">
			    		</div>
              <div class="form-group">
			    			<input class="form-control" placeholder="Tempat Lahir" name="tempat_lahir" type="text" value="<?php if(isset($tempat_lahir)) echo $tempat_lahir; ?>">
			    		</div>
              <div class="form-group">
			    			<input class="form-control" placeholder="Nomor Telepon" name="nomor_telepon" type="telp" value="<?php if(isset($nomor_telepon)) echo $nomor_telepon; ?>"/>
			    		</div>
              <div class="form-group">
			    			<input class="form-control" placeholder="Password" name="password" type="password"/>
			    		</div>
              <div class="g-recaptcha" data-sitekey="6Lf7gJkUAAAAALVmKvtG5msTceFZLQY0CGnFuIMB"></div>
              <br>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" name="registrasi" value="registrasi"/>
			    		<br>
			    		<div class="form-group" align="left">
			    			<font color='red' size='1px'>* Please use Google Chrome for better experience.</font>
			    		</div>
              <div class="form-group" align="right">
                <a href="../login/login.php">Sudah punya akun?</a>
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