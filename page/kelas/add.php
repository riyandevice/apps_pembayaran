<?php
    include '../../config/Config.php';
    $page       = "Tambah Kelas";
    $menu       = "Kelas";
    $submenu    = "Data Master";
    $config = new Config();

    include "hak-akses.php";

// Jika Sudah Login
if(!empty($_SESSION['kodeakses'])) {   
    include "../../layout/header.php";    
?> 
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="<?= $base_url; ?>">Home</a></li>
        <li><?= $submenu; ?></li>    
        <li class="active"><?= $page; ?></li>
    </ul>
    <!-- END BREADCRUMB -->   

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
    
        <div class="row">
            <div class="col-md-12">
                
                <form class="form-horizontal" action="_action.php" method="POST" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $page; ?></h3>
                    </div>
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Jurusan</label>
                            <div class="col-md-6 col-xs-12"> 
                                <select class="form-control select" name="id_jurusan" data-live-search="true">
                                    <?php
                                        $query_jurusan = $config->getData("SELECT * FROM tbl_jurusan");

                                        foreach ($query_jurusan as $qj) {
                                    ?>
                                            <option value="<?= $qj['id_jurusan']; ?>"><?= $qj['nama_jurusan']; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Tingkat</label>
                            <div class="col-md-6 col-xs-12"> 
                                <select class="form-control select" name="tingkat_kelas">
                                    <option value="10">X</option>
                                    <option value="11">XI</option>
                                    <option value="12">XII</option>
                                </select>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Nama Kelas</label>
                            <div class="col-md-6 col-xs-12"> 
                                <input type="text" class="form-control" name="nama_kelas" placeholder="Masukan Nama Kelas" required=""/>    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Wali Kelas</label>
                            <div class="col-md-6 col-xs-12"> 
                                <select class="form-control select" name="id_pegawai" data-live-search="true">
                                    <?php
                                        $query_pegawai = $config->getData("SELECT * FROM tbl_pegawai WHERE level_pegawai='Wali Kelas' AND aktif_pegawai='1'");

                                        foreach ($query_pegawai as $qj) {
                                    ?>
                                            <option value="<?= $qj['id_pegawai']; ?>"><?= $qj['nama_pegawai']; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>    
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button class="btn btn-primary pull-right" name="simpan">Simpan</button>
                    </div>
                </div>
                </form>
                
            </div>
        </div>                    
        
    </div>
    <!-- END PAGE CONTENT WRAPPER -->                                                
<?php
    include "../../layout/footer.php";
} else {
    header("Location:" . $base_url . "login.php");
}

?>          


