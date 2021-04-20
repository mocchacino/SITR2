<?php	
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');

		$dari_tgl='';
		$sampai_tgl='';

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
			var header = '<html><head><title>Print Daftar Mahasiswa Tugas Akhir</title></head><style> table,tr,th,td { border: 1px solid black; border-collapse: collapse;}</style>';
			var kopsurat = '<body><h1 style="text-align:center;">DAFTAR TUGAS AKHIR<br>DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</h1><hr><hr><br> <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"><\/script>';
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
<title>Daftar Dosen</title>
<!-- /. ROW  -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class="row " >
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="row panel-heading" style="margin: 0;">
				Daftar Dosen
				<button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
			</div>
			
			<div class="panel-body ">
				<div class="col-sm-12 table-responsive ">
				<div class="printArea">
					<table id ="myTable" class="display printArea" style="width: 100%">
						<thead align="center">
							<tr align="center">
								<th >No</th>
								<th >NIP</th>
								<th >Nama</th>
								<th >Alamat</th>
								<th >Nomor Telepon</th>
								<th >E-mail</th>
								<th >Laboratorium</th>
								<th >Topik</th>
								<th id='header_tools'></th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >No</th>
								<th >NIP</th>
								<th >Nama</th>
								<th >Alamat</th>
								<th >Nomor Telepon</th>
								<th >E-mail</th>
								<th >Laboratorium</th>
								<th >Topik</th>
								<th id='header_tools'></th>
							</tr>
						</tfoot>
						<tbody width='100%'>
						<?php	
						$i=1;
						$showDosen = "SELECT * FROM dosen order by nip";
							
							$fshowDosen = $con->query( $showDosen );
							if(!$fshowDosen){
								die('Could not connect to database : <br/>'.$con->error);
							}
							$nomor=1;
							
							while($rshowDosen = $fshowDosen->fetch_object()){
								echo "<tr>";
								echo "<td align='center'>".$i."</td>";$i++;
								echo "<td>".$rshowDosen->nip."</td>";
								echo "<td>".$rshowDosen->nama_dosen."</td>";
								echo "<td>".$rshowDosen->alamat."</td>";
								echo "<td>".$rshowDosen->no_telp."</td>";
								echo "<td>".$rshowDosen->email."</td>";
								switch ($rshowDosen->idlab) {
									case '1':
										$lab='Biokimia';
										break;
									case '2':
										$lab='Kimia Analitik';
										break;
									case '3':
										$lab='Kimia Anorganik';
										break;
									case '4':
										$lab='Kimia Fisik';
										break;
									case '5':
										$lab='Kimia Organik';
										break;
								}
								echo "<td>".$lab."</td>";
								if($rshowDosen->topik != '') echo "<td>".$rshowDosen->topik."</td>";
								else echo "<td></td>";
								echo "<td class='tools' id='tools' align='center'>
									<a data-toggle='modal' 
										data-target='#repass' 
										data-nip='".$rshowDosen->nip."'
										title='Reset Password'
										class='button_reset'><i class='fa fa-key'></i></a>
									<a href='edit_dosen.php?nip=".$rshowDosen->nip."' title='Edit'><i class='fa fa-pencil-square-o'></i></a>
									<a data-toggle='modal' 
										data-target='#myModal' 
										data-nip='".$rshowDosen->nip."'
										title='Hapus'
										class='button_hapus'><i class='fa fa-trash-o'></i></a>
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
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
<div class="modal-dialog modal-md">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Hapus Data Dosen</h4>
    </div>
    <div class="modal-body">
      <p>Apakah anda yakin untuk menghapus data Dosen?</p>
      <p id="hapus_idpetugas"></p>
    </div>
    <div class="modal-footer">
    <form id='form_hapus'>
    	<input type="hidden" name="nip" id="nip">
    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	<button type="submit" class="btn btn-danger" id="hapus">Hapus</button>
    </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
	$('.button_hapus').click(function(){
		$('#nip').val($(this).data('nip'));
		$('#hapus_idpetugas').html('NIP : '+ $(this).data('nip'));
	});
	$('#form_hapus').submit(function(e){
		e.preventDefault();
		var nip=$('#nip').val();
		$.ajax({
			url:'delete.php',
			type:'get',
			data:'data=dosen&nip='+nip,
			success:function(data){
				var obj = jQuery.parseJSON(data);
				$('#myModal').modal('hide');
				if(obj.status=='success'){
					alert('Hapus data sukses');
					location.reload();
				}else if(obj.status=='error') {
					alert('Tidak dapat menghapus');
				}
			},
			error:function(data){
				console.log(data);
			}
		})
	})
</script>

<div class="modal fade" id="repass" role="dialog">
<div class="modal-dialog modal-md">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Reset Password Dosen</h4>
    </div>
    <div class="modal-body">
      <p>Apakah anda yakin untuk mereset password?</p>
      <p id="reset_idpetugas"></p>
    </div>
    <div class="modal-footer">
    <form id='form_reset'>
    	<input type="hidden" name="nip" id="reset_nip">
    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	<button type="submit" class="btn btn-danger" id="reset">Reset</button>
    </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
	$('.button_reset').click(function(){
		$('#nip').val($(this).data('nip'));
		$('#reset_idpetugas').html('NIP : '+ $(this).data('nip'));
	});
	$('#form_reset').submit(function(e){
		e.preventDefault();
		var nip=$('#nip').val();
		$.ajax({
			url:'reset_password.php',
			type:'get',
			data:'data=dosen&nip='+nip,
			success:function(data){
				var obj = jQuery.parseJSON(data);
				$('#myModal').modal('hide');
				if(obj.status=='success'){
					alert('Reset password sukses');
					location.reload();
				}else if(obj.status=='error') {
					alert('Tidak dapat mereset password');
				}
			},
			error:function(data){
				console.log(data);
			}
		})
	})
</script>							
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>