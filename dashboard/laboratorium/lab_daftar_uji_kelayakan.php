<?php	
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'laboratorium'){
		include_once('../sidebar.php');
		switch ($idlab) {
			case '1':
				$namaLab='BIOKIMIA';
				break;
			case '2':
				$namaLab='KIMIA_ANALITIK';
				break;
			case '3':
				$namaLab='KIMIA_ANORGANIK';
				break;
			case '4':
				$namaLab='KIMIA_FISIK';
				break;
			case '5':
				$namaLab='KIMIA_ORGANIK';
				break;
			default:
				header("Location:../login/login.php");
				break;
		}

		$dari_tgl='';
		$sampai_tgl='';
		$periodeTersedia=mysqli_query($con, "SELECT distinct tahun_ajaran from daftar_uji_kelayakan inner join tr1 on tr1.nim=daftar_uji_kelayakan.nim where tr1.idlab_tr1='$idlab' order by tahun_ajaran desc");

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
			var header = '<html><head><title>Print daftar peserta Uji Kelayakan</title></head><style> table,tr,th,td { border: 1px solid black; border-collapse: collapse;}</style>';
			var kopsurat = '<body><h1 style="text-align:center;">DAFTAR UJI KELAYALAYAKAN<br>DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>UNIVERSITAS DIPONEGORO SEMARANG</h1><hr><hr><br>';
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
			<div class="panel-heading row" style="margin: 0;">
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
					<input class="form-control btn-info" type="submit" name="search_tgl" value="Search" >
				</div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				<div class="form-group">
					<br>
					<!-- <a href="daftar_uji_kelayakan.php">
					   <input class="form-control" type="button" value="Reset" />
					</a>
					 -->
					 <input class="form-control btn-danger" type="button" name="reset" value="Reset" onclick="window.location.href='lab_daftar_uji_kelayakan.php'" />
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
								<th align="center">Judul</th>
								<th >Tanggal & Waktu</th>
								<th >Tempat</th>
								<th >Pembimbing</th>
								<th >Penguji</th>
								<th ></th>
							</tr>
						</tfoot>
						<tbody align="center" width='100%'>
						<?php	
						if(isset($_POST['search_tgl'])) {
							if(empty($dari_tgl) && empty($sampai_tgl)){
								if(empty($periode)){
									$show_pendaftar="SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, daftar_tugas_riset2.judul, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3,daftar_uji_kelayakan.tahun_ajaran,id_daftar_uji_kelayakan from daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim where tr1.idlab_tr1='$idlab' ";
								}else{
									$show_pendaftar = "SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, daftar_tugas_riset2.judul, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3,daftar_uji_kelayakan.tahun_ajaran,id_daftar_uji_kelayakan from daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim where tr1.idlab_tr1='$idlab' and daftar_uji_kelayakan.tahun_ajaran='$periode' ";
								}
							}else{
								$show_pendaftar = "SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, daftar_tugas_riset2.judul, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3,daftar_uji_kelayakan.tahun_ajaran,id_daftar_uji_kelayakan from daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim left join uji_kelayakan on uji_kelayakan.nim = mahasiswa.nim where tr1.idlab_tr1='1' and (uji_kelayakan.jadwal BETWEEN '$dari_tgl' AND '$sampai_tgl') ";
							}
						}else{
							$show_pendaftar="SELECT DISTINCT mahasiswa.nim, mahasiswa.nama, daftar_tugas_riset2.judul, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3,daftar_uji_kelayakan.tahun_ajaran,id_daftar_uji_kelayakan from daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim where tr1.idlab_tr1='$idlab'";
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
								echo "<td >".$rshow_pendaftar->judul."</td>";
								$infoUjiKelayakan=mysqli_query($con, "SELECT * from uji_kelayakan where id_uji_kelayakan='$rshow_pendaftar->id_daftar_uji_kelayakan' ");
								$jmlInfoUjiKelayakan=mysqli_num_rows($infoUjiKelayakan);
								if($jmlInfoUjiKelayakan!=0){
									while ($rinfoUjiKelayakan=$infoUjiKelayakan->fetch_object()) {
										echo"<td >".$rinfoUjiKelayakan->jadwal."</td>";
										echo"<td >".$rinfoUjiKelayakan->tempat."</td>";					
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
								// daftar penguji dari lab
								$nama_penguji="SELECT * FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip WHERE id_uji_kelayakan='$rshow_pendaftar->id_daftar_uji_kelayakan' and jabatan='penguji' ";
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
								echo "<td id='tools' align='center'>
									<a href='detail_mahasiswa_uji_kelayakan.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_uji_kelayakan."' title='Detail Mahasiswa Uji Kelayakan'><i class='fa fa-info-circle'></i></a>
									<a href='daftar_penguji_kelayakan.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_uji_kelayakan."' title='Jadwal & Penguji'><i class='fa fa-plus'></i></a>
									<a href='edit_mahasiswa_uji_kelayakan.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_uji_kelayakan."' title='Ubah Mahasiswa Uji Kelayakan'><i class='fa fa-edit'></i></a>
									<a href='print.php?nim=".$rshow_pendaftar->nim."&id=".$rshow_pendaftar->id_daftar_uji_kelayakan."' title='cetak berkas seminar'><i class='fa fa-print'></i></a>
								 </td>";

// 									<a 
// 										data-toggle='modal' 
// 										data-target='#myModal' 
// 										data-nim='".$rshow_pendaftar->nim."'
// 										data-id='".$rshow_pendaftar->id_daftar_uji_kelayakan."'
// 										title='Hapus'
// 										class='button_hapus'><i class='fa fa-trash-o'></i></a>
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
      <p>Apakah anda yakin untuk menghapus data Mahasiswa Uji Kelayakan ini?</p>
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
			data:'data=mahasiswa_uji_kelayakan&id='+id,
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