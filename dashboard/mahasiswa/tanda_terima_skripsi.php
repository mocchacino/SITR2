<?php  
setlocale(LC_ALL, 'id_ID'); 
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //if (isset($_POST['submit'])) {
    require_once('../functions.php'); 
    if (!isset($_SESSION['sip_masuk_aja'])){
      header("Location:../login/login.php");
    }elseif($status == 'mahasiswa'){
      include_once('../sidebar.php');
      $nim=$rMahasiswa->nim;
      // $eksemplar_pembimbing1=test_input($_POST['eksemplar_pembimbing1']);
      // $eksemplar_pembimbing2=test_input($_POST['eksemplar_pembimbing2']);
      // $eksemplar_kbk=test_input($_POST['eksemplar_kbk']);

      $retrieve_info = mysqli_query($con, "SELECT daftar_skripsi.nim, nama, nip1, nip2, nip3, judul, tgl_lulus FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.nim='$nim' and tgl_lulus is not null ");
      $fretrieve_info=$retrieve_info->fetch_assoc();

      $lab_mahasiswa=mysqli_query($con, "SELECT idlab_tr1 from tr1 where nim = '$nim' ");
      $rlab_mahasiswa=$lab_mahasiswa->fetch_assoc();
      $kbk=mysqli_query($con, "SELECT nama_dosen from dosen inner join lab on lab.nip=dosen.nip where lab.idlab= '".$rlab_mahasiswa['idlab_tr1']."' ");
      $rkbk=$kbk->fetch_assoc();
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
                      'font-size:18px;'+
                  '}'+
                '</style>'; 
                htmlToPrint += divToPrint.outerHTML;        
                htmlToPrint = divToPrint.outerHTML;
              var win = window.open();
              win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 3cm; margin: 1cm auto;} .ttd{padding:0 0 0 175px; width:50%;} .pengisi{width:25%;} div.isisurat{          line-height: 25px;} #scissors {height: 43px;width: 90%;margin: auto auto;background-image: url("../assets/img/gunting.png");background-repeat: no-repeat;background-position: right;position: relative;overflow: hidden;} #scissors:after {content: "";position: relative;top: 50%;display: block;border-top: 3px dashed black;margin-top: -3px;}</style>');
              win.document.write(htmlToPrint);
              win.print();
              win.close();
            })
          });
        </script>
        <style> 
          table {  
            border-collapse: collapse;
            font-size:18px;
            font-family:"Times New Roman"
          }
          tr,td{
            padding: 15px;
          }
          div.margin{
            width: 21cm;
            min-height: 29.7cm;
            padding: 2cm;
            margin: 1cm auto;
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
          #scissors {
              height: 43px; /* image height */
              width: 90%;
              margin: auto auto;
              content: '\002702';
              background-image: url('../assets/img/gunting.png');
              background-repeat: no-repeat;
              background-position: right;
              position: relative;
              overflow: hidden;
          }
          #scissors:after {
              content: "";
              position: relative;
              top: 50%;
              display: block;
              border-top: 3px dashed black;
              margin-top: -3px;
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
        <hr>
        <div class="isisurat">
        <div class="margin" id="printArea">
        <h3>
          DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>
          UNIVERSITAS DIPONEGORO<br>
          SEMARANG
        </h3>
        <h3 align="center"><u>TANDA TERIMA</u></h3>
        <p>Yang bertanda tangan dibawah ini menyatakan bahwa mahasiswa: </p>
        <table align="right" border="1" cellpadding="15">
          <tr>
            <td><b>SCORE TOEFL : .........</b></td>
          </tr>
        </table>
        <?php
        echo '<table>';
        echo '<tr>';
        echo '<td>Nama</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info['nama'].'</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>NIM</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info['nim'].'</td>';
        echo '</tr>';
        echo '<td>Tanggal Ujian</td>';
        echo '<td>:</td>';
        $tanggal= strftime("%e %B %Y",strtotime($fretrieve_info['tgl_lulus']));
        echo '<td>'.$tanggal.'</td>';
        echo '</tr>';
        echo '</table>';
       
        echo '<p>Telah menyerahkan Skripsi/ Tugas Akhir dengan judul:<br>';
        echo '<center>'.$fretrieve_info['judul'].'</center></p>';
        // echo '<table>';
        // echo '<tr>';
        // echo '<td>Sebanyak</td>';
        // echo '<td>:</td>';
        // echo '<td>'.$eksemplar_departemen.'</td>';
        // echo '<td> eksemplar</td>';
        // echo '</tr>';
        // echo '</table>';

        echo '<table width="100%" height="50px" border="1">';
        echo '<tr>';
        $no=1;
        echo '<td>NO</td>';
        echo '<td>YANG MENERIMA</td>';
        echo '<td>NAMA</td>';
        echo '<td>TANGGAL DITERIMA</td>';
        echo '<td>TANDA TANGAN</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>'.$no.'</td>';
        echo '<td>Dosen Pembimbing 1</td>';
        echo '<td>'.$rpembimbing1['nama_dosen'].'</td>';
        echo '<td></td>';
        echo '<td></td>';
        $no++;
        echo '</tr>';
        $j=2;
        //ganti jadi 3 jika diperlukan pembimbing3
        for($i=2; $i<3; $i++){
          if($i==3){
            if (${"rpembimbing".$i}['nip']){
               echo '<tr>';echo '<td>'.$no.'</td>';echo '<td>Dosen Pembimbing '.$j.'</td>';echo '<td>Dosen Pembimbing '.${"rpembimbing".$i}['nama'].'</td>';echo '<td></td>';echo '<td></td>';echo '</tr>';
               $no++;
            }
          }else {
            echo '<tr>';echo '<td>'.$no.'</td>';echo '<td>Dosen Pembimbing '.$j.'</td>';echo '<td>'.${"rpembimbing".$i}['nama_dosen'].'</td>';echo '<td></td>';echo '<td></td>';echo '</tr>';
            $no++;
          }
          $j++;
        }
        echo '<tr>';
        echo '<td>'.$no.'</td>';
        echo '<td>Ketua KBK</td>';
        echo '<td>'.$rkbk['nama_dosen'].'</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
        $no++;
        echo '</table>';

        echo '<table>';
        echo '<tr>';
        echo '<td>1. Arsip Departemen</td>';
        echo '<td class="pengisi"></td>';
        echo '<td class="ttd"></td>';
        echo '</tr>';
        echo '</table>';
        // $tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));
        // echo '<table >';
        // echo '<tr>';
        // echo '<td width="550"></td>';
        // echo '<td>Semarang, '.$tgl_sekarang.'</td>';
        // echo '</tr>';
        // echo '<br>';
        // echo '</table>';
        // echo '<table>';
        // echo '<tr>';
        // echo '<td ></td>';
        // echo '<td class="pengisi"></td>';
        // echo '<td class="ttd">Penerima,</td>';
        // echo '</tr>';
        // echo '<tr>';
        // echo '<td height="50"></td>';
        // echo '<td height="50"></td>';
        // echo '<td class="pengisi" height="50"></td>';
        // echo '</tr>';
        // echo '<tr>';
        // echo '<td ></td>';
        // echo '<td class="pengisi"></td>';
        // echo '<td class="ttd">(................................................)</td>';
        // echo '</tr>';
        // echo '<tr>';
        // echo '<td ></td>';
        // echo '<td class="pengisi"></td>';
        // echo '<td class="ttd">NIP. </td>';
        // echo '</tr>';
        // echo '<tr>';
        // echo '<td>1. Arsip Departemen</td>';
        // echo '<td class="pengisi"></td>';
        // echo '<td class="ttd"></td>';
        // echo '</tr>';
        // echo '</table>';
        
        
        

        echo '</div>';
        echo '</div>';
      echo '</body>';
      echo '</html>';

    

      include_once('../footer.php');
      $con->close();
    }else{
      header("Location:./");
    }
  //}
//}
?>