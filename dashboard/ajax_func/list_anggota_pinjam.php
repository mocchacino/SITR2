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
	$query = "SELECT peminjaman.nim, anggota.nama, buku.judul FROM peminjaman INNER JOIN anggota ON peminjaman.nim=anggota.nim INNER JOIN detail_transaksi ON peminjaman.idtransaksi=detail_transaksi.idtransaksi INNER JOIN buku ON detail_transaksi.idbuku=buku.idbuku WHERE detail_transaksi.tgl_kembali = date(0000-00-00) LIMIT 10 OFFSET $start";
	// Execute the query
	$result = $con->query( $query );
	if(!$result){
		die('Could not connect to database : <br/>'.$con->error);
	}
	$i=$start+1;
	while($row = $result->fetch_object()){
		echo '<tr>';
		echo '<td class="text-left">'.$i.'</td>';
		echo '<td class="text-left">'.$row->nim.'</td>';
		echo '<td class="text-left">'.$row->nama.'</td>';
		echo '<td class="text-left">'.$row->judul.'</td>';
		echo '</tr>';	
		$i++;
	}			
?>