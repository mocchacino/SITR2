<?php		
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'mahasiswa'){
		include_once('../sidebar.php');
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
			var header = '<html><head><title>Print Daftar Mahasiswa Skripsi</title></head><style> table,tr,th,td { border: 1px solid black; border-collapse: collapse;}</style>';
			var kopsurat = '<body><h2 style="text-align:center;">DAFTAR MAHASISWA TUGAS AKHIR<br>DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</h2><hr><hr><br>';
			var tes="<script>var element = document.getElementById('myTable_length'); element.parentNode.removeChild(element); var element1 = document.getElementById('myTable_filter');element1.parentNode.removeChild(element1); var element2 = document.getElementById('tfoot_search');element2.parentNode.removeChild(element2); var element3 = document.getElementById('myTable_info');element3.parentNode.removeChild(element3); var element4 = document.getElementById('myTable_paginate');element4.parentNode.removeChild(element4); <\/script> ";
			win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 0.5cm 0.5cm 0.5cm 0.5cm;} .page-break{page-break-after: always; }</style>');
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
        // Add event listeners to the two range filtering inputs
	    // $('#dari_tgl, #sampai_tgl').keyup( function() {
     //    	table.draw();
     //    	table.DataTable();
	    // } );
	});
</script>
<head>
	<title>Daftar Mahasiswa yang mendaftar Skripsi</title>
</head>


<!-- /. ROW  -->

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class="row " >
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row panel-heading" style="margin: 0;">
				   Daftar Mahasiswa Tugas Akhir
				    <button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
				</div>
			</div>
			<div class="panel-body ">
				<div class="col-sm-12 table-responsive ">
				<div class="printArea">
					<table id ="myTable" class="display printArea" style="width: 100%">
						<thead align="center">
							<tr align="center">
								<th >No.</th>
								<th >NIM</th>
								<th >Nama</th>
								<th  align="center">Judul</th>
								<th >Pembimbing 1</th>
								<th >Pembimbing 2</th>
								<th >Pembimbing 3</th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >No.</th>
								<th >NIM</th>
								<th >Nama</th>
								<th  align="center">Judul</th>
								<th >Pembimbing 1</th>
								<th >Pembimbing 2</th>
								<th >Pembimbing 3</th>
							</tr>
						</tfoot>
						<tbody >
						<?php	
						$no=1;
						$periodeSekarang=mysqli_query($con, "SELECT deskripsi as periode_sekarang from misc where judul='tahun_ajaran' ");
						$rPeriodeSekarang=$periodeSekarang->fetch_assoc();
	
						$show_pendaftar = "SELECT * FROM daftar_skripsi INNER JOIN mahasiswa on mahasiswa.nim=daftar_skripsi.nim where tahun_ajaran='".$rPeriodeSekarang['periode_sekarang']."' ORDER BY daftar_skripsi.nim";
						
							
							$fshow_pendaftar = $con->query( $show_pendaftar );
							if(!$fshow_pendaftar){
								die('Could not connect to database : <br/>'.$con->error);
							}
							echo '<h2 align="center">Daftar Mahasiswa Skripsi Tahun ';
							for ($i=0; $i <9 ; $i++) { 
								echo $rPeriodeSekarang['periode_sekarang'][$i];
							}
							
							echo ( $rPeriodeSekarang['periode_sekarang'][10] =='1')?' Semester Ganjil':' Semester Genap';
							
							echo '</h2>';
							echo '<div class="printArea">';
							while($rshow_pendaftar = $fshow_pendaftar->fetch_object()){
								echo "<tr>";
								echo "<td>".$no."</td>";$no++;
								echo "<td>".$rshow_pendaftar->nim."</td>";
								echo "<td>".$rshow_pendaftar->nama."</td>";
								
								echo "<td>".$rshow_pendaftar->judul."</td>";
								if($rshow_pendaftar->nip1){
									$pembimbing1=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rshow_pendaftar->nip1."' ");
									$rPembimbing1=$pembimbing1->fetch_object();
									echo "<td>".$rPembimbing1->nama_dosen."</td>";
								}else{
									echo "<td></td>";
								}

								if($rshow_pendaftar->nip2){
									$pembimbing2=mysqli_query($con, "SELECT nama_dosen from dosen where nip='".$rshow_pendaftar->nip2."' ");
									$rPembimbing2=$pembimbing2->fetch_object();
									echo "<td>".$rPembimbing2->nama_dosen."</td>";
								}else{
									echo "<td></td>";
								}
								
								if($rshow_pendaftar->nip3){
									$pembimbing3=mysqli_query($con, "SELECT nama from pembimbing_luar where nip='".$rshow_pendaftar->nip3."' ");
									$rPembimbing3=$pembimbing3->fetch_object();
									echo "<td>".$rPembimbing3->nama."</td>";
								}else{
									echo "<td></td>";
								}
								
								
								echo '</div>';
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