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
			var header = '<html><head><title>Print daftar peserta uji kelayakan</title></head><style> table,tr,th,td { border: 1px solid black; border-collapse: collapse;}</style>';
			var kopsurat = '<body><h2 style="text-align:center;">DAFTAR MAHASISWA UJI KELAYAKAN<br>DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</h2><hr><hr><br>';
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


<!-- /. ROW  -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class="row " >
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row panel-heading" style="margin: 0;">
				   Daftar Mahasiswa yang mendaftar Uji Kelayakan 
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
								<th >Laboratorium</th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >No.</th>
								<th >NIM</th>
								<th >Nama</th>
								<th  align="center">Judul</th>
								<th >Laboratorium</th>
							</tr>
						</tfoot>
						<tbody >
						<?php	
						$no=1;
						$periodeSekarang=mysqli_query($con, "SELECT deskripsi as periode_sekarang from misc where judul='tahun_ajaran' ");
						$rPeriodeSekarang=$periodeSekarang->fetch_assoc();
	
						$show_pendaftar = "SELECT * FROM daftar_uji_kelayakan INNER JOIN mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim inner join lab on lab.idlab =tr1.idlab_tr1 where tahun_ajaran='".$rPeriodeSekarang['periode_sekarang']."' ORDER BY daftar_uji_kelayakan.nim";
							$fshow_pendaftar = $con->query( $show_pendaftar );
							if(!$fshow_pendaftar){
								die('Could not connect to database : <br/>'.$con->error);
							}
							$fshow_pendaftar = $con->query( $show_pendaftar );
							if(!$fshow_pendaftar){
								die('Could not connect to database : <br/>'.$con->error);
							}
							echo '<h2 align="center">Daftar Mahasiswa Uji Kelayakan Tahun ';
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
								echo "<td>".$rshow_pendaftar->nama_lab."</td>";
								
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