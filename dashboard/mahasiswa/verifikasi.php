<?php
require_once('../functions.php');
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'petugas'){
	if(isset($_GET['data'])){
		if($_GET['data']=='mahasiswa'){
			$nim = $_GET['nim'];
			$query = mysqli_query($con,"UPDATE mahasiswa SET terverifikasi='1' where nim='".$nim."' ");
			$data=array('status'=>'success', 'msg'=>'Mahasiswa sudah diverifikasi.');
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