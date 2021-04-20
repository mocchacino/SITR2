<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		
		$sukses=TRUE;
		ini_set('display_errors', 1);
		$nim=$_GET['nim'];
		$id=$_GET['id'];
		// $get_nim=mysqli_query($con,"SELECT nim from daftar_tugas_riset2 where daftar_tugas_riset2.nim not in (SELECT daftar_uji_kelayakan.nim from daftar_uji_kelayakan inner JOIN uji_kelayakan on uji_kelayakan.nim=daftar_uji_kelayakan.nim where is_lulus='1' and jadwal!=0000-00-00)" );
		$showMhsUK=mysqli_query($con,"SELECT daftar_tugas_riset2.nip1,daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3, daftar_tugas_riset2.judul, mahasiswa.nama, daftar_uji_kelayakan.* FROM daftar_tugas_riset2 inner join mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim inner join daftar_uji_kelayakan on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where daftar_uji_kelayakan.id_daftar_uji_kelayakan='$id' ");
		$rShowMhsUK=$showMhsUK->fetch_object();
		$namaPemb1=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rShowMhsUK->nip1."' ");
		$rNamaPemb1=$namaPemb1->fetch_assoc();
		$namaPemb2=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rShowMhsUK->nip2."' ");
		$rNamaPemb2=$namaPemb2->fetch_assoc();
		$namaPemb3=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rShowMhsUK->nip3."' ");
		$rNamaPemb3=$namaPemb3->fetch_assoc();
	
		
		
?>

<!DOCTYPE html>
<html>
<head>
	<title>Detail Mahasiswa Uji Kelayakan</title>
	
</head>
<body>
<div class="row">
	<div class="col-md-12">
		<!-- Form Elements -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="admin_daftar_uji_kelayakan.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
				Detail Mahasiswa Uji Kelayakan
<?php
				echo "<a href='download_transkrip_uji_kelayakan.php?file=".$rShowMhsUK->path_file."' class='btn btn-primary pull-right'>Download Transkrip</a>";
?>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
							<?php
								if ($sukses){
									?>
									<span class="label label-success"><?php if(isset($pesan_sukses)) echo $pesan_sukses;?></span>
									<?php
								} if (!$sukses){
									?>
									<span class="label label-danger"><?php if(isset($pesan_gagal)) echo $pesan_gagal;?></span>
									<?php
								}
							?>
							<div class="form-group">
								<label>NIM</label>
								<input class="form-control" autofocus readonly value="<?php echo $nim?>">
							</div>
							<div class="form-group">
								<label>Nama</label>&nbsp;
								<input class="form-control" type="text" id="nama" name="nama" readonly value="<?php echo $rShowMhsUK->nama;?>">
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
								<textarea class="form-control" name="judul" id="judul" cols="26" rows="5" readonly maxlength="150" value=""><?php echo $rShowMhsUK->judul; ?></textarea>
							</div>
							<div class="form-group">
								<label>SKS Komulatif</label>
								<input class="form-control" type="text" name="komulatif" readonly autofocus value="<?php echo $rShowMhsUK->sks_komulatif; ?>">
							</div>
							<div class="form-group">
								<label>Jumlah SKS Semester Berjalan</label>
								<input class="form-control" type="text" name="sks" readonly autofocus value="<?php echo $rShowMhsUK->sks_semester; ?>">
							</div>
							<!-- <div class="form-group">
								<label>Semester</label>
								<input class="form-control" type="text" name="semester" readonly autofocus value="<?php echo $rShowMhsUK->semester; ?>">
							</div>
							<div class="form-group">
								<label>IPK</label>
								<input class="form-control" type="text" name="ipk" readonly autofocus value="<?php echo $rShowMhsUK->ipk; ?>">
							</div> -->
							<div class="form-group">
								<label>Tanggal Submit KRS</label>
								<input class="form-control" type="date" name="krs" readonly autofocus value="<?php echo $rShowMhsUK->tgl_krs; ?>">
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