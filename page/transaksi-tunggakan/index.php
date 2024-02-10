<?php
    include '../../config/Config.php';
    $page       = "Transaksi Tunggakan";
    $menu       = "Transaksi Tunggakan";
    $submenu    = "";
    $config = new Config();
    include "hak-akses.php";
    
    $query = "SELECT * FROM tbl_transaksi t, tbl_siswa s, tbl_pegawai p, tbl_pembayaran pn, tbl_kelas k WHERE t.id_siswa = s.id_siswa AND t.id_pegawai = p.id_pegawai AND t.id_pembayaran = pn.id_pembayaran AND t.id_kelas = k.id_kelas ORDER BY t.id_transaksi DESC";
    $result = $config->getData($query);

// Jika Sudah Login
if(!empty($_SESSION['kodeakses'])) {   
    include "../../layout/header.php";    
?>      
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="<?= $base_url; ?>">Home</a></li>
        <li class="active"><?= $page; ?></li>
    </ul>
    <!-- END BREADCRUMB -->    

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">                

        <div class="row">
            <div class="col-md-12">
            <?= @$_SESSION['cetak']; 
                unset($_SESSION['cetak']);
            ?>
            <div class="btn-group pull-right">
                <a class="btn btn-danger btn-lg" href="export.php"><i class="fa fa-file"></i> Download Excell</a>
            </div>
            <button class="btn btn-info btn-lg" data-toggle="collapse" data-target="#pembayaran"><i class='fa fa-plus'></i> Tambah Transaksi Tunggakan</button><br/><br/>

                <form class="form-horizontal collapse" action="add.php" method="POST" enctype="multipart/form-data" id="pembayaran">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $page; ?> Pembayaran</h3>
                    </div>
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Nomor Induk Siswa</label>
                            <div class="col-md-6 col-xs-12"> 
                                <input type="text" class="form-control" name="nis" placeholder="Masukan Nomor Induk Siswa" required="" onkeypress="return isNumberKey(event)" />    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Untuk Pembayaran</label>
                            <div class="col-md-6 col-xs-12"> 
                                <select class="form-control select" name="jenis_pembayaran" data-live-search="true">
                                <?php
                                    $query_pembayaran = $config->getData("SELECT * FROM tbl_pembayaran WHERE aktif_pembayaran='1' ORDER BY id_pembayaran DESC");
                                    foreach ($query_pembayaran as $qp) {
                            
                                    echo "<option value='". $qp['id_pembayaran'] ."'>". $qp['nama_pembayaran'] ."</option>";
                                    }
                                ?>

                                </select>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Kelas</label>
                            <div class="col-md-6 col-xs-12"> 
                                <select class="form-control select" name="id_kelas" data-live-search="true">
                                    <?php
                                        $query_kelas = $config->getData("SELECT * FROM tbl_kelas");

                                        foreach ($query_kelas as $qk) {
                                    ?>
                                            <option value="<?= $qk['id_kelas']; ?>"><?= $config->format_romawi($qk['tingkat_kelas']); ?> <?= $qk['nama_kelas']; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Metode Pembayaran</label>
                            <div class="col-md-6 col-xs-12"> 
                                <select class="form-control select" name="metode_pembayaran">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>    
                            </div>
                        </div>
                    </div>

                    
                    <div class="panel-footer">
                        <button class="btn btn-primary pull-right" name="simpan">Cari</button>
                    </div>
                </div>
                </form>

                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Riwayat <?= $page; ?></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table datatable ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th><center>Waktu</center></th>
                                    <th><center>Siswa</center></th>
                                    <th><center>Kelas</center></th>
                                    <th><center>Pembayaran</center></th>
                                    <th><center>Metode</center></th>
                                    <th><center>Nominal</center></th>
                                    <th><center>Opsi</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i=1;
                                    foreach ($result as $r) {
									$kelas=$config->format_romawi($r['tingkat_kelas']);
                                ?>

                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= date("d-m-Y H:i:s", strtotime(ucfirst($r['waktu_transaksi']))); ?></td>
                                    <td><?= ucfirst($r['nama_siswa']); ?></td>
                                    <td><?= $kelas." - ".ucfirst($r['nama_kelas']); ?></td>
                                    <td><?= ucfirst($r['nama_pembayaran']); ?></td>
                                    <?php
                                        if($r['file_foto']<>'0') {
                                    ?>
                                    <td><a href="<?= $base_url . 'assets/img/bukti_pembayaran/' . $r['file_foto']; ?>" target='_blank' ><?= $r['pembayaran_melalui']; ?></a></td>
                                    <?php
                                        } else {
                                    ?>
                                        <td><?= $r['pembayaran_melalui']; ?></td>
                                    <?php
                                        }
                                    ?>

                                    <td><?= $config->format_rupiah($r['nominal_transaksi']); ?></td>
                                    <td align="center">
									<?php 
										$enidtransaksi1=base64_encode($r['id_transaksi']);
										$enidtransaksi2=base64_encode($enidtransaksi1);
										$enidtransaksi3=base64_encode($enidtransaksi2);
										
										$enidsiswa1=base64_encode($r['id_siswa']);
										$enidsiswa2=base64_encode($enidsiswa1);
										$enidsiswa3=base64_encode($enidsiswa2);
										

										$enidkelas=base64_encode(base64_encode(base64_encode($r['id_kelas'])));

										$enidpembayaran1=base64_encode($r['id_pembayaran']);
										$enidpembayaran2=base64_encode($enidpembayaran1);
										$enidpembayaran3=base64_encode($enidpembayaran2);
										
										$encicilan1=base64_encode($r['cicilan_transaksi']);
										$encicilan2=base64_encode($encicilan1);
										$encicilan3=base64_encode($encicilan2);
									?>
                                        <a href="cetak.php?id=<?= $enidtransaksi3; ?>&siswa=<?= $enidsiswa3; ?>&pembayaran=<?= $enidpembayaran3; ?>&cicilan=<?= $encicilan3; ?>&id_kelas=<?= $enidkelas; ?>" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i></a>
                                    </td>
                                </tr>

                                <?php
                                    $i++;
                                    }
                                ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END DEFAULT DATATABLE -->
            </div>
        </div>                                
        
    </div>
    
<?php
    include "../../layout/footer.php";
} else {
    header("Location:" . $base_url . "login.php");
}

?>