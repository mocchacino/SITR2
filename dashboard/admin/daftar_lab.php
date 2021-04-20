<?php
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
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
<style type="text/css" media="print">
   .no-print { display: none; }
</style>
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
			var header = '<html><head><title>Print Pembimbing Luar</title></head><style> table,tr,th,td { border: 1px solid black; border-collapse: collapse;}</style>';
			var kopsurat = '<body><h1 style="text-align:center;">DAFTAR LABORATORIUM<br>DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</h1><hr><hr><br><script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"><\/script>';
			var tes="<script>var element = document.getElementById('myTable_length'); element.parentNode.removeChild(element); var element1 = document.getElementById('myTable_filter');element1.parentNode.removeChild(element1); var element2 = document.getElementById('tfoot_search');element2.parentNode.removeChild(element2); var element3 = document.getElementById('myTable_info');element3.parentNode.removeChild(element3); var element4 = document.getElementById('myTable_paginate');element4.parentNode.removeChild(element4); var element5 = document.getElementById('header_tools');element5.parentNode.removeChild(element5);$('.tools').remove(); <\/script> ";

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
			<div class="row panel-heading" style="margin: 0;">
			   Daftar Laboratorium
			<button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
			</div>

			<div class="panel-body ">
				<div class="col-sm-12 table-responsive ">
				<div class="printArea">
					<table id ="myTable" class="display" style="width: 100%">
						<thead align="center">
							<tr align="center">
								<th >No</th>
								<th >Nama</th>
								<th >E-mail</th>
								<th >KBK</th>
								<th id='header_tools'></th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >No</th>
								<th >Nama</th>
								<th >E-mail</th>
								<th >KBK</th>
								<th></th>
							</tr>
						</tfoot>
						<tbody >
						<?php	
						$i=1;
						$showAdmin = mysqli_query($con,"SELECT * from lab order by idlab");
							while($rshowAdmin = $showAdmin->fetch_object()){
								echo "<tr>";
								echo "<td align='center'>".$i."</td>";$i++;
								echo "<td>".$rshowAdmin->nama_lab."</td>";
								echo "<td>".$rshowAdmin->admin."</td>";
								$showKbk = mysqli_query($con, "SELECT nama_dosen FROM dosen where nip = '".$rshowAdmin->nip."'");
								$rshowKbk = $showKbk->fetch_object();
								echo "<td>".$rshowKbk->nama_dosen."</td>";
								echo "<td class='tools' id='tools' align='center'>
									<a data-toggle='modal' 
										data-target='#repass' 
										data-email='".$rshowAdmin->admin."'
										title='Reset Password'
										class='button_reset'><i class='fa fa-key'></i></a>
									<a href='edit_lab.php?idlab=".$rshowAdmin->idlab."' title='Edit'><i class='fa fa-pencil-square-o'></i></a>
									<a data-toggle='modal' 
										data-target='#myModal' 
										data-email='".$rshowAdmin->admin."'
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
      <h4 class="modal-title">Hapus Data Laboratorium</h4>
    </div>
    <div class="modal-body">
      <p>Apakah anda yakin untuk menghapus data Laboratorium?</p>
      <p id="hapus_idpetugas"></p>
    </div>
    <div class="modal-footer">
    <form id='form_hapus'>
    	<input type="hidden" name="email" id="email">
    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	<button type="submit" class="btn btn-danger" id="hapus">Hapus</button>
    </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
	$('.button_hapus').click(function(){
		$('#email').val($(this).data('email'));
		$('#hapus_idpetugas').html('Email : '+ $(this).data('email'));
	});
	$('#form_hapus').submit(function(e){
		e.preventDefault();
		var email=$('#email').val();
		$.ajax({
			url:'delete.php',
			type:'get',
			data:'data=laboratorium&email='+email,
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
      <h4 class="modal-title">Reset Password Admin Laboratorium</h4>
    </div>
    <div class="modal-body">
      <p>Apakah anda yakin untuk mereset password?</p>
      <p id="reset_idpetugas"></p>
    </div>
    <div class="modal-footer">
    <form id='form_reset'>
    	<input type="hidden" name="email" id="reset_email">
    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	<button type="submit" class="btn btn-danger" id="reset">Reset</button>
    </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
	$('.button_reset').click(function(){
		$('#email').val($(this).data('email'));
		$('#reset_idpetugas').html('E-mail : '+ $(this).data('email'));
	});
	$('#form_reset').submit(function(e){
		e.preventDefault();
		var email=$('#email').val();
		$.ajax({
			url:'reset_password.php',
			type:'get',
			data:'data=lab&email='+email,
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
