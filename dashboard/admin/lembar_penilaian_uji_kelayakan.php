<?php
	date_default_timezone_set("Asia/Bangkok"); 
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
				$dataMahasiswa=mysqli_query($con, "SELECT id_daftar_uji_kelayakan,nama, mahasiswa.nim, daftar_tugas_riset2.judul, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2,daftar_tugas_riset2.nip3, idlab_tr1 FROM daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join mahasiswa on daftar_tugas_riset2.nim=mahasiswa.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim where daftar_uji_kelayakan.id_daftar_uji_kelayakan='$id' and mahasiswa.nim= '$nim'  ");
				$rDataMahasiswa=$dataMahasiswa->fetch_assoc();
				$ketua=mysqli_query($con,"SELECT dosen.nip,nama_dosen FROM lab join dosen on dosen.nip=lab.nip where lab.idlab='".$rDataMahasiswa['idlab_tr1']."'");
				$rKetua=$ketua->fetch_assoc();
				$infoUjiKelayakan=mysqli_query($con, "SELECT uji_kelayakan.id_uji_kelayakan, jadwal, tempat from uji_kelayakan inner join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan where uji_kelayakan.id_uji_kelayakan='".$rDataMahasiswa['id_daftar_uji_kelayakan']."' ");
				$rinfoUjiKelayakan=$infoUjiKelayakan->fetch_assoc();
				$penguji=mysqli_query($con, "SELECT dosen.nip, nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip where id_uji_kelayakan='".$rinfoUjiKelayakan['id_uji_kelayakan']."' and jabatan='penguji' ");

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
		                    win.document.write('<style>div.margin{width: 21cm;padding: 0cm 3cm 3cm 3cm; margin: auto;} .margin_top{margin-top:1cm;} .ttd{width:50%;} .pengisi{width:25%;} table.isi{line-height: 30px; font-size: 18px;} tr.title{background:#D3D3D3;} .footer_surat{font-size:10px; position:fixed; bottom:50; width: 21cm;} body{-webkit-print-color-adjust:exact;} .page-break{page-break-after: always; } </style>');
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
		                /*tr,td{
		                  padding: 15px;
		                }*/
		                /*div.margin{
		                  width: 21cm;
		                  min-height: 29.7cm;
		                  padding: 2cm;
		                  margin: 1cm auto;
		                }*/
		                div.isisurat{
		                  line-height: 55px;
		                }
		                /*.head{
		                  text-align: left;
		                  padding: 0px 0px 0px 550px;
		                }*/
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
		                  	echo '<a href="print_lab.php?nim='.$nim.'&id='.$id.'" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Print</a>';
		                  	?>
								<button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
							</div>
		                  </div>
		                </div>
		                <hr>
		                <div class="margin" id="printArea">
<?php
					$noPenguji=1;
					while($rPenguji=$penguji->fetch_assoc()){
?>

						<h3 class="margin_top"><center><b>LEMBAR PENILAIAN SEMINAR KELAYAKAN SKRIPSI<br>PROGRAM STUDI S1 KIMIA DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h3>
						<hr><hr>

		                <div class="isisurat">
		<?php  
					
						echo '<table class="isi" width="100%">';

						$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiKelayakan['jadwal']));
						$hari= strftime("%A",strtotime($rinfoUjiKelayakan['jadwal']));
						echo '<tr>';
						echo '<td width="170">Hari/ Tanggal</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$hari.' / '.$tanggal.'</td>';
						echo '</tr>';

						$waktu= strftime("%R",strtotime($rinfoUjiKelayakan['jadwal']));
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
						echo '<td>'.$rinfoUjiKelayakan['tempat'].'</td>';
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
						echo '<td></td>';echo '<td></td>';
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
						echo '<td colspan="4">I. Skripsi (50%)</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>1. Bahasa dan Format (1-100)</td>';
						echo '<td>20%</td>';
						echo '<td>20% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>2. Substansi (1-100)</td>';
						echo '<td>30%</td>';
						echo '<td>30% x .....</td>';
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
						echo '<td>3. Desain komunikasi gagasan (1-100)</td>';
						echo '<td>10%</td>';
						echo '<td>10% x .....</td>';
						echo '<td>.....</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>4. Bahasa ilmiah (1-100)</td>';
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
						echo '<td colspan="4">III. Diskusi (30%)</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td>6. Penguasaan Materi (1-100)</td>';
						echo '<td>15%</td>';
						echo '<td>15% x .....</td>';
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
						echo '<td>5%</td>';
						echo '<td>5% x .....</td>';
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
						echo '</table>';
//CATATAN
						
						echo '<b>Catatan Pereview :</b>';
						
						echo '<br><br><br><br><br><br><br><br>';
//TANDA TANGAN
						$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

						echo '<table style="font-size:18" width="100%">';
						echo '<tr>';
						echo '<td class="ttd">Menyetujui,</td>';
						echo '<td width="15%"></td>';
						echo '<td >Semarang, '.$tgl_sekarang.'</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td >Ketua KBK,</td>';
						echo '<td ></td>';
						echo '<td>Pereview '.$noPenguji.',</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td height="50px"></td>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td ><u>'.$rKetua['nama_dosen'].'</u></td>';
						echo '<td></td>';
						echo '<td ><u>'.$rPenguji['nama_dosen'].'</u></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td >NIP. '.$rKetua['nip'].'</td>';
						echo '<td ></td>';
						echo '<td >NIP. '.$rPenguji['nip'].'</td>';
						echo '</tr>';
						echo '</table>';
						
						echo '<div class="page-break"></div>';
						echo '</div>';
						$noPenguji++;
					}
					
					for($jml=1; $jml<3;$jml++){
						if(${"rPembimbing".$jml}['nama_dosen']!=''){
							echo '<h3 class="margin_top"><center><b>LEMBAR PENILAIAN SEMINAR KELAYAKAN SKRIPSI<br>PROGRAM STUDI S1 KIMIA DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h3>
							<hr><hr>
			                <div class="isisurat">';
			
						
							echo '<table class="isi" width="100%">';

							$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiKelayakan['jadwal']));
							$hari= strftime("%A",strtotime($rinfoUjiKelayakan['jadwal']));
							echo '<tr>';
							echo '<td width="170">Hari/ Tanggal</td>';
							echo '<td>:</td>';
							echo '<td></td>';
							echo '<td>'.$hari.' / '.$tanggal.'</td>';
							echo '</tr>';

							$waktu= strftime("%R",strtotime($rinfoUjiKelayakan['jadwal']));
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
							echo '<td>'.$rinfoUjiKelayakan['tempat'].'</td>';
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
							for($i=1; $i<4;$i++){
								if(${"rPembimbing".$i}['nama_dosen']!=''){
									if($no==1){
										echo '<tr>';
										echo '<td  width="170" colspan="2">Pembimbing</td>';
										echo '<td>:</td>';
										echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
										echo '</tr>';
									}else{
										echo '<tr>';
										echo '<td  width="170" colspan="2"></td>';
										echo '<td></td>';
										echo '<td>'.$no.'. '.${"rPembimbing".$i}['nama_dosen'].'</td>';
										echo '</tr>';
									}
									$no++;	
								}
								
							}
							echo '<tr><td></td></tr>';
							echo '<tr>';
							echo '<td width="170">Judul Skripsi</td>';
							echo '<td></td>';echo '<td></td>';
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
							echo '<td colspan="4">I. Skripsi (50%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>1. Bahasa dan Format (1-100)</td>';
							echo '<td>20%</td>';
							echo '<td>20% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>2. Substansi (1-100)</td>';
							echo '<td>30%</td>';
							echo '<td>30% x .....</td>';
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
							echo '<td>3. Desain komunikasi gagasan (1-100)</td>';
							echo '<td>10%</td>';
							echo '<td>10% x .....</td>';
							echo '<td>.....</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>4. Bahasa ilmiah (1-100)</td>';
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
							echo '<td colspan="4">III. Diskusi (30%)</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td>6. Penguasaan Materi (1-100)</td>';
							echo '<td>15%</td>';
							echo '<td>15% x .....</td>';
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
							echo '<td>5%</td>';
							echo '<td>5% x .....</td>';
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
							echo '</table>';
	//CATATAN
							
							echo '<b>Catatan Pereview :</b>';
							
							echo '<br><br><br><br><br><br><br><br>';
	//TANDA TANGAN
							$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

							echo '<table style="font-size:18" width="100%">';
							echo '<tr>';
							echo '<td class="ttd">Menyetujui,</td>';
							echo '<td width="15%"></td>';
							echo '<td >Semarang, '.$tgl_sekarang.'</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td >Ketua KBK,</td>';
							echo '<td ></td>';
							echo '<td>Pembimbing '.$jml.',</td>';
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