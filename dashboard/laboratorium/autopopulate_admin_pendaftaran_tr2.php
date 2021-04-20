<?php	
	require_once('../functions.php');	
	header('Content-type: application/json');
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'laboratorium'){
		ini_set('display_errors', 1);

		$nim = $_POST['nim'];
		$query = mysqli_query($con,"SELECT nama,nip1, nip2, nip3, judul FROM tr1 inner join mahasiswa on mahasiswa.nim=tr1.nim where tr1.nim='".$nim."'"); 
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