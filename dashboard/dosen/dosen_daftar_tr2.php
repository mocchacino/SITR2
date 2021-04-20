<?php		
	require_once('../functions.php'); 
  if (!isset($_SESSION['sip_masuk_aja'])){
    header("Location:../login/login.php");
  }elseif($status == 'dosen'){
    include_once('../sidebar.php');
		$nip_saya=$rDosen->nip;
		$dari_tgl='';
		$sampai_tgl='';
		$periodeTersedia=mysqli_query($con, "SELECT distinct tahun_ajaran from daftar_tugas_riset2 where nip1='".$nip_saya."' or nip2='".$nip_saya."' order by tahun_ajaran desc");
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<!-- /. ROW  -->
<div class="row " >
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="row panel-heading" style="margin: 0;">
			   Daftar Mahasiswa yang mendaftar TR 2
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
								<th >No</th>
								<th >NIM</th>
								<th >Nama</th>
								<th >Pembimbing 1</th>
								<th >Pembimbing 2</th>
								<th >Pembimbing 3</th>
								<th align="center">Judul</th>
								<!-- <th >Tanggal KRS</th>
								<th >Jumlah SKS</th>
								<th >Jumlah SKS Komulatif</th>
								<th >Tanggal Daftar</th> 
								<th > Lihat Transkrip</th>-->
								<th id='header_tools'></th>
							</tr>
						</thead>
				</div>
						<tfoot align="center" id="tfoot_search">
							<tr align="center">
								<th >No</th>
								<th >NIM</th>
								<th >Nama</th>
								<th >Pembimbing 1</th>
								<th >Pembimbing 2</th>
								<th >Pembimbing 3</th>
								<th align="center">Judul</th><!-- 
								<th >Tanggal KRS</th>
								<th >Jumlah SKS</th>
								<th >Jumlah SKS Komulatif</th>
								<th >Tanggal Daftar</th>
								<th >Lihat Transkrip</th>-->
								<th ></th>
							</tr>
						</tfoot>
						<tbody >
						<?php	
							//proses klik tombol pencarian date
							if(isset($_POST['search_tgl'])) {
								if($periode!='--Pilih Periode--'){
									$show_pendaftar = "SELECT daftar_tugas_riset2.*, mahasiswa.nama, lab.nama_lab FROM daftar_tugas_riset2 INNER JOIN mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim inner join lab on lab.idlab=tr1.idlab_tr1 where (daftar_tugas_riset2.nip1='$nip_saya' OR daftar_tugas_riset2.nip2='$nip_saya') AND daftar_tugas_riset2.tahun_ajaran='$periode' ORDER BY daftar_tugas_riset2.tgl_daftar";
								}elseif(($dari_tgl!='')&&($sampai_tgl!='')){
									$show_pendaftar = "SELECT daftar_tugas_riset2.*, mahasiswa.nama, lab.nama_lab FROM daftar_tugas_riset2 INNER JOIN mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim inner join lab on lab.idlab=tr1.idlab_tr1 where (daftar_tugas_riset2.nip1='$nip_saya' OR daftar_tugas_riset2.nip2='$nip_saya') AND (daftar_tugas_riset2.tgl_daftar BETWEEN '$dari_tgl' AND '$sampai_tgl') ORDER BY daftar_tugas_riset2.tgl_daftar";
								}
							}else{
								$show_pendaftar = "SELECT daftar_tugas_riset2.*, mahasiswa.nama, lab.nama_lab FROM daftar_tugas_riset2 INNER JOIN mahasiswa on mahasiswa.nim=daftar_tugas_riset2.nim inner join tr1 on tr1.nim=daftar_tugas_riset2.nim inner join lab on lab.idlab=tr1.idlab_tr1 where daftar_tugas_riset2.nip1='$nip_saya' OR daftar_tugas_riset2.nip2='$nip_saya' ORDER BY daftar_tugas_riset2.tgl_daftar";
							}
								
							
							$fshow_pendaftar = $con->query( $show_pendaftar );
							if(!$fshow_pendaftar){
								die('Could not connect to database : <br/>'.$con->error);
							}
							$nomor=1;

							while($rshow_pendaftar = $fshow_pendaftar->fetch_object()){
								echo "<tr>";
								echo "<td>".$nomor."</td>";$nomor++;
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
								// echo "<td>".$rshow_pendaftar->tgl_krs."</td>";
								// echo "<td>".$rshow_pendaftar->sks_semester."</td>";
								// echo "<td>".$rshow_pendaftar->sks_komulatif."</td>";
								// echo "<td>".$rshow_pendaftar->tgl_daftar."</td>";
								//echo "<td class='tools' align='center'><a href='download_transkrip_tr2.php?file=".$rshow_pendaftar->path_file."'>Download Transkrip</a></td>";
								if ($rshow_pendaftar->nip1 == $nip_saya){
									echo "<td align='center' class='tools'>
									<a href='detail_mahasiswa_tr2.php?nim=".$rshow_pendaftar->nim."'><i class='fa fa-info-circle' title='Lihat Detail'></i></a>&nbsp;
									<a href='edit_judul_dan_pembimbing_tr2.php?nim=".$rshow_pendaftar->nim."'><i class='fa fa-edit' title='Edit Judul dan Pembimbing'></i></a>&nbsp;
									<a data-toggle='modal' 
										data-target='#myModal' 
										data-nim='".$rshow_pendaftar->nim."'
										title='Hapus'
										class='button_hapus'><i class='fa fa-trash-o'></i></a>
								 </td>";
								}elseif($rshow_pendaftar->nip2 == $nip_saya){
									echo "<td align='center' class='tools'>
									<a href='detail_mahasiswa_tr2.php?nim=".$rshow_pendaftar->nim."'><i class='fa fa-info-circle' title='Lihat Detail'></i></a>&nbsp;
									
								 </td>";
								}
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
      <p>Apakah anda yakin untuk menghapus data Mahasiswa Tugas Riset 2 ini?</p>
      <p id="hapus_nim"></p>
    </div>
    <div class="modal-footer">
    <form id='form_hapus'>
    	<input type="hidden" name="nim" id="nim">
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
		$('#hapus_nim').html('NIM : '+ $(this).data('nim'));
	});
	$('#form_hapus').submit(function(e){
		e.preventDefault();
		var nim=$('#nim').val();
		$.ajax({
			url:'delete.php',
			type:'get',
			data:'data=mahasiswa_tr2&nim='+nim,
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