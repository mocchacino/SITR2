<?php
setlocale(LC_ALL, 'id_ID');
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

    $get_nim=$_GET['nim'];
    $retrieve_info = mysqli_query($con, "SELECT * from mahasiswa inner join daftar_tugas_riset2 on mahasiswa.nim=daftar_tugas_riset2.nim inner join daftar_uji_kelayakan on mahasiswa.nim=daftar_uji_kelayakan.nim where mahasiswa.nim='$get_nim' ");
    $fretrieve_info=$retrieve_info->fetch_object();

    $nama_pembimbing1="SELECT * FROM dosen WHERE dosen.nip='".$fretrieve_info->nip1."' ";
    $nama_pembimbing2="SELECT * FROM dosen WHERE dosen.nip='".$fretrieve_info->nip2."' ";
    $fnama_pembimbing1=$con->query($nama_pembimbing1);
    $fnama_pembimbing2=$con->query($nama_pembimbing2);
    $rnama_pembimbing2=$fnama_pembimbing2->fetch_object();
  ?>

  <html>
  <head>
    <title>Print Form Ujian Kelayakan</title>
    <style> 
      table {  
        border-collapse: collapse;
        font-size:20px;
        font-family:"Times New Roman"
      }
      tr,td{
        padding: 15px;
      }
      .head{
        text-align: left;
        padding: 0px 0px 0px 550px;
      }
      .ttd{
        padding: 0px 0px 0px 500px;
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
    <table>
      <tr>
        <td></td>
        <td class='head'>Form Ujian Sarjana</td>
      </tr>
    </table>
    <hr>
    <h2 style="text-align:center;">
      UJIAN TUGAS AKHIR<br>
      DEPARTEMEN KIMIA FAKULTAS SAINS DAN MATEMATIKA<br>
      UNIVERSITAS DIPONEGORO SEMARANG
    </h2><hr><br>
    <table>
      <tr>
      <?php
        echo '<td>NAMA MAHASISWA</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info->nama. '</td>';
        echo '<td></td>';
      echo'</tr>';
      echo '<tr>';
        echo '<td>NIM</td>';
        echo '<td>:</td>';
        echo '<td>'.$get_nim.'</td>';
        echo '<td></td>';
      echo'</tr>';
      echo '<tr>';
        echo '<td>TANGGAL & WAKTU</td>';
        echo '<td>:</td>';
        echo '<td>'.strftime("%e %B %Y",strtotime($fretrieve_info->jadwal)).'</td>';
        echo '<td></td>';
      echo'</tr>';
      echo '<tr>';
        echo '<td>TEMPAT</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info->tempat.'</td>';
        echo '<td></td>';
      echo'</tr>';
      echo '<tr>';
        echo '<td>JUDUL SKRIPSI</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info->judul_tr2.'</td>';
        echo '<td></td>';
      echo'</tr>';
      echo '<tr>';
        echo '<td>PEMBIMBING</td>';
        echo '<td>:</td>';
      //daftar pembimbing
      while($rnama_pembimbing1=$fnama_pembimbing1->fetch_object()){
        echo "<td> 1. ".$rnama_pembimbing1->nama."</td>";
        echo "<td></td>";
      }
      echo'</tr>';
      echo '<tr>';
        if(!$rnama_pembimbing2){
          echo "<td></td>";
          echo "<td></td>";
          echo "<td></td>";
        }else{
          echo "<td></td>";
          echo "<td></td>";
          echo"<td class='numerasi'> 2. ".$rnama_pembimbing2->nama."</td>";
          echo "<td></td>"; 
        } 
      echo'</tr>';
      echo '<tr>';
        echo '<td>PENGUJI</td>';
        echo '<td>:</td>';
        echo '<td> KETUA </td>';
        echo '<td>aaaaaaaa</td>';
      echo'</tr>';
      echo '<tr>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td> SEKRETARIS </td>';
        echo '<td>aaaaaaaa</td>';
      echo'</tr>';
      echo '<tr>';
        echo '<td>ANGGOTA</td>';
        echo '<td>:</td>';
      //daftar anggota
      while($rnama_pembimbing1=$fnama_pembimbing1->fetch_object()){
        echo "<td> 1. ".$rnama_pembimbing1->nama."</td>";
        echo '<td></td>';
      };
      echo'</tr>';
      echo '<tr>';
        if(!$rnama_pembimbing2){
          echo "<td></td>";
          echo "<td></td>";
          echo "<td></td>";
        }else{
          echo "<td></td>";
          echo "<td></td>";
          echo"<td class='numerasi'> 2. ".$rnama_pembimbing2->nama."</td>";
          echo "<td></td>"; 
        } 
      echo'</tr>';
      echo '<tr>';
        echo '<td>CATATAN</td>';
        echo '<td>:</td>';
        echo '<td>'.$fretrieve_info->judul_tr2.'</td>';
        echo '<td></td>';
      echo'</tr>';
    ?>
    </table>
    <br><br><br><br><br>
    <table >
      <tr>
        <td class='ttd'>Semarang, </td>
      </tr>
      <tr>
        <td class='ttd'>Panitia Ujian,</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class='ttd'><hr>NIP</td>
      </tr>
    </table>
  </body>
    
  </html>
  <?php 
    mysqli_close($con);
    include_once('footer.php');
  }
  ?>