<?php
$nim = $_GET['nim'];
include_once('../sidebar.php');
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'dosen'){
	include_once('../sidebar.php');

// Assign the query
$hapus = mysqli_query($con," SELECT * FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim WHERE daftar_skripsi.nim='".$nim."'");
$rHapus = $hapus->fetch_object();

		echo '<table border="0">';
			echo '<tr>';
				echo '<td>NIM</td>';
				echo '<td> : '.$rHapus->nim.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Nama</td>';
				echo '<td> : '.$rHapus->nama.'</td>';
			echo '</tr>';
			
		echo '</table>';
		echo '<br />';
		echo 'Apakah anda yakin ingin menghapus mahasiswa ini? <a href="delete.php?data=mahasiswa_ujian_skripsi&nim='.$rHapus->nim.'">YA</a> / <a href="daftar_skripsi.php">TIDAK</a>';
		$con->close();
?>
<?php
include_once('../footer.php');
	$con->close();
}else{
	header("Location:./");
}
?>