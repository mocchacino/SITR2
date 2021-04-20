<?php 
	setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
				ini_set('display_errors', 1);
				$nim=$_GET['nim'];
				$id=$_GET['id'];
		if($_SERVER['REQUEST_METHOD']==='GET'){				
				$ketua=mysqli_query($con,"SELECT nip,nama_dosen FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi WHERE jabatan='ketua' and uji_skripsi.id_uji_skripsi='$id'");
				$rKetua=$ketua->fetch_assoc();
				$anggota=mysqli_query($con,"SELECT dosen.nip, nama_dosen FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi WHERE uji_skripsi.id_uji_skripsi='$id' and jabatan!='sekretaris' order by jabatan");
				$dataMahasiswa=mysqli_query($con, "SELECT id_daftar_skripsi,nama, mahasiswa.nim, judul, nip1, nip2, nip3 FROM daftar_skripsi inner join mahasiswa on daftar_skripsi.nim=mahasiswa.nim where daftar_skripsi.id_daftar_skripsi='$id' and mahasiswa.nim= '$nim'  ");
				$rDataMahasiswa=$dataMahasiswa->fetch_assoc();
				$infoUjiSkripsi=mysqli_query($con, "SELECT uji_skripsi.id_uji_skripsi, jadwal, tempat from uji_skripsi inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='".$rDataMahasiswa['id_daftar_skripsi']."' ");
				$rinfoUjiSkripsi=$infoUjiSkripsi->fetch_assoc();

				$pembimbing1=mysqli_query($con, "SELECT nip, nama_dosen from dosen where nip='".$rDataMahasiswa['nip1']."' ");
				$rPembimbing1=$pembimbing1->fetch_assoc();
				$pembimbing2=mysqli_query($con, "SELECT nip, nama_dosen from dosen where nip='".$rDataMahasiswa['nip2']."' ");
				$rPembimbing2=$pembimbing2->fetch_assoc();
				$pembimbing3=mysqli_query($con, "SELECT nip, nama from pembimbing_luar where nip='".$rDataMahasiswa['nip3']."' ");
				$rPembimbing3=$pembimbing3->fetch_assoc();
				
				if(($rKetua != '') || ($dataMahasiswa!= '')){
?>

				<!DOCTYPE html>
		          <html>
		            <head>
		              <title>Print Surat Permohonan Pengantar</title>
		              <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		              <script>
		              $(document).ready(function(){
		                  $('#print').click(function(){
		                    var divToPrint = document.getElementById('printArea');  
		                    var htmlToPrint = '' +
		                      '<style type="text/css">' +
		                        '.ket_lembar{'+
		                          'font-size:10px;'+
		                        '}'+
		                      '</style>'; 
		                      htmlToPrint += divToPrint.outerHTML;        
		                      htmlToPrint = divToPrint.outerHTML;
		                    var win = window.open();
		                    win.document.write('<style>div.margin{width: 21cm;padding: 0cm 3cm 3cm 3cm; margin: auto;} .margin_top{margin-top:1cm;} .ttd{width:50%;} .pengisi{width:25%;} table.isi{line-height: 30px; font-size: 18px; height:10px;} tr.title{background:#D3D3D3;} .footer_surat{font-size:10px; position:fixed; bottom:50; width: 21cm;} body{-webkit-print-color-adjust:exact;} .page-break{page-break-after: always; } </style>');
		                    win.document.write(htmlToPrint);
		                    win.print();
		                    win.close();
		                  })
		                });
		              </script>
		              <style> 
		              table {  
		                  border-collapse: collapse;
		                  font-size:18px;
		                  font-family:"Times New Roman"
		                }
		                div.isisurat{
		                  line-height: 55px;
		                }
		                .tanggal{
		                  padding: 0px 0px 0px 500px;
		                }
		                .ttd{
		                  width: 50%;
		                }
		                .numerasi{
		                  vertical-align:top;
		                }
		                .tabulasi{
		                  text-indent: 100px;
		                }
		                .page-break{
				        	page-break-after: always;
				        }
		              </style>
		            </head>
		            <body>
		              <div class="panel-body">
		                  <div class="col-md-12 col-sm-12 col-xs-12">
		                  <br>
		                  <div class="panel-body"><div class="well clearfix">
<?php
		                  	echo '<a href="print_berkas_ujian_sarjana.php?nim='.$nim.'&id='.$id.'" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Print</a>';
?>
								<button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
							</div>
		                  </div>
		                </div>
		                <hr>
		                <div class="margin" id="printArea">
<?php
//PENGUJI
					$noPenguji=1;
					while($rAnggota=$anggota->fetch_assoc()){
						echo'<h3 class="margin_top"><center><b>LEMBAR PENILAIAN UJIAN TUGAS RISET<br>PROGRAM STUDI S1 KIMIA DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h3>
						<hr><hr>
		                <div class="isisurat">'; 
					
						echo '<table class="isi" width="100%">';

						$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
						$hari= strftime("%A",strtotime($rinfoUjiSkripsi['jadwal']));
						echo '<tr>';
						echo '<td width="170">Hari/ Tanggal</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$hari.' / '.$tanggal.'</td>';
						echo '</tr>';

						$waktu= strftime("%R",strtotime($rinfoUjiSkripsi['jadwal']));
						echo '<tr>';
						echo '<td width="170">Waktu</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$waktu.' WIB</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td width="170">Tempat</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$rinfoUjiSkripsi['tempat'].'</td>';
						echo '</tr>';
						

						echo '<tr>';
						echo '<td width="170">Nama/ NIM</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$rDataMahasiswa['nama'].' / '.$rDataMahasiswa['nim'].'</td>';
						echo '</tr>';				
						// echo '</table>';
						
						// echo '<table class="isi">';
						$no=1;
						for($i=1; $i<3;$i++){
							if(${"rPembimbing".$i}['nama_dosen']!=''){
								if($no==1){
									echo '<tr>';
									echo '<td  width="170" colspan="2">Pembimbing</td>';
									echo '<td>:</td>';
									echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}else{
									echo '<tr>';
									echo '<td  width="170" colspan="2"></td>';
									echo '<td></td>';
									echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}
									
							}
						}
						if($rPembimbing3['nama']!=''){
							echo '<tr>';
							echo '<td  width="170" colspan="2"></td>';
							echo '<td></td>';
							echo '<td>'.$no.'. '.$rPembimbing3['nama'].'</td>';
							echo '</tr>';
						}
						echo '<tr><td></td></tr>';
						echo '<tr>';
						echo '<td width="170">Judul Skripsi</td>';
						echo '<td></td>';echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa['judul'].'</td>';
						echo '</tr>';
						echo '<tr><td></td></tr>';
						echo '<tr style="font-weight:bold;">';
						echo '<td width="170">Penilaian</td>';
						echo '<td></td>';echo '<td></td>';
						echo '<td>Antara 1 sampai dengan 100</td>';
						echo '</tr>';
						echo '</table>';
//TABEL PENILAIAN
						echo '<table class="isi" border="1" width="100%">';
						echo '<tr style="font-weight:bold;" class="title">';
						echo '<td>Komposisi Penilaian</td>';
						echo '<td>Bobot</td>';
						echo '<td>Bobot x Nilai</td>';
						echo '<td>Jumlah</td>';
						echo '</tr>';
// SKRIPSI
						echo '<tr style="font-weight:bold;">';
						echo '<td colspan="4">I. Skripsi (30%)</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>1. Bahasa dan Format (1-100)</td>';
						echo '<td>10%</td>';
						echo '<td>10% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>2. Substansi (1-100)</td>';
						echo '<td>20%</td>';
						echo '<td>20% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr style="font-weight:bold;" align="right">';
						echo '<td colspan="3">Jumlah I</td>';
						echo '<td>.....</td>';
						echo '</tr>';
// PRESENTASI
						echo '<tr style="font-weight:bold;">';
						echo '<td colspan="4">II. Presentasi (20%)</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>3. Desain Komunikasi Gagasan (1-100)</td>';
						echo '<td>10%</td>';
						echo '<td>10% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>4. Bahasa Ilmiah (1-100)</td>';
						echo '<td>5%</td>';
						echo '<td>5% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>5. Sikap (1-100)</td>';
						echo '<td>5%</td>';
						echo '<td>5% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr style="font-weight:bold;" align="right">';
						echo '<td colspan="3">Jumlah II</td>';
						echo '<td>.....</td>';
						echo '</tr>';
// DISKUSI
						echo '<tr style="font-weight:bold;">';
						echo '<td colspan="4">III. Diskusi (50%)</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>6. Penguasaan Materi (1-100)</td>';
						echo '<td>30%</td>';
						echo '<td>30% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>7. Kemampuan Analisis (1-100)</td>';
						echo '<td>10%</td>';
						echo '<td>10% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>8. Penguasaan Pengetahuan Penunjang (1-100)</td>';
						echo '<td>10%</td>';
						echo '<td>10% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr style="font-weight:bold;" align="right">';
						echo '<td colspan="3">Jumlah III</td>';
						echo '<td>.....</td>';
						echo '</tr>';
						echo '</table>';
//NILAI TOTAL
						echo '<table class="isi" border="1" width="100%">';
						echo '<tr style="font-weight:bold;" align="right">';
						echo '<td colspan="3">Jumlah Total (I + II + III)</td>';
						echo '<td>.....</td>';
						echo '</tr>';
//catatan
						echo '<tr style="font-weight:bold;" align="left">';
						echo '<td colspan="4" height="50">Catatan Penguji:</td>';
						echo '</tr>';

						echo '<tr style="font-weight:bold;" align="right">';
						echo '<td colspan="3">Konversi Nilai Angka ke Huruf</td>';
						echo '<td>.....</td>';
						echo '</tr>';
						echo '</table>';
//KISARAN NILAI
						echo '<table class="isi" border="1" width="100%" style="text-align:center;">';
						echo '<tr style="font-weight:bold;">';
						echo '<td colspan="5">Nilai Huruf dan Kisaran Rerata Nilai Angka</td>';
						echo '</tr>';

						echo '<tr style="font-weight:bold;">';
						echo '<td>A</td>';
						echo '<td>B</td>';
						echo '<td>C</td>';
						echo '<td>D</td>';
						echo '<td>E</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>80 <= X <= 100</td>';
						echo '<td>70 <= X < 80</td>';
						echo '<td>60 <= X < 70</td>';
						echo '<td>50 <= X < 60</td>';
						echo '<td>X < 50</td>';
						echo '</tr>';
						echo '</table>';
						echo '*) Presentasi maks. 10 menit';
						echo '<br>';
//TANDA TANGAN
						$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

						echo '<table style="font-size:18" width="100%">';
						echo '<tr>';
						echo '<td class="ttd">Menyetujui,</td>';
						echo '<td width="15%"></td>';
						echo '<td >Semarang, '.$tgl_sekarang.'</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td >Panitia Ujian Tugas Riset</td>';
						echo '<td ></td>';
						echo '<td>Penguji '.$noPenguji.',</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td >Ketua</td>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td height="50px"></td>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td ><u>'.$rKetua['nama_dosen'].'</u></td>';
						echo '<td></td>';
						echo '<td ><u>'.$rAnggota['nama_dosen'].'</u></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td >NIP. '.$rKetua['nip'].'</td>';
						echo '<td ></td>';
						echo '<td >NIP. '.$rAnggota['nip'].'</td>';
						echo '</tr>';
						echo '</table>';
						
						echo '<div class="page-break"></div>';
						echo '</div>';
						$noPenguji++;
					}
//PEMBIMBING					
					for($jml=1; $jml<3;$jml++){
						if(${"rPembimbing".$jml}['nama_dosen']!=''){
							echo '<h3 class="margin_top"><center><b>LEMBAR PENILAIAN UJIAN TUGAS RISET<br>PROGRAM STUDI S1 KIMIA DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h3>
							<hr><hr>
		                	<div class="isisurat">';  
					
							echo '<table class="isi" width="100%">';

							$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
							$hari= strftime("%A",strtotime($rinfoUjiSkripsi['jadwal']));
							echo '<tr>';
							echo '<td width="170">Hari/ Tanggal</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$hari.' / '.$tanggal.'</td>';
							echo '</tr>';

							$waktu= strftime("%R",strtotime($rinfoUjiSkripsi['jadwal']));
							echo '<tr>';
							echo '<td width="170">Waktu</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$waktu.' WIB</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td width="170">Tempat</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$rinfoUjiSkripsi['tempat'].'</td>';
							echo '</tr>';
							

							echo '<tr>';
							echo '<td width="170">Nama/ NIM</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$rDataMahasiswa['nama'].' / '.$rDataMahasiswa['nim'].'</td>';
							echo '</tr>';				
							// echo '</table>';
							
							// echo '<table class="isi">';
							$no=1;
							for($i=1; $i<3;$i++){
							if(${"rPembimbing".$i}['nama_dosen']!=''){
								if($no==1){
									echo '<tr>';
									echo '<td  width="170" colspan="2">Pembimbing</td>';
									echo '<td>:</td>';
									echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}else{
									echo '<tr>';
									echo '<td  width="170" colspan="2"></td>';
									echo '<td></td>';
									echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}
									
							}
						}
						if($rPembimbing3['nama']!=''){
							echo '<tr>';
							echo '<td  width="170" colspan="2"></td>';
							echo '<td></td>';
							echo '<td>'.$no.'. '.$rPembimbing3['nama'].'</td>';
							echo '</tr>';
						}
							echo '<tr><td></td></tr>';
							echo '<tr>';
							echo '<td width="170">Judul Skripsi</td>';
							echo '<td></td>';echo '<td>:</td>';
							echo '<td>'.$rDataMahasiswa['judul'].'</td>';
							echo '</tr>';
							echo '<tr><td></td></tr>';
							echo '<tr style="font-weight:bold;">';
							echo '<td width="170">Penilaian</td>';
							echo '<td></td>';echo '<td></td>';
							echo '<td>Antara 1 sampai dengan 100</td>';
							echo '</tr>';
							echo '</table>';
	//TABEL PENILAIAN
							echo '<table class="isi" border="1" width="100%">';
							echo '<tr style="font-weight:bold;" class="title">';
							echo '<td>Komposisi Penilaian</td>';
							echo '<td>Bobot</td>';
							echo '<td>Bobot x Nilai</td>';
							echo '<td>Jumlah</td>';
							echo '</tr>';
	// SKRIPSI
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="4">I. Skripsi (30%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>1. Bahasa dan Format (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>2. Substansi (1-100)</td>';
							echo '<td>20%</td>';
							echo '<td>20% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah I</td>';
							echo '<td>.....</td>';
							echo '</tr>';
	// PRESENTASI
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="4">II. Presentasi (20%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>3. Desain Komunikasi Gagasan (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>4. Bahasa Ilmiah (1-100)</td>';
							echo '<td>5%</td>';
							echo '<td>5% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>5. Sikap (1-100)</td>';
							echo '<td>5%</td>';
							echo '<td>5% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah II</td>';
							echo '<td>.....</td>';
							echo '</tr>';
	// DISKUSI
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="4">III. Diskusi (50%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>6. Penguasaan Materi (1-100)</td>';
							echo '<td>30%</td>';
							echo '<td>30% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>7. Kemampuan Analisis (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>8. Penguasaan Pengetahuan Penunjang (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah III</td>';
							echo '<td>.....</td>';
							echo '</tr>';
							echo '</table>';
	//NILAI TOTAL
							echo '<table class="isi" border="1" width="100%">';
							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah Total (I + II + III)</td>';
							echo '<td>.....</td>';
							echo '</tr>';
	//catatan
							echo '<tr style="font-weight:bold;" align="left">';
							echo '<td colspan="4" height="50">Catatan Penguji:</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Konversi Nilai Angka ke Huruf</td>';
							echo '<td>.....</td>';
							echo '</tr>';
							echo '</table>';
	//KISARAN NILAI
							echo '<table class="isi" border="1" width="100%" style="text-align:center;">';
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="5">Nilai Huruf dan Kisaran Rerata Nilai Angka</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;">';
							echo '<td>A</td>';
							echo '<td>B</td>';
							echo '<td>C</td>';
							echo '<td>D</td>';
							echo '<td>E</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>80 <= X <= 100</td>';
							echo '<td>70 <= X < 80</td>';
							echo '<td>60 <= X < 70</td>';
							echo '<td>50 <= X < 60</td>';
							echo '<td>X < 50</td>';
							echo '</tr>';
							echo '</table>';
							echo '*) Presentasi maks. 10 menit';
							echo '<br>';
	//TANDA TANGAN
							$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

							echo '<table style="font-size:18" width="100%">';
							echo '<tr>';
							echo '<td class="ttd">Menyetujui,</td>';
							echo '<td width="15%"></td>';
							echo '<td >Semarang, '.$tgl_sekarang.'</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td >Panitia Ujian Tugas Riset</td>';
							echo '<td ></td>';
							echo '<td>Pembimbing '.$jml.',</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td >Ketua</td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td height="50px"></td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td ><u>'.$rKetua['nama_dosen'].'</u></td>';
							echo '<td></td>';
							echo '<td ><u>'.${"rPembimbing".$jml}['nama_dosen'].'</u></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td >NIP. '.$rKetua['nip'].'</td>';
							echo '<td ></td>';
							echo '<td >NIP. '.${"rPembimbing".$jml}['nip'].'</td>';
							echo '</tr>';
							echo '</table>';
							
							echo '<div class="page-break"></div>';
							echo '</div>';
						}
					}
					if($rPembimbing3['nama']!=''){
							echo '<h3 class="margin_top"><center><b>LEMBAR PENILAIAN UJIAN TUGAS RISET<br>PROGRAM STUDI S1 KIMIA DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h3>
							<hr><hr>
		                	<div class="isisurat">';  
					
							echo '<table class="isi" width="100%">';

							$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
							$hari= strftime("%A",strtotime($rinfoUjiSkripsi['jadwal']));
							echo '<tr>';
							echo '<td width="170">Hari/ Tanggal</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$hari.' / '.$tanggal.'</td>';
							echo '</tr>';

							$waktu= strftime("%R",strtotime($rinfoUjiSkripsi['jadwal']));
							echo '<tr>';
							echo '<td width="170">Waktu</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$waktu.' WIB</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td width="170">Tempat</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$rinfoUjiSkripsi['tempat'].'</td>';
							echo '</tr>';
							

							echo '<tr>';
							echo '<td width="170">Nama/ NIM</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$rDataMahasiswa['nama'].' / '.$rDataMahasiswa['nim'].'</td>';
							echo '</tr>';				
							// echo '</table>';
							
							// echo '<table class="isi">';
							$no=1;
							for($i=1; $i<3;$i++){
							if(${"rPembimbing".$i}['nama_dosen']!=''){
								if($no==1){
									echo '<tr>';
									echo '<td  width="170" colspan="2">Pembimbing</td>';
									echo '<td>:</td>';
									echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}else{
									echo '<tr>';
									echo '<td  width="170" colspan="2"></td>';
									echo '<td></td>';
									echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}
									
							}
						}
						if($rPembimbing3['nama']!=''){
							echo '<tr>';
							echo '<td  width="170" colspan="2"></td>';
							echo '<td></td>';
							echo '<td>'.$no.'. '.$rPembimbing3['nama'].'</td>';
							echo '</tr>';
						}
							echo '<tr><td></td></tr>';
							echo '<tr>';
							echo '<td width="170">Judul Skripsi</td>';
							echo '<td></td>';echo '<td>:</td>';
							echo '<td>'.$rDataMahasiswa['judul'].'</td>';
							echo '</tr>';
							echo '<tr><td></td></tr>';
							echo '<tr style="font-weight:bold;">';
							echo '<td width="170">Penilaian</td>';
							echo '<td></td>';echo '<td></td>';
							echo '<td>Antara 1 sampai dengan 100</td>';
							echo '</tr>';
							echo '</table>';
	//TABEL PENILAIAN
							echo '<table class="isi" border="1" width="100%">';
							echo '<tr style="font-weight:bold;" class="title">';
							echo '<td>Komposisi Penilaian</td>';
							echo '<td>Bobot</td>';
							echo '<td>Bobot x Nilai</td>';
							echo '<td>Jumlah</td>';
							echo '</tr>';
	// SKRIPSI
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="4">I. Skripsi (30%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>1. Bahasa dan Format (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>2. Substansi (1-100)</td>';
							echo '<td>20%</td>';
							echo '<td>20% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah I</td>';
							echo '<td>.....</td>';
							echo '</tr>';
	// PRESENTASI
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="4">II. Presentasi (20%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>3. Desain Komunikasi Gagasan (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>4. Bahasa Ilmiah (1-100)</td>';
							echo '<td>5%</td>';
							echo '<td>5% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>5. Sikap (1-100)</td>';
							echo '<td>5%</td>';
							echo '<td>5% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah II</td>';
							echo '<td>.....</td>';
							echo '</tr>';
	// DISKUSI
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="4">III. Diskusi (50%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>6. Penguasaan Materi (1-100)</td>';
							echo '<td>30%</td>';
							echo '<td>30% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>7. Kemampuan Analisis (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>8. Penguasaan Pengetahuan Penunjang (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah III</td>';
							echo '<td>.....</td>';
							echo '</tr>';
							echo '</table>';
	//NILAI TOTAL
							echo '<table class="isi" border="1" width="100%">';
							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Jumlah Total (I + II + III)</td>';
							echo '<td>.....</td>';
							echo '</tr>';
	//catatan
							echo '<tr style="font-weight:bold;" align="left">';
							echo '<td colspan="4" height="50">Catatan Penguji:</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;" align="right">';
							echo '<td colspan="3">Konversi Nilai Angka ke Huruf</td>';
							echo '<td>.....</td>';
							echo '</tr>';
							echo '</table>';
	//KISARAN NILAI
							echo '<table class="isi" border="1" width="100%" style="text-align:center;">';
							echo '<tr style="font-weight:bold;">';
							echo '<td colspan="5">Nilai Huruf dan Kisaran Rerata Nilai Angka</td>';
							echo '</tr>';

							echo '<tr style="font-weight:bold;">';
							echo '<td>A</td>';
							echo '<td>B</td>';
							echo '<td>C</td>';
							echo '<td>D</td>';
							echo '<td>E</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>80 <= X <= 100</td>';
							echo '<td>70 <= X < 80</td>';
							echo '<td>60 <= X < 70</td>';
							echo '<td>50 <= X < 60</td>';
							echo '<td>X < 50</td>';
							echo '</tr>';
							echo '</table>';
							echo '*) Presentasi maks. 10 menit';
							echo '<br>';
	//TANDA TANGAN
							$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

							echo '<table style="font-size:18" width="100%">';
							echo '<tr>';
							echo '<td class="ttd">Menyetujui,</td>';
							echo '<td width="15%"></td>';
							echo '<td >Semarang, '.$tgl_sekarang.'</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td >Panitia Ujian Tugas Riset</td>';
							echo '<td ></td>';
							echo '<td>Pembimbing '.$jml.',</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td >Ketua</td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td height="50px"></td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td ><u>'.$rKetua['nama_dosen'].'</u></td>';
							echo '<td></td>';
							echo '<td ><u>'.$rPembimbing3['nama'].'</u></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td >NIP. '.$rKetua['nip'].'</td>';
							echo '<td ></td>';
							echo '<td >NIP. '.$rPembimbing3['nip'].'</td>';
							echo '</tr>';
							echo '</table>';
							
							echo '<div class="page-break"></div>';
							echo '</div>';
						}
						echo '</div>';
					echo '</body>';
					echo '</html>';
		        }
		      
		    

		    include_once('../footer.php');
		    $con->close();
		// 	}
		}
	}else{
		header("Location:./");
	}
?>