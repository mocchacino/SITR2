<?php
require_once('../functions.php');
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'laboratorium'){

	if(isset($_GET['data'])){
		if($_GET['data']=='mahasiswa_uji_kelayakan'){
			$id = $_GET['id'];
			$query = mysqli_query($con,"DELETE FROM daftar_uji_kelayakan WHERE id_daftar_uji_kelayakan='".$id."'");
			$data=array('status'=>'success', 'msg'=>'Data mahasiswa ini telah dihapus.');
			// echo 'Data mahasiswa ini telah dihapus. <br />';
			// echo '<a href="lab_daftar_uji_kelayakan.php">Daftar Mahasiswa Uji Kelayakan</a>';
		
		}else{
			$data=array('status'=>'error', 'msg'=>'Tidak ada data dihapus.');
			// echo 'Tidak ada data dihapus.';
		}
		
	}else{
		$data=array('status'=>'error', 'msg'=>'Data tidak dikenali');
		// echo 'Tidak ada data dihapus. Data tidak dikenali.';
	}
	$data = json_encode($data);
	echo ($data);
$con->close();
}else{
	header("Location:./");
}
?>