<?php	
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		include_once('../sidebar.php');

		$dari_tgl='';
		$sampai_tgl='';
		$periodeTersedia=mysqli_query($con, "SELECT distinct tahun_ajaran from daftar_skripsi order by tahun_ajaran desc");

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
<title>Daftar Mahasiswa Ujian Tugas Akhir</title>
<!-- /. ROW  -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class="row " >
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="row panel-heading" style="margin: 0;">
				Daftar Mahasiswa Ujian Tugas Akhir
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
								while($rPeriodeTersedia=$periodeTersedia->fetch_object()){ 
?>
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
						<input class="form-control btn btn-danger" type="button" name="reset" value="Reset" onclick="window.location.href='daftar_uji_skripsi.php'" />
					 
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
								<th align="center">Judul</th>
								<th >Tanggal & Waktu</th>
								<th >Tempat</th>
								<th >Pembimbing</th>
								<th >Penguji</th>
								<th id='header_tools'></th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >No</th>
								<th >NIM</th>
								<th >Nama</th>
								<th >Judul</th>
								<th >Tanggal & Waktu</th>
								<th >Tempat</th>
								<th >Pembimbing</th>
								<th >Penguji</th>
								<th ></th>
							</tr>
						</tfoot>
						<tbody width='100%'>
						<?php	
						if(isset($_POST['search_tgl'])) {
							if(empty($dari_tgl) && empty($sampai_tgl)){
								if($periode=='-Pilih Periode-'){
									$show_pendaftar="SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, judul, nip1, nip2, nip3, jadwal, tempat,tahun_ajaran,id_uji_skripsi from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim ";
								}else{
									$show_pendaftar = "SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, judul, nip1, nip2, nip3, jadwal, tempat,tahun_ajaran,id_uji_skripsi from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.tahun_ajaran='$periode' ";
								}
							}else{
								$show_pendaftar = "SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, judul, nip1, nip2, nip3, jadwal, tempat,tahun_ajaran,id_uji_skripsi from daftar_skripsi inner join uji_skripsi on uji_skripsi.id_uji_skripsi=daftar_skripsi.id_daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim where (uji_skripsi.jadwal BETWEEN '$dari_tgl' AND '$sampai_tgl')";
							}
						}else{
							$show_pendaftar="SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, judul, nip1, nip2, nip3, tahun_ajaran,id_daftar_skripsi from daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim";
						}
							
							$fshow_pendaftar = $con->query( $show_pendaftar );
							if(!$fshow_pendaftar){
								die('Could not connect to database : <br/>'.$con->error);
							}
							$nomor=1;
							
							while($rshow_pendaftar = $fshow_pendaftar->fetch_object()){
								echo "<tr >";
								echo "<td >".$nomor."</td>";$nomor++;
								echo "<td >".$rshow_pendaftar->nim."</td>";
								echo "<td >".$rshow_pendaftar->nama."</td>";
								echo "<td >".$rshow_pendaftar->judul."</td>";
								$infoUjiSkripsi=mysqli_query($con, "SELECT * from uji_skripsi where id_uji_skripsi='$rshow_pendaftar->id_daftar_skripsi' ");
								$jmlInfoUjiSkripsi=mysqli_num_rows($infoUjiSkripsi);
								if($jmlInfoUjiSkripsi!=0){
									while($rinfoUjiSkripsi=$infoUjiSkripsi->fetch_object()){
										echo "<td >".$rinfoUjiSkripsi->jadwal."</td>";	
										echo "<td >".$rinfoUjiSkripsi->tempat."</td>";
									}
								
								}else{
									echo'<td></td>' ;
									echo'<td></td>' ;
								}
								//daftar pembimbing
								$nama_pembimbing1="SELECT * FROM dosen WHERE dosen.nip='".$rshow_pendaftar->nip1."' ";
								$nama_pembimbing2="SELECT * FROM dosen WHERE dosen.nip='".$rshow_pendaftar->nip2."' ";
								$fnama_pembimbing1=$con->query($nama_pembimbing1);
								$fnama_pembimbing2=$con->query($nama_pembimbing2);
								$rnama_pembimbing2=$fnama_pembimbing2->fetch_object();;
								while($rnama_pembimbing1=$fnama_pembimbing1->fetch_object()){
									if(!$rnama_pembimbing2){
										echo "<td> 1.".$rnama_pembimbing1->nama_dosen."</td>";
									}else{
										echo "<td> 1.".$rnama_pembimbing1->nama_dosen."<br>2.".$rnama_pembimbing2->nama_dosen."</td>";;	
									} 
									
								}
								// daftar penguji
								$nama_penguji="SELECT * FROM dosen inner join penguji_skripsi on penguji_skripsi.nip_penguji_skripsi=dosen.nip WHERE id_uji_skripsi='".$rshow_pendaftar->id_daftar_skripsi."' and jabatan != 'sekretaris' order by jabatan asc";
								$fnama_penguji=$con->query($nama_penguji);

								$no_urut=1;
								echo "<td>";
								while($rnama_penguji=$fnama_penguji->fetch_object()){
									echo $no_urut.'. '.$rnama_penguji->nama_dosen;
									echo "<br>";
									$no_urut++;
								}
								echo "</td>";
								
								//tools
								echo "
									<td align='center' class='tools'>
									<a href='detail_mahasiswa_skripsi.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_skripsi."' title='Detail Mahasiswa'><i class='fa fa-info-circle' title='Lihat Detail'></i></a>
									<br>
									<a href='daftar_penguji_skripsi.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_skripsi."' title='Tambah Jadwal & Penguji'><i class='fa fa-plus'></i></a>
									<br>
									<a href='edit_mahasiswa_skripsi.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_skripsi."' title='Ubah Data Mahasiswa Skripsi'><i class='fa fa-edit'></i></a>
									<br>
									<a href='print_berkas_ujian_sarjana.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_skripsi."' title='Print Berkas Ujian Sarjana'><i class='fa fa-print'></i></a>
									<br>
									<a data-toggle='modal' 
										data-target='#myModal' 
										data-nim='".$rshow_pendaftar->nim."'
										data-id='".$rshow_pendaftar->id_daftar_skripsi."'
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
      <h4 class="modal-title">Hapus Data Mahasiswa</h4>
    </div>
    <div class="modal-body">
      <p>Apakah anda yakin untuk menghapus data Mahasiswa Ujian Tugas akhir ini?</p>
      <p id="hapus_nim"></p>
    </div>
    <div class="modal-footer">
    <form id='form_hapus'>
    	<input type="hidden" name="nim" id="nim">
    	<input type="hidden" name="id" id="id">
    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      	<button type="submit" class="btn btn-danger" id="hapus">Hapus</button>
    </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
	$('.button_hapus').click(function(){
		$('#nim').val($(this).data('nim'));
		$('#id').val($(this).data('id'));
		$('#hapus_nim').html('NIM : '+ $(this).data('nim'));
	});
	$('#form_hapus').submit(function(e){
		e.preventDefault();
		var id=$('#id').val();
		$.ajax({
			url:'delete.php',
			type:'get',
			data:'data=mahasiswa_uji_skripsi&id='+id,
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
								
<?php
		include_once('../footer.php');
		$con->close();
	}else{
		header("Location:./");
	}
?>