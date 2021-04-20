<?php
	require_once('functions.php');
	if(isset($_GET['page'])){
		$page=$_GET['page'];
		$start = ($page-1)*10;
	}else{
		$page=1;
		$start=0;
	}
	
	$query="SELECT * FROM buku JOIN detail_transaksi ON buku.idbuku=detail_transaksi.idbuku JOIN peminjaman ON detail_transaksi.idtransaksi=peminjaman.idtransaksi WHERE peminjaman.nim= ".$id." ORDER BY peminjaman.tgl_pinjam LIMIT 10 OFFSET $start";
	//execute
	$result = $con->query($query);
	if(!$result){
		die ("Could not query the database: <br />". $con->error);
	}
	$i = $start+1;
	while($row=$result->fetch_object()){
	echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$row->isbn.'</td>';
		echo '<td>'.$row->judul.'</td>';
		echo '<td>'.$row->pengarang.'</td>';
		echo '<td>'.$row->tgl_pinjam.'</td>';
		echo '<td>';
			if($row->tgl_kembali == '0000-00-00'){echo '<span class="label label-warning">Belum Kembali</span>';} else {echo $row->tgl_kembali;} 
		echo '</td>';
		echo '<td>';
			if($row->tgl_kembali == '0000-00-00'){
				$denda=hitungdenda($row->tgl_pinjam,date('Y-m-d'));
				if($denda!=0){
					echo '<span class="label label-warning"><i class="fa fa-check"></i> Rp '.$denda.'</span>';
				}else{
					echo '<span class="label label-success"><i class="fa fa-check"></i> Rp 0</span>';;
				}
			}elseif($row->denda!=0){
				echo '<span class="label label-warning"><i class="fa fa-check"></i> Rp '.$row->denda.'</span>';
			}else{
				echo '<span class="label label-success"><i class="fa fa-check"></i> Rp 0</span>';
			}
		echo '</td>';
		echo '<tr>';
		$i++;
	}
?>