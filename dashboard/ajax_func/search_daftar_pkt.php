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
	if($search==''){
		$query = "SELECT * FROM daftar_pkt d LEFT JOIN anggota a ON d.nim=a.nim  LIMIT 10 OFFSET $start";		
	}else{
		$query = "SELECT * FROM daftar_pkt d LEFT JOIN anggota a ON d.nim=a.nim WHERE d.nim like '%$search%' OR nama like '%$search%' LIMIT 10 OFFSET $start";
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
		echo "<td>".$row->nim."</td>";
		echo "<td>".$row->pilihan1."</td>";
		echo "<td>".$row->pilihan2."</td>";
		echo "<td>".$row->pilihan3."</td>";
		echo "<td align'center'>
		<a href='edit_daftar_pkt.php?nim=".$row->nim."'><i class='fa fa-edit'></i></a>&nbsp;
		<a href='delete_pkt.php?nim=".$row->nim."'><i class='fa fa-trash-o'></i></a>&nbsp;
		</td>
		</tr>";
	}			
?>