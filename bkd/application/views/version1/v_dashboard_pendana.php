<?php
if ($memberdata['mum_type'] == '1'){
    $tipe_member = 'Peminjam';
}else{
    $tipe_member = 'Pendana';
}
?>

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
                    }else if ($msg_type == 'warning') {
                        $icon  = 'info_icon.png';
                        $color = 'warning';
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
                            <h1>Dashboard</h1>
                            <div class="sub-title">Selamat Datang, <?php echo $memberdata['Nama_pengguna']; ?></div>

                            <?php if ($memberdata['peringkat_pengguna_persentase'] < '100') {  ?>
                            <div class="alert alert-info">
                                <img src="<?php echo base_url(); ?>assets/images/info-blue.png" width="20" style="width:25px; height: auto;">
                                Harap lengkapi data diri Anda di halaman <a href="<?php echo site_url('ubah-profil'); ?>">Ubah Profil</a>.
                            </div>
                            <?php } ?>
                            
                            <div class="panel panel-default">
                                <div class="panel-body text-center">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <i class="big fas fa-user-tag"></i>
                                            <div class="title">Tipe User</div>
                                            <strong class="non-counter"><span><?php echo $tipe_member; ?></span></strong>
                                        </div>
                                        <div class="col-sm-4">
                                            <i class="big far fa-file-alt"></i>
                                            <div class="title">Jumlah Pendanaan</div>
                                            <strong class="counter"><?php echo $total_pinjaman['itotal']; ?></strong>
                                        </div>
                                        <div class="col-sm-4">
                                            <i class="big fas fa-coins"></i>
                                            <div class="title">Total Slado</div>
                                            <strong class="counter"><?php echo number_format($total_saldo['Amount']); ?></strong> <span>IDR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="sub-title">Transaksi Repayment</div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Jenis</th>
                                                    <th>Nama Transaksi</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                    <th>&nbsp;</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php 
                                                if (count($list_transaksi)) {
                                                foreach ($list_transaksi as $tra) { 

                                                    if ($tra['tgl_approve'] != '0000-00-00 00:00:00')
                                                    {
                                                        $totalbln = (int)$tra['Loan_term'];
                                                        $jatuhtempo = date('d/m/Y', strtotime("+".$totalbln." months", strtotime($tra['tgl_approve'])));
                                                    }else{
                                                        $jatuhtempo = '';
                                                    }

                                                    if ($logintype == '1') {
                                                        $link_detail = site_url('transaksi/detail/?tid='.$tra['transaksi_id']);
                                                    }else{
                                                        $link_detail = site_url('transaksi/detail-pendana/?tid='.$tra['transaksi_id']);
                                                    }

                                                    if ($tra['id_mod_type_business'] == '1'){
                                                        $label_tenor = 'Hari';
                                                    }else{
                                                        $label_tenor = 'Bulan';
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?php echo $tra['transaksi_id']; ?></td>
                                                    <td><span class="text-danger">Pendanaan</span></td>
                                                    <td>
                                                        <?php echo $tra['product_title']; ?> <br> 
                                                        <div class="sub-description">Tenor : <?php echo $tra['Loan_term'].' '.$label_tenor; ?></div>
                                                    </td>
                                                    <td><?php echo number_format($tra['totalrp']); ?></td>
                                                    <td><?php echo number_format($tra['totalrp']); ?></td>
                                                    <td>
                                                        <a href="<?php echo $link_detail; ?>" class="btn btn-action" title="Detil Transaksi">
                                                            <i class="far fa-clipboard"></i>
                                                        </a>
                                                    </td>
                                                    
                                                </tr>
                                                <?php }
                                                }else{ ?>
                                                <tr><td colspan="5" class="text-center"> <em>Tidak ada transaksi</em></td></tr>
                                            <?php } ?>
                                               
                                            </tbody> 
                                        </table> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="widget quote">
                                        <a href="<?php echo site_url('daftar-peminjam'); ?>" title="">
                                            <div class="content">
                                                <h2>Pendanaan</h2>
                                                <p>Investasi dimulai dari 7%* / per tahun</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Daftar Peminjam -->
<div class="modal fade" id="modal-form-pinjaman" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-register">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Jenis Pinjaman di BKDana</h2><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="img-wrapp">
                                <a href="<?php echo site_url('register-pinjaman-kilat'); ?>" title="Daftar Pinjaman Kilat">
                                    <img src="assets/images/icon-register-1.png" class="img-responsive" alt="Daftar Pinjaman Kilat" title="Daftar Pinjaman Kilat" />
                                </a>
                            </div>
                            <a href="<?php echo site_url('register-pinjaman-kilat'); ?>">Daftar BKDana Kilat</a>
                            <p>Butuh dana Kilat 1 - 2 juta? Seperti biaya Rumah Sakit, Sekolah, Kontrakan, dll. Proses persetujuan hanya 15 menit!</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="img-wrapp">
                                <a href="<?php echo site_url('register-pinjaman-mikro'); ?>" title="Daftar Pinjaman Mikro">
                                    <img src="assets/images/icon-register-2.png" class="img-responsive" alt="Daftar Pinjaman Mikro" title="Daftar Pinjaman Mikro" />
                                </a>
                            </div>
                            <a href="<?php echo site_url('register-pinjaman-mikro'); ?>">Daftar BKDana Mikro</a>
                            <p>Pinjaman Mikro (Usaha Kecil) untuk solusi Bisnis anda. Platform maksimal sampai dengan 50 juta!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>