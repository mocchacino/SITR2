<?php
	require_once('functions.php');
	if(isset($_GET['id'])){
		$id=$_GET['id'];
	}else{
		$id='';
	}
	if(isset($_GET['page'])){
		$page=$_GET['page'];
		$start = ($page-1)*10;
	}else{
		$page=1;
		$start=0;
	}
?>
<div class="panel-body">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>			
					<th>No</th>
					<th>ISBN</th>
					<th>Judul</th>
					<th>Pengarang</th>
					<th>Penerbit</th>
				</tr>
			</thead>
		<tbody>
			<?php
			//Asign Query
			if($id==''){
				$query="SELECT isbn, judul, pengarang, penerbit FROM buku JOIN detail_transaksi ON buku.idbuku=detail_transaksi.idbuku JOIN peminjaman ON detail_transaksi.idtransaksi=peminjaman.idtransaksi WHERE detail_transaksi.tgl_kembali = date(0000-00-00) GROUP BY detail_transaksi.idbuku LIMIT 10 OFFSET $start";
			}else{	
				$query="SELECT isbn, judul, pengarang, penerbit FROM buku JOIN detail_transaksi ON buku.idbuku=detail_transaksi.idbuku JOIN peminjaman ON detail_transaksi.idtransaksi=peminjaman.idtransaksi WHERE detail_transaksi.tgl_kembali = date(0000-00-00) AND peminjaman.nim= ".$id." GROUP BY detail_transaksi.idbuku LIMIT 10 OFFSET $start";
			}
			//execute
			$result = $con->query($query);
			if(!$result){
			}
			$i = $start+1;
			while($row=$result->fetch_object()){
			echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$row->isbn.'</td>';
				echo '<td>'.$row->judul.'</td>';
				echo '<td>'.$row->pengarang.'</td>';
				echo '<td>'.$row->penerbit.'</td>';
				echo '<tr>';
				$i++;
			}
		?>
		</tbody>
		</table>
	</div>
</div>