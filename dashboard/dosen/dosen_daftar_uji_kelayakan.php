<?php		
  require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		include_once('../sidebar.php');
		ini_set('display_errors', 1);
		$nip_saya=$rDosen->nip;
		$dari_tgl='';
		$sampai_tgl='';
		$periodeTersedia=mysqli_query($con, "SELECT distinct daftar_uji_kelayakan.tahun_ajaran from daftar_uji_kelayakan inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim where nip1='".$nip_saya."' or nip2='".$nip_saya."' order by tahun_ajaran desc");
?>
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
			var kopsurat = '<body><h2 style="text-align:center;">DAFTAR TUGAS RISET II<br>DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</h2><hr><hr><br> <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"><\/script>';
			var tes="<script>var element = document.getElementById('myTable_length'); element.parentNode.removeChild(element); var element1 = document.getElementById('myTable_filter');element1.parentNode.removeChild(element1); var element2 = document.getElementById('tfoot_search');element2.parentNode.removeChild(element2); var element3 = document.getElementById('myTable_info');element3.parentNode.removeChild(element3); var element4 = document.getElementById('myTable_paginate');element4.parentNode.removeChild(element4); var element5 = document.getElementById('header_tools');element5.parentNode.removeChild(element5);$('.tools').remove();<\/script> ";

			win.document.write(header);
			win.document.write(kopsurat);
			win.document.write($(".printArea").html());
			win.document.write(tes);
			win.document.write("<\/body><\/html>");
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
        // Add event listeners to the two range filtering inputs
	    $('#dari_tgl, #sampai_tgl').keyup( function() {
        	table.draw();
        	table.DataTable();
	    } );
	});
</script>
<!-- /. ROW  -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<!-- /. ROW  -->
<div class="row " >
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="row panel-heading" style="margin: 0;">
			   Daftar Mahasiswa yang mendaftar Uji Kelayakan 
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
							<option>--Pilih Periode--</option>
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
						 <input class="form-control btn btn-danger" type="button" name="reset" value="Reset" onclick="window.location.href='dosen_daftar_tr2.php'" />
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
					<table id ="myTable" class="display" style="width: 100%">
						<thead align="center">
							<tr align="center">
								<th >NIM</th>
								<th >Nama</th>
								<th >Pembimbing 1</th>
								<th >Pembimbing 2</th>
								<th >Pembimbing 3</th>
								<th  align="center">Judul</th>
								<th id='header_tools'></th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >NIM</th>
								<th >Nama</th>
								<th >Pembimbing 1</th>
								<th >Pembimbing 2</th>
								<th >Pembimbing 3</th>
								<th  align="center">Judul</th>
								<th ></th>
							</tr>
						</tfoot>
						<tbody >
						<?php	
						if(isset($_POST['search_tgl'])) {
								if($periode!='--Pilih Periode--'){
									$show_pendaftar = "SELECT mahasiswa.nim, nama, nip1, nip2, nip3, judul,id_daftar_uji_kelayakan FROM daftar_uji_kelayakan INNER JOIN mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim INNER JOIN daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim where (nip1='".$nip_saya."' OR nip2='".$nip_saya."') AND daftar_uji_kelayakan.tahun_ajaran='$periode' ORDER BY daftar_uji_kelayakan.id_daftar_uji_kelayakan";
								}elseif(($dari_tgl!='')&&($sampai_tgl!='')){
									$show_pendaftar = "SELECT mahasiswa.nim, nama, nip1, nip2, nip3, judul,id_daftar_uji_kelayakan FROM daftar_uji_kelayakan INNER JOIN mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim INNER JOIN daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim where (nip1='".$nip_saya."' OR nip2='".$nip_saya."') AND (daftar_uji_kelayakan.tgl_daftar BETWEEN '$dari_tgl' AND '$sampai_tgl') ORDER BY daftar_uji_kelayakan.id_daftar_uji_kelayakan";
								}
						}else{
							$show_pendaftar = "SELECT mahasiswa.nim, nama, nip1, nip2, nip3, judul,id_daftar_uji_kelayakan FROM daftar_uji_kelayakan INNER JOIN mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim INNER JOIN daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim where nip1='".$nip_saya."' OR nip2='".$nip_saya."' ORDER BY daftar_uji_kelayakan.id_daftar_uji_kelayakan";
						}
							$fshow_pendaftar = $con->query( $show_pendaftar );
							if(!$fshow_pendaftar){
								die('Could not connect to database : <br/>'.$con->error);
							}
							while($rshow_pendaftar = $fshow_pendaftar->fetch_object()){
								echo "<tr>";
								echo "<td>".$rshow_pendaftar->nim."</td>";
								echo "<td>".$rshow_pendaftar->nama."</td>";
								$nama_pembimbing1="SELECT * FROM dosen WHERE dosen.nip='".$rshow_pendaftar->nip1."' ";
								$fnama_pembimbing1=$con->query($nama_pembimbing1);
								while($rnama_pembimbing1=$fnama_pembimbing1->fetch_object()){
									echo "<td>".$rnama_pembimbing1->nama_dosen."</td>";
								}
								$nama_pembimbing2="SELECT * FROM dosen WHERE dosen.nip='".$rshow_pendaftar->nip2."' ";
								$fnama_pembimbing2=$con->query($nama_pembimbing2);
								$rnama_pembimbing2=$fnama_pembimbing2->fetch_object();
								if(!$rnama_pembimbing2){
									echo "<td></td>";
								}else{
									echo "<td>".$rnama_pembimbing2->nama_dosen."</td>";	
								} 
								$nama_pembimbing3="SELECT * FROM pembimbing_luar WHERE pembimbing_luar.nip='".$rshow_pendaftar->nip3."' ";
								$fnama_pembimbing3=$con->query($nama_pembimbing3);
								$rnama_pembimbing3=$fnama_pembimbing3->fetch_object();
								if(!$rnama_pembimbing3){
									echo "<td></td>";
								}else{
									echo "<td>".$rnama_pembimbing3->nama."</td>";	
								} 
								echo "<td>".$rshow_pendaftar->judul."</td>";
								echo "<td align='center' class='tools'>
									<a href='detail_ujian_kelayakan.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_uji_kelayakan."'><i class='fa fa-info-circle' title='Lihat Detail'></i></a>&nbsp;
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
    header("Location:../");
  }
?>