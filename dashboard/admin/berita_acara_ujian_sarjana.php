<?php	
setlocale(LC_ALL, 'id_ID');
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$get_nim=$_GET['nim'];
		$id=$_GET['id'];
		$show_pendaftar = mysqli_query($con,"SELECT nama, daftar_skripsi.nim, judul, nip1, nip2 FROM daftar_skripsi INNER JOIN mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.id_daftar_skripsi='$id' limit 1 ");
		$rshow_pendaftar = $show_pendaftar->fetch_assoc();
		$mulai_ta=mysqli_query($con, "SELECT MIN(tgl_daftar) as tgl_mulai_ta from daftar_skripsi where nim='$get_nim' LIMIT 1");
		$rMulai_ta=$mulai_ta->fetch_assoc();	
		$tgl_mulai_ta= strftime("%e %B %Y",strtotime($rMulai_ta['tgl_mulai_ta']));
		
		$ketua=mysqli_query($con, "SELECT nip, nama_dosen from dosen inner join penguji_skripsi on penguji_skripsi.nip_penguji_skripsi=dosen.nip where penguji_skripsi.jabatan='ketua' and penguji_skripsi.id_uji_skripsi='$id' ");
		$rKetua=$ketua->fetch_assoc();
		$infoUjiSkripsi=mysqli_query($con, "SELECT * from uji_skripsi where id_uji_skripsi='$id' ");
		$rinfoUjiSkripsi=$infoUjiSkripsi->fetch_assoc();
		$jml_infoUjiSkripsi=mysqli_num_rows($infoUjiSkripsi);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Jadwal dan Penguji Kelayakan</title>	
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
				var win = window.open('');
				win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 3cm; margin: auto;} .ttd{padding:0 0 0 175px; width:50%;} .pengisi{width:25%;} table.isi{line-height: 50px; font-size: 18px} .footer_surat{font-size:10px; position:fixed; bottom:50; width: 21cm;} .page-break{page-break-after: always; }</style>');
				win.document.write(htmlToPrint);
				win.print();
				win.close();
			})
		});
	</script>
	<style> 
      table {  
          border-collapse: collapse;
          font-size:20px;
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
          padding: 500px;
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
<?php
if($jml_infoUjiSkripsi==0){
	echo'<div class="alert alert-warning"><strong>Penguji Belum ditetapkan!</strong> Masukkan penguji terlebih dahulu.</div>';
	echo'<div class="well clearfix">';
	echo'<div class="col-md-12 col-sm-12 col-xs-12">';
		echo'<div class="form-group">';
		echo '<a href="print_berkas_ujian_sarjana.php?nim='.$get_nim.'&id='.$id.'" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Print</a>';
	echo '</div></div></div></div>';
}else{
	echo'<div class="well clearfix">';
	echo'<div class="col-md-12 col-sm-12 col-xs-12">';
		echo'<div class="form-group">';
	  	echo '<a href="print_berkas_ujian_sarjana.php?nim='.$get_nim.'&id='.$id.'" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali ke Daftar Print</a>';
	  	echo '<button class="btn btn-primary pull-right" name="print" id="print"><i class="fa fa-print"></i> | Print</button>';
?>
		</div>
	  </div>
	</div>
	<div class="isisurat" id="printArea">
<?php
	$waktu= strftime("%e %B %Y",strtotime($rinfoUjiSkripsi['jadwal']));
	for($i=1;$i<10;$i++){
		echo '<div class="margin">';
		echo '<h2>UNIVERSITAS DIPONEGORO <br> FAKULTAS SAINS DAN MATEMATIKA</h2>';
		echo '<hr>';
		echo '<h2 align="center"><u>BERITA ACARA UJIAN TUGAS RISET</u></h2>';
		echo '<table align="right" border="1" cellpadding="5"><tr><td>Lembar '.$i.'</td></tr></table><br>';
		//echo '<h4 align="right"> Lembar '.$nomor.' </h4><br>';

		echo '<table>';
			echo '<tr >';
				echo '<td >TANGGAL UJIAN</td>';
				echo '<td>:</td>';
				echo '<td>'.$waktu.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>DEPARTEMEN/ PROGRAM SATUDI</td>';
				echo '<td>:</td>';
				echo '<td>KIMIA/ S1 KIMIA</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>NAMA MAHASISWA</td>';
				echo '<td>:</td>';
				echo '<td>'.$rshow_pendaftar['nama'].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>NOMOR INDUK MAHASISWA</td>';
				echo '<td>:</td>';
				echo '<td>'.$rshow_pendaftar['nim'].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>JUDUL SKRIPSI</td>';
				echo '<td>:</td>';
				echo '<td>'.$rshow_pendaftar['judul'].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>TANGGAL MULAI TA</td>';
				echo '<td>:</td>';
				echo '<td>'.$tgl_mulai_ta.'</td>';
			echo '</tr>';
		echo '</table>';
		echo '<h2 align="center">PERSONIL PENGUJI</h2>';
		echo '<table class="isi" style="width:100%" border="1px">';
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
			$nomor=1;
				$infoPembimbing1=mysqli_query($con, "SELECT * from dosen where nip='".$rshow_pendaftar['nip1']."' ");
				$rInfoPembimbing1=$infoPembimbing1->fetch_object();
					echo '<tr>';
					echo '<td align="center">'.$nomor.'</td>'; 
					echo '<td>'.$rInfoPembimbing1->nama_dosen.'</td>';
					echo '<td>PEMBIMBING '.$nomor.'/ SEKRETARIS</td>';
					echo '<td>'.$nomor.'</td>';
					echo '<td></td>';
					echo '</tr>';
					$nomor++;
				if($rshow_pendaftar['nip2']!=''){
					$infoPembimbing2=mysqli_query($con, "SELECT * from dosen where nip='".$rshow_pendaftar['nip2']."' ");
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
				$show_penguji = mysqli_query($con,"SELECT penguji_skripsi.nip_penguji_skripsi, nama_dosen, jabatan FROM dosen inner join penguji_skripsi on penguji_skripsi.nip_penguji_skripsi=dosen.nip where penguji_skripsi.id_uji_skripsi='$id' and jabatan != 'sekretaris' order by jabatan");
				$jml_anggota=2;
				while($rshow_penguji=$show_penguji->fetch_object()){
					switch ($rshow_penguji->jabatan) {
						case 'ketua':
							$jabatan = 'PENGUJI 1/ KETUA';
							break;
						case 'anggota':
							$jabatan = 'PENGUJI '.$jml_anggota;
							$jml_anggota++;
							break;
					}
					echo '<tr>';
					echo '<td align="center">'.$nomor.'</td>'; 
					echo '<td>'.$rshow_penguji->nama_dosen.'</td>';
					echo '<td>'.$jabatan.'</td>';
					echo '<td>'.$nomor.'</td>';
					echo '<td></td>';
					echo '</tr>';
					$nomor++;
				}
				
			echo '</tbody>';
		echo '</table><br><br>';
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
		// echo 'Mahasiswa tersebut dinyatakan <b>LULUS / TIDAK LULUS</b>'.$ket_lulus.' dengan nilai huruf '.$ket_nilai_huruf;
		echo 'Mahasiswa tersebut dinyatakan <b>LULUS / TIDAK LULUS</b> dengan nilai huruf.... <br><br>';
		$tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));
		echo '<table width="100%" style="font-size:18">';
		echo '<tr>';echo '<td width="70%">';
		echo '<td >Semarang, '.$tgl_sekarang.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td >';
		echo '<td >Panitia Ujian Tugas Riset,</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td >';
		echo '<td >Ketua,</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td >';
		echo '<td class="pengisi" height="50"></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td >';
		echo '<td ><u>'.$rKetua['nama_dosen'].'</u></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td >';
		echo '<td >NIP. '.$rKetua['nip'].'</td>';
		echo '</tr>';
		echo '</table>';
		echo '<table align="left">';
			echo '<tr>';
				echo '<td class="ket_lembar">Lembar :</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="ket_lembar">1. Bagian Pengajaran</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="ket_lembar">2. Dosen Wali</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="ket_lembar">3. Mahasiswa</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="ket_lembar">4. Arsip</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="ket_lembar">5. Penguji</td>';
			echo '</tr>';
		echo '</table>';
		echo '<div class="footer_surat"><hr>DEPARTEMEN KIMIA FSM UNDIP</div>';
		echo '<div class="page-break"></div>';
		echo '</div>';
	}
	echo '</div>';
}

	include_once('../footer.php');
	$con->close();
}else{
	header("Location:./");
}
?>