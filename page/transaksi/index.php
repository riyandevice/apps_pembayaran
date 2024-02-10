<?php
    include '../../config/Config.php';
    $page       = "Transaksi";
    $menu       = "Transaksi";
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
            
            <button class="btn btn-info btn-lg" data-toggle="collapse" data-target="#pembayaran"><i class='fa fa-plus'></i> Tambah Transaksi</button>
            <a class="btn btn-danger btn-lg"><i class="fa fa-arrow-left"></i> Silahkan Gunakan Tombol Tambah Transaksi untuk Membayar</a><br/><br/>

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

                
            </div>
        </div>                                
        
    </div>
    
<?php
    include "../../layout/footer.php";
} else {
    header("Location:" . $base_url . "login.php");
}

?>