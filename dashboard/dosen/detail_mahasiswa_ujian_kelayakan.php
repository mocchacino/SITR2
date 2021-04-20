<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$nim=$_GET['nim'];
		$nip_saya=$rDosen->nip;

		$show_detail=mysqli_query($con, "SELECT daftar_uji_kelayakan.*, mahasiswa.nama, lab.nama_lab, daftar_tugas_riset2.nip1,daftar_tugas_riset2.nip2,daftar_tugas_riset2.nip3,daftar_tugas_riset2.judul FROM daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim INNER JOIN mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim inner join lab on lab.idlab=tr1.idlab_tr1 where (daftar_tugas_riset2.nip1='$nip_saya' OR daftar_tugas_riset2.nip2='$nip_saya') and daftar_tugas_riset2.nim='$nim' ORDER BY daftar_tugas_riset2.tgl_daftar ");	
		$rshow_detail=$show_detail->fetch_assoc();
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
	<title>Input Nilai</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="dosen_daftar_uji_kelayakan.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Detail Mahasiswa Uji Kelayakan
<?php
				echo '<a href="detail_ujian_kelayakan.php?nim='.$nim.'" class="btn btn-primary pull-right">Detail Ujian kelayakan <i class="fa fa-chevron-circle-right"></i></a>';
?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>NIM</label>&nbsp;
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>Nama</label>&nbsp;
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" list="nim" id='input_nim' name="nim" value=<?php echo $nim;?> readonly autofocus">
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php echo $rshow_detail['nama'];?>" >
									</div>
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
								<div class="row">
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<label>Periode Pendaftaran</label>&nbsp;
									</div>
									<div class="form-group col-md-3 col-sm-3 col-xs-12">
										<label>Tanggal Daftar</label>&nbsp;
									</div>
									<div class="form-group col-md-3 col-sm-3 col-xs-12">
										<label>Tanggal Daftar KRS</label>&nbsp;
									</div>
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<label>Jumlah SKS Semester</label>&nbsp;
									</div>
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<label>Jumlah SKS Komulatif</label>&nbsp;
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<input class="form-control" name="periode" id="periode" value="<?php echo $rshow_detail['tahun_ajaran'];?>" readonly>
									</div>
									<div class="form-group col-md-3 col-sm-3 col-xs-12">
										<input class="form-control" name="tgl_daftar" id="tgl_daftar" value="<?php echo $rshow_detail['tgl_daftar'];?>" readonly>
									</div>
									<div class="form-group col-md-3 col-sm-3 col-xs-12">
										<input class="form-control" name="tgl_krs" id="tgl_krs" value="<?php echo $rshow_detail['tgl_krs'];?>" readonly>
									</div>
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<input class="form-control" name="sks_semester" id="sks_semester" value="<?php echo $rshow_detail['sks_semester'];?>" readonly>
									</div>
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<input class="form-control" name="sks_komulatif" id="sks_komulatif" value="<?php echo $rshow_detail['sks_komulatif'];?>" readonly>
									</div>
								</div>
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