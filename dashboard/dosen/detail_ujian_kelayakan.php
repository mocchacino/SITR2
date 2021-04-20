<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$nim=$_GET['nim'];
		$id=$_GET['id'];
		$nip_saya=$rDosen->nip;

		$show_detail=mysqli_query($con, "SELECT uji_kelayakan.id_uji_kelayakan,uji_kelayakan.*, penguji_kelayakan.*, nama from uji_kelayakan inner join penguji_kelayakan on penguji_kelayakan.id_uji_kelayakan=uji_kelayakan.id_uji_kelayakan inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=uji_kelayakan.nim inner join mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim where daftar_tugas_riset2.nim='$nim' and uji_kelayakan.id_uji_kelayakan='$id' and (nip1='$nip_saya' or nip2='$nip_saya')");	
		$rshow_detail=$show_detail->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Input Nilai</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
		    font-size: 14pt;
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
		    font-size: 14pt;
		}
	</style>
</head>
<body>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<!-- Form Elements -->
			<div class="panel panel-default">
				<div class="panel-heading">
<?php
				echo '<a href="dosen_daftar_uji_kelayakan.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
					Detail Mahasiswa Uji Kelayakan';
?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<form>
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
										<input class="form-control" list="nim" id='input_nim' name="nim" value=<?php echo $nim;?> readonly autofocus">
									</div>
									<div class="form-group col-md-6 col-sm-6 col-xs-12">
										<input class="form-control" type="text" id="jadwal" name="jadwal" readonly value="<?php echo $rshow_detail['jadwal'];?>" >
									</div>
								</div>
<?php
								$show_pembimbing = mysqli_query($con,"SELECT nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim= '".$nim."' and uji_kelayakan.id_uji_kelayakan='".$rshow_detail['id_uji_kelayakan']."' and jabatan='pembimbing' ");
								$no_pembimbing=1;
								while($rshow_pembimbing=$show_pembimbing->fetch_object()){
									echo '<div class="form-group"><label>Pembimbing '.$no_pembimbing.'</label>&nbsp;';
									echo '<input class="form-control" type="text" readonly value="';
									echo $rshow_pembimbing->nama_dosen;
									echo '"></div>';
									$no_pembimbing++;
								}
								$show_penguji = mysqli_query($con,"SELECT nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim= '".$nim."' and uji_kelayakan.id_uji_kelayakan='".$rshow_detail['id_uji_kelayakan']."' and jabatan!='pembimbing' ");
								$no_penguji=1;
								while($rshow_penguji=$show_penguji->fetch_object()){
									echo '<div class="form-group"><label>Penguji Kelayakan '.$no_penguji.'</label>&nbsp;';
									echo '<input class="form-control" type="text" readonly value="';
									echo $rshow_penguji->nama_dosen;
									echo '"></div>';
									$no_penguji++;
								}
								if($rshow_detail['is_lulus'] == '1'){
									echo "<input class='form-control' type='text' id='keterangan' name='keterangan' readonly value='Mahasiswa sudah lulus Uji Kelayakan' "; 
								}else{
									echo "<input class='form-control' type='text' id='keteranganGagal' name='keterangan' readonly value='Mahasiswa belum/tidak lulus Uji Kelayakan' "; 
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