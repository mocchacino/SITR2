<?php
	require_once('connect.php');
	
	$con = mysqli_connect($db_host, $db_username, $db_password, $db_database);
	if(mysqli_connect_errno()){
		die('Could not connect to database : <br/>'.$mysqli_connect_error());
	}
	$query = "SELECT * FROM anggota INNER JOIN daftar_tr1 ON anggota.nim = daftar_tr1.nim ";
	$result = $con->query($query);
	if(!$result){
		die("Query tidak terkoneksi dengan database: </br>" .$con->error);
	}
	$result = $result->fetch_object();

	require('assets/fpdf/fpdf.php');
	
	$pdf = new FPDF("p", "mm", "A4");
	$pdf -> AddPage();
	$pdf -> SetFont("Times", "B", 12);
	$pdf->Cell(0,5,'UNIVERSITAS DIPONEGORO',0,1,'L');
	$pdf -> SetFont("Times", "B", 12);
	$pdf->Cell(0,5,'FAKULTAS SAINS DAN MATEMATIKA',0,1,'L');
	$pdf->Cell(0,5,'DEPARTEMEN KIMIA',0,1,'L');
	$pdf->Line(10,27,200,27);$pdf->Ln();
	$pdf -> SetFont("Times", "B", 14);
	$pdf->Cell(0,4,'BERITA ACARA SEMINAR OUTLINE',0,1,'C');
	$pdf->Ln();	
	$pdf -> SetFont("Times", "", 12);
	$pdf->Cell(50,5,'Tanggal Seminar',0,0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->Cell(135,5,'.................',0,1,'L');
	$pdf->Cell(50,5,'Departemen',0,0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->Cell(135,5,'.................',0,1,'L');
	$pdf->Cell(50,5,'Nama Mahasiswa',0,0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->Cell(135,5,'.................',0,1,'L');
	$pdf->Cell(50,5,'NIM',0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->Cell(135,5,'.................',0,1,'L');
	
	//$pdf->Cell(50,5,'JUDUL',0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->Cell(135,5,'..............................................................',0,1,'L');
	//$pdf->Cell(50,5,'JUDUL',0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->Cell(5,5,'1.',0,'C');$pdf->Cell(130,5,'',0,1,'L');
	//$pdf->Cell(50,5,'',0,'L');$pdf->Cell(5,5,'',0,'C');$pdf->Cell(5,5,'2.',0,'C');$pdf->Cell(130,5,'',0,1,'L');
	$pdf->Cell(50,5,'Judul Presentasi',0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->MultiCell(135,5,'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',0,'L');
	$pdf->Cell(50,5,'Tanggal Mulai TR',0,0,'L');$pdf->Cell(5,5,':',0,'C');$pdf->Cell(135,5,'.................',0,1,'L');

	$pdf->Ln();
	$pdf -> SetFont("Times", "B", 12);
	$pdf->Cell(0,5,'Penguji',0,1,'L');
	$pdf->Cell(10,8,'No.',1,0,'C');$pdf->Cell(90,8,'Nama',1,0,'C');$pdf->Cell(40,8,'Jabatan',1,0,'C');$pdf->Cell(30,8,'Tanda Tangan',1,0,'C');$pdf->Cell(20,8,'Nilai',1,1,'C');
	$pdf->Cell(10,8,'1',1,0,'C');$pdf->Cell(90,8,'',1,0,'C');$pdf->Cell(40,8,'Pembimbing I',1,0,'C');$pdf->Cell(30,8,'',1,0,'C');$pdf->Cell(20,8,'',1,1,'C');
	$pdf->Cell(10,8,'2',1,0,'C');$pdf->Cell(90,8,'',1,0,'C');$pdf->Cell(40,8,'Pembimbing II',1,0,'C');$pdf->Cell(30,8,'',1,0,'C');$pdf->Cell(20,8,'',1,1,'C');
	$pdf->Cell(10,8,'3',1,0,'C');$pdf->Cell(90,8,'',1,0,'C');$pdf->Cell(40,8,'',1,0,'C');$pdf->Cell(30,8,'',1,0,'C');$pdf->Cell(20,8,'',1,1,'C');
	$pdf->Cell(10,8,'4',1,0,'C');$pdf->Cell(90,8,'',1,0,'C');$pdf->Cell(40,8,'',1,0,'C');$pdf->Cell(30,8,'',1,0,'C');$pdf->Cell(20,8,'',1,1,'C');
	$pdf->Cell(10,8,'5',1,0,'C');$pdf->Cell(90,8,'',1,0,'C');$pdf->Cell(40,8,'',1,0,'C');$pdf->Cell(30,8,'',1,0,'C');$pdf->Cell(20,8,'',1,1,'C');
	
	// $pdf->Cell(5,6,'c.','L,B',0,'C');$pdf->Cell(45,6,'Pendekatan, Format sebagaimana skripsi (Bab I,II,III, dan hasil (hasil final atau sementara), pembahasan, kesimpulan serta rencana penelitian selanjutnya), Penggunaan bahasa ilmiah dan kemutahiran referensi','B,R,T',0,'L');$pdf->Cell(20,6,'10%',1,0,'C');$pdf->Cell(20,6,'',1,0,'C');$pdf->Cell(30,6,'...',1,1,'C');
	$pdf -> SetFont("Times", "B", 12);
	//$pdf->Cell(5,6,'','L,B',0,'C');$pdf->Cell(0,6,'','R',1,'L');
	$pdf->Cell(170,6,'Rata - rata ',1,0,'C');$pdf->Cell(20,6,'...','L,B,R',1,'C');$pdf -> Ln();
	$pdf -> SetFont("Times", "B", 10);
	$pdf->Cell(0,4,'CATATAN:',1,1,'L');
	$pdf->Cell(0,20,'',1,1,'L');
	

	$pdf->Cell(0,7,'',0,1,'L');
	$pdf -> SetFont("Times", "", 10);
	$pdf->Cell(140,5,'',0,0,'FJ');$pdf->Cell(20,5,'Semarang,',0,0,'FJ');$pdf->Cell(60,5,'TANGGAL',0,1,'FJ');
	$pdf->Cell(85,5,'',0,0,'FJ');$pdf->Cell(55,5,'',0,0,'FJ');$pdf->Cell(100,5,'Pembimbing ...',0,1,'FJ');
	$pdf->Cell(85,5,'',0,0,'FJ');$pdf->Cell(45,5,'',0,0,'FJ');$pdf->Cell(100,5,'',0,1,'FJ');
	$pdf->Cell(85,5,'',0,0,'FJ');$pdf->Cell(45,5,'',0,0,'FJ');$pdf->Cell(100,5,'',0,1,'FJ');
	$pdf->Cell(0,20,'',0,0,'FJ');
	$pdf -> SetFont("Times", "U", 12);	
	$pdf->Cell(85,5,'',0,0,'FJ');$pdf->Cell(45,5,'',0,0,'FJ');$pdf->Cell(100,5,'<pnguji>',1,1,'FJ');
	$pdf -> SetFont("Times", "U", 12);

	$pdf->Cell(85,5,'',0,0,'FJ');$pdf->Cell(55,5,'',0,0,'FJ');$pdf->Cell(100,5,'pembimbing',0,1,'FJ');
	$pdf -> SetFont("Times", "", 12);
	$pdf->Cell(40,5,'',0,0,'FJ');$pdf->Cell(55,5,'',0,0,'FJ');$pdf->Cell(45,5,'',0,0,'FJ');$pdf->Cell(10,5,'NIP.',0,0,'FJ');$pdf->Cell(70,5,'NIP pembimbing',0,1,'FJ');

	$pdf ->Output();

?>
