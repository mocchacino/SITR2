<?php
require_once('functions.php');
	if(isset($_GET['page'])){
		$page=$_GET['page'];
		$start = ($page-1)*10;
	}else{
		$page=1;
		$start=0;
	}
	$nim=$_GET['nim'];
	//Asign query
	if($nim==''){
		$query = "SELECT detail_transaksi.idbuku, anggota.nama, peminjaman.nim, buku.judul, peminjaman.tgl_pinjam, detail_transaksi.denda, detail_transaksi.idtransaksi 
			FROM peminjaman
			INNER JOIN anggota ON peminjaman.nim = anggota.nim
			INNER JOIN detail_transaksi ON detail_transaksi.idtransaksi = peminjaman.idtransaksi 
			INNER JOIN buku ON detail_transaksi.idbuku = buku.idbuku WHERE detail_transaksi.tgl_kembali = date(0000-00-00) LIMIT 10 OFFSET $start";
	}else{
		$query = "SELECT detail_transaksi.idbuku, anggota.nama, peminjaman.nim, buku.judul, peminjaman.tgl_pinjam, detail_transaksi.denda, detail_transaksi.idtransaksi 
			FROM peminjaman
			INNER JOIN anggota ON peminjaman.nim = anggota.nim
			INNER JOIN detail_transaksi ON detail_transaksi.idtransaksi = peminjaman.idtransaksi 
			INNER JOIN buku ON detail_transaksi.idbuku = buku.idbuku WHERE detail_transaksi.tgl_kembali = date(0000-00-00) AND peminjaman.nim='".$nim."' LIMIT 10 OFFSET $start";
	}
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
		echo '<td class="text-left">'.$row->tgl_pinjam.'</td>';
		echo "<td>
				<a href='pengembalian.php?idtransaksi=".$row->idtransaksi."&idbuku=".$row->idbuku."'><button class='btn-link'>Kembali</button></a>
			 </td>";
		echo '</tr>';	
		$i++;
	}			
?>