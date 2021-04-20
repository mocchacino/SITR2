<?php  
date_default_timezone_set("Asia/Bangkok"); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['submit'])) {
    require_once('../functions.php'); 
    if (!isset($_SESSION['sip_masuk_aja'])){
      header("Location:../login/login.php");
    }elseif($status == 'mahasiswa'){
      include_once('../sidebar.php');
      $nim=$rMahasiswa->nim;
      $instansi=test_input($_POST['instansi']);

      $retrieve_info = mysqli_query($con, "SELECT daftar_skripsi.nim, nama, nip1, nip2, nip3, tgl_lulus FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.nim='$nim' and tgl_lulus != '0000-00-00' ");
      $fretrieve_info=$retrieve_info->fetch_assoc();

      $pembimbing1=mysqli_query($con, "SELECT nip, nama_dosen FROM dosen WHERE dosen.nip='".$fretrieve_info['nip1']."' ");
      $pembimbing2=mysqli_query($con, "SELECT nip, nama_dosen FROM dosen WHERE dosen.nip='".$fretrieve_info['nip2']."' ");
      $pembimbing3=mysqli_query($con, "SELECT nip, nama, instansi FROM pembimbing_luar WHERE nip='".$fretrieve_info['nip3']."' ");
      $rpembimbing1=$pembimbing1->fetch_assoc();
      $rpembimbing2=$pembimbing2->fetch_assoc();
      $rpembimbing3=$pembimbing3->fetch_assoc();
?>

      <html>
      <head>
        <title>Print Surat Permohonan Pengantar</title>
        <script src="assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script>
          
          $(document).ready(function(){
            $('#print').click(function(){
              var divToPrint = document.getElementById('printArea');  
              var htmlToPrint = '' +
                '<style type="text/css">' +
                  '.ket_lembar{'+
                    'font-size:10px;'+
                  '}'+
                  '.footer_surat {'+
                      'font-size:12px;'+
                  '}'+
                '</style>'; 
                htmlToPrint += divToPrint.outerHTML;        
                htmlToPrint = divToPrint.outerHTML;
              var win = window.open();
              win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 2cm; margin: 1cm auto;} .ttd{padding:0 0 0 175px; width:50%;} .pengisi{width:25%;} div.isisurat{          line-height: 25px;}</style>');
              win.document.write(htmlToPrint);
              win.print();
              win.close();
            })
          });
        </script>
        <style> 
          table {  
            border-collapse: collapse;
            font-size:16px;
            font-family:"Times New Roman"
          }
          tr,td{
            padding: 15px;
          }
          div.margin{
            width: 21cm;
            min-height: 29.7cm;
            padding: 2cm;
            margin: 0.5cm auto;
          }
          div.isisurat{
            line-height: 55px;
          }
          .head{
            text-align: left;
            padding: 0px 0px 0px 550px;
          }
          .tanggal{
            padding: 0px 0px 0px 500px;
          }
          .ttd{
            width: 50%;
          }
          .numerasi{
            vertical-align:top;
          }
          .tabulasi{
            text-indent: 100px;
          }
        </style>
        
      </head>
      <body>
      <div class="panel-body">
          <div class="col-md-12 col-sm-12 col-xs-12">
          <br>
            <div class="form-group">
              <div class="well clearfix">
                <button class="btn btn-primary pull-right" name="print" id="print"><i class='fa fa-print'></i> | Print</button>
              </div> 
            </div>
          </div>
        </div>
        <div class="isisurat">
        <div class="margin" id="printArea">
        <h2 style="text-align:center;">
          PERMOHONAN PENGANTAR
        </h2>
        <br>
        <p>Yth. Ketua Departemen Kimia<br>
        Fakultas Sains dan Matematika<br>
        Universitas Diponegoro<br>
        Semarang<br>
        <br>
        Bersama ini, mohon dapat dibuatkan surat pengatar yang ditujukan kepada Dekan Fakultas Dains dan Matematika untuk keperluan:</p>
        <input type="checkbox" checked> Pembuatan SKL (Surat Keterangan Lulus) <br>
        <input type="checkbox"> Melaksanakan Penelitian <br>
        <input type="checkbox"> Analisis Sampel <br>  Nama Alat..........<br>
        <input type="checkbox"> Lain-lain<br><br>
  
        <p>Kelengkapan data:</p>
        <?php
        echo '<table>';
        echo '<tr>';
        echo '<td width="50"></td>';
        echo '<td>a.</td>';
        echo '<td>Nama</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info['nama'].'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td></td>';
        echo '<td>b.</td>';
        echo '<td>NIM</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info['nim'].'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td></td>';
        echo '<td>c.</td>';
        echo '<td>Pembimbing</td>';
        echo '<td>:</td>';
        echo '<td>1.'.$rpembimbing1['nama_dosen'].'</td>';
        echo '</tr>';
        $j=2;
        for($i=2; $i<4; $i++){
          if($i==3){
            if (${"rpembimbing".$i}['nip']){
               echo '<tr>';echo '<td></td>';echo '<td></td>';echo '<td></td>';echo '<td></td>';echo '<td>'.$j.'. '.${"rpembimbing".$i}['nama'].'</td>';echo '</tr>';
            }
          }else {
            echo '<tr>';echo '<td></td>';echo '<td></td>';echo '<td></td>';echo '<td></td>';echo '<td>'.$j.'. '.${"rpembimbing".$i}['nama_dosen'].'</td>';echo '</tr>';
          }
          $j++;
        }
        echo '<tr>';
        echo '<td></td>';
        echo '<td>d.</td>';
        echo '<td>Nama Instansi yang dituju</td>';
        echo '<td>:</td>';
        echo '<td>'.$instansi.'</td>';
        echo '</tr>';
         echo '<tr>';
        echo '<td></td>';
        echo '<td>e.</td>';
        echo '<td>Tanggal Lulus</td>';
        echo '<td>:</td>';
        $tanggal= strftime("%e %B %Y",strtotime($fretrieve_info['tgl_lulus']));
        echo '<td>'.$tanggal.'</td>';
        echo '</tr>';
        echo '</table>';
        echo '<p>Demikian surat permohonan kami ajukan, atas perhatiannya kami ucapkan terimakasih.</p><br>';
        $tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));
        echo '<table >';
        echo '<tr>';
        echo '<td width="500" height="50"></td>';
        echo '<td height="50">Semarang, '.$tgl_sekarang.'</td>';
        echo '</tr>';
        echo '<br>';
        echo '</table>';
        echo '<table>';
        echo '<tr>';
        echo '<td >Menyetujui</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td >Pembimbing,</td>';
        echo '<td class="pengisi"></td>';
        echo '<td class="ttd">Pemohon,</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td height="50"></td>';
        echo '<td height="50"></td>';
        echo '<td class="pengisi" height="50"></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td ><u>'.$rpembimbing1['nama_dosen'].'</u></td>';
        echo '<td class="pengisi"></td>';
        echo '<td class="ttd"><u>'.$fretrieve_info['nama'].'</u></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td >NIP. '.$rpembimbing1['nip'].'</td>';
        echo '<td class="pengisi"></td>';
        echo '<td class="ttd">NIM. '.$fretrieve_info['nim'].'</td>';
        echo '</tr>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
      echo '</body>';
      echo '</html>';

    

      include_once('../footer.php');
      $con->close();
    }else{
      header("Location:./");
    }
  }
}
?>