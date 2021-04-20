<?php 
	require_once('functions.php');
	if(isset($_GET['page'])){
		$page=$_GET['page'];
		$start = ($page-1)*10;
	}else{
		$page=1;
		$start=0;
	}
	// Assign a query
	$query = "SELECT * FROM petugas ORDER BY nama LIMIT 10 OFFSET $start";
	// Execute the query
	$result = $con->query( $query );
	if(!$result){
		die('Could not connect to database : <br/>'.$con->error);
	}
	$i=$start+1;

	while($row = $result->fetch_object()){
		echo "<tr>";
		echo "<td>".$i."</td>";$i++;
		echo "<td>".$row->nama."</td>";
		echo "<td>".$row->email."</td>";
		echo "<td>
				<a href='edit_petugas.php?id=".$row->idpetugas."'><i class='fa fa-edit'></i></a><br/>
				<a href='delete.php?id=".$row->idpetugas."'><i class='fa fa-trash-o'></i></a>
			 </td>";
		echo "</tr>";
	}			
?>