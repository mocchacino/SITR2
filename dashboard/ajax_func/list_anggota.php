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
	$query = "SELECT * FROM anggota INNER JOIN perwalian ON anggota.id_wali=perwalian.id_wali ORDER BY nama LIMIT 10 OFFSET $start";
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
		echo "<td>".$row->alamat."</td>";
		echo "<td>".$row->kota."</td>";
		//echo "<td>".$row->email."</td>";
		echo "<td>".$row->no_telp."</td>";
		echo "<td>".$row->nama_wali."</td>";
		echo "<td>".$row->angkatan."</td>";
		echo "<td>
				<a href='edit_anggota.php?nim=".$row->nim."'><i class='fa fa-edit'></i></a><br/>
				<a href='delete.php?nim=".$row->nim."'><i class='fa fa-trash-o'></i></a>
			 </td>";
		echo "</tr>";
	}			
?>