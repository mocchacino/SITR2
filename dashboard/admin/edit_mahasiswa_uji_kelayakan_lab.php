<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
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
		$sukses=false;	
		if(!isset($_POST['edit'])){
			if ($_SERVER['REQUEST_METHOD']==='GET'){
				$nim=$_GET['nim'];
				$id=$_GET['id'];
			 	
				$show_detail=mysqli_query($con, "SELECT daftar_uji_kelayakan.*, mahasiswa.nama, lab.nama_lab, daftar_tugas_riset2.nip1,daftar_tugas_riset2.nip2,daftar_tugas_riset2.nip3,daftar_tugas_riset2.judul FROM daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim INNER JOIN mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim inner join lab on lab.idlab=tr1.idlab_tr1 where idlab_tr1='$idlab' and daftar_tugas_riset2.nim='$nim' and id_daftar_uji_kelayakan='$id' ORDER BY daftar_tugas_riset2.tgl_daftar ");	
				$rshow_detail=$show_detail->fetch_assoc();
				$pembimbing1=$rshow_detail['nip1'];
				$show_pembimbing1=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '$pembimbing1' ");
				$rshow_pembimbing1=$show_pembimbing1->fetch_assoc();
				$show_pembimbing2=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '".$rshow_detail['nip2']."'");
				$rshow_pembimbing2=$show_pembimbing2->fetch_assoc();
				$show_pembimbing3=mysqli_query($con, "SELECT nama FROM pembimbing_luar where nip= '".$rshow_detail['nip3']."'");
				$rshow_pembimbing3=$show_pembimbing3->fetch_assoc();
				$show_detail_uji_kelayakan=mysqli_query($con, "SELECT * from uji_kelayakan where uji_kelayakan.id_uji_kelayakan='$id'");	
				$rshow_detail_uji_kelayakan=$show_detail_uji_kelayakan->fetch_assoc();
			}
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if (isset($_POST['edit'])) {
					$savenim=test_input($_POST['savenim']);
					$saveid=test_input($_POST['saveid']);
					$nim=test_input($_POST['nim']);
					$id=test_input($_POST['id']);
					$idlab=test_input($_POST['lab']);
					$savelab=test_input($_POST['savelab']);
					$show_detail=mysqli_query($con, "SELECT daftar_uji_kelayakan.*, mahasiswa.nama, lab.nama_lab, daftar_tugas_riset2.nip1,daftar_tugas_riset2.nip2,daftar_tugas_riset2.nip3,daftar_tugas_riset2.judul FROM daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim INNER JOIN mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim inner join lab on lab.idlab=tr1.idlab_tr1 where idlab_tr1='$idlab' and daftar_tugas_riset2.nim='$nim' and id_daftar_uji_kelayakan='$id' ORDER BY daftar_tugas_riset2.tgl_daftar ");	
					$rshow_detail=$show_detail->fetch_assoc();
					$pembimbing1=$rshow_detail['nip1'];
					$show_pembimbing1=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '$pembimbing1' ");
					$rshow_pembimbing1=$show_pembimbing1->fetch_assoc();
					$show_pembimbing2=mysqli_query($con, "SELECT nama_dosen FROM dosen where nip= '".$rshow_detail['nip2']."'");
					$rshow_pembimbing2=$show_pembimbing2->fetch_assoc();
					$show_pembimbing3=mysqli_query($con, "SELECT nama FROM pembimbing_luar where nip= '".$rshow_detail['nip3']."'");
					$rshow_pembimbing3=$show_pembimbing3->fetch_assoc();
					$show_detail_uji_kelayakan=mysqli_query($con, "SELECT * from uji_kelayakan where uji_kelayakan.id_uji_kelayakan='$id'");	
					$rshow_detail_uji_kelayakan=$show_detail_uji_kelayakan->fetch_assoc();
					$komulatif=test_input($_POST['sks_komulatif']);
					if ($komulatif!=''){
						if (is_numeric($komulatif)){
							if ($komulatif >= 120){
								$validKomulatif=TRUE;
							}else{
								$errorKomulatif='sks komulatif minimal 120';
								$validKomulatif=FALSE;
							}
						}else{
							$errorKomulatif='isi dengan angka';
							$validKomulatif=FALSE;
						}
					}else{
						$errorKomulatif='wajib diisi';
						$validKomulatif=FALSE;
					}

					$sks=test_input($_POST['sks_semester']);
					if ($sks !=''){
						if (is_numeric($sks)){
							if($sks >= 7){
								$validSks=TRUE;
							}else{
								$errorSks='sks berjalan minimal 7';
								$validSks=FALSE;
							}
						}else{
							$errorSks='isi dengan angka';
							$validSks=FALSE;
						}
					}else{
						$errorSks='wajib diisi';
						$validSks=FALSE;
					}

					if($validSks && $validKomulatif){
						$edit_pendaftar = mysqli_query($con,"UPDATE daftar_uji_kelayakan SET sks_komulatif = '".$komulatif."',sks_semester = '".$sks."' where  nim = '".$nim."' and id_daftar_uji_kelayakan='".$id."' " );
						
						if($edit_pendaftar){
							$sukses=TRUE;
							$pesan_sukses="Berhasil menambahkan data.";	
						}else{
							$sukses=false;
							$pesan_gagal="Tidak berhasil menambahkan data.";	
						}
					}else{
						$sukses=FALSE;
						$pesan_gagal="Data yang diinputkan tidak valid";
					}
				}
			}
		}	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Detail Nilai</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="row panel-heading" style="margin: 0;">
					<a href="lab_daftar_uji_kelayakan.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Edit Mahasiswa Uji Kelayakan
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  enctype="multipart/form-data">
								<?php
									if ($sukses){
										if(isset($pesan_sukses)) {
											echo '<div class="alert alert-success" role="alert">';
											echo $pesan_sukses; 
											echo '</div>';
										}
									}if (!$sukses){
										if(isset($pesan_gagal)){
											echo'<div class="alert alert-danger" role="alert">';
											echo $pesan_gagal;
											echo'</div>';
										}
									}
								?>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>NIM</label>&nbsp;
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>Nama</label>&nbsp;
									</div>
								</div>
								<input type="text" name="savelab" hidden value="<?php echo $lab;?>">
								<input type="text" name="lab" hidden value="<?php echo $idlab;?>">
								<input type="text" name="savenim" hidden value="<?php echo $nim;?>">
								<input type="text" name="saveid" hidden value="<?php echo $id;?>">
								<input type="text" id='input_id' hidden name="id"  value="<?php if(isset($id)){echo $id;}else $saveid; ?>">
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" list="nim" id='input_nim' name="nim"  value="<?php if(isset($nim)){echo $nim;}else $savenim; ?>" readonly autofocus">
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
										<label>Jumlah SKS Semester</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorSks)) echo $errorSks;?></span>
									</div>
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<label>Jumlah SKS Komulatif</label>&nbsp;<span class="label label-warning">*<?php if(isset($errorKomulatif)) echo $errorKomulatif;?></span>
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
										<input class="form-control" name="sks_semester" id="sks_semester" value="<?php echo $rshow_detail['sks_semester'];?>">
									</div>
									<div class="form-group col-md-2 col-sm-2 col-xs-12">
										<input class="form-control" name="sks_komulatif" id="sks_komulatif" value="<?php echo $rshow_detail['sks_komulatif'];?>" >
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>Tempat</label>&nbsp;
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<label>Jadwal</label>&nbsp;
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" type='text' id='tempat' name="tempat" value="<?php echo $rshow_detail_uji_kelayakan['tempat'];?>" readonly autofocus">
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" type="text" id="jadwal" name="jadwal" readonly value="<?php echo $rshow_detail_uji_kelayakan['jadwal'];?>" >
									</div>
								</div>
<?php
								$show_penguji = mysqli_query($con,"SELECT nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim= '".$nim."' and uji_kelayakan.id_uji_kelayakan='".$rshow_detail_uji_kelayakan['id_uji_kelayakan']."' ");
								$no_penguji=1;
								while($rshow_penguji=$show_penguji->fetch_object()){
									echo '<div class="form-group"><label>Penguji Kelayakan '.$no_penguji.'</label>&nbsp;';
									echo '<input class="form-control" type="text" readonly value="';
									echo $rshow_penguji->nama_dosen;
									echo '"></div>';
									$no_penguji++;
								}

								
								
								
								
?>
								<div class="form-group">
									<input class="form-control btn btn-primary" type="submit" name="edit" value="edit" />
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