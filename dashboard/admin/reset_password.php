<?php
require_once('../functions.php');
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'petugas'){
	if(isset($_GET['data'])){
		if($_GET['data']=='admin'){
			$email = $_GET['email'];
			$query = mysqli_query($con,"UPDATE petugas SET password='".md5('sip'.$email.'pis')."' where email='".$email."' ");
			$data=array('status'=>'success', 'msg'=>'Password sudah direset.');
		}elseif($_GET['data']=='lab'){
			$email = $_GET['email'];
			$query = mysqli_query($con,"UPDATE lab SET password='".md5('sip'.$email.'pis')."' where admin='".$email."' ");
			$data=array('status'=>'success', 'msg'=>'Password sudah direset.');
		}elseif($_GET['data']=='dosen'){
			$nip = $_GET['nip'];
			$query = mysqli_query($con,"UPDATE dosen SET password='".md5('sip'.$nip.'pis')."' where nip='".$nip."' ");
			$data=array('status'=>'success', 'msg'=>'Password sudah direset.');
		}elseif($_GET['data']=='mahasiswa'){
			$nim = $_GET['nim'];
			$query = mysqli_query($con,"UPDATE mahasiswa SET password='".md5('sip'.$nim.'pis')."' where nim='".$nim."' ");
			$data=array('status'=>'success', 'msg'=>'Password sudah direset.');
		}
		
	}else{
		$data=array('status'=>'error', 'msg'=>'Data tidak dikenali');
		//echo 'Tidak ada data dihapus. Data tidak dikenali.';
	}

	$data = json_encode($data);
	echo ($data);
$con->close();
}else{
	header("Location:./");
}
?>