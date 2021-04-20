<?php 
session_start();
// date_default_timezone_set("Asia/Jakarta"); 
// setlocale(LC_ALL, 'id_ID');
ini_set('display_errors', 1);
include_once('../sidebar.php');
$status = $_SESSION['sip_status'];
if (!isset($_SESSION['sip_masuk_aja'])){
	header("Location:../login/login.php");
}elseif($status == 'mahasiswa'){
	$endTugasRiset2=mysqli_query($con, "SELECT awal, akhir FROM waktu where nama='TR2' ORDER BY akhir DESC");
	if(mysqli_num_rows($endTugasRiset2) > 0){
		$waktuSelesaiTugasRiset2 = mysqli_fetch_assoc($endTugasRiset2);
		$endDateTugasRiset2 = ($waktuSelesaiTugasRiset2['akhir']);
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

	<div class="row">
	    <div class="col-md-12">
	        <h2>Dashboard</h2>   
	        <h5>Selamat datang <b>
	        <?php if($status=="mahasiswa") echo $rMahasiswa->nama;
	        ?>
	        </b>. <small><i>Anda masuk sebagai <b>Mahasiswa</b></i></small></h5>
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

	
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:../login/logout.php");
	}
?>