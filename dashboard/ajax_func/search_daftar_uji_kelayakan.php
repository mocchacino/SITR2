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
			$search_pendaftar = "SELECT * FROM daftar_uji_kelayakan INNER JOIN profil on profil.nim=daftar_uji_kelayakan.nim INNER JOIN judul on judul.nim=daftar_uji_kelayakan.nim INNER JOIN bimbingan on bimbingan.nim=daftar_uji_kelayakan.nim LEFT JOIN penguji_kelayakan on penguji_kelayakan.id_bimbingan=bimbingan.id_bimbingan where daftar_uji_kelayakan.nim like '%$search%' OR nama like '%$search%' OR nip like '%$search%' OR judul_tr2 like '%$search%' AND tgl_daftar BETWEEN '%$dari_tgl%' AND '%$sampai_tgl%' ORDER BY daftar_uji_kelayakan.tgl_daftar DESC LIMIT 10 OFFSET $start";
		}else{
			$search_pendaftar = "SELECT * FROM daftar_uji_kelayakan INNER JOIN profil on profil.nim=daftar_uji_kelayakan.nim INNER JOIN judul on judul.nim=daftar_uji_kelayakan.nim INNER JOIN bimbingan on bimbingan.nim=daftar_uji_kelayakan.nim LEFT JOIN penguji_kelayakan on penguji_kelayakan.id_bimbingan=bimbingan.id_bimbingan where daftar_uji_kelayakan.nim like '%$search%' OR nama like '%$search%' OR nip like '%$search%' OR judul_tr2 like '%$search%' ORDER BY daftar_uji_kelayakan.tgl_daftar DESC LIMIT 10 OFFSET $start";
		}
	}else{
		if($dari_tgl!='' && $sampai_tgl!=''){
			$search_pendaftar = "SELECT * FROM daftar_uji_kelayakan INNER JOIN profil on profil.nim=daftar_uji_kelayakan.nim INNER JOIN judul on judul.nim=daftar_uji_kelayakan.nim INNER JOIN bimbingan on bimbingan.nim=daftar_uji_kelayakan.nim LEFT JOIN penguji_kelayakan on penguji_kelayakan.id_bimbingan=bimbingan.id_bimbingan WHERE tgl_daftar BETWEEN '%$dari_tgl%' AND '%$sampai_tgl%' ORDER BY daftar_uji_kelayakan.tgl_daftar DESC LIMIT 10 OFFSET $start";
		}else{
			$search_pendaftar = "SELECT * FROM daftar_uji_kelayakan INNER JOIN profil on profil.nim=daftar_uji_kelayakan.nim INNER JOIN judul on judul.nim=daftar_uji_kelayakan.nim INNER JOIN bimbingan on bimbingan.nim=daftar_uji_kelayakan.nim LEFT JOIN penguji_kelayakan on penguji_kelayakan.id_bimbingan=bimbingan.id_bimbingan ORDER BY daftar_uji_kelayakan.tgl_daftar DESC LIMIT 10 OFFSET $start";
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
	while($rshow_pendaftar = $fshow_pendaftar->fetch_object()){
		echo "<tr>";
		echo "<td rowspan='3'>".$nomor."</td>";$i++;
		echo "<td rowspan='3'>".$rshow_pendaftar->nim."</td>";
		echo "<td rowspan='3'>".$rshow_pendaftar->nama."</td>";
		echo "<td rowspan='3'>".$rshow_pendaftar->judul_tr2."</td>";
		echo "<td rowspan='3'>".$rshow_pendaftar->tgl_daftar."</td>";	
		//daftar pembimbing
		$nama_pembimbing1="SELECT * FROM profil WHERE profil.nip='".$rshow_pendaftar->nip1."' ";
		$nama_pembimbing2="SELECT * FROM profil WHERE profil.nip='".$rshow_pendaftar->nip2."' ";
		$fnama_pembimbing1=$con->query($nama_pembimbing1);
		while($rnama_pembimbing1=$fnama_pembimbing1->fetch_object()){
			echo "<td> 1.".$rnama_pembimbing1->nama."</td>";
		}
		// daftar penguji dari lab
		$nama_penguji1="SELECT * FROM profil WHERE profil.nip='".$rshow_pendaftar->nip_penguji_kelayakan1."' ";
		$nama_penguji2="SELECT * FROM profil WHERE profil.nip='".$rshow_pendaftar->nip_penguji_kelayakan2."' ";
		$nama_penguji3="SELECT * FROM profil WHERE profil.nip='".$rshow_pendaftar->nip_penguji_kelayakan3."' ";
		$fnama_penguji1=$con->query($nama_penguji1);
		while($rnama_penguji1=$fnama_penguji1->fetch_object()){
			echo "<td> 1. ".$rnama_penguji1->nama."</td>";
		}
		//tools
		echo "<td align='center' rowspan='3'>
			<a href='edit_daftar_tr2.php?nim=".$rshow_pendaftar->nim."'><i class='fa fa-edit'></i></a>&nbsp;
			<a href='delete_tr2.php?nim=".$rshow_pendaftar->nim."'><i class='fa fa-trash-o'></i></a>&nbsp;
		 </td>";
		echo "</tr>";
		
		echo "<tr>";
		//lanjut daftar pembimbing
		$fnama_pembimbing2=$con->query($nama_pembimbing2);
		$rnama_pembimbing2=$fnama_pembimbing2->fetch_object();
		if(!$rnama_pembimbing2){
			echo "<td></td>";
		}else{
			echo "<td> 2. ".$rnama_pembimbing2->nama."</td>";	
		} 
		echo "<td></td>";
		echo "</tr>";

		echo "<tr>";
		//lanjut daftar penguji dari lab
		$fnama_penguji2=$con->query($nama_penguji2);
		while($rnama_penguji2=$fnama_penguji2->fetch_object()){
			echo "<td> 2. ".$rnama_penguji2->nama."</td>";
		}
		$fnama_penguji3=$con->query($nama_penguji3);
		$rnama_penguji3=$fnama_penguji3->fetch_object();
		if(!$rnama_penguji3){
			echo "<td></td>";
		}else{
			echo "<td> 3. ".$rnama_penguji3->nama."</td>";	
		} 
		echo "</tr>";
	}			
?>