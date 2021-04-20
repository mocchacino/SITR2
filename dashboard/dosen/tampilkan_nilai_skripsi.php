<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$id=$_GET['id'];

		$show_detail=mysqli_query($con, "SELECT mahasiswa.nim,mahasiswa.nama, uji_skripsi.*, judul, nip1, nip2, nip3, tgl_lulus from uji_skripsi inner join mahasiswa on mahasiswa.nim=uji_skripsi.nim inner join daftar_skripsi on daftar_skripsi.id_daftar_skripsi=uji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='$id' ");	
		$rshow_detail=$show_detail->fetch_assoc();	
		$nilai_pembimbing1=mysqli_query($con, "SELECT penguji_skripsi.nilai from penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi where uji_skripsi.nim='".$rshow_detail['nim']."' and uji_skripsi.id_uji_skripsi='$id' and nip_penguji_skripsi = '".$rshow_detail['nip1']."' ");
		$rnilai_pembimbing1=$nilai_pembimbing1->fetch_object();
		$nilai_pembimbing2=mysqli_query($con, "SELECT penguji_skripsi.nilai from penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi where uji_skripsi.nim='".$rshow_detail['nim']."' and uji_skripsi.id_uji_skripsi='$id' and nip_penguji_skripsi = '".$rshow_detail['nip2']."' ");
		$rnilai_pembimbing2=$nilai_pembimbing2->fetch_object();
		$nilai_pembimbing3=mysqli_query($con, "SELECT penguji_skripsi.nilai from penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi where uji_skripsi.nim='".$rshow_detail['nim']."' and uji_skripsi.id_uji_skripsi='$id' and nip_penguji_skripsi = '".$rshow_detail['nip3']."' ");
		$rnilai_pembimbing3=$nilai_pembimbing3->fetch_object();
		$pembimbing1=$rshow_detail['nip1'];
		$show_pembimbing1=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '$pembimbing1' ");
		$rshow_pembimbing1=$show_pembimbing1->fetch_assoc();
		$show_pembimbing2=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '".$rshow_detail['nip2']."'");
		$rshow_pembimbing2=$show_pembimbing2->fetch_assoc();
		$show_pembimbing3=mysqli_query($con, "SELECT nama FROM pembimbing_luar where nip= '".$rshow_detail['nip3']."'");
		$rshow_pembimbing3=$show_pembimbing3->fetch_assoc();
		

?>
<!DOCTYPE html>
<html>
<head>
	<title>Detail Nilai Mahasiswa</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="daftar_nilai_skripsi.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Detail Nilai Skripsi Mahasiswa 
<?php			
					echo '<a href="edit_nilai_skripsi.php?id='.$id.'" class="btn btn-primary btn-sm pull-right"><i class="fa fa-pencil-square-o"></i> Edit Nilai</a>';
								
?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form>
								<div class="form-group">
									<label>NIM</label>&nbsp;
									<input class="form-control" list="nim" id='input_nim' name="nim" value=<?php echo $rshow_detail['nim'];?> readonly autofocus">
								</div>
								<div class="form-group">
									<label>Nama</label>&nbsp;
									<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php echo $rshow_detail['nama'];?>" >
								</div>
								<div class="form-group">
									<label>Tanggal & Waktu</label>&nbsp;
									<input class="form-control" name="jadwal" id="jadwal" value="<?php echo $rshow_detail['jadwal'];?>" readonly>
								</div>
								<div class="form-group">
									<label>Tempat</label>&nbsp;
									<input type="text" class="form-control" name="tempat" id="tempat" value="<?php echo $rshow_detail['tempat'];?>" readonly>
								</div>
								<div class="form-group">
									<label>Pembimbing 1</label>&nbsp;
									<input class="form-control" type="text" name="pembimbing1" id="pembimbing1" readonly value="<?php echo $rshow_pembimbing1['nama_dosen'];?>">
								</div>
								<div class="form-group">
									<label>Pembimbing 2</label>&nbsp;
									<input class="form-control" type="text" name="pembimbing2" id="pembimbing2" readonly value="<?php echo $rshow_pembimbing2['nama_dosen'];?>">
								</div>
								<div class="form-group">
									<label>Pembimbing 3</label>&nbsp;
									<input class="form-control" type="text" name="pembimbing3" id="pembimbing3" readonly value="<?php echo $rshow_pembimbing3['nama'];?>">
								</div>
								<div class="form-group">
									<label>Judul</label>&nbsp;
									<textarea class="form-control" name="judul" id="judul" cols="26" rows="5" maxlength="150" readonly ><?php echo $rshow_detail['judul'];?> </textarea>
								</div>
								<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">
									<label>Pembimbing 1</label>
									<input class="form-control" type="text" value="<?php echo $rshow_pembimbing1['nama_dosen'];?>" readonly></div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input type="text" class="form-control" readonly value="<?php echo $rnilai_pembimbing1->nilai;?>">
									</div>
								</div>
								<?php
								if($rshow_detail['nip2']!=''){
									echo '<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Pembimbing 2</label>
										<input class="form-control" type="text" value="';
									echo $rshow_pembimbing2['nama_dosen'];
									echo'" readonly></div>
										<div class="form-group col-md-4 col-sm-4 col-xs-12">
											<label>Nilai</label>&nbsp;
											<input type="text" class="form-control" readonly value="';
									if($rnilai_pembimbing2->nilai !='') echo $rnilai_pembimbing2->nilai;
									else echo '';
									echo '"></div></div>';
								}
								if($rshow_detail['nip3']!=''){
									echo '<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">
										<label>Pembimbing 3</label>
										<input class="form-control" type="text" value="';
									echo $rshow_pembimbing3['nama'];
									echo '" readonly></div>
										<div class="form-group col-md-4 col-sm-4 col-xs-12">
											<label>Nilai</label>&nbsp;
											<input type="text" class="form-control" readonly value="';
									if($rnilai_pembimbing3 == '') echo '';
									else echo $rnilai_pembimbing3->nilai;
									echo '"></div></div>';
								}

								$i=1;
								$show_penguji=mysqli_query($con, "SELECT dosen.nama_dosen, penguji_skripsi.nilai from penguji_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi inner join dosen on dosen.nip=penguji_skripsi.nip_penguji_skripsi where uji_skripsi.id_uji_skripsi='$id' and jabatan != 'sekretaris' order by jabatan");
								while ($rshow_penguji=$show_penguji->fetch_object()) {
									echo '<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">';
									echo '<label>Penguji '; echo $i; echo '</label>';
									echo '<input class="form-control" type="text" value="';
									echo $rshow_penguji->nama_dosen;
									echo '" readonly></div>';
									echo '<div class="form-group col-md-4 col-sm-4 col-xs-12"><label>Nilai</label>&nbsp;<input type="text" class="form-control" readonly value="';
									echo $rshow_penguji->nilai;
									echo '"></div></div>';
									$i++;
								}

								$jml_nilai=mysqli_query($con,"SELECT count(nip_penguji_skripsi) as jml_penguji, sum(nilai) as jumlah_nilai from penguji_skripsi where id_uji_skripsi='".$id."' ");
								$rjml_nilai=$jml_nilai->fetch_object();
								$nilai_rata= ($rjml_nilai->jumlah_nilai)/($rjml_nilai->jml_penguji);
								if($rshow_detail['is_test'] == 'sudah'){
									if($rshow_detail['is_lulus'] == '0'){
										$nilai_huruf= '-';
									}else{
										if ((80 <= $nilai_rata) && ($nilai_rata<=100)){
											$nilai_huruf= "A ";
										}elseif ((70 <= $nilai_rata )&&($nilai_rata< 80)){
											$nilai_huruf= "B ";
										}elseif((60 <= $nilai_rata)&&($nilai_rata< 70)){
											$nilai_huruf= "C " ;
										}elseif((50 <= $nilai_rata)&& ($nilai_rata< 60)){ 
											$nilai_huruf= "D ";
										}elseif(($nilai_rata < 50)&&($nilai_rata >= 0)){
											$nilai_huruf= "E ";
										}
									}
								}
								?>
								
								<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">
									<label>Nilai Huruf</label>&nbsp;
								</div>
								<div class="form-group col-md-4 col-sm-4 col-xs-12">
									<input class="form-control" type="text" value="<?php echo $nilai_huruf;?>" readonly>
									
								</div></div>
								
								<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">
									<label>Mahasiswa dinyatakan </label>&nbsp;
								</div>
								<div class="form-group col-md-4 col-sm-4 col-xs-12">
									<input class="form-control" type="text" name="is_lulus" readonly value="<?php echo ($rshow_detail['is_lulus'] == '1')? 'LULUS':'TIDAK LULUS'; ?>">
									
								</div></div>
<?php
								if ($rshow_detail['is_lulus'] == '1'){
?>
								<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">
									<label>Tanggal mahasiswa LULUS</label></div>
								<div class="form-group col-md-4 col-sm-4 col-xs-12">
<?php
									$tgl=strftime("%e %B %Y",strtotime($rshow_detail['tgl_lulus']));
									echo '<input class="form-control" type="text" name="tgl_lulus" readonly value="'.$tgl.'">';
								echo '</div></div>';
								}
?>	
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>