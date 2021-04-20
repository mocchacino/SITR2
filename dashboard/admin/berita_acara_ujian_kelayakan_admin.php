<?php	
setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');

		ini_set('display_errors', 1);
		$get_nim=$_GET['nim'];
		$id=$_GET['id'];
		$show_pendaftar = mysqli_query($con,"SELECT * FROM daftar_uji_kelayakan INNER JOIN mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim where daftar_uji_kelayakan.nim='$get_nim' and id_daftar_uji_kelayakan='$id' ");
		$rshow_pendaftar = $show_pendaftar->fetch_object();	
		//$show_penguji = mysqli_query($con,"SELECT * FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim= '$get_nim' ");
		$infoUjiKelayakan=mysqli_query($con, "SELECT * from uji_kelayakan inner join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan inner join dosen on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip  where nim='$get_nim' and uji_kelayakan.id_uji_kelayakan='$id' ");
		$rInfoUjiKelayakan=$infoUjiKelayakan->fetch_object();
		$jml_rInfoUjiKelayakan=mysqli_num_rows($infoUjiKelayakan);
		$kalab=mysqli_query($con, "SELECT * from dosen inner join lab on lab.nip=dosen.nip where lab.idlab='$rshow_pendaftar->idlab_tr1' ");
		$rkalab=$kalab->fetch_object();

		$nomor=1;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Jadwal dan Penguji Kelayakan</title>	
	<style>
		th, td {
		    padding: 5px;
		}
		.ket_lembar{
			font-size:10px;
		}
		.ttd {
		    padding: 0px;
		}
		.footer_surat {
		    font-size:12px;
		}
		.page-break{
        	page-break-after: always;
        }
	</style>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script>
		$(document).ready(function(){
			$('#print').click(function(){
				var divToPrint = document.getElementById('printArea');
			    var htmlToPrint = '' +
			        '<style type="text/css">' +
					
					'.footer_surat {'+
					    'font-size:12px;'+
					'}'+
			        '</style>';
			    htmlToPrint += divToPrint.outerHTML;
				var win = window.open('');
				win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 0.5cm;} .ttd{padding:0 0 0 175px; width:50%;} .pengisi{width:25%;} table.isi{line-height: 25px; font-size: 18px} .footer_surat{font-size:10px; bottom:10px; width: 21cm;} .page-break{page-break-after: always;} .ket_lembar{font-size:7pt;}</style>');
				win.document.write(divToPrint.outerHTML);
				win.print();
				win.close();
			})
		});
	</script>
</head>
<body>
<div class="panel-body">
<?php
if($jml_rInfoUjiKelayakan==0){
	echo'<div class="alert alert-warning"><strong>Penguji Belum ditetapkan!</strong> Masukkan penguji terlebih dahulu.</div>';
	echo'<div class="well clearfix">';
	echo'<div class="col-md-12 col-sm-12 col-xs-12">';
		echo'<div class="form-group">';
		echo '<a href="print_lab_admin.php?nim='.$get_nim.'&id='.$id.'"" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>';
	echo '</div></div></div></div>';
}else{
	echo'<div class="well clearfix">';
	echo'<div class="col-md-12 col-sm-12 col-xs-12">';
		echo'<div class="form-group">';
			echo '<a href="print_lab_admin.php?nim='.$get_nim.'&id='.$id.'"" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>';
			echo '<button class="btn btn-primary pull-right" name="print" id="print"><i class="fa fa-print"></i> | Print</button>';
?>
			
		</div>
	</div>
	</div>
</div>
<div class="margin" id="printArea">
<?php
	$waktu= strftime("%e %B %Y",strtotime($rInfoUjiKelayakan->jadwal));
	for($halaman=1;$halaman<3;$halaman++) {
		$nomor=1;$noPenguji=1;
		echo '<div class="margin">';
		echo '<h3>UNIVERSITAS DIPONEGORO <br> FAKULTAS SAINS DAN MATEMATIKA</h3><hr>';
		echo '<h3 align="center"><u>BERITA ACARA SEMINAR KELAYAKAN SKRIPSI</u></h3>';
		echo '<h4 align="right"> Lembar '.$halaman.' </h4>';
		echo '<table>';
			echo '<tr >';
				echo '<td >TANGGAL UJIAN</td>';
				echo '<td>:</td>';
				echo '<td>'.$waktu.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>DEPARTEMEN/ PROGRAM STUDI/ KBK</td>';
				echo '<td>:</td>';
				$nama_lab=mysqli_query($con, "SELECT * FROM lab where idlab='".$rshow_pendaftar->idlab_tr1."' ");
				$rnama_lab=$nama_lab->fetch_object();
				echo '<td>KIMIA/ S1 KIMIA/ '.$rnama_lab->nama_lab.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>NAMA MAHASISWA</td>';
				echo '<td>:</td>';
				echo '<td>'.$rshow_pendaftar->nama.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>NOMOR INDUK MAHASISWA</td>';
				echo '<td>:</td>';
				echo '<td>'.$rshow_pendaftar->nim.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>JUDUL</td>';
				echo '<td>:</td>';
				echo '<td>'.$rshow_pendaftar->judul.'</td>';
			echo '</tr>';
		echo '</table>';
		echo '<h3 align="center">PERSONIL PENGUJI</h3>';
		echo '<table class="isi" style="width:100%" height="80%" border="1px">';
			echo '<thead align="center">';
				echo '<tr>';
				echo '<td>NO</td>';
				echo '<td>NAMA</td>';
				echo '<td>JABATAN</td>';
				echo '<td>TANDA TANGAN</td>';
				echo '<td>NILAI</td>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				$infoPembimbing1=mysqli_query($con, "SELECT * from dosen where nip='$rshow_pendaftar->nip1' ");
				$rInfoPembimbing1=$infoPembimbing1->fetch_object();
					echo '<tr>';
					echo '<td align="center">'.$nomor.'</td>'; 
					echo '<td>'.$rInfoPembimbing1->nama_dosen.'</td>';
					echo '<td>PEMBIMBING '.$nomor.'</td>';
					echo '<td>'.$nomor.'</td>';
					echo '<td></td>';
					echo '</tr>';
					$nomor++;
				if($rshow_pendaftar->nip1!=''){
					$infoPembimbing2=mysqli_query($con, "SELECT * from dosen where nip='$rshow_pendaftar->nip2' ");
					$rInfoPembimbing2=$infoPembimbing2->fetch_object();
					echo '<tr>';
					echo '<td align="center">'.$nomor.'</td>'; 
					echo '<td>'.$rInfoPembimbing2->nama_dosen.'</td>';
					echo '<td>PEMBIMBING '.$nomor.'</td>';
					echo '<td>'.$nomor.'</td>';
					echo '<td></td>';
					echo '</tr>';
					$nomor++;
				}
					
				$infoPenguji=mysqli_query($con, "SELECT * from penguji_kelayakan inner join dosen on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip where penguji_kelayakan.id_uji_kelayakan='$id' and jabatan != 'pembimbing' ");
				while($rInfoPenguji=$infoPenguji->fetch_object()){
					echo '<tr>';
					echo '<td align="center">'.$nomor.'</td>'; 
					echo '<td>'.$rInfoPenguji->nama_dosen.'</td>';
					echo '<td>PREVIEW '.$noPenguji.'</td>';
					echo '<td>'.$nomor.'</td>';
					echo '<td></td>';
					echo '</tr>';
					$nomor++;$noPenguji++;
				}
				
			echo '</tbody>';
		echo '</table><br>';
		echo '<table style="width:100%" border="1px">';
			echo '<thead align="center">';
				echo '<tr>';
					echo '<td colspan="5"><b>Nilai Akhir</b></td>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				echo '<tr align="center">';
					echo '<td><b>A : </b>80 <= X <= 100</td>';
					echo '<td><b>B : </b>70 <= X < 80</td>';
					echo '<td><b>C : </b>60 <= X < 70</td>';
					echo '<td><b>D : </b>50 <= X < 60</td>';
					echo '<td><b>E : </b>X < 50</td>';
				echo '</tr>';
			echo '</tbody>';
		echo '</table><br><br>';
		echo '<table>';
			echo '<tr>';
			echo '<td>Skripsi tersebut dinyatakan</td>';
			echo '<td>:</td>';
			echo '<td style="font-weight:bold">A. Layak tanpa revisi</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td style="font-weight:bold">B. Layak dengan revisi</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td style="font-weight:bold">C. Tidak layak</td>';
			echo '</tr>';
		echo '</table>';
		echo '<br><br><br>';
		$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));

		echo '<table style="font-size:18" align="right">';
		echo '<tr>';
		echo '<td width="50%"></td>';
		echo '<td class="ttd">Semarang, '.$tgl_sekarang.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="50%"></td>';
		echo '<td class="ttd">Ketua KBK,</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="50%"></td>';
		echo '<td class="pengisi" height="50"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="50%"></td>';
		echo '<td class="ttd"><u>'.$rkalab->nama_dosen.'</u></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td width="50%"></td>';
		echo '<td class="ttd">NIP. '.$rkalab->nip.'</td>';
		echo '</tr>';
		echo '</table><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
		echo '<table width="100%" align="left" style="font-size:10pt">';
			echo '<tr>';
				echo '<td class="ket_lembar">Lembar :</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="ket_lembar">1. Pembimbing</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="ket_lembar">2. KBK</td>';
			echo '</tr>';
		echo '</table>';
		echo '<div class="footer_surat"><hr>DEPARTEMEN KIMIA FSM UNDIP</div>';
		echo '<div class="page-break"></div>';
	}
}
	
	echo '</div>';


	include_once('../footer.php');
	$con->close();
}else{
	header("Location:./");
}
?>