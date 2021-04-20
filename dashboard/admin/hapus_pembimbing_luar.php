<?php
$nip = $_GET['nip'];
include_once('../sidebar.php');
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'petugas'){
	include_once('../sidebar.php');

// Assign the query
$hapus = mysqli_query($con," SELECT * FROM pembimbing_luar WHERE nip='".$nip."'");
$rHapus = $hapus->fetch_object();

		echo '<table border="0">';
			echo '<tr>';
				echo '<td>NIP</td>';
				echo '<td> : '.$rHapus->nip.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Nama</td>';
				echo '<td> : '.$rHapus->nama.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Nama Instansi</td>';
				echo '<td> : '.$rHapus->instansi.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Nomor Telepon</td>';
				echo '<td> : '.$rHapus->no_telp.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Email</td>';
				echo '<td> : '.$rHapus->email.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Alamat</td>';
				echo '<td> : '.$rHapus->alamat.'</td>';
			echo '</tr>';
			
		echo '</table>';
		echo '<br />';
		echo 'Apakah anda yakin ingin menghapus Dosen/Pembimbing ini? <a href="delete.php?data=pembimbing_luar&nip='.$rHapus->nip.'">YA</a> / <a href="daftar_pembimbing_luar.php">TIDAK</a>';
		$con->close();
?>
<?php
include_once('../footer.php');
	$con->close();
}else{
	header("Location:./");
}
?>