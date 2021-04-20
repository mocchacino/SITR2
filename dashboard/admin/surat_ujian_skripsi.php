<?php
	setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$nim=$rMahasiswa->nim;
		$nama=$rMahasiswa->nama;
		$penguji=mysqli_query($con,"SELECT nama_dosen FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_skripsi.id_uji_kelayakan where uji_skripsi.nim='".$nim."' order by jadwal desc");
		$jumlahPenguji=mysqli_num_rows($penguji);
		$dataMahasiswa=mysqli_query($con, "SELECT nama, uji_skripsi.nim,jadwal,tempat, judul FROM uji_skripsi inner join penguji_skripsi on uji_skripsi.id_uji_kelayakan=penguji_skripsi.id_uji_kelayakan inner join mahasiswa on uji_skripsi.nim=mahasiswa.nim where mahasiswa.nim= '$nim' order by jadwal desc");
		
		

		

?>
<!DOCTYPE html> 	
<html>
<head>
	<title>Jadwal dan Penguji Kelayakan</title>	
	<style type="text/css">
		table#head{
			text-align: center;
		}
		input#keteranganGagal   {
			width: 100%;
			height: 100px;
		    padding: 12px 20px;
		    margin: 8px 0;
		    box-sizing: border-box;
		    border: none;
		    background-color: #E74C3C;
		    color: white;
		    text-align: center;
		}
	</style>
	<script src="assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
	<script>
		
		$(document).ready(function(){
			$('#print').click(function(){
				var divToPrint = document.getElementById('printArea');	
				var htmlToPrint = '' +
			        '<style type="text/css">' +
			        
					'.ket_lembar{'+
						'font-size:10px;'+
					'}'+
					'.footer_surat {'+
					    'font-size:12px;'+
					'}'+
			        '</style>'; 
			    htmlToPrint += divToPrint.outerHTML;        
			    htmlToPrint = divToPrint.outerHTML;
				var win = window.open('');
				win.document.write(htmlToPrint);
				win.print();
				win.close();
			})
		});
	</script>
</head>
<body>
<div class="panel-body">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<br>
		<div class="form-group">
			<button class="btn pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
		</div>
	</div>
</div>

<div id="printArea">
<?php
if(($jumlahPenguji != 0) || ($dataMahasiswa!= '')){
	$rDataMahasiswa=$dataMahasiswa->fetch_row();
	$sekreDepartemen=mysqli_query($con, "SELECT nama_dosen, dosen.nip FROM dosen inner join misc on misc.nip=dosen.nip where misc.deskripsi='sekretaris_departemen' ");
	$rSekreDepartemen=$sekreDepartemen->fetch_row();
	$i=1;
	while($rPenguji=$penguji->fetch_object()){
			echo'<div style="page-break-after:always;">';
				echo '<table style="text-align:center; width: 100%; font-family: "Times New Roman", Times, serif"; font-size:"14pt"; >';
					echo '<tr>';
						echo '<td rowspan="6""><img src="../assets/img/lambang_undip.png"height=115 width=100></img></td>';
						echo '<td class="head"> KEMENTERIAN RISET, TEKNOLOGI, DAN PENDIDIKAN TINGGI </td>';
					echo '</tr >';
					echo '<tr >';
						echo '<td>UNIVERSITAS DIPONEGORO</td>';
					echo '</tr>';
					echo '<tr >';
						echo '<td>FAKULTAS SAINS DAN MATEMATIKA</td>';
					echo '</tr>';
					echo '<tr >';
						echo '<td><b>DEPARTEMEN KIMIA</b></td>';
					echo '</tr>';
					echo '<tr >';
						echo '<td>Jalan Prof. Soedarto, SH Tembalang Semarang 50275</td>';
					echo '</tr>';
					echo '<tr >';
						echo '<td>Telepon/Fax.: (024) 76480824; e-mail: chemistry@live.undip.ac.id</td>';
					echo '</tr>';
				echo '</table>';
	
				echo '<hr><br>';
	
				echo '<table width="100%">';
					echo '<tr>';
						echo '<td>Nomor</td>';
						echo '<td>:</td>';
						echo '<td>.../UN7.5.8.K/UND/'. date("Y"). '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Lamp.</td>';
						echo '<td>:</td>';
						echo '<td>1 bendel</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Hal</td>';
						echo '<td>:</td>';
						echo '<td>Ujian Skripsi Tugas Akhir</td>';
					echo '</tr>';
				echo '</table>';
				switch ($i) {
					case 1:
						$penguji_ke='I';
						break;
					case 2:
						$penguji_ke='II';
						break;
					case 3:
						$penguji_ke='III';
						break;
					case 4:
						$penguji_ke='IV';
						break;
					default:
						$penguji_ke='';
						break;
				}
				$i++;
				echo '<br><br><br>';
				echo '<table width="100%">';
					echo '<tr>';
						echo '<td>Yth. '.$rPenguji->nama_dosen.'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Dosen Penguji '.$penguji_ke.'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Departemen Kimia</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Fakultas Sains dan Matematika</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>UNDIP - Semarang</td>';
					echo '</tr>';
				echo '</table>';
	
				echo '<br><br><br>';
				echo 'Mengharap kehadiran Bapak/ Ibu/ Saudara tepat waktu untuk menguji pada acara Ujian Skripsi/ pendadaran Tugas Akhir Mahasiswa :<br><br>';
				echo '<table width="100%">';
					echo '<tr>';
						echo '<td>Nama</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[0].'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>NIM</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[2].'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Hari</td>';
						echo '<td>:</td>';
						$hari= strftime("%A",strtotime($rDataMahasiswa[2]));
						echo '<td>'.$hari.'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tanggal</td>';
						echo '<td>:</td>';
						$tanggal= strftime("%e %B %Y",strtotime($rDataMahasiswa[2]));
						echo '<td>'.$tanggal.'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tanggal</td>';
						echo '<td>:</td>';
						$waktu= strftime("%R",strtotime($rDataMahasiswa[2]));
						$waktuSelesai=strftime("%R", strtotime($rDataMahasiswa[2])+7200);
						echo '<td> Pukul'.$waktu.'-'.$waktuSelesai.'WIB</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tempat</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[3].'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td width="10">Judul Skripsi</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[4].'</td>';
					echo '</tr>';
				echo '</table>';
	
				echo '<br><br>';
				echo 'Atas perhatian dan kerjasama Bapak/Ibu/Saudaradiucapkan terima kasih.';
				echo '<br><br>';
	
				echo '<table width="100%">';
					echo '<tr>';
						echo '<td rowspan="4"; width="70%"></td>';
						echo '<td>An. Ketua</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td rowspan="4"; width="70%"></td>';
						echo '<td>Sekretaris Departemen</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td height="100"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>'.$rSekreDepartemen[0].'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><hr>NIP.'.$rSekreDepartemen[1].'</td>';
					echo '</tr>';
				echo '</table>';
			echo'</div>';
		}
	}else{
		echo "<input class='form-control' type='text' id='keteranganGagal' name='keteranganGagal' readonly value='Penguji belum ditetapkan'>";
	}
echo '</div>';





include_once('../footer.php');
$con->close();
}else{
	header('Location:./');
}
?>
