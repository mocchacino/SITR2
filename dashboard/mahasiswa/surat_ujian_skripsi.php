<?php
	setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'mahasiswa'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$nim=$rMahasiswa->nim;
		$nama=$rMahasiswa->nama;
		
		$dataMahasiswa=mysqli_query($con, "SELECT uji_skripsi.id_uji_skripsi, nama, uji_skripsi.nim,jadwal,tempat, judul, nip1, nip2 FROM uji_skripsi inner join penguji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi inner join mahasiswa on uji_skripsi.nim=mahasiswa.nim inner join daftar_skripsi on daftar_skripsi.nim=uji_skripsi.nim where mahasiswa.nim= '$nim' order by jadwal desc");
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
				win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 0.5cm 0.5cm 0.5cm 0.5cm; margin:0.5cm 0.5 0.5 0.5} .page-break{page-break-after: always; }</style>');
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
		<div class="form-group"><div class="well clearfix">
<!-- <?php
      	echo '<a href="print_berkas_ujian_sarjana.php?nim='.$nim.'" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Print</a>';
?> -->
			<button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>		
		</div></div>
	</div>
</div>


<div class="margin" id="printArea">
<?php
$rDataMahasiswa=$dataMahasiswa->fetch_row();
if($rDataMahasiswa!= ''){
	$penguji=mysqli_query($con,"SELECT nama_dosen FROM penguji_skripsi join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi where uji_skripsi.nim='$nim' and uji_skripsi.id_uji_skripsi='$rDataMahasiswa[0]' order by jabatan");
	$jumlahPenguji=mysqli_num_rows($penguji);
	if($jumlahPenguji != 0){
		$kaDep=mysqli_query($con, "SELECT nama_dosen, dosen.nip FROM dosen inner join misc on misc.deskripsi=dosen.nip where misc.judul='kepala_departemen' ");
	$rkaDep=$kaDep->fetch_row();
	$i=1;
	while($rPenguji=$penguji->fetch_object()){
		echo'<div class="margin" id="printArea">';
		echo'<div style="page-break-after:always;">';
			echo '<table style="text-align:center; width: 100%; font-family: Times New Roman, Times, serif; font-size:14pt;" >';
			echo '<tr>';
				echo '<td rowspan="7""><img src="../assets/img/logo_undip.jpg"height=150 width=130></img></td>';
				echo '<td class="head"> KEMENTERIAN RISET, TEKNOLOGI, DAN PENDIDIKAN TINGGI </td>';
			echo '</tr >';
			echo '<tr >';
				echo '<td>UNIVERSITAS DIPONEGORO</td>';
			echo '</tr>';
			echo '<tr >';
				echo '<td>FAKULTAS SAINS DAN MATEMATIKA</td>';
			echo '</tr>';
			echo '<tr >';
				echo '<td>DEPARTEMEN KIMIA</td>';
			echo '</tr>';
			echo '<tr >';
				echo '<td>PROGRAM STUDI S1 KIMIA</td>';
			echo '</tr>';
			echo '<tr >';
				echo '<td>Jalan Prof. Soedarto, SH Tembalang Semarang 50275</td>';
			echo '</tr>';
			echo '<tr >';
				echo '<td>Telepon/Fax.: (024) 76480824; e-mail: chemistry@live.undip.ac.id</td>';
			echo '</tr>';
		echo '</table>';

		echo '<hr>';

		$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));
		echo '<table align="right"><tr>';
        echo '<td>'.$tgl_sekarang.'</td>';
        echo '</tr></table>';

		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Nomor</td>';
				echo '<td>:</td>';
				echo '<td>...................................</td>';
				// echo '<td>.../UN7.5.8.K/UND/'. date("Y"). '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Lamp.</td>';
				echo '<td>:</td>';
				echo '<td>1 bendel</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Hal</td>';
				echo '<td>:</td>';
				echo '<td>Ujian Tugas Riset</td>';
			echo '</tr>';
		echo '</table>';
		switch ($i) {
			case 1:
				$penguji_ke='Ketua/ Penguji I';
				break;
			case 2:
				$penguji_ke='Anggota/ Penguji II';
				break;
			case 3:
				$penguji_ke='Anggota/ Penguji III';
				break;
			case 4:
				$penguji_ke='Anggota/ Penguji IV';
				break;
			case 5:
				$penguji_ke='Anggota/ Penguji V';
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
				echo '<td>'.$penguji_ke.'</td>';
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
		echo 'Mengharap kehadiran Bapak/ Ibu/ Saudara tepat waktu untuk menguji pada acara Ujian Tugas Riset Mahasiswa :<br><br>';
		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Nama</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[1].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>NIM</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[3].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Hari</td>';
				echo '<td>:</td>';
				$hari= strftime("%A",strtotime($rDataMahasiswa[3]));
				echo '<td>'.$hari.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Tanggal</td>';
				echo '<td>:</td>';
				$tanggal= strftime("%e %B %Y",strtotime($rDataMahasiswa[3]));
				echo '<td>'.$tanggal.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Waktu</td>';
				echo '<td>:</td>';
				$waktu= strftime("%R",strtotime($rDataMahasiswa[3]));
				$waktuSelesai=strftime("%R", strtotime($rDataMahasiswa[3])+7200);
				echo '<td> Pukul'.$waktu.'-'.$waktuSelesai.'WIB</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Tempat</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[4].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="10">Judul Skripsi</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[5].'</td>';
			echo '</tr>';
		echo '</table>';

		echo '<br><br>';
		echo 'Atas perhatian dan kerjasama Bapak/Ibu/Saudara diucapkan terima kasih.';
		echo '<br><br>';

		echo '<table style="font-size:18" width="100%">';
		// echo '<tr>';
		// echo '<td width="520"></td>';
		// echo '<td class="ttd">Semarang, '.$tgl_sekarang.'</td>';
		// echo '</tr>';
		echo '<tr>';
		echo '<td width="75%"></td>';
		echo '<td class="ttd">Ketua Departemen,</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="75%"></td>';
		echo '<td class="pengisi" height="50"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="75%"></td>';
		echo '<td class="ttd"><u>'.$rkaDep[0].'</u></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="70%"></td>';
		echo '<td class="ttd">NIP. '.$rkaDep[1].'</td>';
		echo '</tr>';
		echo '</table>';
		echo'</div>';
		echo '</div>';
}
	}
	$dosbing1 = mysqli_query($con, "SELECT nama_dosen FROM dosen where nip = '".$rDataMahasiswa[6]."' ");
	$dosbing2 = mysqli_query($con, "SELECT nama_dosen FROM dosen where nip = '".$rDataMahasiswa[7]."' ");
	$rdosbing1 = $dosbing1->fetch_row();
		echo'<div class="margin" id="printArea">';
		echo'<div style="page-break-after:always;">';
			echo '<table style="text-align:center; width: 100%; font-family: Times New Roman, Times, serif; font-size:14pt;" >';
			echo '<tr>';
				echo '<td rowspan="6""><img src="../assets/img/logo_undip.jpg"height=150 width=130></img></td>';
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

		echo '<hr>';

		$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));
		echo '<table align="right"><tr>';
        echo '<td>'.$tgl_sekarang.'</td>';
        echo '</tr></table>';

		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Nomor</td>';
				echo '<td>:</td>';
				echo '<td>...................................</td>';
				// echo '<td>.../UN7.5.8.K/UND/'. date("Y"). '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Lamp.</td>';
				echo '<td>:</td>';
				echo '<td>1 bendel</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Hal</td>';
				echo '<td>:</td>';
				echo '<td>Ujian Tugas Riset</td>';
			echo '</tr>';
		echo '</table>';
		echo '<br><br><br>';
		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Yth. '.$rdosbing1[0].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Pembimbing I/ Sekretaris</td>';
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
		echo 'Mengharap kehadiran Bapak/ Ibu/ Saudara tepat waktu untuk menguji pada acara Ujian Tugas Riset Mahasiswa :<br><br>';
		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Nama</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[1].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>NIM</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[3].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Hari</td>';
				echo '<td>:</td>';
				$hari= strftime("%A",strtotime($rDataMahasiswa[3]));
				echo '<td>'.$hari.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Tanggal</td>';
				echo '<td>:</td>';
				$tanggal= strftime("%e %B %Y",strtotime($rDataMahasiswa[3]));
				echo '<td>'.$tanggal.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Waktu</td>';
				echo '<td>:</td>';
				$waktu= strftime("%R",strtotime($rDataMahasiswa[3]));
				$waktuSelesai=strftime("%R", strtotime($rDataMahasiswa[3])+7200);
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
				echo '<td>'.$rDataMahasiswa[5].'</td>';
			echo '</tr>';
		echo '</table>';

		echo '<br><br>';
		echo 'Atas perhatian dan kerjasama Bapak/Ibu/Saudara diucapkan terima kasih.';
		echo '<br><br>';

		echo '<table style="font-size:18">';
		// echo '<tr>';
		// echo '<td width="520"></td>';
		// echo '<td class="ttd">Semarang, '.$tgl_sekarang.'</td>';
		// echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="ttd">Ketua Departemen,</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="pengisi" height="50"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="ttd"><u>'.$rkaDep[0].'</u></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="ttd">NIP. '.$rkaDep[1].'</td>';
		echo '</tr>';
		echo '</table>';
		echo'</div>';
		echo '</div>';
	if($dosbing2){
		$rdosbing2 = $dosbing2->fetch_row();
		echo'<div class="margin" id="printArea">';
		echo'<div style="page-break-after:always;">';
			echo '<table style="text-align:center; width: 100%; font-family: Times New Roman, Times, serif; font-size:14pt;" >';
			echo '<tr>';
				echo '<td rowspan="6""><img src="../assets/img/logo_undip.jpg"height=150 width=130></img></td>';
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

		echo '<hr>';

		$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));
		echo '<table align="right"><tr>';
        echo '<td>'.$tgl_sekarang.'</td>';
        echo '</tr></table>';

		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Nomor</td>';
				echo '<td>:</td>';
				echo '<td>...................................</td>';
				// echo '<td>.../UN7.5.8.K/UND/'. date("Y"). '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Lamp.</td>';
				echo '<td>:</td>';
				echo '<td>1 bendel</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Hal</td>';
				echo '<td>:</td>';
				echo '<td>Ujian Tugas Riset</td>';
			echo '</tr>';
		echo '</table>';
		echo '<br><br><br>';
		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Yth. '.$rdosbing2[0].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Pembimbing II</td>';
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
		echo 'Mengharap kehadiran Bapak/ Ibu/ Saudara tepat waktu untuk menguji pada acara Ujian Tugas Riset Mahasiswa :<br><br>';
		echo '<table width="100%">';
			echo '<tr>';
				echo '<td>Nama</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[1].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>NIM</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[3].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Hari</td>';
				echo '<td>:</td>';
				$hari= strftime("%A",strtotime($rDataMahasiswa[3]));
				echo '<td>'.$hari.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Tanggal</td>';
				echo '<td>:</td>';
				$tanggal= strftime("%e %B %Y",strtotime($rDataMahasiswa[3]));
				echo '<td>'.$tanggal.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Waktu</td>';
				echo '<td>:</td>';
				$waktu= strftime("%R",strtotime($rDataMahasiswa[3]));
				$waktuSelesai=strftime("%R", strtotime($rDataMahasiswa[3])+7200);
				echo '<td> Pukul'.$waktu.'-'.$waktuSelesai.'WIB</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Tempat</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[4].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="10">Judul Skripsi</td>';
				echo '<td>:</td>';
				echo '<td>'.$rDataMahasiswa[5].'</td>';
			echo '</tr>';
		echo '</table>';

		echo '<br><br>';
		echo 'Atas perhatian dan kerjasama Bapak/Ibu/Saudara diucapkan terima kasih.';
		echo '<br><br>';

		echo '<table style="font-size:18">';
		// echo '<tr>';
		// echo '<td width="520"></td>';
		// echo '<td class="ttd">Semarang, '.$tgl_sekarang.'</td>';
		// echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="ttd">Ketua Departemen,</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="pengisi" height="50"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="ttd"><u>'.$rkaDep[0].'</u></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="520"></td>';
		echo '<td class="ttd">NIP. '.$rkaDep[1].'</td>';
		echo '</tr>';
		echo '</table>';
		echo'</div>';
		echo '</div>';
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
