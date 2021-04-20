<?php
require_once('../functions.php');
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'dosen'){
	if(isset($_GET['data'])){
		if($_GET['data']=='mahasiswa_tr2'){
			$nim = $_GET['nim'];
			$query = mysqli_query($con,"DELETE FROM daftar_tugas_riset2 WHERE nim='".$nim."'");
			$data=array('status'=>'success', 'msg'=>'Data mahasiswa ini telah dihapus.');
		}elseif($_GET['data']=='mahasiswa_uji_skripsi'){
			$id = $_GET['id'];
			$nim = $_GET['nim'];
			$query = mysqli_query($con,"DELETE FROM daftar_skripsi WHERE nim='".$nim."' and id_daftar_skripsi='".$id."'");
			$data=array('status'=>'success', 'msg'=>'Data mahasiswa ini telah dihapus.');
		}else{
			$data=array('status'=>'error', 'msg'=>'Tidak ada data dihapus.');
			//echo 'Tidak ada data dihapus.';
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