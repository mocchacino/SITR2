<?php
require_once('../functions.php');	
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'mahasiswa'){
	include_once('../sidebar.php');
	$show_pendaftar = mysqli_query($con,"SELECT * FROM daftar_uji_kelayakan INNER JOIN mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim LEFT JOIN uji_kelayakan on uji_kelayakan.nim=daftar_uji_kelayakan.nim where daftar_uji_kelayakan.nim='$rMahasiswa->nim' order by jadwal desc ");
	$rshow_pendaftar = $show_pendaftar->fetch_object();	
	if($rshow_pendaftar){
		$show_penguji=mysqli_query($con,"SELECT * FROM penguji_kelayakan inner join dosen on dosen.nip=penguji_kelayakan.nip_penguji_kelayakan where jabatan ='penguji' and id_uji_kelayakan='$rshow_pendaftar->id_uji_kelayakan'");
		$show_pembimbing=mysqli_query($con,"SELECT * FROM penguji_kelayakan inner join dosen on dosen.nip=penguji_kelayakan.nip_penguji_kelayakan where jabatan ='pembimbing' and id_uji_kelayakan='$rshow_pendaftar->id_uji_kelayakan'");
		$kalab=mysqli_query($con, "SELECT * from dosen inner join lab on lab.nip=dosen.nip where lab.idlab='$rshow_pendaftar->idlab_tr1' ");
		$rkalab=$kalab->fetch_object();

		$jml_nilai= mysqli_query($con, "SELECT count(nip_penguji_kelayakan) as jml_penguji, sum(nilai) as jumlah_nilai from penguji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim='$rMahasiswa->nim' group by jadwal order by jadwal desc ");
		$rjml_nilai=$jml_nilai->fetch_object();

		//$tahunAjaran= mysqli_query($con, "SELECT tahun_ajaran from misc");
		//$rTahunAjaran=$tahunAjaran->fetch_object();

		$nilai_rata= ($rjml_nilai->jumlah_nilai)/($rjml_nilai->jml_penguji);

		if($rshow_pendaftar->is_test == 'sudah'){
			if($rshow_pendaftar->is_lulus == '0'){
				$hasil= 'Maaf anda dinyatakan tidak layak Ujian Kelayakan';
				//$hasil= 'Anda dinyatakan tidak lulus Uji Kelayakan tahun ajaran'.$rTahunAjaran;
				$displayHasil=false;
			}else{
				if ((80 <= $nilai_rata) && ($nilai_rata<=100)){
					$nilai_huruf= "nilai huruf  A ";
				}elseif ((70 <= $nilai_rata )&&($nilai_rata< 80)){
					$nilai_huruf= "nilai huruf  B ";
				}elseif((60 <= $nilai_rata)&&($nilai_rata< 70)){
					$nilai_huruf= "nilai huruf  C " ;
				}elseif((50 <= $nilai_rata)&& ($nilai_rata< 60)){ 
					$nilai_huruf= "nilai huruf  D ";
				}elseif(($nilai_rata < 50)&&($nilai_rata >= 0)){
					$nilai_huruf= "nilai huruf  E ";
				}
				$hasil='Selamat anda dinyatakan Layak Ujian Kelayakan dengan '.$nilai_huruf;
				$displayHasil=true;
			}
		}else{
			$hasil='Hasil belum tersedia';
			$displayHasil=false;
		}

	?>
		<!DOCTYPE html> 	
		<html>
		<head>
			<title>Jadwal dan Penguji Kelayakan</title>	
			<style>
				input#keterangan   {
					width: 100%;
					height: 100px;
				    padding: 12px 20px;
				    margin: 8px 0;
				    box-sizing: border-box;
				    border: none;
				    background-color: #2ECC71;
				    color: white;
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
			<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
			
		</head>
		<body>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Hasil dari Uji Kelayakan
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form>
									<div class="form-group">
										<label>Tanggal Ujian</label>&nbsp;
										<input class="form-control" type="text" id="jadwal" name="jadwal" value="<?php echo $rshow_pendaftar->jadwal; ?>" readonly>
									</div>
									<div class="form-group">
										<label>NIM</label>
										<input class="form-control" type="text" id="nim" name="nim" readonly value="<?php echo $rshow_pendaftar->nim; ?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;
										<input class="form-control" type="text" id="nama" name="nama" value="<?php echo $rshow_pendaftar->nama; ?>" readonly>
									</div>
									<div class="form-group">
										<label>Judul</label>&nbsp;
										<input class="form-control" type="text" id="judul" name="judul" value="<?php echo $rshow_pendaftar->judul; ?>" readonly>
									</div>
									<div class="form-group">
										<label>Pembimbing</label>&nbsp;
										<input class="form-control" type="text" id="judul" name="judul" value="<?php while($rshow_pembimbing=$show_pembimbing->fetch_object()){ echo nl2br($rshow_pembimbing->nama_dosen.', ');} ?>" readonly>
									</div>
									<div class="form-group">
										<label>Penguji</label>&nbsp;
										<input class="form-control" type="text" id="judul" name="judul" value="<?php while($rshow_penguji=$show_penguji->fetch_object()){ echo nl2br($rshow_penguji->nama_dosen.', ');} ?>" readonly>
									</div>
									<br>
									<br>
									<div class="form-group">
									<?php 
										if($displayHasil){
											echo "<input class='form-control' type='text' id='keterangan' name='keterangan' readonly value='"; 
											echo$hasil; 
											echo"'>";
										}else{
											echo "<input class='form-control' type='text' id='keteranganGagal' name='keterangan' readonly value='"; 
											echo$hasil; 
											echo"'>";
										}

											
									?>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	/*
	echo '<table>';
		echo '<tr >';
			echo '<td >TANGGAL UJIAN</td>';
			echo '<td>:</td>';
			echo '<td>'.$rshow_pendaftar->jadwal.'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>DEPARTEMEN</td>';
			echo '<td>:</td>';
			echo '<td>KIMIA</td>';
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
			echo '<td>'.$rshow_pendaftar->judul_tr2.'</td>';
		echo '</tr>';
	echo '</table>';
	echo '<br><br>';
	echo 'Mahasiswa tersebut dinyatakan '; echo $ket_lulus; echo' dengan '; echo $nilai_huruf;
	echo '<br><br><br><br>';
	echo '<table style="width:100%" border="1px">';
		echo '<thead align="center">';
			echo '<tr>';
				echo '<td colspan="5"><b>Nilai Huruf dan Kisaran Rerata Nilai Angka</b></td>';
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
	*/



		include_once('../footer.php');
		$con->close();
	}else{
		echo '<div class="alert alert-danger" role="alert" align="center">BELUM ADA HASIL</div>';
		include_once('../footer.php');
		$con->close();
	}

	
}else{
	header("Location:./");
}
?>