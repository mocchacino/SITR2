<?php	
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'dosen'){
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			// $nim='24010314120014';
			$id = $_POST['id'];
			$query = mysqli_query($con,"SELECT mahasiswa.nim, mahasiswa.nama, jadwal, tempat, nip1, nip2, nip3, judul FROM daftar_skripsi inner join mahasiswa on mahasiswa.nim=daftar_skripsi.nim inner join uji_skripsi on uji_skripsi.nim=mahasiswa.nim inner join penguji_skripsi on penguji_skripsi.id_uji_skripsi=uji_skripsi.id_uji_skripsi  where uji_skripsi.id_uji_skripsi='$id' ");
			$row = $query->fetch_row();
			$pembimbing1=mysqli_query($con, "SELECT nip, nama_dosen from dosen where dosen.nip='$row[4]' ");
			$rpembimbing1=$pembimbing1->fetch_assoc();

			$pembimbing2=mysqli_query($con, "SELECT nip, nama_dosen from dosen where dosen.nip='$row[5]' ");
			$rpembimbing2=$pembimbing2->fetch_assoc();
			
			$pembimbing3=mysqli_query($con, "SELECT nip, nama from pembimbing_luar where pembimbing_luar.nip='$row[6]' ");
			$rpembimbing3=$pembimbing3->fetch_assoc();

			$penguji = mysqli_query($con,"SELECT dosen.nip, dosen.nama_dosen FROM dosen inner join penguji_skripsi on penguji_skripsi.nip_penguji_skripsi=dosen.nip inner join uji_skripsi on uji_skripsi.id_uji_skripsi=penguji_skripsi.id_uji_skripsi where uji_skripsi.id_uji_skripsi='$id' and jabatan!='sekretaris' order by jabatan"); 

			$ket_penguji = mysqli_fetch_all ($penguji, MYSQLI_ASSOC);
			$jml_penguji = mysqli_num_rows($penguji);
			
			// Cek key kosong
			if (isset($ket_penguji[2])) {
				$ket_penguji_3 = $ket_penguji[2];
			}
			else {
				$ket_penguji_3 = array('nip' => '', 'nama_dosen' => '');
			}

			// Cek key kosong
			if (isset($ket_penguji[3])) {
				$ket_penguji_4 = $ket_penguji[3];
			}
			else {
				$ket_penguji_4 = array('nip' => '', 'nama_dosen' => '');
			}

			// Cek key kosong
			if (isset($ket_penguji[4])) {
				$ket_penguji_5 = $ket_penguji[4];
			}
			else {
				$ket_penguji_5 = array('nip' => '', 'nama_dosen' => '');
			}

			$data = array('nim'=>$row[0],'nama'=>$row[1],'jadwal'=>$row[2],'tempat'=>$row[3],'pembimbing1'=>$rpembimbing1['nama_dosen'],'pembimbing2'=>$rpembimbing2['nama_dosen'],'pembimbing3'=>$rpembimbing3['nama'],'judul'=>$row[7],'nipPenguji1'=>$ket_penguji[0]['nip'],'namaPenguji1'=>$ket_penguji[0]['nama_dosen'],'nipPenguji2'=>$ket_penguji[1]['nip'],'namaPenguji2'=>$ket_penguji[1]['nama_dosen'],'nipPenguji3'=>$ket_penguji_3['nip'],'namaPenguji3'=>$ket_penguji_3['nama_dosen'],'nipPenguji4'=>$ket_penguji_4['nip'],'namaPenguji4'=>$ket_penguji_4['nama_dosen'],'nipPenguji5'=>$ket_penguji_4['nip'],'namaPenguji5'=>$ket_penguji_5['nama_dosen'],'jml_penguji'=>$jml_penguji, 'nipPembimbing1'=> $rpembimbing1['nip'], 'nipPembimbing2'=> $rpembimbing2['nip'], 'nipPembimbing3'=> $rpembimbing3['nip'],);
			if($row==null){
				echo null;
			}else{
				$data = json_encode($data);
				echo ($data);
			}
		}

		
		
	}

?>