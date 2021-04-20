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
	if($search=='Uncategories' || $search=='uncategories'){
		$query = "SELECT * FROM buku WHERE idkategori=0
				LIMIT 10 OFFSET $start";
	}else{
		$query = "SELECT * FROM buku b LEFT JOIN kategori k ON b.idkategori=k.idkategori 
				WHERE judul like '%$search%' OR pengarang like '%$search%' OR penerbit like '%$search%' OR nama like '%$search%'
				LIMIT 10 OFFSET $start";		
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
		echo "<td>".$row->judul."</td>";
		echo "<td>"; if(empty($row->nama)) echo '<a href="?search=Uncategories"><span class="label label-warning">Uncategories</span></a>' ; else echo '<a href="?search='.$row->nama.'"><span class="label label-success">'.$row->nama.'</span></a>'; echo "</td>";
		echo "<td><img src='assets/img/".$row->file_gambar."' height='50px;' /></td>";
		echo "<td>".$row->stok_tersedia."</td>";
		echo "<td>
				<a href='detail_buku.php?isbn=".$row->isbn."'><i class='fa fa-eye fa'></i></a>&nbsp;";
		if($status=="petugas"){
			echo "<a href='edit_buku.php?isbn=".$row->isbn."'><i class='fa fa-edit'></i></a>&nbsp;
			<a href='delete_buku.php?isbn=".$row->isbn."'><i class='fa fa-trash-o'></i></a>
			 </td>";
		}
		echo "</tr>";
	}			
?>