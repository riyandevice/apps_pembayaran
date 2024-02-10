<?php
    include '../../config/Config.php';
    $page       = "Siswa";
    $menu       = "Siswa";
    $submenu    = "Data Master";
    $config = new Config();

    include "hak-akses.php";
    $query = "SELECT * FROM tbl_siswa, tbl_kelas WHERE tbl_siswa.id_kelas = tbl_kelas.id_kelas ORDER BY tbl_siswa.id_siswa DESC";
    $result = $config->getData($query);

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
                <?= @$_SESSION['status']; 
                    unset($_SESSION['status']);
                ?>
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default">
                    <div class="panel-heading">                                
                        <h3 class="panel-title">Data <?= $page; ?></h3>
                        <div class="btn-group pull-right">
                            <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                            <ul class="dropdown-menu">
                                <li><a href="export.php">Export Excel</a></li>
                                <li><a href="import.php">Import Excel</a></li>
                            </ul>
                        </div>


                        <ul class="panel-controls">
                            <a href="_action.php?kenaikankelas=TRUE" onClick="return confirm('Apakah Anda Yakin Ingin menaikan kelas ?')" class="btn btn-success"><i class="fa fa-home"></i>Kenaikan Kelas</a>
                        </ul>

                        <ul class="panel-controls">
                            <a href="add.php" class="btn btn-info"><i class="fa fa-plus"></i>Tambah Data</a>
                        </ul>
                                                       
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th><center>No</center></th>
                                    <th><center>NIS</center></th>
                                    <th><center>Nama</center></th>
                                    <th><center>Jenis Kelamin</center></th>
                                    <th><center>Kelas</center></th>
                                    <th><center>Angkatan</center></th>
                                    <th><center>Status</center></th>
                                    <th><center>Opsi</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i=1;
                                    foreach ($result as $r) {
                                ?>

                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= ucfirst($r['nis']); ?></td>
                                    <td><?= ucfirst($r['nama_siswa']); ?></td>
                                    <td><?= ucfirst($r['jekel_siswa']); ?></td>
                                    <td><?= $config->format_romawi(ucfirst($r['tingkat_kelas'])); ?> <?= ucfirst($r['nama_kelas']); ?></td>
                                    <td><?= ucfirst($r['angkatan_siswa']); ?></td>
                                    <td>
                                        <?php
                                            if($r['aktif_siswa']=='1') {
                                                echo "Aktif";
                                            } else if($r['aktif_siswa']=='11') {
                                                echo "Lulus";
                                            } else {
                                                echo "Tidak Aktif";
                                            }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <img src="<?= $base_url . 'assets/img/siswa/' . $r['foto_siswa']; ?>" width='100' height='150'><br/>
										<?php 
											$id1 =base64_encode($r['id_siswa']);
											$id2 =base64_encode($id1);
											$id3 =base64_encode($id2);
										?>
                                        <a href="update.php?id=<?= $id3; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                        <a href="_action.php?id=<?= $id3; ?>&foto_siswa=<?= $r['foto_siswa']; ?>" class="btn btn-danger" onClick="return confirm('Apakah Anda Yakin Ingin Menghapus Ini?')"><i class="fa fa-times"></i></a>
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
    <!-- PAGE CONTENT WRAPPER -->

<?php
    include "../../layout/footer.php";
} else {
    header("Location:" . $base_url . "login.php");
}

?>