<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
			$sukses=FALSE;
			ini_set('display_errors', 1);
			$nim=$_GET['nim'];
			$showMhsTR2=mysqli_query($con,"SELECT daftar_tugas_riset2.*, mahasiswa.nama FROM daftar_tugas_riset2 inner join mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim where daftar_tugas_riset2.nim='$nim' ");
			$rShowMhsTR2=$showMhsTR2->fetch_object();
			$namaPemb1=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rShowMhsTR2->nip1."' ");
			$rNamaPemb1=$namaPemb1->fetch_assoc();
			$namaPemb2=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rShowMhsTR2->nip2."' ");
			$rNamaPemb2=$namaPemb2->fetch_assoc();
			$namaPemb3=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rShowMhsTR2->nip3."' ");
			$rNamaPemb3=$namaPemb3->fetch_assoc();
?>

		<!DOCTYPE html>
		<html>
		<head>
			<title>Detail Mahasiswa TR II</title>
			
		</head>
		<body>
		<div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="row panel-heading" style="margin-left: 0px;
    margin-right: 0px;">
						<a href="admin_daftar_tr2.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
						Detail Mahasiswa Tugas Riset II
<?php
						echo "<a href='download_transkrip_tr2.php?file=".$rShowMhsTR2->path_file."' class='btn btn-primary pull-right'>Download Transkrip</a>";
?>
						
						
					</div>
				</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form>
									<div class="form-group">
										<label>NIM</label>
										<input class="form-control" autofocus readonly value="<?php echo $nim?>">
									</div>
									<div class="form-group">
										<label>Nama</label>&nbsp;
										<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php echo $rShowMhsTR2->nama;?>">
									</div>
									<div class="form-group">
										<label>Pembimbing 1</label>
										<input class="form-control" type="text" id='input_pembimbing1' name="pembimbing1" readonly value="<?php echo $rNamaPemb1['nama_dosen'];?>">
									</div>
									<div class="form-group">
										<label>Pembimbing 2</label>
										<input class="form-control" type="text" id='input_pembimbing2' name="pembimbing2" readonly value="<?php echo $rNamaPemb2['nama_dosen'];?>">
									</div>
									<div class="form-group">
										<label>Pembimbing 3</label>
										<input class="form-control" type="text" id='input_pembimbing3' name="pembimbing3" readonly value="<?php echo $rNamaPemb3['nama_dosen'];?>">
									</div>
									<div class="form-group">
										<label>Judul Tugas Riset 2</label>
										<textarea class="form-control" name="judul" id="judul" cols="26" rows="5" readonly maxlength="150"><?php echo $rShowMhsTR2->judul; ?></textarea>
									</div>
									<div class="form-group">
										<label>SKS Komulatif</label>
										<input class="form-control" type="text" name="komulatif" readonly autofocus value="<?php echo $rShowMhsTR2->sks_komulatif; ?>">
									</div>
									<div class="form-group">
										<label>Jumlah SKS Semester Berjalan</label>
										<input class="form-control" type="text" name="sks" readonly autofocus value="<?php echo $rShowMhsTR2->sks_semester; ?>">
									</div>
									<div class="form-group">
										<label>Semester</label>
										<input class="form-control" type="text" name="semester" readonly autofocus value="<?php echo $rShowMhsTR2->semester; ?>">
									</div>
									<div class="form-group">
										<label>IPK</label>
										<input class="form-control" type="text" name="ipk" readonly autofocus value="<?php echo $rShowMhsTR2->ipk; ?>">
									</div>
									<div class="form-group">
										<label>Tanggal Submit KRS</label>
										<input class="form-control" type="date" name="krs" readonly autofocus value="<?php echo $rShowMhsTR2->tgl_krs; ?>">
									</div>
									
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
<?php
		include_once('../footer.php');
		$con->close();
}else{
	header("Location:./");
}
?>