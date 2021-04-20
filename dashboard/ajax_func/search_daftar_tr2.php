<?php 
	require_once('functions.php');

	if(isset($_GET['page'])){
		$page=$_GET['page'];
		$start = ($page-1)*10;
	}else{
		$page=1;
		$start=0;
	}

	// if(isset($_GET['search'])){
	// 	$search=$_GET['search'];
	// }else{
	// 	$search='';
	// }
	// Assign a query
	$search=$_GET['search'];
	$dari_tgl=$_POST['from_date'];
	$sampai_tgl=$_POST['to_date'];
	if ($search!=''){
		if($dari_tgl!='' && $sampai_tgl!=''){
			$search_pendaftar = "SELECT * FROM daftar_tugas_riset2 INNER JOIN profil on profil.nim=daftar_tugas_riset2.nim INNER JOIN bimbingan ON bimbingan.nim=daftar_tugas_riset2.nim INNER JOIN judul on judul.nim=daftar_tugas_riset2.nim WHERE daftar_tugas_riset2.nim like '%$search%' OR nama like '%$search%' OR nip like '%$search%' OR judul_tr2 like '%$search%' AND tgl_daftar BETWEEN '%$dari_tgl%' AND '%$sampai_tgl%' ORDER BY daftar_tugas_riset2.tgl_daftar DESC LIMIT 10 OFFSET $start";
		}else{
			$search_pendaftar = "SELECT * FROM daftar_tugas_riset2 INNER JOIN profil on profil.nim=daftar_tugas_riset2.nim INNER JOIN bimbingan ON bimbingan.nim=daftar_tugas_riset2.nim INNER JOIN judul on judul.nim=daftar_tugas_riset2.nim WHERE daftar_tugas_riset2.nim like '%$search%' OR nama like '%$search%' OR nip like '%$search%' OR judul_tr2 like '%$search%' ORDER BY daftar_tugas_riset2.tgl_daftar DESC LIMIT 10 OFFSET $start";
		}
	}else{
		if($dari_tgl!='' && $sampai_tgl!=''){
			$search_pendaftar = "SELECT * FROM daftar_tugas_riset2 INNER JOIN profil on profil.nim=daftar_tugas_riset2.nim INNER JOIN bimbingan ON bimbingan.nim=daftar_tugas_riset2.nim INNER JOIN judul on judul.nim=daftar_tugas_riset2.nim WHERE tgl_daftar BETWEEN '%$dari_tgl%' AND '%$sampai_tgl%' ORDER BY daftar_tugas_riset2.tgl_daftar DESC LIMIT 10 OFFSET $start";
		}else{
			$search_pendaftar = "SELECT * FROM daftar_tugas_riset2 INNER JOIN profil on profil.nim=daftar_tugas_riset2.nim INNER JOIN bimbingan ON bimbingan.nim=daftar_tugas_riset2.nim INNER JOIN judul on judul.nim=daftar_tugas_riset2.nim ORDER BY daftar_tugas_riset2.tgl_daftar DESC LIMIT 10 OFFSET $start";
		}
	}
	
		
	// Execute the query
	$fsearch_pendaftar = $con->query( $search_pendaftar );
	if(!$fsearch_pendaftar){
		die('Could not connect to database : <br/>'.$con->error);
	}
	$nomor=$start+1;
?>
<?php
	while($rsearch_pendaftar = $fsearch_pendaftar->fetch_object()){
		echo "<tr>";
		echo "<td>".$nomor."</td>";$i++;
		echo "<td>".$rsearch_pendaftar->nim."</td>";
		echo "<td>".$rsearch_pendaftar->nama."</td>";
		$nama_pembimbing1="SELECT * FROM profil WHERE profil.nip='".$rsearch_pendaftar->nip1."' ";
		$nama_pembimbing2="SELECT * FROM profil WHERE profil.nip='".$rsearch_pendaftar->nip2."' ";
		$fnama_pembimbing1=$con->query($nama_pembimbing1);
		while($rnama_pembimbing1=$fnama_pembimbing1->fetch_object()){
			echo "<td>".$rnama_pembimbing1->nama."</td>";
		}
		$fnama_pembimbing2=$con->query($nama_pembimbing2);
		$rnama_pembimbing2=$fnama_pembimbing2->fetch_object();
		if(!$rnama_pembimbing2){
			echo "<td></td>";
		}else{
			echo "<td>".$rnama_pembimbing2->nama."</td>";	
		}  
		echo "<td>".$rsearch_pendaftar->judul_tr2."</td>";
		if ($status==1) {
			echo "<td align='center'>
				<a href='print_daftar_tr2.php'><i class='fa fa-print'></i></a>&nbsp;
				<a href='edit_daftar_tr2.php?nim=".$rsearch_pendaftar->nim."'><i class='fa fa-edit'></i></a>&nbsp;
				<a href='delete_tr2.php?nim=".$rsearch_pendaftar->nim."'><i class='fa fa-trash-o'></i></a>&nbsp;
			 </td>";
		}else {
			echo "<td>".$rsearch_pendaftar->tgl_krs."</td>";
			echo "<td>".$rsearch_pendaftar->sks_semester."</td>";
			echo "<td>".$rsearch_pendaftar->sks_komulatif."</td>";
			echo "<td>".$rsearch_pendaftar->tgl_daftar."</td>";
		
			echo "<td align='center'>
				<a href='edit_daftar_tr2.php?nim=".$rsearch_pendaftar->nim."'><i class='fa fa-edit'></i></a>&nbsp;
				<a href='delete_tr2.php?nim=".$rsearch_pendaftar->nim."'><i class='fa fa-trash-o'></i></a>&nbsp;
			 </td>";	
		}
		echo "</tr>";
	}			
?>