<?php	
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');
		$dari_tgl='';
		$sampai_tgl='';
		$periodeTersedia=mysqli_query($con, "SELECT distinct tahun_ajaran from daftar_uji_kelayakan inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim order by tahun_ajaran desc");

?>
<head>
	<title>Daftar Nilai Mahasiswa</title>
</head>
<style type="text/css">
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
	function getQueryVariable(variable)
	{
		   var query = window.location.search.substring(1);
		   var vars = query.split("&");
		   for (var i=0;i<vars.length;i++) {
				   var pair = vars[i].split("=");
				   if(pair[0] == variable){return pair[1];}
		   }
		   return(false);
	}
	
	$(document).ready(function(){
		var table=$('#myTable').DataTable();
		$('#print').click(function(){
			var win = window.open('','printwindow');
			var header = '<html><head><title>Print daftar peserta TRII</title></head><style> table,tr,th,td { border: 1px solid black; border-collapse: collapse;}</style>';
			var kopsurat = '<body><h1 style="text-align:center;">DAFTAR TUGAS RISET II<br>DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</h1><hr><hr><br>';
			var tes="<script>var element = document.getElementById('myTable_length'); element.parentNode.removeChild(element); var element1 = document.getElementById('myTable_filter');element1.parentNode.removeChild(element1); var element2 = document.getElementById('tfoot_search');element2.parentNode.removeChild(element2); var element3 = document.getElementById('myTable_info');element3.parentNode.removeChild(element3); var element4 = document.getElementById('myTable_paginate');element4.parentNode.removeChild(element4); var element5 = document.getElementById('header_tools');element5.parentNode.removeChild(element5); var element6 = document.getElementById('tools');element6.parentNode.removeChild(element6); <\/script> ";

			win.document.write(header);
			win.document.write(kopsurat);
			win.document.write($(".printArea").html());
			win.document.write(tes);
			win.document.write('<\/body><\/html>');
			win.document.close();
			setTimeout(function(){
				win.print();
				//win.close();
			},1000);
		})
		//kotak search
    	$('#myTable tfoot th').each( function () {
	        var title = $(this).text();
	         $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
	    } );
	    // Apply the search
	    table.columns().every( function () {
	        var that = this;
	 
	        $( 'input', this.footer() ).on( 'keyup change', function () {
	            if ( that.search() !== this.value ) {
	                that
                    .search( this.value )
                    .draw();
	            }
	        } );
	    } );
	});
</script>
<!-- /. ROW  -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class="row " >
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="row panel-heading" style="margin: 0;">
				<a href="home_lab.php" class="btn btn-primary"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
			   Daftar Nilai Mahasiswa Uji Kelayakan
			   <button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
			</div>

			<div class="well clearfix">
			<div class="col-md-2 col-sm-12 col-xs-12">
				Dari Tanggal :
				<div class="form-group">
					<input class="form-control" id="dari_tgl" name="dari_tgl" type="date" />
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				Sampai Tanggal :
				<div class="form-group">
					<input class="form-control" id="sampai_tgl" name="sampai_tgl" type="date" />
				</div>
			</div>
			<div class="col-md-1 col-sm-12 col-xs-12">
				<br>atau
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				Periode :
				<div class="form-group">
					<select class="form-control" id="periode" name="periode" >
						<option>-Pilih Periode-</option>
						<?php
							while($rPeriodeTersedia=$periodeTersedia->fetch_object()){ ?>
									<option value="<?php echo $rPeriodeTersedia->tahun_ajaran;?>"> <?php echo $rPeriodeTersedia->tahun_ajaran;?>
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
					<!-- <a href="daftar_uji_kelayakan.php">
					   <input class="form-control" type="button" value="Reset" />
					</a>
					 -->
					 <input class="form-control btn btn-danger" type="button" name="reset" value="Reset" onclick="window.location.href='daftar_nilai_uji_kelayakan.php'" />
					 <!-- <input class="form-control" type="button" name="reset" value="Reset" /> -->
				</div>
			</div>
			<?php
				if ($_SERVER['REQUEST_METHOD'] === 'POST'){
					$dari_tgl=$_POST['dari_tgl'];
					$sampai_tgl=$_POST['sampai_tgl'];
					$periode=$_POST['periode'];

				}
			?>
			</div>
			<div class="panel-body ">
				<div class="col-sm-12 table-responsive ">
				<div class="printArea">
					<table id ="myTable" class="display printArea" style="width: 100%">
						<thead align="center">
							<tr align="center">
								<th >No</th>
								<th >NIM</th>
								<th >Nama</th>
								<th >Tanggal Ujian</th>
								<th >Status</th>
								<th >Nilai Huruf</th>
								<th id='header_tools'></th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >No</th>
								<th >NIM</th>
								<th >Nama</th>
								<th >Tanggal Ujian</th>
								<th >Status</th>
								<th >Nilai Huruf</th>
								<th ></th>
							</tr>
						</tfoot>
						<tbody align="center" width='100%'>
						<?php	
						if(isset($_POST['search_tgl'])) {
							if(empty($dari_tgl) && empty($sampai_tgl)){
								if($periode=='-Pilih Periode-'){
									$show_pendaftar="SELECT DISTINCT mahasiswa.nim, mahasiswa.nama,jadwal, tempat,id_uji_kelayakan,is_lulus, is_test from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim where is_test='sudah'";
								}else{
									$show_pendaftar = "SELECT DISTINCT mahasiswa.nim, mahasiswa.nama,jadwal, tempat,id_uji_kelayakan,is_lulus, is_test from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim where is_test='sudah' and daftar_uji_kelayakan.tahun_ajaran='$periode' ";
								}
							}else{
								$show_pendaftar = "SELECT DISTINCT mahasiswa.nim, mahasiswa.nama,jadwal, tempat,id_uji_kelayakan,is_lulus, is_test from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim where is_test='sudah' and (cast(uji_kelayakan.jadwal as date) BETWEEN '$dari_tgl' AND '$sampai_tgl')";
							}
						}else{
							$show_pendaftar="SELECT DISTINCT mahasiswa.nim, mahasiswa.nama,jadwal, tempat,id_uji_kelayakan,is_lulus, is_test from daftar_uji_kelayakan inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=daftar_uji_kelayakan.id_daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim where is_test='sudah' ";
							// $show_pendaftar = "SELECT profil.nim, profil.nama,judul.judul_tr2, penguji_kelayakan.jadwal, penguji_kelayakan.tempat, bimbingan.nip1, bimbingan.nip2, GROUP_CONCAT(penguji_kelayakan.nip_penguji_kelayakan) as 'penguji' FROM daftar_uji_kelayakan INNER JOIN profil on profil.nim=daftar_uji_kelayakan.nim INNER JOIN judul on judul.nim=daftar_uji_kelayakan.nim INNER JOIN bimbingan on bimbingan.nim=daftar_uji_kelayakan.nim LEFT JOIN penguji_kelayakan on penguji_kelayakan.nim=bimbingan.nim GROUP by profil.nim ";
						}
							
							$fshow_pendaftar = $con->query( $show_pendaftar );
							if(!$fshow_pendaftar){
								die('Could not connect to database : <br/>'.$con->error);
							}
							$nomor=1;
							echo '<div class="printArea">';
							while($rshow_pendaftar = $fshow_pendaftar->fetch_object()){
								echo "<tr >";
								echo "<td >".$nomor."</td>";$nomor++;
								echo "<td >".$rshow_pendaftar->nim."</td>";
								echo "<td >".$rshow_pendaftar->nama."</td>";
								echo "<td >".$rshow_pendaftar->jadwal."</td>";
								if($rshow_pendaftar->is_lulus == '1'){
									echo "<td >LAYAK</td>";
								}else echo "<td > TIDAK LAYAK</td>";

								$jml_nilai=mysqli_query($con,"SELECT count(nip_penguji_kelayakan) as jml_penguji, sum(nilai) as jumlah_nilai from penguji_kelayakan where id_uji_kelayakan='".$rshow_pendaftar->id_uji_kelayakan."' ");
								$rjml_nilai=$jml_nilai->fetch_object();
								$nilai_rata= ($rjml_nilai->jumlah_nilai)/($rjml_nilai->jml_penguji);
								if($rshow_pendaftar->is_test == 'sudah'){
									if($rshow_pendaftar->is_lulus == '0'){
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
								echo "<td >".$nilai_huruf."</td>";
								
								//tools
								echo "<td id='tools' align='center'>
									<a href='tampilkan_nilai_uji_kelayakan.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_uji_kelayakan."'><i class='fa fa-eye'></i> Tampilkan Nilai</a>
								 </td>";

								echo "</tr>";
							}			
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

								
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>