<?PHP
  include_once("../../config/Config.php");

  $config = new Config();

  include "hak-akses.php";
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // filename for download
  $filename = "Data Siswa " . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  $query = "SELECT t.waktu_transaksi AS 'WAKTU', s.nama_siswa AS 'NAMA SISWA', CONCAT_WS(' ', k.tingkat_kelas, k.nama_kelas) AS 'NAMA KELAS', pn.nama_pembayaran AS 'PEMBAYARAN', t.pembayaran_melalui AS 'METODE', t.nominal_transaksi AS 'NOMINAL' FROM tbl_transaksi t, tbl_siswa s, tbl_pegawai p, tbl_pembayaran pn, tbl_kelas k WHERE t.id_siswa = s.id_siswa AND t.id_pegawai = p.id_pegawai AND t.id_pembayaran = pn.id_pembayaran AND t.id_kelas = k.id_kelas ORDER BY t.id_transaksi DESC";
  $data = $config->getData($query);

  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
  }
  exit;
?>