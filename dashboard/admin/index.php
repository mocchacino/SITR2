<?php 
	session_start();
	// date_default_timezone_set("Asia/Bangkok"); 
	// setlocale(LC_ALL, 'id_ID');

	ini_set('display_errors', 1);
	include_once('../sidebar.php');
	$status = $_SESSION['sip_status'];
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		unset($_SESSION['admin_sebagai_lab']);
		if(isset($_POST['masuk_biokimia'])){
			$_SESSION['admin_sebagai_lab']='biokimia';
			header("Location:../admin/home_lab.php");
		}elseif(isset($_POST['masuk_analitik'])){
			$_SESSION['admin_sebagai_lab']='analitik';
			header("Location:../admin/home_lab.php");
		}elseif(isset($_POST['masuk_fisik'])){
			$_SESSION['admin_sebagai_lab']='fisik';
			header("Location:../admin/home_lab.php");
		}elseif(isset($_POST['masuk_organik'])){
			$_SESSION['admin_sebagai_lab']='organik';
			header("Location:../admin/home_lab.php");
		}elseif(isset($_POST['masuk_anorganik'])){
			$_SESSION['admin_sebagai_lab']='anorganik';
			header("Location:../admin/home_lab.php");
		}
		$endTugasRiset2=mysqli_query($con, "SELECT awal, akhir FROM waktu where nama='TR2' ORDER BY akhir DESC");
		if(mysqli_num_rows($endTugasRiset2) > 0){
			$waktuSelesaiTugasRiset2 = mysqli_fetch_assoc($endTugasRiset2);
			$endDateTugasRiset2 = $waktuSelesaiTugasRiset2['akhir'];
			$startDateTugasRiset2 = $waktuSelesaiTugasRiset2['awal'];
			if(($endDateTugasRiset2 >= date("Y-m-d"))&&($startDateTugasRiset2 <= date("Y-m-d")))
				$noteTR2=" dibuka sampai tanggal ".strftime("%e %B %Y",strtotime($endDateTugasRiset2));
			else if(($endDateTugasRiset2 < date("Y-m-d"))&&($startDateTugasRiset2 > date("Y-m-d")))
				$noteTR2=" dimulai dari tanggal ".strftime("%e %B %Y",strtotime($startDateTugasRiset2));
		}
		$endUjiKelayakan=mysqli_query($con, "SELECT awal, akhir FROM waktu where nama='UK' ORDER BY akhir DESC");
		if(mysqli_num_rows($endUjiKelayakan) > 0){
			$waktuSelesaiUjiKelayakan = mysqli_fetch_assoc($endUjiKelayakan);
			$endDateUjiKelayakan = $waktuSelesaiUjiKelayakan['akhir'];
			$startDateUjiKelayakan = $waktuSelesaiUjiKelayakan['awal'];
			if(($endDateUjiKelayakan >= date("Y-m-d"))&&($startDateUjiKelayakan <= date("Y-m-d")))
				$noteUK=" dibuka sampai tanggal ".strftime("%e %B %Y",strtotime($endDateUjiKelayakan));
			else if(($endDateTugasRiset2 < date("Y-m-d"))&&($startDateTugasRiset2 > date("Y-m-d")))
				$noteUK=" dimulai dari tanggal ".strftime("%e %B %Y",strtotime($startDateUjiKelayakan));
		}
		$endSkripsi=mysqli_query($con, "SELECT awal, akhir FROM waktu where nama='Skr' ORDER BY akhir DESC");
		if(mysqli_num_rows($endSkripsi) > 0){
			$waktuSelesaiSkripsi = mysqli_fetch_assoc($endSkripsi);
			$endDateSkripsi = $waktuSelesaiSkripsi['akhir'];
			$startDateSkripsi = $waktuSelesaiSkripsi['awal'];
			if(($endDateSkripsi >= date("Y-m-d"))&&($startDateSkripsi <= date("Y-m-d")))
				$noteSkr=" dibuka sampai tanggal ".strftime("%e %B %Y",strtotime($endDateSkripsi));
			else if(($endDateTugasRiset2 < date("Y-m-d"))&&($startDateTugasRiset2 > date("Y-m-d")))
				$noteSkr=" dimulai dari tanggal ".strftime("%e %B %Y",strtotime($startDateSkripsi));
		}
	?>
		<script type="text/javascript">				
		function createCountDown(elementIdCounterTR2,elementIdCounterUK,elementIdCounterSkripsi){
			// Set the date we're counting down to
		    var TugasRiset2Expired = '<?php echo $endDateTugasRiset2; ?>';
		    var UjiKelayakanExpired = '<?php echo $endDateUjiKelayakan; ?>';
		    var SkripsiExpired = '<?php echo $endDateSkripsi; ?>';

		    var TugasRiset2Start = '<?php echo $startDateTugasRiset2; ?>';
		    var UjiKelayakanStart = '<?php echo $startDateUjiKelayakan; ?>';
		    var SkripsiStart = '<?php echo $startDateSkripsi; ?>';

		    var TugasRiset2Start = new Date(TugasRiset2Start);
		    var UjiKelayakanStart = new Date(UjiKelayakanStart);
		    var SkripsiStart = new Date(SkripsiStart);

		    var TugasRiset2CountDownDate = new Date(TugasRiset2Expired+' GMT+0700');
		    TugasRiset2CountDownDate.setDate(TugasRiset2CountDownDate.getDate()+1);
		    TugasRiset2CountDownDate = TugasRiset2CountDownDate.getTime();

		    var UjiKelayakanCountDownDate = new Date(UjiKelayakanExpired+' GMT+0700');
		    UjiKelayakanCountDownDate.setDate(UjiKelayakanCountDownDate.getDate()+1);
		    UjiKelayakanCountDownDate = UjiKelayakanCountDownDate.getTime();

		    var SkripsiCountDownDate = new Date(SkripsiExpired+' GMT+0700');
		    SkripsiCountDownDate.setDate(SkripsiCountDownDate.getDate()+1);
		    SkripsiCountDownDate = SkripsiCountDownDate.getTime();

		    // Update the count down every 1 second
		    var x = setInterval(function() {

		        // Get todays date and time
		        var now = new Date().getTime();
		        // Find the distance between now an the count down date
		        var TugasRiset2Distance = TugasRiset2CountDownDate - now;
		        var UjiKelayakanDistance = UjiKelayakanCountDownDate - now;
		        var SkripsiDistance = SkripsiCountDownDate - now;
		        
		        // Time calculations for days, hours, minutes and seconds
		        //tugas riset 2
		        var TugasRiset2Days = Math.floor(TugasRiset2Distance / (1000 * 60 * 60 * 24));
		        var TugasRiset2Hours = Math.floor((TugasRiset2Distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		        var TugasRiset2Minutes = Math.floor((TugasRiset2Distance % (1000 * 60 * 60)) / (1000 * 60));
		        var TugasRiset2Seconds = Math.floor((TugasRiset2Distance % (1000 * 60)) / 1000);
		        //uji kelayakan
		        var UjiKelayakanDays = Math.floor(UjiKelayakanDistance / (1000 * 60 * 60 * 24));
		        var UjiKelayakanHours = Math.floor((UjiKelayakanDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		        var UjiKelayakanMinutes = Math.floor((UjiKelayakanDistance % (1000 * 60 * 60)) / (1000 * 60));
		        var UjiKelayakanSeconds = Math.floor((UjiKelayakanDistance % (1000 * 60)) / 1000);
		        //skripsi
		        var SkripsiDays = Math.floor(SkripsiDistance / (1000 * 60 * 60 * 24));
		        var SkripsiHours = Math.floor((SkripsiDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		        var SkripsiMinutes = Math.floor((SkripsiDistance % (1000 * 60 * 60)) / (1000 * 60));
		        var SkripsiSeconds = Math.floor((SkripsiDistance % (1000 * 60)) / 1000);
		        
		        // Output the result in an element with id
		        document.getElementById(elementIdCounterTR2).innerHTML = TugasRiset2Days + " Hari " + TugasRiset2Hours + " Jam " + TugasRiset2Minutes + " Menit " + TugasRiset2Seconds + "s ";
		        document.getElementById(elementIdCounterUK).innerHTML = UjiKelayakanDays + " Hari " + UjiKelayakanHours + " Jam " + UjiKelayakanMinutes + " Menit " + UjiKelayakanSeconds + "s ";
		        document.getElementById(elementIdCounterSkripsi).innerHTML = SkripsiDays + " Hari " + SkripsiHours + " Jam " + SkripsiMinutes + " Menit " + SkripsiSeconds + "s ";
		        
		        // If the count down is over, write some text 
		        if ((TugasRiset2Distance < 0)||(isNaN(TugasRiset2Distance)) || (TugasRiset2Start > now)) {
		            clearInterval();
		            document.getElementById(elementIdCounterTR2).innerHTML = "Pendaftaran Ditutup";
		        }if ((UjiKelayakanDistance < 0)||(isNaN(UjiKelayakanDistance)) || (UjiKelayakanStart > now)) {
		            clearInterval();
		            document.getElementById(elementIdCounterUK).innerHTML = "Pendaftaran Ditutup";
		        }if ((SkripsiDistance < 0)||(isNaN(SkripsiDistance)) || (SkripsiStart > now)) {
		            clearInterval();
		            document.getElementById(elementIdCounterSkripsi).innerHTML = "Pendaftaran Ditutup";
		        }
		    }, 1000);
		}

	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
	<title>Admin | Sistem Informasi Tugas Riset 2</title>
</head>
<body>
	<div class="row">
	    <div class="col-md-12">
	        <h2>Dashboard</h2>   
	        <h5>Selamat datang <b>
	        <?php if($status=="petugas") echo $rAdmin->nama;
	        ?>
	        </b>. <small><i>Anda masuk sebagai <b>Admin</b></i></small></h5>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-12">
<?php
		if (isset($noteTR2)){
			echo '<div class="alert alert-info alert-dismissible fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
			echo '<strong>Pendaftaran Tugas Riset 2</strong>';
			echo $noteTR2;
			echo '</div>';
		}if (isset($noteUK)){
			echo '<div class="alert alert-info alert-dismissible fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
			echo '<strong>Pendaftaran Uji Kelayakan</strong>';
			echo $noteUK;
			echo '</div>';
		}if (isset($noteSkr)){
			echo '<div class="alert alert-info alert-dismissible fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
			echo '<strong>Pendaftaran Skripsi</strong>';
			echo $noteSkr;
			echo '</div>';
		}
		
?>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">Countdown Pendaftaran Tugas Riset</div>
				<div class="panel-body">
					<script type="text/javascript">
						createCountDown('countdownTugasRiset2', 'countdownUjiKelayakan', 'countdownSkripsi');
					</script>
					<p id='countdownTugasRiset2' align="center"></p>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">Countdown Pendaftaran Uji Kelayakan</div>
				<div class="panel-body">
					<p id='countdownUjiKelayakan' align="center"></p>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-4">
			<div class="panel panel-default">
				<div class="panel-heading">Countdown Pendaftaran Skripsi</div>
				<div class="panel-body">
					<p id='countdownSkripsi' align="center"></p>
				</div>
			</div>
		</div>
	</div>

	<hr />                
	 <!-- /. ROW  -->
	<div class="row">
	    <div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				   Masuk sebagai Laboratorium
				</div>
				<div class="panel-body">
					<div class="col-md-4 col-sm-4 col-xs-6">
						<div class="well clearfix">
					    	<form accept-charset="UTF-8" role="form" method="POST">
					    	<label>Biokimia</label>
					    		<button type="submit" class="btn btn-primary pull-right" name="masuk_biokimia" value="Masuk"><i class="fa fa-sign-in"></i> Masuk</button>
					      	</form>
					    </div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-6">
						<div class="well clearfix">
					    	<form accept-charset="UTF-8" role="form" method="POST">
					    	<label>Kimia Analitik</label>
					    		<button type="submit" class="btn btn-primary pull-right" name="masuk_analitik" value="Masuk"><i class="fa fa-sign-in"></i> Masuk</button>
					      	</form>
					    </div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-6">
						<div class="well clearfix">
					    	<form accept-charset="UTF-8" role="form" method="POST">
					    	<label>Kimia Fisik</label>
					    		<button type="submit" class="btn btn-primary pull-right" name="masuk_fisik" value="Masuk"><i class="fa fa-sign-in"></i> Masuk</button>
					      	</form>
					    </div>
					</div>
					<div class="col-md-offset-2 col-md-4 col-sm-4 col-xs-6">
						<div class="well clearfix">
					    	<form accept-charset="UTF-8" role="form" method="POST">
					    	<label>Kimia Organik</label>
					    		<button type="submit" class="btn btn-primary pull-right" name="masuk_organik" value="Masuk"><i class="fa fa-sign-in"></i> Masuk</button>
					      	</form>
					    </div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-6">
						<div class="well clearfix">
					    	<form accept-charset="UTF-8" role="form" method="POST">
					    	<label>Kimia Anorganik</label>
					    		<button type="submit" class="btn btn-primary pull-right" name="masuk_anorganik" value="Masuk"><i class="fa fa-sign-in"></i> Masuk</button>
					    		<!-- <input class="btn btn-primary pull-right" type="submit" name="masuk_biokimia" value="Masuk"> -->
					      	</form>
					    </div>
					</div>
				</div>
			</div>
	    </div>
	</div>

<!-- GRAFIK -->
	<!-- GRAFIK UJI KELAYAKAN -->
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				   Grafik Uji Kelayakan
				</div>
				<div class="panel-body">
				<?php
					
					$periodeTersediadari=mysqli_query($con, "SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim order by tahun_ajaran asc");
					$periodeTersediasampai=mysqli_query($con, "SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim order by tahun_ajaran asc");
				?>
				<div class="col-md-2 col-sm-12 col-xs-12">
				Dari Periode :
				<div class="form-group">
					<select class="form-control" id="dari_periode" name="dari_periode" >
						<option>--Pilih Periode--</option>
						<?php
							while($rPeriodeTersediadari=$periodeTersediadari->fetch_object()){ ?>
									<option value="<?php echo $rPeriodeTersediadari->tahun_ajaran;?>"> <?php echo $rPeriodeTersediadari->tahun_ajaran;?>
									</option>
								<?php 
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				Sampai Periode :
				<div class="form-group">
					<select class="form-control" id="sampai_periode" name="sampai_periode" >
						<option>--Pilih Periode--</option>
						<?php
							while($rPeriodeTersediasampai=$periodeTersediasampai->fetch_object()){ ?>
									<option value="<?php echo $rPeriodeTersediasampai->tahun_ajaran;?>"> <?php echo $rPeriodeTersediasampai->tahun_ajaran;?>
									</option>
								<?php 
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				<div class="form-group">
					<br>
					<input class="form-control btn btn-info" type="submit" name="search_tgl" value="Search" >
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				<div class="form-group">
					<br>
					
					 <input class="form-control btn btn-danger" type="button" name="reset" value="Reset" onclick="window.location.href='index.php'" />
					 
				</div>
			</div>
			<?php
				if ($_SERVER['REQUEST_METHOD'] === 'POST'){
					if(isset($_POST['search_tgl'])) {
						$dari_periode=$_POST['dari_periode'];
						$sampai_periode=$_POST['sampai_periode'];
						if(($dari_periode!='')&&($sampai_periode!='')){
							$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where (daftar_uji_kelayakan.tahun_ajaran >= '".$dari_periode."' and daftar_uji_kelayakan.tahun_ajaran <= '".$sampai_periode."') and is_lulus='1' GROUP by tahun_ajaran");
							$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where (daftar_uji_kelayakan.tahun_ajaran >= '".$dari_periode."' and daftar_uji_kelayakan.tahun_ajaran <= '".$sampai_periode."') and is_lulus='0' GROUP by tahun_ajaran");
							$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where (daftar_uji_kelayakan.tahun_ajaran BETWEEN '".$dari_periode."' AND '".$sampai_periode."') order by tahun_ajaran");
						}else{
							$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where is_lulus='1' GROUP by tahun_ajaran");
							$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where is_lulus='0' GROUP by tahun_ajaran");
							$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim order by tahun_ajaran");
						}
						
					}else{
						$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where is_lulus='1' GROUP by tahun_ajaran");
						$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where is_lulus='0' GROUP by tahun_ajaran");
						$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim order by tahun_ajaran");
					}
				}else{
					$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where is_lulus='1' GROUP by tahun_ajaran");
					$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim where is_lulus='0' GROUP by tahun_ajaran");
					$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim order by tahun_ajaran");
				}
				
			?>
					<div class="chart-container" >
				        <canvas id="graphUjiKelayakan" style="position: relative; height:150px; width:500px;"></canvas>
				    </div>
				    <script>
				        $(document).ready(function () {
				            showGraph();
				        });
				        function showGraph(){
		                    var tahun_ajaran = [];
		                    var lulus = [];
		                    var tidak_lulus = [];
<?php  
		                    while($rPeriode=$periode->fetch_object()){
?>
		                    	tahun_ajaran.push("<?= $rPeriode->tahun_ajaran?>");				
<?php
		                    }
				            while($rjmlUjiKelayakanLulus=$jmlUjiKelayakanLulus->fetch_object()){
?>
				                for (var i = 0 ; i < tahun_ajaran.length; i++) {
				                    if("<?= $rjmlUjiKelayakanLulus->tahun_ajaran?>" == tahun_ajaran[i]){
				                    	lulus[i]=("<?= $rjmlUjiKelayakanLulus->jumlah?>");
				                    }else{
				                    	if (!lulus[i]) {
				                    		lulus[i]=("");	
				                    	}
				                    }
				                }		                    		
<?php
				            }
				            while($rjmlUjiKelayakanTidakLulus=$jmlUjiKelayakanTidakLulus->fetch_object()){
?>
				                for (var i = 0 ; i < tahun_ajaran.length; i++) {
				                    if("<?= $rjmlUjiKelayakanTidakLulus->tahun_ajaran?>" == tahun_ajaran[i]){
				                    	tidak_lulus[i]=("<?= $rjmlUjiKelayakanTidakLulus->jumlah?>");
					                }else{
					                    if (!tidak_lulus[i]) {
					                    	tidak_lulus[i]=("");	
					                	}
					                }
					            }		                    		
<?php
				            }
?>
				            var chartdata = {
				                labels: tahun_ajaran,
				                datasets: [
					                {
		                                label: 'LULUS',
		                                backgroundColor: '#87CEFA',
		                                borderColor: '#87CEEB',
		                                hoverBackgroundColor: '#6495ED',
		                                hoverBorderColor: '#4682B4',
		                                data: lulus
		                            },
		                            {
		                                label: 'TIDAK LULUS',
		                                backgroundColor: '#FFA07A',
		                                borderColor: '#FA8072',
		                                hoverBackgroundColor: '#F08080',
		                                hoverBorderColor: '#CD5C5C',
		                                data: tidak_lulus
		                            }
		                        ]
		                    };
		                    var graphTarget = $("#graphUjiKelayakan");
		                    var barGraph = new Chart(graphTarget, {
		                        type: 'bar',
		                        data: chartdata
		                    });
				                
				        }
				    </script>
				</div>
			</div>
        </div>
    </div>
</form>    


<!-- GRAFIK SKRIPSI -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				   Grafik Skripsi
				</div>
				<div class="panel-body">
				<?php
					$periodeSkripsiTersediadari=mysqli_query($con, "SELECT distinct daftar_skripsi.tahun_ajaran from daftar_skripsi order by tahun_ajaran asc");
					$periodeSkripsiTersediasampai=mysqli_query($con, "SELECT distinct daftar_skripsi.tahun_ajaran from daftar_skripsi order by tahun_ajaran asc");
				?>
				<div class="col-md-2 col-sm-12 col-xs-12">
				Dari Periode :
				<div class="form-group">
					<select class="form-control" id="dariPeriodeSkripsi" name="dariPeriodeSkripsi" >
						<option>--Pilih Periode--</option>
						<?php
							while($rPeriodeSkripsiTersediadari=$periodeSkripsiTersediadari->fetch_object()){ ?>
									<option value="<?php echo $rPeriodeSkripsiTersediadari->tahun_ajaran;?>"> <?php echo $rPeriodeSkripsiTersediadari->tahun_ajaran;?>
									</option>
								<?php 
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				Sampai Periode :
				<div class="form-group">
					<select class="form-control" id="sampaiPeriodeSkripsi" name="sampaiPeriodeSkripsi" >
						<option>--Pilih Periode--</option>
						<?php
							while($rPeriodeSkripsiTersediasampai=$periodeSkripsiTersediasampai->fetch_object()){ ?>
									<option value="<?php echo $rPeriodeSkripsiTersediasampai->tahun_ajaran;?>"> <?php echo $rPeriodeSkripsiTersediasampai->tahun_ajaran;?>
									</option>
								<?php 
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				<div class="form-group">
					<br>
					<input class="form-control btn btn-info" type="submit" name="searchPeriodeSkripsi" value="Search" >
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				<div class="form-group">
					<br>
					
					 <input class="form-control btn btn-danger" type="button" name="reset" value="Reset" onclick="window.location.href='index.php'" />
					 
				</div>
			</div>
			<?php
				if ($_SERVER['REQUEST_METHOD'] === 'POST'){
					if(isset($_POST['searchPeriodeSkripsi'])) {
						$sampaiPeriodeSkripsi=$_POST['sampaiPeriodeSkripsi'];
						$dariPeriodeSkripsi=$_POST['dariPeriodeSkripsi'];
						if(($dariPeriodeSkripsi!='')&&($sampaiPeriodeSkripsi!='')){
							$jmlSkripsiLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where (daftar_skripsi .tahun_ajaran >= '".$dariPeriodeSkripsi."' and daftar_skripsi .tahun_ajaran <= '".$sampaiPeriodeSkripsi."') and uji_skripsi.is_test='sudah' and uji_skripsi.is_lulus='1' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
							$jmlSkripsiTidakLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where (daftar_skripsi .tahun_ajaran >= '".$dariPeriodeSkripsi."' and daftar_skripsi .tahun_ajaran <= '".$sampaiPeriodeSkripsi."') and uji_skripsi.is_lulus='0' and uji_skripsi.is_test='sudah' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
							$periodeSkripsi=mysqli_query($con,"SELECT distinct daftar_skripsi.tahun_ajaran from daftar_skripsi where (daftar_skripsi.tahun_ajaran BETWEEN '".$dariPeriodeSkripsi."' AND '".$sampaiPeriodeSkripsi."') order by tahun_ajaran");
						}else{
							$jmlSkripsiLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where uji_skripsi.is_lulus='1' and uji_skripsi.is_test='sudah' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
							$jmlSkripsiTidakLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where uji_skripsi.is_lulus='0' and uji_skripsi.is_test='sudah' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
							$periodeSkripsi=mysqli_query($con,"SELECT distinct daftar_skripsi.tahun_ajaran from daftar_skripsi order by tahun_ajaran");
						}
						
					}else{
						$jmlSkripsiLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where uji_skripsi.is_lulus='1' and uji_skripsi.is_test='sudah' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
						$jmlSkripsiTidakLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where uji_skripsi.is_lulus='0' and uji_skripsi.is_test='sudah' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
						$periodeSkripsi=mysqli_query($con,"SELECT distinct daftar_skripsi.tahun_ajaran from daftar_skripsi order by tahun_ajaran");
					}
				}else{
					$jmlSkripsiLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where uji_skripsi.is_lulus='1' and uji_skripsi.is_test='sudah' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
					$jmlSkripsiTidakLulus=mysqli_query($con, "SELECT count(daftar_skripsi.nim) as jumlah, daftar_skripsi.tahun_ajaran from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join uji_kelayakan on uji_kelayakan.nim=daftar_skripsi.nim where uji_skripsi.is_lulus='0' and uji_skripsi.is_test='sudah' and uji_kelayakan.is_lulus='1' GROUP by tahun_ajaran");
					$periodeSkripsi=mysqli_query($con,"SELECT distinct daftar_skripsi.tahun_ajaran from daftar_skripsi order by tahun_ajaran");
				}
				
			?>
					<div class="chart-container">
				        <canvas id="graphSkripsi" style="position: relative; height:150px; width:500px;"></canvas>
				    </div>
				    <script>
				        $(document).ready(function () {
				            showGraphSkripsi();
				        });
				        function showGraphSkripsi(){
		                    var tahun_ajaran = [];
		                    var lulus = [];
		                    var tidak_lulus = [];
<?php  
		                    while($rPeriodeSkripsi=$periodeSkripsi->fetch_object()){
?>
		                    	tahun_ajaran.push("<?= $rPeriodeSkripsi->tahun_ajaran?>");				
<?php
		                    }
				            while($rJmlSkripsiLulus=$jmlSkripsiLulus->fetch_object()){
?>
				                for (var i = 0 ; i < tahun_ajaran.length; i++) {
				                    if("<?= $rJmlSkripsiLulus->tahun_ajaran?>" == tahun_ajaran[i]){
				                    	lulus[i]=("<?= $rJmlSkripsiLulus->jumlah?>");
				                    }else{
				                    	if (!lulus[i]) {
				                    		lulus[i]=("");	
				                    	}
				                    }
				                }		                    		
<?php
				            }
				            while($rJmlSkripsiTidakLulus=$jmlSkripsiTidakLulus->fetch_object()){
?>
				                for (var i = 0 ; i < tahun_ajaran.length; i++) {
				                    if("<?= $rJmlSkripsiTidakLulus->tahun_ajaran?>" == tahun_ajaran[i]){
				                    	tidak_lulus[i]=("<?= $rJmlSkripsiTidakLulus->jumlah?>");
					                }else{
					                    if (!tidak_lulus[i]) {
					                    	tidak_lulus[i]=("");	
					                	}
					                }
					            }		                    		
<?php
				            }
?>
				            var chartdata = {
				                labels: tahun_ajaran,
				                datasets: [
					                {
		                                label: 'LULUS',
		                                backgroundColor: '#87CEFA',
		                                borderColor: '#87CEEB',
		                                hoverBackgroundColor: '#6495ED',
		                                hoverBorderColor: '#4682B4',
		                                data: lulus
		                            },
		                            {
		                                label: 'TIDAK LULUS',
		                                backgroundColor: '#FFA07A',
		                                borderColor: '#FA8072',
		                                hoverBackgroundColor: '#F08080',
		                                hoverBorderColor: '#CD5C5C',
		                                data: tidak_lulus
		                            }
		                        ]
		                    };
		                    var graphTarget = $("#graphSkripsi");
		                    var barGraph = new Chart(graphTarget, {
		                        type: 'bar',
		                        data: chartdata
		                    });
				                
				        }
				    </script>
				</div>
			</div>
        </div>
    </div>
</form>
</body>              
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:../login/logout.php");
	}
?>