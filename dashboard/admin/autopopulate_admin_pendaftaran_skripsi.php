<?php	
	require_once('../functions.php');	
	header('Content-type: application/json');
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		ini_set('display_errors', 1);

		$nim = $_POST['nim'];
		$query = mysqli_query($con,"SELECT nama,nip1, nip2, nip3, judul FROM daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim where daftar_uji_kelayakan.nim='".$nim."'"); 
		$row = $query->fetch_row();

		$data = array('nama'=>$row[0],'pembimbing1'=>$row[1],'pembimbing2'=>$row[2], 'pembimbing3'=>$row[3],'judul'=>$row[4]);
		if($row==null){
			echo null;
		}else{
			$data = json_encode($data);
			echo ($data);
		}
	}

?>