<?php
date_default_timezone_set("Asia/Bangkok"); 
  require_once('../functions.php'); 
  if (!isset($_SESSION['sip_masuk_aja'])){
    header("Location:../login/login.php");
  }elseif($status == 'mahasiswa'){
    include_once('../sidebar.php');
    ini_set('display_errors', 1);
   
    if ($_SERVER['REQUEST_METHOD']==='GET') {
      $is_lulus=mysqli_query($con,"SELECT count(tgl_lulus) as lulus from daftar_skripsi where nim='$rMahasiswa->nim' and tgl_lulus!='0000-00-00' ");
      $ris_lulus=$is_lulus->fetch_object();
?>
      <!DOCTYPE html>
      <html>
      <head>
        <title>Surat keterangan Lulus</title>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <style type="text/css">
          input#keteranganGagal {
            width: 100%;
            height: 70px;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: none;
            background-color: #E74C3C;
            color: white;
            text-align: center;
          }
        </style>
      </head>
      <body>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <!-- Form Elements -->
            <div class="panel panel-default">
              <div class="panel-heading">
                Input Data yang diperlukan Surat keterangan Lulus
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                    <form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
                      <div class="form-group">
                        <label>Jumlah SKS</label>&nbsp;<span class="label label-warning">*</span><?php if(isset($errorKomulatif)) echo $errorKomulatif;?>
                        <input class="form-control" type='text' name="sks_kumulatif" placeholder="Masukkan jumlah SKS" autofocus required <?php if(($ris_lulus->lulus)==0) echo 'disabled';?>>
                      </div>
                      <div class="form-group">
                        <label>IPK</label>&nbsp;<span class="label label-warning">*</span><?php if(isset($errorIpk)) echo $errorIpk;?>
                        <input class="form-control" type='text' name="ipk" placeholder="Masukkan IPK" autofocus required <?php if(($ris_lulus->lulus)==0) echo 'disabled';?>>
                      </div>
                      <!-- <div class="form-group">
                        <label>Predikat</label>&nbsp;<span class="label label-warning">*</span><?php if(isset($errorPredikat)) echo $errorPredikat;?>
                        <input class="form-control" type='text' name="predikat" placeholder="Masukkan predikat dengan huruf Kapital" autofocus required <?php if(($ris_lulus->lulus)==0) echo 'disabled';?>>
                      </div> -->
                      <div class="form-group">
                        <input class="form-control btn btn-primary" type="submit" name="submit" value="Submit" <?php if(($ris_lulus->lulus)==0) echo 'disabled';?>/>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
<?php         if(($ris_lulus->lulus)==0){
                echo '<input class="form-control" type="text" id="keteranganGagal" name="keterangan" readonly="" value="Maaf anda belum menyelesaikan Tugas Akhir">';
              }
?>
            </div>
          </div>
        </div>
      </body>
    </html>

<?php
    }elseif ($_SERVER['REQUEST_METHOD']==='POST') {
      if (isset($_POST['submit'])) {
        $sks_kumulatif=test_input($_POST['sks_kumulatif']);
        $ipk=test_input($_POST['ipk']);
        //$predikat=test_input($_POST['predikat']);
        if ($sks_kumulatif!=''){
          if (is_numeric($sks_kumulatif)){
            if ($sks_kumulatif >= 120){
              $validKomulatif=TRUE;
            }else{
              $errorKomulatif='sks komulatif minimal 120';
              $validKomulatif=FALSE;
            }
          }else{
            $errorKomulatif='isi dengan angka';
            $validKomulatif=FALSE;
          }
        }else{
          $errorKomulatif='wajib diisi';
          $validKomulatif=FALSE;
        }

        // $syaratDaftar=mysqli_query($con,"SELECT syarat_ipk FROM waktu where nama='Skr' ");
        // $fSyaratDaftar=$syaratDaftar->fetch_assoc();

        if($ipk == ''){
          $errorIpk='wajib diisi';
          $validIpk= false;
        }else{
          if(is_numeric($ipk)){
            $ipk=floatval($ipk);
            // $syarat=floatval($fSyaratDaftar['syarat_ipk']);
            if(($ipk>=0)&&($ipk<=4)){
              $validIpk=true;
            }else{
              $validIpk=false;
              $errorIpk='IPK tidak memenuhi syarat lulus';
            } 
          }else{
            $errorIpk='hanya angka dan menggunakan titik(.) untuk bilangan desimal';
            $validIpk=false;
          }
        }
        // if($predikat==''){
        //   $errorPredikat='wajib diisi';
        //   $validPredikat= false;
        // }else{
        //   if(ctype_alpha($predikat)){
        //     if(($predikat=='A')||($predikat=='B')||($predikat=='C')||($predikat=='D')||($predikat=='E')){
        //       $validPredikat=true;
        //     }else{
        //       $validPredikat=false;
        //       $errorPredikat= 'Gunakan huruf kapital sesuai predikat yang anda peroleh';
        //     }
        //   }else{
        //     $errorPredikat='Gunakan huruf kapital sesuai predikat yang anda peroleh';
        //     $validPredikat=false;
        //   }
        // }
        if ($validIpk && $validKomulatif) {
          $sukses=true;
          $nim=$rMahasiswa->nim;
          $ipk=test_input($_POST['ipk']);
          $sks_kumulatif=test_input($_POST['sks_kumulatif']);
          //$predikat=test_input($_POST['predikat']);

          $retrieve_info = mysqli_query($con, "SELECT daftar_skripsi.nim, nama,  tgl_lulus, tempat_lahir, tgl_lahir, no_telp, alamat FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim where daftar_skripsi.nim='$nim' and tgl_lulus != '0000-00-00' ");
          $fretrieve_info=$retrieve_info->fetch_assoc();

          $kadep=mysqli_query($con, "SELECT nip, nama_dosen from dosen inner join misc on misc.deskripsi=dosen.nip where misc.judul='kepala_departemen' ");
          $rKadep=$kadep->fetch_assoc();
?>
          <!DOCTYPE html>
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
                    win.document.write('<style>div.margin{width: 21cm;min-height: 29.7cm;padding: 3cm; margin: 1cm auto;} .ttd{padding:0 0 0 175px; width:50%;} .pengisi{width:25%;} div.isisurat{line-height: 25px;} #scissors {height: 43px;width: 90%;margin: auto auto;background-image: url("../assets/img/gunting.png");background-repeat: no-repeat;background-position: right;position: relative;overflow: hidden;} #scissors:after {content: "";position: relative;top: 50%;display: block;border-top: 3px dashed black;margin-top: -3px;}</style>');
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
                <table align="right" border="1" cellpadding="10">
                  <tr>
                    <td><b>FSM. AK. 07</b></td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td><b>Perihal</b></td>
                    <td><b>:</b></td>
                    <td><b>Surat Keterangan Lulus</b></td>
                  </tr>
                </table>
                <br><br>
                <table>
                  <tr>
                    <td width="30">Yth.</td><td>Dekan</td>
                  </tr>
                  <tr>
                    <td></td><td>Fakultas Sains dan Matematika Universitas Diponogoro</td>
                  </tr>
                  <tr>
                    <td></td><td>Semarang.</td>
                  </tr>
                </table>
                <br><br>
                <p>Dengan ini kami mengajukan permohonan penerbitan Surat Keterangan Lulus atas nama: </p>
<?php  
              echo '<table>';
              echo '<tr>';
              echo '<td width="30"></td>';
              echo '<td>Nama</td>';
              echo '<td>:</td>';
              echo '<td>'.$fretrieve_info['nama'].'</td>';
              echo '</tr>';

              echo '<tr>';
              echo '<td></td>';
              echo '<td>NIM</td>';
              echo '<td>:</td>';
              echo '<td>'.$fretrieve_info['nim'].'</td>';
              echo '</tr>';

              echo '<tr>';
              echo '<td></td>';
              echo '<td>Tempat/ Tanggal Lahir</td>';
              echo '<td>:</td>';
              echo '<td>'.$fretrieve_info['tempat_lahir'].', '.$fretrieve_info['tgl_lahir'].'</td>';
              echo '</tr>';

              echo '<tr>';
              echo '<td></td>';
              echo '<td>Alamat</td>';
              echo '<td>:</td>';
              echo '<td>'.$fretrieve_info['alamat'].'</td>';
              echo '</tr>';

              echo '<tr>';
              echo '<td></td>';
              echo '<td>No. Telepon/ HP</td>';
              echo '<td>:</td>';
              echo '<td>'.$fretrieve_info['no_telp'].'</td>';
              echo '</tr>';
              echo '</table>';

              $tanggal= strftime("%e %B %Y",strtotime($fretrieve_info['tgl_lulus']));
              echo '<p>Telah dinyatakan lulus pada ujian sarjana Program Studi Kimia Fakultas Sains dan Matematika Universitas Diponogoro pada tanggal '.$tanggal.' dengan Indeks Prestasi Kumulatif (IPK) '.$ipk.' dan Jumlah Satuan Kredit Semester (SKS) '.$sks_kumulatif.' serta predikat .................</p>';

              echo '<p>Berikut kami lampirkan :</p>';
              echo '<ul>';
              echo '<li>Foto Copy Kartu Mahasiswa (KTM)</li>';
              echo '<li>Pas Foto Hitam Putih 4 x 6 sebanyak 2 lembar</li>';
              echo '<li>Foto Copy Berita Acara Ujian Sarjana/ Diploma</li>';
              echo '<li>Daftar Prestasi Akademik yang ditandatangani Dosen Wali</li>';
              echo '</ul>';

              echo '<p>Demikian surat permohonan kami, atas perhatiannya kami ucapkan terimakasih</p><br><br>';
             
              $tgl_sekarang=  strftime("%e %B %Y",strtotime(date("Y-m-d H:i:s")));
              echo '<table >';
              echo '<tr>';
              echo '<td width="500"></td>';
              echo '<td>Semarang, '.$tgl_sekarang.'</td>';
              echo '</tr>';
              echo '<br>';
              echo '</table>';

              echo '<table>';
              echo '<tr>';
              echo '<td >Menyetujui</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td >Ketua Departemen,</td>';
              echo '<td class="pengisi"></td>';
              echo '<td class="ttd">Pemohon,</td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td height="50"></td>';
              echo '<td height="50"></td>';
              echo '<td class="pengisi" height="50"></td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td ><u>'.$rKadep['nama_dosen'].'</u></td>';
              echo '<td class="pengisi"></td>';
              echo '<td class="ttd"><u>'.$fretrieve_info['nama'].'</u></td>';
              echo '</tr>';
              echo '<tr>';
              echo '<td >NIP. '.$rKadep['nip'].'</td>';
              echo '<td class="pengisi"></td>';
              echo '<td class="ttd">NIM. '.$fretrieve_info['nim'].'</td>';
              echo '</tr>';
              echo '</table>';
             
              echo '</div>';
              echo '</div>';
            echo '</body>';
            echo '</html>';
        }else{
?>
          <!DOCTYPE html>
          <html>
          <head>
            <title>Surat keterangan Lulus</title>
            <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
          </head>
          <body>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- Form Elements -->
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Input Data yang diperlukan Surat keterangan Lulus
                  </div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-12">
                        <form method="POST" role="form" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
                          <div class="alert alert-danger" role="alert">Lengkapi form sesuai aturan</div>
                       
                          <div class="form-group">
                            <label>Jumlah SKS</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorKomulatif)) echo $errorKomulatif;?></span>
                            <input class="form-control" type='text' name="sks_kumulatif" placeholder="Masukkan jumlah SKS" autofocus required ">
                          </div>
                          <div class="form-group">
                            <label>IPK</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorIpk)) echo $errorIpk;?> </span>
                            <input class="form-control" type='text' name="ipk" placeholder="Masukkan IPK" autofocus required ">
                          </div>
                          <!-- <div class="form-group">
                            <label>Predikat/Nilai Huruf</label>&nbsp;<span class="label label-warning">* <?php if(isset($errorPredikat)) echo $errorPredikat;?></span>
                            <input class="form-control" type='text' name="predikat" placeholder="Masukkan predikat dengan huruf Kapital" autofocus required ">
                          </div> -->
                          <div class="form-group">
                            <input class="form-control btn btn-primary" type="submit" name="submit" value="Submit" />
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </body>
        </html>
<?php
        }
      }
    }

    include_once('../footer.php');
    $con->close();
  }else{
    header("Location:./");
  }
?>