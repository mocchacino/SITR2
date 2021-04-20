<?php 
	session_start();
	// date_default_timezone_set("Asia/Bangkok"); 
	// setlocale(LC_ALL, 'id_ID');

	ini_set('display_errors', 1);
	include_once('../sidebar.php');
	$status = $_SESSION['sip_status'];
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'laboratorium'){

	$jmlBlmAdaNilai=mysqli_query($con,"SELECT count(uji_kelayakan.nim) as jml from uji_kelayakan inner join tr1 on tr1.nim=uji_kelayakan.nim where uji_kelayakan.is_test='belum' and (uji_kelayakan.jadwal <= now() and uji_kelayakan.jadwal != '0000-00-00 00:00:00') and idlab_tr1='$idlab'");
	$rJmlBlmAdaNilai=$jmlBlmAdaNilai->fetch_object();
	$jmlLulus=mysqli_query($con,"SELECT count(uji_kelayakan.nim) as jml from uji_kelayakan inner join tr1 on tr1.nim=uji_kelayakan.nim where uji_kelayakan.is_test='sudah' and uji_kelayakan.is_lulus='1' and idlab_tr1='$idlab'");
	$rJmlLulus=$jmlLulus->fetch_object();
	$jmlTidakLulus=mysqli_query($con,"SELECT count(uji_kelayakan.nim) as jml from uji_kelayakan inner join tr1 on tr1.nim=uji_kelayakan.nim where uji_kelayakan.is_test='sudah' and uji_kelayakan.is_lulus='0' and idlab_tr1='$idlab'");
	$rJmlTidakLulus=$jmlTidakLulus->fetch_object();

	$endUjiKelayakan=mysqli_query($con, "SELECT akhir FROM waktu where nama='UK' ORDER BY akhir DESC");
	if(mysqli_num_rows($endUjiKelayakan) > 0){
		$waktuSelesaiUjiKelayakan = mysqli_fetch_assoc($endUjiKelayakan);
		$endDateUjiKelayakan = $waktuSelesaiUjiKelayakan['akhir'];
		if($endDateUjiKelayakan >= date("Y-m-d")){
			$noteUK=" dibuka sampai tanggal ".strftime("%e %B %Y",strtotime($endDateUjiKelayakan));
		}
	}
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
<script type="text/javascript">				
function createCountDown(elementIdCounterUK){
	// Set the date we're counting down to
    var UjiKelakyakanExpired = '<?php echo $endDateUjiKelayakan; ?>';
    var UjiKelayakanCountDownDate = new Date(UjiKelakyakanExpired+' GMT+0700');
    UjiKelayakanCountDownDate.setDate(UjiKelayakanCountDownDate.getDate()+1);
    UjiKelayakanCountDownDate = UjiKelayakanCountDownDate.getTime();


    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        var now = new Date().getTime();
        // Find the distance between now an the count down date
        var UjiKelayakanDistance = UjiKelayakanCountDownDate - now;
        
        // Time calculations for days, hours, minutes and seconds
        //uji kelayakan
        var UjiKelayakanDays = Math.floor(UjiKelayakanDistance / (1000 * 60 * 60 * 24));
        var UjiKelayakanHours = Math.floor((UjiKelayakanDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var UjiKelayakanMinutes = Math.floor((UjiKelayakanDistance % (1000 * 60 * 60)) / (1000 * 60));
        var UjiKelayakanSeconds = Math.floor((UjiKelayakanDistance % (1000 * 60)) / 1000);
        
        // Output the result in an element with id
       document.getElementById(elementIdCounterUK).innerHTML = UjiKelayakanDays + " Hari " + UjiKelayakanHours + " Jam " + UjiKelayakanMinutes + " Menit " + UjiKelayakanSeconds + "s ";
        
        // If the count down is over, write some text 
        if ((UjiKelayakanDistance < 0)||(isNaN(UjiKelayakanDistance))) {
            clearInterval(x);
            document.getElementById(elementIdCounterUK).innerHTML = "Pendaftaran Ditutup";
        }
    }, 1000);
}

</script>

<div class="row">
    <div class="col-md-12">
        <h2>Dashboard</h2>   
        <h5>Selamat datang <b>
        <?php if($status=="laboratorium") echo $rLaboratorium->nama_lab;
        ?>
        </b>. <small><i>Anda masuk sebagai <b>Admin Laboratorium</b></i></small></h5>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
    <?php
		if (isset($noteUK)){
			echo '<div class="alert alert-info alert-dismissible fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
			echo '<strong>Pendaftaran Uji Kelayakan</strong>';
			echo $noteUK;
			echo '</div>';
		}
?>
	</div>
</div>
<hr />
<div class="row">
	<script type="text/javascript">
		createCountDown('countdownUjiKelayakan');
	</script>
	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading" align="center">Countdown Pendaftaran </div>
			<div class="panel-body">
				<p id='countdownUjiKelayakan' align="center" style="font-size: 20pt;"></p>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="panel panel-default" align="center">
			<div class="panel-heading">Belum Dinilai</div>
			<div class="panel-body">
				<p align="center" style="font-size: 20pt;"><?php echo $rJmlBlmAdaNilai->jml;?></p>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="panel panel-default" align="center">
			<div class="panel-heading">Jumlah Lulus</div>
			<div class="panel-body">
				<p align="center" style="font-size: 20pt;"><?php echo $rJmlLulus->jml;?></p>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default" align="center">
			<div class="panel-heading">Jumlah Tidak Lulus</div>
			<div class="panel-body">
				<p align="center" style="font-size: 20pt;"><?php echo $rJmlTidakLulus->jml;?></p>
			</div>
		</div>
	</div>
</div>
<hr>
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
							$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where (daftar_uji_kelayakan.tahun_ajaran >= '".$dari_periode."' and daftar_uji_kelayakan.tahun_ajaran <= '".$sampai_periode."') and is_lulus='1' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
							$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where (daftar_uji_kelayakan.tahun_ajaran >= '".$dari_periode."' and daftar_uji_kelayakan.tahun_ajaran <= '".$sampai_periode."') and is_lulus='0' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
							$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where (daftar_uji_kelayakan.tahun_ajaran BETWEEN '".$dari_periode."' AND '".$sampai_periode."') and tr1.idlab_tr1='$idlab' order by tahun_ajaran");
						}else{
							$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where is_lulus='1' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
							$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where is_lulus='0' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
							$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where idlab_tr1='$idlab' order by tahun_ajaran");
						}
						
					}else{
						$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where is_lulus='1' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
						$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where is_lulus='0' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
						$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where idlab_tr1='$idlab' order by tahun_ajaran");
					}
				}else{
					$jmlUjiKelayakanLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where is_lulus='1' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
					$jmlUjiKelayakanTidakLulus=mysqli_query($con, "SELECT count(daftar_uji_kelayakan.nim) as jumlah, daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where is_lulus='0' and is_test='sudah' and tr1.idlab_tr1='$idlab' GROUP by tahun_ajaran");
					$periode=mysqli_query($con,"SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_uji_kelayakan.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where idlab_tr1='$idlab' order by tahun_ajaran");
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
</body>              
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:../login/logout.php");
	}
?>			