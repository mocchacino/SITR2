<?php	
	require_once('../functions.php');	
	if (!isset($_SESSION['sip_masuk_aja'])){
		header("Location:../login/login.php");
	}elseif($status == 'petugas'){
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			// $nim='24010314120014';
			$id = $_POST['id'];
			$query = mysqli_query($con,"SELECT DISTINCT mahasiswa.nama, jadwal, tempat, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3, daftar_tugas_riset2.judul FROM daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join uji_kelayakan on uji_kelayakan.nim=mahasiswa.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim where uji_kelayakan.id_uji_kelayakan='$id' ");
			//$query = mysqli_query($con,"SELECT mahasiswa.nama, jadwal, tempat, daftar_tugas_riset2.nip1, daftar_tugas_riset2.nip2, daftar_tugas_riset2.nip3, daftar_tugas_riset2.judul FROM daftar_uji_kelayakan inner join mahasiswa on mahasiswa.nim=daftar_uji_kelayakan.nim inner join tr1 on tr1.nim=mahasiswa.nim inner join uji_kelayakan on uji_kelayakan.nim=mahasiswa.nim inner join daftar_tugas_riset2 on daftar_tugas_riset2.nim=daftar_uji_kelayakan.nim where uji_kelayakan.is_lulus='0' and tr1.idlab_tr1='$idlab' and mahasiswa.nim='$nim' and uji_kelayakan.id_uji_kelayakan='$id' ");
			$row = $query->fetch_row();
			$pembimbing1=mysqli_query($con, "SELECT nama_dosen from dosen where dosen.nip='$row[3]' ");
			$rpembimbing1=$pembimbing1->fetch_assoc();

			$pembimbing2=mysqli_query($con, "SELECT nama_dosen from dosen where dosen.nip='$row[4]' ");
			$rpembimbing2=$pembimbing2->fetch_assoc();
			
			$pembimbing3=mysqli_query($con, "SELECT nama from pembimbing_luar where pembimbing_luar.nip='$row[5]' ");
			$rpembimbing3=$pembimbing3->fetch_assoc();

			$penguji = mysqli_query($con,"SELECT dosen.nip, dosen.nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.id_uji_kelayakan='$id' "); 
			//$penguji = mysqli_query($con,"SELECT dosen.nip, dosen.nama_dosen FROM dosen inner join penguji_kelayakan on penguji_kelayakan.nip_penguji_kelayakan=dosen.nip inner join uji_kelayakan on uji_kelayakan.id_uji_kelayakan=penguji_kelayakan.id_uji_kelayakan where uji_kelayakan.nim='$nim' and uji_kelayakan.id_uji_kelayakan='$id' "); 

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

			$data = array('nama'=>$row[0],'jadwal'=>$row[1],'tempat'=>$row[2],'pembimbing1'=>$rpembimbing1['nama_dosen'],'pembimbing2'=>$rpembimbing2['nama_dosen'],'pembimbing3'=>$rpembimbing3['nama'],'judul'=>$row[6],'nipPenguji1'=>$ket_penguji[0]['nip'],'namaPenguji1'=>$ket_penguji[0]['nama_dosen'],'nipPenguji2'=>$ket_penguji[1]['nip'],'namaPenguji2'=>$ket_penguji[1]['nama_dosen'],'nipPenguji3'=>$ket_penguji_3['nip'],'namaPenguji3'=>$ket_penguji_3['nama_dosen'],'nipPenguji4'=>$ket_penguji_4['nip'],'namaPenguji4'=>$ket_penguji_4['nama_dosen'],'jml_penguji'=>$jml_penguji);
			if($row==null){
				echo null;
			}else{
				$data = json_encode($data);
				echo ($data);
			}
		}

		
		
	}

?>