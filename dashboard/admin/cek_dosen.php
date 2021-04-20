<?php	
	require_once('../functions.php');	
	header('Content-type: application/json');
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		ini_set('display_errors', 1);

		$nip = $_POST['nip'];
		$query = mysqli_query($con,"SELECT nip FROM dosen where nip='".$nip."' "); 
		$row = $query->fetch_row();

		

		$data = array('nip'=>$row[0]);
		if($row==null){
			echo null;
		}else{
			$data = json_encode($data);
			echo ($data);
		}
	}

?>