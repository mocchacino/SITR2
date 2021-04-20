<?php 
include('functions.php');
if(isset($_GET["addkategori"])){
	$add_id_kategori = test_input ($_GET['kategori_add_del']);
	if(!$add_id_kategori==''){
		$query = " SELECT * FROM kategori WHERE nama='".$add_id_kategori."'";
		$result = $con->query( $query );
		if($result->num_rows!=0){
			echo "Kategori sudah ada";
			$valid_add_kategori=FALSE;
		}
		else{
			$valid_add_kategori = TRUE;
		}
	}
	if ($valid_add_kategori){
			//escape inputs data
			$add_id_kategori = $con->real_escape_string($add_id_kategori);
			//Asign a query
			$query = " INSERT INTO kategori SET nama='".$add_id_kategori."'";
			// Execute the query
			$result = $con->query( $query );
			if (!$result){
			   die ("Could not query the database:". $con->error);
			}else{
				echo 'Kategori berhasil ditambahkan.';
			}
		}
}
if(isset($_GET["deletekategori"])){
	$add_id_kategori = test_input ($_GET['kategori_add_del']);
	if(!$add_id_kategori==''){
		//escape inputs data
		$add_id_kategori = $con->real_escape_string($add_id_kategori);
		$query = " SELECT * FROM kategori WHERE nama='".$add_id_kategori."'";
		$result = $con->query( $query );
		if($result->num_rows==0){
			echo "Kategori tidak ada dalam database.";
			$valid_add_kategori=FALSE;
		}else{
			$valid_add_kategori=TRUE;
		}
		$result=$result->fetch_object();
		$id_kategori=$result->idkategori;
		
		if($valid_add_kategori){
			$con->autocommit(false);
			$flag = true;
			
			$query = " DELETE FROM kategori WHERE nama='".$add_id_kategori."'";
			$query2 = " UPDATE buku SET idkategori=0 WHERE idkategori='".$id_kategori."'";
			
			$pesan="";
			$result1 = $con->query($query);
			if (!$result1) {
				$flag = false;
				$pesan.= "Kategori gagal dihapus. ";
			}
			$result2 = $con->query($query2);
			if (!$result2) {
				$flag = false;
				$pesan.= "Peng(update)an data buku gagal. ";
			}

			if ($flag) {
				$con->commit();
				$pesan.= "Kategori berhasil dihapus.";
			} else {
				$con->rollback();
				$pesan.= "Proses dibatalkan.";
			}
				
			echo $pesan;
		}
	}
}
if(isset($_GET["addstok"])){
	$isbn = test_input ($_GET['isbn']);
	$stok = test_input ($_GET['stok']);
	if($stok<0 || $stok>1000){
		echo 'Nilai STOK tidak valid ( 0-1000 )';
		exit;
	}elseif(!$isbn==''){
		$query = " SELECT * FROM buku WHERE isbn='".$isbn."'";
		$result = $con->query( $query );
		if($result->num_rows==0){
			echo "ISBN tidak terdaftar.";
			$valid_isbn=FALSE;
		}
		else{
			$valid_isbn = TRUE;
		}
		$result=$result->fetch_object();
		$stok_semula=$result->stok;
		$stok_tersedia_semula=$result->stok_tersedia;
	}
	if ($valid_isbn && $valid_stok){
			//escape inputs data
			$isbn = $con->real_escape_string($isbn);
			$stok = $con->real_escape_string($stok);
			$stok_akhir = $stok+$stok_semula;
			$stok_tersedia = $stok+$stok_tersedia_semula;
			//Asign a query
			$query = " UPDATE buku SET stok=".$stok_akhir.", stok_tersedia=".$stok_tersedia." WHERE isbn='".$isbn."'";
			// Execute the query
			$result = $con->query( $query );
			if (!$result){
			   die ("Could not query the database:". $con->error);
			}else{
				echo 'Stok buku berhasil ditambahkan.';
			}
	}
}
if(isset($_GET['viewkat'])){
	$isbn = $_GET['isbn'];
	$query = " SELECT * FROM buku WHERE isbn='".$isbn."'";
	// Execute the query
	$result = $con->query( $query );
	if (!$result){
		die ("Could not query the database: <br />". $con->error);
	}else{
		$row = $result->fetch_object();
		$id_kategori=$row->idkategori;
	}
	echo '<select class="form-control" id="kategori" name="kategori">';
	echo '<option value="0">Uncategories</option>';
	$cat = "SELECT * FROM kategori";
	$cat = $con->query( $cat );
	if ($cat){
		while ($cate = $cat->fetch_object()){
			echo '<option value="'.$cate->idkategori.'" ';
			if(isset($id_kategori) && $id_kategori==$cate->idkategori) echo "selected='selected'"; 
			echo '>'.$cate->nama.'</option>';
		}
	}
	echo '</select>';
}
if(isset($_GET['viewstok'])){
	$isbn = $_GET['isbn'];
	$query = " SELECT * FROM buku WHERE isbn='".$isbn."'";
	// Execute the query
	$result = $con->query( $query );
	if (!$result){
		die ("Could not query the database: <br />". $con->error);
	}else{
		$row = $result->fetch_object();
		$stok=$row->stok;
		$stok_tersedia=$row->stok_tersedia;
	}
	echo 'Semula : ';if(isset($stok)) {echo $stok;};
	echo ', Tersedia : ';if(isset($stok_tersedia)) {echo $stok_tersedia;};
}
if(isset($_GET['search'])){
	$search=$_GET['search'];
	if(isset($_GET['pkt'])){
		$query = "SELECT count(daftar_pkt.nim) as jml_data FROM daftar_pkt d LEFT JOIN anggota a ON d.nim=a.nim
			  WHERE d.nim like '%$search%' OR nama like '%$search%'";
	}elseif(isset($_GET['bimbingan'])){
			$query="SELECT count(bimbingan.nim) FROM bimbingan JOIN dosen ON bimbingan.nip=dosen.nip JOIN anggota ON bimbingan.nim=anggota.nim WHERE bimbingan.nim like '%$search%' OR nama_dosen like '%$search%' LIMIT 10 OFFSET $start";
		}elseif(isset($_GET['penempatan'])){
			$query = "SELECT * FROM penempatan p INNER JOIN anggota a ON p.nim=a.nim WHERE p.nim like '%$search%' OR nama like '%$search%' LIMIT 10 OFFSET $start";
		}else{	
			$query="SELECT count(detail_transaksi.idbuku) FROM buku JOIN detail_transaksi ON buku.idbuku=detail_transaksi.idbuku JOIN peminjaman ON detail_transaksi.idtransaksi=peminjaman.idtransaksi WHERE detail_transaksi.tgl_kembali = date(0000-00-00) AND peminjaman.nim= ".$search." GROUP BY detail_transaksi.idbuku LIMIT 10 OFFSET $start";
		}
	}elseif(isset($_GET['tr2'])){
		$query = "SELECT count(daftar_tr2.nim) as jml_data FROM daftar_tr2 d LEFT JOIN anggota a ON d.nim=a.nim
			  WHERE d.nim like '%$search%' OR nama like '%$search%'";
	}elseif(isset($_GET['uji_kelayakan'])){
		$query = "SELECT count(daftar_uji_kelayakan.nim) as jml_data FROM daftar_uji_kelayakan d LEFT JOIN anggota a ON d.nim=a.nim
			  WHERE d.nim like '%$search%' OR nama like '%$search%'";
	}
	
	// Execute the query
	$result = $con->query( $query );
	$row = $result->fetch_object();
	$jml_data=$row->jml_data;
	$total_page=floor($jml_data/10)+1;
	echo "<select class='form-control' id='page'>";
	for($i=1;$i<=$total_page;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
	echo "</select>";
}
?>