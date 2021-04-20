<?php
require_once('../functions.php');
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'petugas'){
	if(isset($_GET['data'])){
		if($_GET['data']=='mahasiswa_tr2'){
			$nim = $_GET['nim'];
			$query = mysqli_query($con,"DELETE FROM daftar_tugas_riset2 WHERE nim='".$nim."'");
			$data=array('status'=>'success', 'msg'=>'Data mahasiswa ini telah dihapus.');
		}elseif($_GET['data']=='mahasiswa_uji_kelayakan'){
			$id = $_GET['id'];
			$query = mysqli_query($con,"DELETE FROM daftar_uji_kelayakan WHERE id_daftar_uji_kelayakan='".$id."'");
			$data=array('status'=>'success', 'msg'=>'Data mahasiswa ini telah dihapus.');
		}elseif($_GET['data']=='mahasiswa_ujian_skripsi'){
			$id = $_GET['id'];
			$query = mysqli_query($con,"DELETE FROM daftar_skripsi WHERE id_daftar_skripsi='".$id."'");
			$data=array('status'=>'success', 'msg'=>'Data mahasiswa ini telah dihapus.');
		}elseif($_GET['data']=='pembimbing_luar'){
			$nip = $_GET['nip'];
			$query = mysqli_query($con,"DELETE FROM pembimbing_luar WHERE nip='".$nip."'");
			$data=array('status'=>'success', 'msg'=>'Data pembimbing ini telah dihapus.');
		}elseif($_GET['data']=='mahasiswa'){
			$nim = $_GET['nim'];
			$query = mysqli_query($con,"DELETE FROM mahasiswa WHERE nim='".$nim."'");
			$data=array('status'=>'success', 'msg'=>'Data mahasiswa ini telah dihapus.');
		}elseif($_GET['data']=='dosen'){
			$nip = $_GET['nip'];
			$query = mysqli_query($con,"DELETE FROM dosen WHERE nip='".$nip."'");
			$data=array('status'=>'success', 'msg'=>'Data dosen ini telah dihapus.');
		}elseif($_GET['data']=='laboratorium'){
			$email = $_GET['email'];
			$query = mysqli_query($con,"DELETE FROM lab WHERE admin='".$email."'");
			$data=array('status'=>'success', 'msg'=>'Data laboratorium ini telah dihapus.');
		}elseif($_GET['data']=='admin'){
			$email = $_GET['email'];
			$query = mysqli_query($con,"DELETE FROM petugas WHERE email='".$email."'");
			$data=array('status'=>'success', 'msg'=>'Data admin ini telah dihapus.');
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