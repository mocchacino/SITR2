<?php 
	require_once('functions.php');
	if(isset($_GET['search'])){
		$search=$_GET['search'];
	}else{
		$search='';
	}
	if(isset($_GET['page'])){
		$page=$_GET['page'];
		$start = ($page-1)*10;
	}else{
		$page=1;
		$start=0;
	}
	// Assign a query
	$search=$_GET['search'];
	if($search==''){
		$query = "SELECT * FROM bimbingan INNER JOIN anggota ON bimbingan.nim=anggota.nim INNER JOIN dosen on bimbingan.nip=dosen.nip LIMIT 10";		
	}else{
		$query = "SELECT * FROM bimbingan INNER JOIN anggota ON bimbingan.nim=anggota.nim INNER JOIN dosen on bimbingan.nip=dosen.nip WHERE anggota.nama like '%$search%' OR nama_dosen like '%$search%' LIMIT 10 OFFSET $start";
	}	
		
	// Execute the query
	$result = $con->query( $query );
	if(!$result){
		die('Could not connect to database : <br/>'.$con->error);
	}
	$i=$start+1;
?>
<?php
	while($row = $result->fetch_object()){
		echo "<tr>";
		echo "<td>".$i."</td>";$i++;
		echo "<td>".$row->nama."</td>";
		echo "<td>".$row->nama_dosen."</td>";	
		echo "<td>
		<a href='edit_bimbingan.php?nim=".$row->nim."'><i class='fa fa-edit'></i></a>&nbsp;
		<a href='delete_bimbingan.php?nim=".$row->nim."'><i class='fa fa-trash-o'></i></a>&nbsp;
									
		 </td>";
		echo "</tr>";
	}			
?>