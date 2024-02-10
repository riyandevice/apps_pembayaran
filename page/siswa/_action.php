<?php
	session_start();
	error_reporting(0);
	include_once("../../config/Config.php");
	include "../../config/Upload.php";

	$config = new Config();

	include "hak-akses.php";
	
	$nis = "'" . $config->escape_string($_POST['nis']) . "'";
	$nama_siswa = "'" . $config->escape_string($_POST['nama_siswa']) . "'";
	$jekel_siswa = "'" . $config->escape_string($_POST['jekel_siswa']) . "'";
	$alamat_siswa = "'" . $config->escape_string($_POST['alamat_siswa']) . "'";
	$id_kelas 	= "'" . $config->escape_string($_POST['id_kelas']) . "'";
	$aktif_siswa 	= "'" . $config->escape_string($_POST['aktif_siswa']) . "'";
	$angkatan_siswa 	= "'" . $config->escape_string($_POST['angkatan_siswa']) . "'";
	
	//Upload Foto
	$lokasi_file = $_FILES['fupload']['tmp_name'];
	$nama_fileex = $_FILES['fupload']['name'];
	$nama_file   = time()."-".$nama_fileex;
	$ukuran 	 = $_FILES['fupload']['size'];

if(isset($_POST['simpan'])) {
	if(empty($lokasi_file)) {
		$result = $config->execute("
			INSERT INTO tbl_siswa(
				nis,
				nama_siswa,
				jekel_siswa,
				alamat_siswa,
				foto_siswa,
				id_kelas,
				aktif_siswa,
				angkatan_siswa) 
			VALUES(
				$nis,
				$nama_siswa,
				$jekel_siswa,
				$alamat_siswa,
				'default.jpg',
				$id_kelas,
				$aktif_siswa,
				$angkatan_siswa)
			");
	}
	else {	
		UploadSiswa($nama_file);
		$result = $config->execute("
			INSERT INTO tbl_siswa(
				nis,
				nama_siswa,
				jekel_siswa,
				alamat_siswa,
				foto_siswa,
				id_kelas,
				aktif_siswa,
				angkatan_siswa) 
			VALUES(
				$nis,
				$nama_siswa,
				$jekel_siswa,
				$alamat_siswa,
				'$nama_file',
				$id_kelas,
				$aktif_siswa,
				$angkatan_siswa)
			");
	}

	if($result) {
		$config->getHistory("Menambahkan Siswa " . $_POST['nama_siswa']);
		$_SESSION['status'] = '<div class="alert alert-success" role="alert">
	        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <strong>Selamat !</strong> Data berhasil disimpan
	    </div>';
	}
	else {
		$_SESSION['status'] = '<div class="alert alert-danger" role="alert">
	        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <strong>Gagal !</strong> Data gagal disimpan, mungkin data yang anda masukan salah
	    </div>';
	}

	header('Location: index.php');
}

else if (isset($_POST['update'])) {
	$idj1 =base64_decode($_POST['id_siswa']);
	$idj2 =base64_decode($idj1);
	$idj3 =base64_decode($idj2);
	$id_siswa 	= "'" . $config->escape_string($idj3) . "'";
	if(empty($lokasi_file)) {
		$result = $config->execute("
			UPDATE tbl_siswa SET 
				nis				= $nis,
				nama_siswa		= $nama_siswa,
				jekel_siswa		= $jekel_siswa,
				alamat_siswa	= $alamat_siswa,
				id_kelas		= $id_kelas,
				aktif_siswa		= $aktif_siswa,
				angkatan_siswa	= $angkatan_siswa
			WHERE 
				id_siswa		= $id_siswa
		");
	} else {
		UploadSiswa($nama_file);
		$result = $config->execute("
			UPDATE tbl_siswa SET 
				nis				= $nis,
				nama_siswa		= $nama_siswa,
				jekel_siswa		= $jekel_siswa,
				alamat_siswa	= $alamat_siswa,
				foto_siswa		= '$nama_file',
				id_kelas		= $id_kelas,
				aktif_siswa		= $aktif_siswa,
				angkatan_siswa	= $angkatan_siswa
			WHERE 
				id_siswa		= $id_siswa
		");
		$foto_lama = $_POST['fupload_lama'];
		if ($foto_lama<>"default.jpg") {
			unlink("../../assets/img/siswa/". $foto_lama);
		}
		
	}

	if($result) {
		$config->getHistory("Mengubah Siswa " . $_POST['nama_siswa']);
		$_SESSION['status'] = '<div class="alert alert-success" role="alert">
	        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <strong>Selamat !</strong> Data berhasil disimpan
	    </div>';
	}
	else {
		$_SESSION['status'] = '<div class="alert alert-danger" role="alert">
	        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <strong>Gagal !</strong> Data gagal disimpan, mungkin data yang anda masukan salah
	    </div>';
	}
	header("Location: index.php");
}
else if (isset($_POST['import'])) {
	set_time_limit(0);
 
	require '../../config/PHPExcel/PHPExcel/IOFactory.php';
	$inputfilename = $_FILES["url_file"]["tmp_name"];
	$exceldata = array();

	//  Read your Excel workbook
	try
	{
	    $inputfiletype = PHPExcel_IOFactory::identify($inputfilename);
	    $objReader = PHPExcel_IOFactory::createReader($inputfiletype);
	    $objPHPExcel = $objReader->load($inputfilename);
	}
	catch(Exception $e)
	{
	    die('Error loading file "'.pathinfo($inputfilename,PATHINFO_BASENAME).'": '.$e->getMessage());
	}

	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();

	//  Loop through each row of the worksheet in turn
			
	for ($row = 2; $row <= $highestRow; $row++) //baris ke 2 (tanpa judul kolom)
	{ 
		// Calculate the percentation http://stackoverflow.com/questions/15298071/progress-bar-with-mysql-query-with-php
		$percent = intval($row/$highestRow * 100)."%";

		// Javascript for updating the progress bar and information
	  echo '<script language="javascript">
	  document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
	  document.getElementById("information").innerHTML="'.$row.' data pegawai sedang diproses.";
	  </script>';

	  // This is for the buffer achieve the minimum size in order to flush data
	  echo str_repeat(' ',1024*64);

	  // Send output to browser immediately
	  flush();

	  // Sleep one second so we can see the delay
	  sleep(0);
	  
	    //  Read a row of data into an array
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		
	    //  Insert row data array into your database of choice here
		$sql = "REPLACE INTO tbl_siswa VALUES 
				(
				'".$rowData[0][0]."',
				'".$rowData[0][1]."',
				'".$rowData[0][2]."',
				'".$rowData[0][3]."',
				'".$rowData[0][4]."',
				'".$rowData[0][5]."',
				'".$rowData[0][6]."',
				'".$rowData[0][7]."',
				'".$rowData[0][8]."'
				)";

		if ($config->execute($sql)) {

			$exceldata[] = $rowData[0];
		} else {
			//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}

	$config->getHistory("Mengimport Siswa " . $_POST['nama_siswa']);
	
	$_SESSION['status'] = '<div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>Selamat !</strong> Data berhasil diimport
    </div>';
	echo ("<script LANGUAGE='JavaScript'>
	        window.location.href='index.php';
	        </script>");
}

else if($_GET['kenaikankelas']=='TRUE') {
	$lengkap = '';
	$cek_kelas = $config->getData("SELECT count(*) as total FROM tbl_kelas");
	foreach ($cek_kelas as $ck) {
		$total_kelas = $ck['total'];
		$cek_kelas2 = $config->getData("SELECT count(DISTINCT nama_kelas) as total FROM tbl_kelas");
		foreach ($cek_kelas2 as $ck2) {
			$total_kelas2 = $ck2['total'] * 3;
			if($total_kelas == $total_kelas2) {
				$cek_kelas = $config->getData("SELECT * FROM tbl_siswa");
				foreach ($cek_kelas as $ck) {
					$id_kelas = $ck['id_kelas'];
					$cek_nama_kelas = $config->getData("SELECT * FROM tbl_kelas WHERE id_kelas='". $id_kelas ."'");
					foreach ($cek_nama_kelas as $cnk) {
						$tingkat_kelas = $cnk['tingkat_kelas']+1;
						$nama_kelas = $cnk['nama_kelas'];

						if($tingkat_kelas<=12) {
							$cek_id_kelas = $config->getData("SELECT * FROM tbl_kelas WHERE tingkat_kelas='". $tingkat_kelas ."' AND nama_kelas='". $nama_kelas ."'");
							foreach ($cek_id_kelas as $cik) {
								$ganti_id = $cik['id_kelas'];
								$result = $config->execute("UPDATE tbl_siswa SET id_kelas='". $ganti_id . "' WHERE id_kelas='". $id_kelas ."'");
							}
						} else {
							$result = $config->execute("UPDATE tbl_siswa SET aktif_siswa='11' WHERE id_kelas='". $id_kelas ."'");
						}	

					}	
				}

				if($result) {
					$config->getHistory("Menaikan Seluruh Kelas ");
					$_SESSION['status'] = '<div class="alert alert-success" role="alert">
				        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				        <strong>Selamat !</strong> Kelas berhasil dinaikkan
				    </div>';
				}
				else {
					$_SESSION['status'] = '<div class="alert alert-danger" role="alert">
				        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				        <strong>Gagal !</strong> Kelas Gagal dinaikkan
				    </div>';
				}
				header("Location: index.php");
			}
			else {
				$lengkap = 'TIDAK';
			}	
		}
	}

	if($lengkap=='TIDAK') {
		$_SESSION['status'] = '';
		$kelas_lengkap = $config->getData("SELECT * FROM tbl_kelas");
		foreach ($kelas_lengkap as $kl) {
			for ($i=10; $i <= 12; $i++) { 
				$kelas_lengkap2 = $config->getData("SELECT count(*) as total FROM tbl_kelas WHERE tingkat_kelas='". $i . "' AND nama_kelas='". $kl['nama_kelas'] ."'");

				foreach ($kelas_lengkap2 as $kl2) {
					if($kl2['total']==0) {
						$_SESSION['status'] .= '<div class="alert alert-danger" role="alert">
					        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					        <strong>Gagal !</strong> Kelas '. $config->format_romawi($i) . ' ' . $kl['nama_kelas'].' Tidak ada, Mohon ditambahkan kedata kelas
					    </div>';
						
					}
					
				}
			}
		}
		header("Location: index.php");
	}
}
		

else {
	$id1 =base64_decode($_GET['id']);
	$id2 =base64_decode($id1);
	$id3 =base64_decode($id2);
	$result = $config->execute("
		DELETE FROM tbl_siswa WHERE id_siswa='". $id3 ."'");
	if ($_GET['foto_siswa']<>"default.jpg") {
		unlink("../../assets/img/siswa/". $_GET['foto_siswa']);
	}

	if($result) {
		$config->getHistory("Menghapus Siswa " . $_POST['nama_siswa']);
		$_SESSION['status'] = '<div class="alert alert-success" role="alert">
	        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <strong>Selamat !</strong> Data berhasil dihapus
	    </div>';
	} else {
		$_SESSION['status'] = '<div class="alert alert-danger" role="alert">
	        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <strong>Gagal !</strong> Data gagal dihapus
	    </div>';
	}

	header("Location: index.php");
} 

?>
