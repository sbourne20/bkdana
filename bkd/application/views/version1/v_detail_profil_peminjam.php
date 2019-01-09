<style type="text/css">
    .img-pinjam { width: auto; max-width: 350px; height: auto; max-height: 200px; }
</style>

<!-- Header -->
<header class="overflow-wrapp">
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<!-- Content -->
<div class="wrapper-content overflow-wrapp">
    <div class="section-member-page">
        
        <div class="container">
            <div class="row">

                <?php 
                if ($this->session->userdata('message')) {
                    $message_show = $this->session->userdata('message');
                    $msg_type = $this->session->userdata('message_type');
                    if ($msg_type == 'error') {
                        $icon  = 'error_icon.png';
                        $color = 'danger';
                    }else if ($msg_type == 'success') {
                        $icon  = 'success_icon.png';
                        $color = 'success';
                    }else{
                        $icon = 'info_icon.png';
                        $color = 'info';
                    }
                ?>
                <div class="col-sm-12">
                <div class="alert alert-<?php echo $color; ?> text-center"><img src="<?php echo base_url(); ?>assets/images/<?php echo $icon; ?>" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                </div>
                <?php } ?>


                <div class="col-sm-3">
                    <div class="box plain left">
                        <?php $this->load->view('version1/v_menu_dashboard'); ?>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="box plain right">
                        <div class="content">
                            <h1>Profil Peminjam</h1>
                            <div class="sub-title">Detail Profil Peminjam</div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Profil Peminjam</div>
                                        <div class="panel-body">
                                            <table class="table-custom">
                                                <tr>
                                                    <td><span>Nama Lengkap</span> <?php echo $data_profil['Nama_pengguna'] ?></td>
                                                    <td><span>E-mail</span> <?php echo $data_profil['mum_email'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>No. Telp</span> <?php echo $data_profil['mum_telp'] ?></td>
                                                    <td><span>Jenis Kelamin</span> <?php echo $data_profil['Jenis_kelamin'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Tempat Lahir</span> <?php echo $data_profil['Tempat_lahir'] ?></td>
                                                    <td><span>Tanggal Lahir</span> <?php echo $data_profil['Tanggal_lahir'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Alamat</span> <?php echo $data_profil['Alamat'] ?></td>
                                                    <td><span>Kota</span> <?php echo $data_profil['Kota'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Provinsi</span> <?php echo $data_profil['Provinsi'] ?></td>
                                                     <td><span>Kode Pos</span> <?php echo $data_profil['Kodepos'] ?></td>
                                                </tr>
                                                <tr>
                                                    <?php if ($data_profil['mum_type_peminjam']=='2') { ?>
                                                    <td><span>Usaha</span> <?php echo $data_profil['What_is_the_name_of_your_business'] ?>
                                                    </td>
                                                    <?php }?>
                                                    <?php if ($data_profil['mum_type_peminjam']=='1') {
                                                    
                                                        $pk = $data_profil['Pekerjaan'];
                                                            switch($pk){
                                                                case '1':
                                                                $label_pk = 'PNS';
                                                                break;
                                                                case '2':
                                                                $label_pk = 'BUMN';
                                                                break;
                                                                case '3':
                                                                $label_pk = 'Swasta';
                                                                break; 
                                                                case '4':
                                                                $label_pk = 'Wiraswasta';
                                                                break; 
                                                                case '5':
                                                                $label_pk = 'Lain-Lain';
                                                                break;  
                                                            }
                                                    ?>
                                                    <td><span>Pekerjaan</span> <?php echo  $label_pk; ?>
                                                    </td>
                                                    <?php }?>
                                                    <td><span>Nomor NIK</span> <?php echo $data_profil['Id_ktp'] ?></td>
                                                </tr>
                                                <?php if ($data_profil['mum_type_peminjam']=='1') { ?>
                                                <tr>
                                                    <?php
                                                        $sk = $data_profil['status_karyawan'];
                                                            switch($sk){
                                                                case '1':
                                                                $label_sk = 'Kontrak';
                                                                break;
                                                                case '2':
                                                                $label_sk = 'Tetap';
                                                                break; 
                                                            }
                                                    ?>
                                                    <td><span>Status Bekerja</span> <?php echo $label_sk; ?></td>
                                                    <?php
                                                        $p = $data_profil['Pendidikan'];
                                                            switch($p){
                                                                case '1':
                                                                $label_p = 'SD';
                                                                break;
                                                                case '2':
                                                                $label_p = 'SLTP';
                                                                break;
                                                                case '3':
                                                                $label_p = 'SLTA';
                                                                break; 
                                                                case '4':
                                                                $label_p = 'DIPLOMA';
                                                                break; 
                                                                case '5':
                                                                $label_p = 'SARJANA';
                                                                break;  
                                                            }
                                                    ?>
                                                    <td><span>Pendidikan</span> <?php echo  $label_p; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Nama Perusahaan</span> <?php echo $data_profil['nama_perusahaan'] ?></td>
                                                    <td><span>Telepon Tempat Bekerja</span> <?php echo $data_profil['nama_perusahaan'] ?></td>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($data_profil['images_foto_name'] != ''){ 
                                                            $foto_profil = $this->config->item('images_uri') . '/member/'.$data_profil['id_mod_user_member']. '/foto/'. $data_profil['images_foto_name'];
                                                        ?>
                                                        <span>Foto Profil</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_profil; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                    <!--tambahan foto usaha-->
                                                     <?php if ($data_profil['mum_type_peminjam']=='2') { ?>
                                                    <td>
                                                        <?php if ($data_profil['images_usaha_name'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$data_profil['id_mod_user_member']. '/usaha/'. $data_profil['images_usaha_name'];
                                                        ?>
                                                        <span>Foto Usaha</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                         <?php if ($data_profil['images_usaha_name2'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$data_profil['id_mod_user_member']. '/usaha2/'. $data_profil['images_usaha_name2'];
                                                        ?>
                                                        <span>Foto Usaha 2</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($data_profil['images_usaha_name3'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$data_profil['id_mod_user_member']. '/usaha3/'. $data_profil['images_usaha_name3'];
                                                        ?>
                                                        <span>Foto Usaha 3</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                         <?php if ($data_profil['images_usaha_name4'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$data_profil['id_mod_user_member']. '/usaha4/'. $data_profil['images_usaha_name4'];
                                                        ?>
                                                        <span>Foto Usaha 4</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($data_profil['images_usaha_name5'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$data_profil['id_mod_user_member']. '/usaha5/'. $data_profil['images_usaha_name5'];
                                                        ?>
                                                        <span>Foto Usaha 5</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
<!-- 
                                <?php 
                                // if ($transaksi['type_of_business_id']==1)
                                // {
                                //     $label_tenor = 'Hari';
                                // }else{
                                //     $label_tenor = 'Bulan';
                                //}
                                ?> -->
                                
                            
                            <br>
                            <div class="text-center">
                                <a href="javascript:;" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>