<?php
	date_default_timezone_set("Asia/Bangkok"); 
	setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		include_once('../sidebar.php');
				ini_set('display_errors', 1);
				$nim=$_GET['nim'];
				$id=$_GET['id'];
		if($_SERVER['REQUEST_METHOD']==='POST'){
		// 	if(isset($_POST['submit'])){
				$catatan=test_input($_POST['catatan']);
				
				$ketua=mysqli_query($con,"SELECT nip,nama_dosen FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi WHERE jabatan='ketua' and uji_skripsi.id_uji_skripsi='$id'");
				$rKetua=$ketua->fetch_assoc();
				$anggota=mysqli_query($con,"SELECT nama_dosen FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi WHERE jabatan='anggota' and uji_skripsi.id_uji_skripsi='$id'");
				$dataMahasiswa=mysqli_query($con, "SELECT id_daftar_skripsi,nama, mahasiswa.nim, judul, nip1, nip2, nip3 FROM daftar_skripsi inner join mahasiswa on daftar_skripsi.nim=mahasiswa.nim where daftar_skripsi.id_daftar_skripsi='$id' and mahasiswa.nim= '$nim'  ");
				$rDataMahasiswa=$dataMahasiswa->fetch_assoc();
				$infoUjiSkripsi=mysqli_query($con, "SELECT uji_skripsi.id_uji_skripsi, jadwal, tempat from uji_skripsi inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='".$rDataMahasiswa['id_daftar_skripsi']."' ");
				$rinfoUjiSkripsi=$infoUjiSkripsi->fetch_assoc();

				$pembimbing1=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rDataMahasiswa['nip1']."' ");
				$rPembimbing1=$pembimbing1->fetch_assoc();
				$pembimbing2=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rDataMahasiswa['nip2']."' ");
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
		                    win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 3cm; margin: 1cm auto;} .ttd{padding:0 0 0 175px; width:50%;} .pengisi{width:25%;} table.isi{line-height: 50px; font-size: 18px} .footer_surat{font-size:10px; position:fixed; bottom:50; width: 21cm;} </style>');
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
		                  font-family:"Times New Roman"
		                }
		                tr,td{
		                  padding: 15px;
		                }
		                div.margin{
		                  width: 21cm;
		                  min-height: 29.7cm;
		                  padding: 2cm;
		                  margin: 1cm auto;
		                }
		                div.isisurat{
		                  line-height: 55px;
		                }
		                .head{

		                  text-align: left;
		                  padding: 0px 0px 0px 550px;
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
		                <table align="right" border="1" cellpadding="5">
		                  <tr>
		                    <td><b>Form Ujian Sarjana</b></td>
		                  </tr>
		                </table>
		                <br><br>
						<hr>
						<h2><center><b>UJIAN TUGAS RISET<br>PROGRAM STUDI S1 KIMIA<br>DEPARTEMEN KIMIA<br>FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</b></center></h2>
						<hr>

		                <div class="isisurat">
		<?php  
						echo '<table class="isi">';
						echo '<tr>';
						echo '<td width="170">NAMA MAHASISWA</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$rDataMahasiswa['nama'].'</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td width="170">NIM</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$rDataMahasiswa['nim'].'</td>';
						echo '</tr>';

						$tanggal= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
						echo '<tr>';
						echo '<td width="170">TANGGAL</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$tanggal.'</td>';
						echo '</tr>';

						$waktu= strftime("%R",strtotime($rinfoUjiSkripsi['jadwal']));
						echo '<tr>';
						echo '<td width="170">WAKTU</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$waktu.' WIB</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td width="170">TEMPAT</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$rinfoUjiSkripsi['tempat'].'</td>';
						echo '</tr>';
						echo '</table>';

						echo '<table class="isi">';
						echo '<tr>';
						echo '<td width="170">JUDUL SKRIPSI</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$rDataMahasiswa['judul'].'</td>';
						echo '</tr>';

						echo '<table class="isi">';
						$no=1;
						for($i=1; $i<3;$i++){
							if(${"rPembimbing".$i}['nama_dosen']!=''){
								if($no==1){
									echo '<tr>';
									echo '<td  width="170" colspan="2">PEMBIMBING</td>';
									echo '<td>:</td>';
									echo '<td>'.$no.'</td>';
									echo '<td>'.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}else{
									echo '<tr>';
									echo '<td  width="170" colspan="2"></td>';
									echo '<td></td>';
									echo '<td>'.$no.'. </td>';
									echo '<td>'.${"rPembimbing".$i}['nama_dosen'].'</td>';
									echo '</tr>';
									$no++;
								}
									
							}
							
						}
						if($rPembimbing3['nama']!=''){
							echo '<tr>';
							echo '<td  width="170" colspan="2"></td>';
							echo '<td></td>';
							echo '<td>'.$no.'. </td>';
							echo '<td>'.$rPembimbing3['nama'].'</td>';
							echo '</tr>';
						}
						

						echo '<tr>';
						echo '<td  width="170" colspan="2">PENGUJI</td>';
						echo '<td >:</td>';
						echo '<td >KETUA</td>';
						echo '<td>  '.$rKetua['nama_dosen'].'</td>';
						echo '</tr>';

						echo '<tr>';
						echo '<td width="170" colspan="2"></td>';
						echo '<td ></td>';
						echo '<td>SEKRETARIS</td>';
						echo '<td> '.$rPembimbing1['nama_dosen'].'</td>';
						echo '</tr>';

						$no=1;
						while($rAnggota=$anggota->fetch_assoc()){
							if($no==1){
								echo '<tr>';
								echo '<td width="170" colspan="2">ANGGOTA</td>';
								echo '<td>:</td>';
								echo '<td>'.$no.'. </td>';
								echo '<td>'.$rAnggota['nama_dosen'].'</td>';
								echo '</tr>';
							}else{
								echo '<tr>';
								echo '<td width="170" colspan="2"></td>';
								echo '<td></td>';
								echo '<td>'.$no.'. </td>';
								echo '<td>'.$rAnggota['nama_dosen'].'</td>';
								echo '</tr>';
							}
							$no++;	
						}
						echo '</table>';

						echo '<table class="isi" >';
						echo '<tr>';
						echo '<td width="170">CATATAN</td>';
						echo '<td>:</td>';
						echo '<td></td>';
						echo '<td>'.$catatan.'</td>';
						echo '</tr>';
						echo '</table>';

						
						 
						  $tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

						  echo '<table style="font-size:18">';
						  echo '<tr>';
						  echo '<td width="520"></td>';
						  echo '<td class="ttd">Semarang, '.$tgl_sekarang.'</td>';
						  echo '</tr>';
						  echo '<tr>';
						  echo '<td width="520"></td>';
						  echo '<td class="ttd">Ketua Panitia Ujian,</td>';
						  echo '</tr>';
						  echo '<tr>';
						  echo '<td width="520"></td>';
						  echo '<td class="pengisi" height="50"></td>';
						  echo '</tr>';
						  echo '<tr>';
						  echo '<td width="520"></td>';
						  echo '<td class="ttd"><u>'.$rKetua['nama_dosen'].'</u></td>';
						  echo '</tr>';
						  echo '<tr>';
						  echo '<td width="520"></td>';
						  echo '<td class="ttd">NIP. '.$rKetua['nip'].'</td>';
						  echo '</tr>';
						  echo '</table>';

						  echo '<div class="footer_surat"><hr>DEPARTEMEN KIMIA FSM UNDIP</div>';
						 
						  echo '</div>';
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