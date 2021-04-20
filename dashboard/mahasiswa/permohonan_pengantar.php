<?php
  require_once('../functions.php'); 
  if (!isset($_SESSION['sip_masuk_aja'])){
    header("Location:../login/login.php");
  }elseif($status == 'mahasiswa'){
    include_once('../sidebar.php');
    $is_lulus=mysqli_query($con,"SELECT count(tgl_lulus) as lulus from daftar_skripsi where nim='$rMahasiswa->nim' and tgl_lulus!='0000-00-00' ");
    $ris_lulus=$is_lulus->fetch_object();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Surat Permohonan Pengantar</title>
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
          Input Nama Instansi
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              <form method="POST" role="form" autocomplete="on" action="print_permohonan_pengantar.php" >
                <div class="form-group">
                  <label>Nama Instansi yang dituju</label>&nbsp;<span class="label label-warning">*</span>&nbsp;<br>
                  <input class="form-control" type='text' name="instansi" placeholder="Jika tidak ada yang dituju, isi dengan '-'" autofocus required <?php if(($ris_lulus->lulus)==0) echo 'disabled';?>>
                </div>
                <div class="form-group">
                  <input class="form-control btn-primary" type="submit" name="submit" value="Submit" <?php if(($ris_lulus->lulus)==0) echo 'disabled';?>/>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

<?php         if(($ris_lulus->lulus)==0){
                echo '<input class="form-control" type="text" id="keteranganGagal" name="keterangan" readonly="" value="Maaf anda belum menyelesaikan Tugas Akhir">';
              }
?>
    </div>
  </div>
</body>
</html>

<?php
    include_once('../footer.php');
    $con->close();
  }else{
    header("Location:./");
  }
?>