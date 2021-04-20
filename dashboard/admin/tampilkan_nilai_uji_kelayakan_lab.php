<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		switch ($_SESSION['admin_sebagai_lab']) {
			case 'biokimia':
				$idlab='1';
				break;
			case 'analitik':
				$idlab='2';
				break;
			case 'fisik':
				$idlab='4';
				break;
			case 'organik':
				$idlab='5';
				break;
			case 'anorganik':
				$idlab='3';
				break;
			
			default:
				header("Location:./");
				break;
		}

		
		$nim=$_GET['nim'];
		$id=$_GET['id'];
		$show_detail=mysqli_query($con, "SELECT mahasiswa.nama, uji_kelayakan.*, daftar_tugas_riset2.judul, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3 from uji_kelayakan inner join tr1 on tr1.nim=uji_kelayakan.nim inner join mahasiswa on mahasiswa.nim=uji_kelayakan.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=uji_kelayakan.nim where tr1.idlab_tr1='$idlab' and uji_kelayakan.nim='$nim' and uji_kelayakan.id_uji_kelayakan='$id'");	
		$rshow_detail=$show_detail->fetch_assoc();	
		$show_penguji=mysqli_query($con, "SELECT dosen.nama_dosen, penguji_kelayakan.nilai from penguji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan inner join dosen on dosen.nip=penguji_kelayakan.nip_penguji_kelayakan inner join tr1 on tr1.nim=uji_kelayakan.nim where tr1.idlab_tr1='$idlab' and uji_kelayakan.nim='$nim' and uji_kelayakan.id_uji_kelayakan='$id' and jabatan = 'penguji' ");
		$pembimbing1=$rshow_detail['nip1'];
		$show_pembimbing1=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '$pembimbing1' ");
		$rshow_pembimbing1=$show_pembimbing1->fetch_assoc();
		$show_pembimbing2=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '".$rshow_detail['nip2']."'");
		$rshow_pembimbing2=$show_pembimbing2->fetch_assoc();
		$show_pembimbing3=mysqli_query($con, "SELECT nama FROM pembimbing_luar where nip= '".$rshow_detail['nip3']."'");
		$rshow_pembimbing3=$show_pembimbing3->fetch_assoc();
		$nilai_pembimbing1=mysqli_query($con, "SELECT penguji_kelayakan.nilai from penguji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim='$nim' and uji_kelayakan.id_uji_kelayakan='$id' and nip_penguji_kelayakan = '".$rshow_detail['nip1']."' ");
		$rnilai_pembimbing1=$nilai_pembimbing1->fetch_object();
		$nilai_pembimbing2=mysqli_query($con, "SELECT penguji_kelayakan.nilai from penguji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim='$nim' and uji_kelayakan.id_uji_kelayakan='$id' and nip_penguji_kelayakan = '".$rshow_detail['nip2']."' ");
		$rnilai_pembimbing2=$nilai_pembimbing2->fetch_object();

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
					<a href="daftar_nilai_uji_kelayakan_lab.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Detail Nilai Mahasiswa
<?php
								
					if($rshow_detail['is_lulus'] == '0') echo '<a href="edit_nilai.php?nim='.$nim.'&id='.$id.'" class="btn btn-primary btn-sm pull-right"><i class="fa fa-pencil-square-o"></i> Edit Nilai</a>';
								
?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form>

								<div class="form-group">
									<label>NIM</label>&nbsp;<span class="label label-warning">*</span>
									<input class="form-control" list="nim" id='input_nim' name="nim" value=<?php echo $nim;?> readonly autofocus">
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
								<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">
									<label>Pembimbing 2</label>
									<input class="form-control" type="text" value="<?php echo $rshow_pembimbing2['nama_dosen'];?>" readonly></div>
									<div class="form-group col-md-4 col-sm-4 col-xs-12">
										<label>Nilai</label>&nbsp;
										<input type="text" class="form-control" readonly value="<?php echo $rnilai_pembimbing2->nilai;?>">
									</div>
								</div>
								<?php
								$i=1;
								while ($rshow_penguji=$show_penguji->fetch_object()) {
									echo '<div class="row"><div class="form-group col-md-8 col-sm-8 col-xs-12">';
									echo '<label>Penguji Lab '; echo $i; echo '</label>';
									echo '<input class="form-control" type="text" value="';
									echo $rshow_penguji->nama_dosen;
									echo '" readonly></div>';
									echo '<div class="form-group col-md-4 col-sm-4 col-xs-12"><label>Nilai</label>&nbsp;<input type="text" class="form-control" readonly value="';
									echo $rshow_penguji->nilai;
									echo '"></div></div>';
									$i++;
								}
								?>
								
								<div class="form-group">
									<label>Mahasiswa dinyatakan </label>&nbsp;
									<?php echo ($rshow_detail['is_lulus'] == '1')? 'LAYAK':'TIDAK LAYAK'; ?>

								</div>
								<br><br>

								
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