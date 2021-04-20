<?php
require_once('functions.php');
	if(isset($_GET['page'])){
		$page=$_GET['page'];
		$start = ($page-1)*10;
	}else{
		$page=1;
		$start=0;
	}
	/// Assign a query
	// $query = "SELECT * FROM anggota INNER JOIN perwalian ON anggota.id_wali=perwalian.id_wali ORDER BY nama LIMIT 10";
	$search=$_GET['search'];
	if($search==''){
		$query = "SELECT * FROM penempatan INNER JOIN anggota ON penempatan.nim=anggota.nim ORDER BY nama LIMIT 10 OFFSET $start";		
	}else{
		$query = "SELECT * FROM penempatan p INNER JOIN anggota a ON p.nim=a.nim WHERE p.nim like '%$search%' OR nama like '%$search%' LIMIT 10 OFFSET $start";
	}	
	// Execute the query
	$result = $con->query( $query );
	if(!$result){
		die('Could not connect to database : <br/>'.$con->error);
	}
	$i=$start+1;
	while($row = $result->fetch_object()){
			echo "<tr>";
			echo "<td>".$i."</td>";$i++;
			echo "<td>".$row->nim."</td>";
			echo "<td>".$row->nama."</td>";
			echo "<td>".$row->id_lab."</td>";		
			echo "<td>
				<a href='edit_lab.php?nim=".$row->nim."'><i class='fa fa-edit'></i></a>&nbsp;
				<a href='delete_penempatan.php?nim=".$row->nim."'><i class='fa fa-trash-o'></i></a>&nbsp;
				 </td>";
			echo "</tr>";
	}			
?>