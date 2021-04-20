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
		$id=mysqli_query($con, "SELECT max(id_uji_kelayakan) as iduji from uji_kelayakan where nim='$nim' ");
		$rId=$id->fetch_object();
		$penguji=mysqli_query($con,"SELECT nama_dosen FROM penguji_kelayakan join dosen on dosen.nip=penguji_kelayakan.nip_penguji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim='".$nim."' and uji_kelayakan.id_uji_kelayakan='".$rId->iduji."' and jabatan='penguji'");
		$jumlahPenguji=mysqli_num_rows($penguji);
		$dataMahasiswa=mysqli_query($con, "SELECT nama, uji_kelayakan.nim,jadwal,tempat, judul, idlab_tr1, nip1, nip2 FROM uji_kelayakan inner join penguji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan inner join mahasiswa on uji_kelayakan.nim=mahasiswa.nim inner join tr1 on tr1.nim=uji_kelayakan.nim where mahasiswa.nim= '$nim' order by uji_kelayakan.id_uji_kelayakan desc");
		
		
		

		

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
					'.footer_surat {'+
					    'font-size:12px;'+
					'}'+
			        '</style>'; 
			    htmlToPrint += divToPrint.outerHTML;        
			    htmlToPrint = divToPrint.outerHTML;
				var win = window.open('');
				win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 0.5cm 0.5cm 0.5cm 0.5cm;} .page-break{page-break-after: always; }</style>');
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
		<div class="well clearfix">
			<div class="form-group">
                <button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
            </div> 
        </div>
	</div>
</div>

<div class='margin' id="printArea">
<?php
if(($jumlahPenguji != 0) || ($dataMahasiswa!= '')){
	$rDataMahasiswa=$dataMahasiswa->fetch_row();
	$ketuaLab=mysqli_query($con, "SELECT nama_dosen, dosen.nip FROM dosen inner join lab on lab.nip=dosen.nip where lab.idlab='".$rDataMahasiswa[5]."' ");
	$rKetuaLab=$ketuaLab->fetch_row();
	$dosbing1 = mysqli_query($con, "SELECT nama_dosen FROM dosen where nip = '".$rDataMahasiswa[6]."' ");
	$dosbing2 = mysqli_query($con, "SELECT nama_dosen FROM dosen where nip = '".$rDataMahasiswa[7]."' ");
	$rdosbing1 = $dosbing1->fetch_row();
	echo'<div class="margin" id="printArea">';
		echo'<div style="page-break-after:always;">';
			echo '<table style="text-align:center; width: 100%; font-family: Times New Roman, Times, serif; font-size:14pt;" >';
				echo '<tr>';
				echo '</tr>';
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

			echo '<hr><br>';

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
					echo '<td>Seminar Kelayakan Skripsi</td>';
				echo '</tr>';
			echo '</table>';

			echo '<br><br><br>';
			echo '<table width="100%">';
				echo '<tr>';
					echo '<td>Yth. '.$rdosbing1[0].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Dosen Pembimbing 1</td>';
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
			echo 'Mengharap kehadiran Bapak/ Ibu/ Saudara pada seminar kelayakan :<br><br>';
			echo '<table width="100%">';
				echo '<tr>';
					echo '<td>Nama</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[0].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>NIM</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[1].'</td>';
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
					echo '<td>Waktu</td>';
					echo '<td>:</td>';
					$waktu= strftime("%R",strtotime($rDataMahasiswa[2]));
					$waktuSelesai=strftime("%R", strtotime($rDataMahasiswa[2])+7200);
					echo '<td> Pukul '.$waktu.' - '.$waktuSelesai.' WIB</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Tempat</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[3].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Judul Skripsi</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[4].'</td>';
				echo '</tr>';
			echo '</table>';

			echo '<br><br>';
			echo 'Atas perhatian dan kerjasama Bapak/Ibu/Saudara diucapkan terima kasih.';
			echo '<br><br>';

			echo '<table width="100%">';
				echo '<tr>';
					echo '<td rowspan="4"; width="100%"></td>';
					echo '<td>Ketua KBK,</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td height="100"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><u>'.$rKetuaLab[0].'</u></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>NIP.'.$rKetuaLab[1].'</td>';
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
				echo '</tr>';
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

			echo '<hr><br>';

			echo '<table width="100%">';
				echo '<tr>';
					echo '<td>Nomor</td>';
					echo '<td>:</td>';
					//echo '<td>.../UN7.5.8.K/UND/'. date("Y"). '</td>';
					echo '<td>...................................</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Lamp.</td>';
					echo '<td>:</td>';
					echo '<td>1 bendel</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Hal</td>';
					echo '<td>:</td>';
					echo '<td>Seminar Kelayakan Skripsi</td>';
				echo '</tr>';
			echo '</table>';

			echo '<br><br><br>';
			echo '<table width="100%">';
				echo '<tr>';
					echo '<td>Yth. '.$rdosbing2[0].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Dosen Pembimbing 2</td>';
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
			echo 'Mengharap kehadiran Bapak/ Ibu/ Saudara pada seminar kelayakan :<br><br>';
			echo '<table width="100%">';
				echo '<tr>';
					echo '<td>Nama</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[0].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>NIM</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[1].'</td>';
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
					echo '<td>Waktu</td>';
					echo '<td>:</td>';
					$waktu= strftime("%R",strtotime($rDataMahasiswa[2]));
					$waktuSelesai=strftime("%R", strtotime($rDataMahasiswa[2])+7200);
					echo '<td> Pukul '.$waktu.' - '.$waktuSelesai.' WIB</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Tempat</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[3].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Judul Skripsi</td>';
					echo '<td>:</td>';
					echo '<td>'.$rDataMahasiswa[4].'</td>';
				echo '</tr>';
			echo '</table>';

			echo '<br><br>';
			echo 'Atas perhatian dan kerjasama Bapak/Ibu/Saudara diucapkan terima kasih.';
			echo '<br><br>';

			echo '<table width="100%">';
				echo '<tr>';
					echo '<td rowspan="4"; width="100%"></td>';
					echo '<td>Ketua KBK,</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td height="100"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><u>'.$rKetuaLab[0].'</u></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>NIP.'.$rKetuaLab[1].'</td>';
				echo '</tr>';
			echo '</table>';
		echo'</div>';
		echo '</div>';
	}
	while($rPenguji=$penguji->fetch_object()){
		echo'<div class="margin" id="printArea">';
			echo'<div style="page-break-after:always;">';
				echo '<table style="text-align:center; width: 100%; font-family: Times New Roman, Times, serif; font-size:14pt;" >';
					echo '<tr>';
					echo '</tr>';
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
	
				echo '<hr><br>';
	
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
						echo '<td>Seminar Kelayakan Skripsi</td>';
					echo '</tr>';
				echo '</table>';
	
				echo '<br><br><br>';
				echo '<table width="100%">';
					echo '<tr>';
						echo '<td>Yth. '.$rPenguji->nama_dosen.'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Pereview Seminar Kelayakan</td>';
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
				echo 'Mengharap kehadiran Bapak/ Ibu/ Saudara pada seminar kelayakan :<br><br>';
				echo '<table width="100%">';
					echo '<tr>';
						echo '<td>Nama</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[0].'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>NIM</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[1].'</td>';
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
						echo '<td>Waktu</td>';
						echo '<td>:</td>';
						$waktu= strftime("%R",strtotime($rDataMahasiswa[2]));
						$waktuSelesai=strftime("%R", strtotime($rDataMahasiswa[2])+7200);
						echo '<td> Pukul '.$waktu.' - '.$waktuSelesai.' WIB</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Tempat</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[3].'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td >Judul Skripsi</td>';
						echo '<td>:</td>';
						echo '<td>'.$rDataMahasiswa[4].'</td>';
					echo '</tr>';
				echo '</table>';
	
				echo '<br><br>';
				echo 'Atas perhatian dan kerjasama Bapak/Ibu/Saudara diucapkan terima kasih.';
				echo '<br><br>';
	
				echo '<table width="100%">';
					echo '<tr>';
						echo '<td rowspan="4"; width="100%"></td>';
						echo '<td>Ketua KBK,</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td height="100"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><u>'.$rKetuaLab[0].'</u></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>NIP.'.$rKetuaLab[1].'</td>';
					echo '</tr>';
				echo '</table>';
			echo'</div>';
			echo '</div>';
		}
	}else{
		echo "<input class='form-control' type='text' id='keteranganGagal' name='keteranganGagal' readonly value='Penguji belum ditetapkan'>";
	}






include_once('../footer.php');
$con->close();
}else{
	header('Location:./');
}
?>
