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
				$ketua=mysqli_query($con,"SELECT nip,nama_dosen FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi WHERE jabatan='ketua' and uji_skripsi.id_uji_skripsi='$id'");
				$rKetua=$ketua->fetch_assoc();
				$penguji=mysqli_query($con,"SELECT dosen.nip, nama_dosen, jabatan FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi WHERE uji_skripsi.id_uji_skripsi='$id' and jabatan!='sekretaris' order by jabatan");
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
		                    win.document.write('<style>div.margin{width: 21cm;padding: 0cm 3cm 3cm 3cm; margin: auto;} .margin_top{margin-top:1cm;} .ttd{width:50%;} .pengisi{width:25%;} table.isi{line-height: 30px; font-size: 18px; font-weight:bold;} tr.title{background:#D3D3D3; font-weight:bold;} .footer_surat{font-size:10px; position:fixed; bottom:50; width: 21cm;} body{-webkit-print-color-adjust:exact;} .page-break{page-break-after: always; } </style>');
		                    win.document.write(htmlToPrint);
		                    win.print();
		                    win.close();
		                  })
		                });
		              </script>
		              <style> 
		              table {  
		                  border-collapse: collapse;
		                  font-size:16px;
		                  font-family:"Times New Roman";
		                  font-weight:bold;
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
		                  	echo '<a href="print_berkas_ujian_sarjana.php?nim='.$nim.'&id='.$id.'" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Print</a>';
		                  	?>
								<button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
							</div>
		                  </div>
		                </div>
		                <hr>
		                <div class="margin" id="printArea">
<?php
					$jml_anggota=2;
					while($rPenguji=$penguji->fetch_assoc()){
?>
						<h2 class="margin_top"><center><b>SIDANG UJIAN TUGAS RISET<br>PROGRAM STUDI S1 KIMIA<br>DEPARTEMEN KIMIA<br>FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h2>
						<hr><hr>

		                <div class="isisurat">
<?php  
					
						echo '<table class="isi" >';
						echo '<tr>';
						echo '<td>FORMULIR REVIEW</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td width="170">Nama</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa['nama'].'</td>';
						echo '</tr>';	

						echo '<tr>';
						echo '<td width="170">NIM</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa['nim'].'</td>';
						echo '</tr>';	

						$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
						$hari= strftime("%A",strtotime($rinfoUjiSkripsi['jadwal']));
						//$waktu= strftime("%R",strtotime($rinfoUjiSkripsi['jadwal']));
						echo '<tr>';
						echo '<td width="170">Tanggal Ujian</td>';
						echo '<td>:</td>';
						echo '<td>'.$hari.', '.$tanggal.'</td>';
						echo '</tr>';
						echo '</table>';
						echo '<br>';
// ISI
						echo '<table class="isi" width="100%" style="text-align:center;" border="1">';
						echo '<tr class="title">';
						echo '<td width="50px">No.</td>';
						echo '<td width="150px">Halaman/Hal</td>';
						echo '<td><i>Review</i></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td height="800px"></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '</tr>';
						echo '</table>';
						echo '<br>';
//TANDA TANGAN
						$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

						echo '<table style="font-size:18" width="100%">';
						echo '<tr>';
						echo '<td class="ttd"></td>';
						echo '<td width="15%"></td>';
						echo '<td >Semarang, '.$tgl_sekarang.'</td>';
						echo '</tr>';
						
						switch ($rPenguji['jabatan']) {
							case 'ketua':
								$jabatan = 'Penguji 1/ Ketua';
								break;
							case 'anggota':
								$jabatan = 'Penguji '.$jml_anggota;
								$jml_anggota++;
								break;
						}
						echo '<tr>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '<td>'.$jabatan.',</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td height="50px"></td>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td ></td>';
						echo '<td></td>';
						echo '<td ><u>'.$rPenguji['nama_dosen'].'</u></td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td ></td>';
						echo '<td ></td>';
						echo '<td >NIP. '.$rPenguji['nip'].'</td>';
						echo '</tr>';
						echo '</table>';
						
						echo '<div class="page-break"></div>';
						echo '</div>';
					}
//LEMBAR UNTUK PEMBIMBING
					for($jml=1; $jml<3;$jml++){
						if(${"rPembimbing".$jml}['nama_dosen']!=''){
							echo '<h2 class="margin_top"><center><b>SIDANG UJIAN TUGAS RISET<br>PROGRAM STUDI S1 KIMIA<br>DEPARTEMEN KIMIA<br>FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h2>
							<hr><hr>
			                <div class="isisurat">';
							echo '<table class="isi" >';
							echo '<tr>';
							echo '<td>FORMULIR REVIEW</td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td width="170">Nama</td>';
							echo '<td>:</td>';
							echo '<td>'.$rDataMahasiswa['nama'].'</td>';
							echo '</tr>';	

							echo '<tr>';
							echo '<td width="170">NIM</td>';
							echo '<td>:</td>';
							echo '<td>'.$rDataMahasiswa['nim'].'</td>';
							echo '</tr>';	

							$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
							$hari= strftime("%A",strtotime($rinfoUjiSkripsi['jadwal']));
							//$waktu= strftime("%R",strtotime($rinfoUjiSkripsi['jadwal']));
							echo '<tr>';
							echo '<td width="170">Tanggal Ujian</td>';
							echo '<td>:</td>';
							echo '<td>'.$hari.', '.$tanggal.'</td>';
							echo '</tr>';
							echo '</table>';
							echo '<br>';
	// ISI
							echo '<table class="isi" width="100%" style="text-align:center;" border="1">';
							echo '<tr class="title">';
							echo '<td width="50px">No.</td>';
							echo '<td width="150px">Halaman/Hal</td>';
							echo '<td><i>Review</i></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td height="800px"></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '</tr>';
							echo '</table>';
							echo '<br>';
	//TANDA TANGAN
							$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

							echo '<table style="font-size:18" width="100%">';
							echo '<tr>';
							echo '<td class="ttd"></td>';
							echo '<td width="15%"></td>';
							echo '<td >Semarang, '.$tgl_sekarang.'</td>';
							echo '</tr>';
							if ($jml==1){
								echo '<tr>';
								echo '<td ></td>';
								echo '<td ></td>';
								echo '<td>Pembimbing '.$jml.'/ Sekretaris,</td>';
								echo '</tr>';
							}else{
								echo '<tr>';
								echo '<td ></td>';
								echo '<td ></td>';
								echo '<td>Pembimbing '.$jml.',</td>';
								echo '</tr>';
							}
							

							echo '<tr>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td height="50px"></td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td ></td>';
							echo '<td></td>';
							echo '<td ><u>'.${"rPembimbing".$jml}['nama_dosen'].'</u></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '<td >NIP. '.${"rPembimbing".$jml}['nip'].'</td>';
							echo '</tr>';
							echo '</table>';
							
							echo '<div class="page-break"></div>';
							echo '</div>';
						}
					}
					if($rPembimbing3['nama']!=''){
							echo '<h2 class="margin_top"><center><b>SIDANG UJIAN TUGAS RISET<br>PROGRAM STUDI S1 KIMIA<br>DEPARTEMEN KIMIA<br>FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h2>
							<hr><hr>
			                <div class="isisurat">';
							echo '<table class="isi" >';
							echo '<tr>';
							echo '<td>FORMULIR REVIEW</td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td width="170">Nama</td>';
							echo '<td>:</td>';
							echo '<td>'.$rDataMahasiswa['nama'].'</td>';
							echo '</tr>';	

							echo '<tr>';
							echo '<td width="170">NIM</td>';
							echo '<td>:</td>';
							echo '<td>'.$rDataMahasiswa['nim'].'</td>';
							echo '</tr>';	

							$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
							$hari= strftime("%A",strtotime($rinfoUjiSkripsi['jadwal']));
							//$waktu= strftime("%R",strtotime($rinfoUjiSkripsi['jadwal']));
							echo '<tr>';
							echo '<td width="170">Tanggal Ujian</td>';
							echo '<td>:</td>';
							echo '<td>'.$hari.', '.$tanggal.'</td>';
							echo '</tr>';
							echo '</table>';
							echo '<br>';
	// ISI
							echo '<table class="isi" width="100%" style="text-align:center;" border="1">';
							echo '<tr class="title">';
							echo '<td width="50px">No.</td>';
							echo '<td width="150px">Halaman/Hal</td>';
							echo '<td><i>Review</i></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td height="800px"></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '</tr>';
							echo '</table>';
							echo '<br>';
	//TANDA TANGAN
							$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

							echo '<table style="font-size:18" width="100%">';
							echo '<tr>';
							echo '<td class="ttd"></td>';
							echo '<td width="15%"></td>';
							echo '<td >Semarang, '.$tgl_sekarang.'</td>';
							echo '</tr>';
								echo '<tr>';
								echo '<td ></td>';
								echo '<td ></td>';
								echo '<td>Pembimbing '.$jml.',</td>';
								echo '</tr>';				

							echo '<tr>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td height="50px"></td>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td ></td>';
							echo '<td></td>';
							echo '<td ><u>'.${"rPembimbing".$jml}['nama'].'</u></td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td ></td>';
							echo '<td ></td>';
							echo '<td >NIP. '.${"rPembimbing".$jml}['nip'].'</td>';
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