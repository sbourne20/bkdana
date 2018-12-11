<?php 
switch ($transaksi['Pekerjaan']) {
    case '1':
        $label_pekerjaan = 'PNS';
        break;
    case '2':
        $label_pekerjaan = 'BUMN';
        break;
    case '3':
        $label_pekerjaan = 'Swasta';
        break;
    case '4':
        $label_pekerjaan = 'wiraswasta';
        break;
    
    default:
        $label_pekerjaan = 'lain-lain';
        break;
}

switch ($transaksi['How_many_years_have_you_been_in_business']) {
    case '0':
        $label_lama_usaha = 'Kurang dari setahun';
        break;
    case '11':
        $label_lama_usaha = 'Lebih dari 10 tahun';
        break;
    
    default:
        $label_lama_usaha = $transaksi['How_many_years_have_you_been_in_business'] . ' tahun';
        break;
}
?>

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
                            <h1>Detail Peminjam</h1>
                            <div class="sub-title">Detail Keseluruhan Pinjaman</div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Pendanaan <span class="pull-right">Kuota <?php echo $kuota_dana; ?>%</span></div>
                                        <div class="panel-body">
                                            <table class="table-custom">
                                                <tr>
                                                    <td><span>Total Pinjaman</span> <?php echo number_format($transaksi['Amount']); ?> IDR</td>
                                                    <td><span>Total Pendanaan</span> <?php echo number_format($transaksi['jml_kredit']); ?> IDR</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="progress-custom">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-success" style="width: <?php echo $kuota_dana; ?>%;"></div>
                                                            </div>
                                                            <?php echo $transaksi['total_lender']; ?> Lender mengikuti Pendanaan ini 
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php 
                                if ($transaksi['type_of_business_id']==1)
                                {
                                    //$label_tenor = 'Hari';
                                    if ($transaksi['type_of_interest_rate'] == 1) {
                                        $label_tenor = 'Hari';
                                    }if ($transaksi['type_of_interest_rate'] == 2) {
                                        $label_tenor = 'Bulan';
                                    }if ($transaksi['type_of_interest_rate'] == 3){
                                        $label_tenor = 'Minggu';
                                    }

                                }else{
                                    //$label_tenor = 'Bulan';
                                     if ($transaksi['type_of_interest_rate'] == 1) {
                                        $label_tenor = 'Hari';
                                    }if ($transaksi['type_of_interest_rate'] == 2) {
                                        $label_tenor = 'Bulan';
                                    }if ($transaksi['type_of_interest_rate'] == 3){
                                        $label_tenor = 'Minggu';
                                    }
                                }
                                ?>
                                
                                <div class="col-sm-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Pinjaman</div>
                                        <div class="panel-body">
                                            <table class="table-custom">
                                                <tr>
                                                    <td><span>Nama Peminjam</span> <?php echo $transaksi['Nama_pengguna']; ?></td>
                                                    <td><span>No. Transaksi</span> <?php echo $transaksi['Master_loan_id']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Grade & Rate</span> <?php echo $transaksi['peringkat_pengguna']; ?></td>
                                                    <td><span>Tenor</span> <?php echo $transaksi['Loan_term'] .' '. $label_tenor; ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Informasi Peminjam</div>
                                        <div class="panel-body">
                                            <table class="table-custom">
                                                <tr>
                                                    <td><span>Pekerjaan</span> <?php echo $label_pekerjaan; ?></td>
                                                    <td><span>Alamat</span> <?php echo $transaksi['Alamat'] .', '.$transaksi['Kota'].', ' .$transaksi['Provinsi'] ; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Usaha</span> <?php echo $transaksi['What_is_the_name_of_your_business']; ?></td>
                                                    <td><span>Lama Usaha</span> <?php echo $label_lama_usaha; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Lokasi (Kota)</span> <?php echo $transaksi['Kota']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php if ($transaksi['images_foto_name'] != ''){ 
                                                            $foto_profil = $this->config->item('images_uri') . '/member/'.$transaksi['id_mod_user_member']. '/foto/'. $transaksi['images_foto_name'];
                                                        ?>
                                                        <span>Foto Profil</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_profil; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaksi['images_usaha_name'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$transaksi['id_mod_user_member']. '/usaha/'. $transaksi['images_usaha_name'];
                                                        ?>
                                                        <span>Foto Usaha</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <!--tambahan foto usaha-->
                                                <tr>
                                                    <td>
                                                         <?php if ($transaksi['images_usaha_name2'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$transaksi['id_mod_user_member']. '/usaha2/'. $transaksi['images_usaha_name2'];
                                                        ?>
                                                        <span>Foto Usaha 2</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaksi['images_usaha_name3'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$transaksi['id_mod_user_member']. '/usaha3/'. $transaksi['images_usaha_name3'];
                                                        ?>
                                                        <span>Foto Usaha 3</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                         <?php if ($transaksi['images_usaha_name4'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$transaksi['id_mod_user_member']. '/usaha4/'. $transaksi['images_usaha_name4'];
                                                        ?>
                                                        <span>Foto Usaha 4</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaksi['images_usaha_name5'] != ''){ 
                                                            $foto_usaha = $this->config->item('images_uri') . '/member/'.$transaksi['id_mod_user_member']. '/usaha5/'. $transaksi['images_usaha_name5'];
                                                        ?>
                                                        <span>Foto Usaha 5</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="<?php echo $foto_usaha; ?>" alt="" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>

                                                <!--Batas tambahan foto usaha-->

                                                <?php /*
                                                <tr>
                                                    <td>
                                                        <?php if ($transaksi['size_foto_profil'] > '1'){ ?>
                                                        <span>Foto Profil</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="data:image/jpeg;base64,<?php echo base64_encode($transaksi['Profile_photo']); ?>" />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($transaksi['size_foto_usaha'] > '1'){ ?>
                                                        <span>Foto Usaha</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="data:image/jpeg;base64,<?php echo base64_encode($transaksi['foto_usaha']); ?>" />
                                                        <?php } ?>
                                                    </td>
                                                </tr> */?>
                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <a href="javascript:;" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>

                                <?php if ($kuota_dana < 100 && ($transaksi['Master_loan_status']=='approve' OR ($transaksi['Master_loan_status']=='draft' && $transaksi['id_mod_type_business']=='1')) ) { ?>
                                <a data-toggle="modal" data-target="#modalPayment" href="#" class="btn btn-purple" title="Biayai">Biayai</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Biayai -->
<div class="modal fade" id="modalPayment" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-payment-process">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Proses Pembiayaan</h2><br>
                        </div>
                    </div>

                    <?php 
                    if (empty($transaksi['jml_kredit'])) {
                        $penawaran = $Amount;
                    }else{
                        $penawaran = $Amount - $transaksi['jml_kredit'];
                    }

                    $jmltagihan = $penawaran;

                    if ($total_saldo['Amount'] < $penawaran)
                    {
                        $penawaran = $total_saldo['Amount'];
                    }
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="balance">Saldo : <?php echo number_format($total_saldo['Amount']); ?> IDR</div>
                            <div class="bill">Tagihan : <?php echo number_format($jmltagihan); ?> IDR</div>
                            <br><br>
                            <!-- <input type="" name="jml_analis" value="<?php echo $transaksi['Amount']; ?>"> -->

                            <?php if ($total_saldo['Amount'] >= 100000) {  
                                ?>
                                <form id="form_pembiayaan" method="POST" action="<?php echo site_url('submit-pembiayaan-pinjaman'); ?>">
                                    <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['Master_loan_id']; ?>">
                                    <input type="hidden" name="jml_pinjaman" value="<?php echo $total_bayar; ?>">
                                    <input type="hidden" name="jml_pinj_disetujui" value="<?php echo $pinjaman_disetujui; ?>">
                                    <input type="hidden" name="Product_id" value="<?php echo $Product_id; ?>">

                                    <input type="hidden" name="id_peminjam" value="<?php echo $transaksi['Id_pengguna']; ?>">
                                    <input type="hidden" name="id_peminjam_member" value="<?php echo $transaksi['id_mod_user_member']; ?>">
                                    <input type="hidden" name="jml_kredit" value="<?php echo $transaksi['jml_kredit']; ?>">
                                    <input type="hidden" name="jml_kekurangan" value="<?php echo $jmltagihan; ?>">

                                    <div class="form-group">
                                        <label for="handphone">Jumlah Pendanaan</label>
                                        <input type="text" name="jml_pendanaan" class="form-control text-center numeric" value="<?php echo $penawaran; ?>">
                                    </div>
                                    <button type="button" id="submit_pembiayaan" style="background: transparent; border: none;">
                                    <a href="javascript:;" data-dismiss="modal" class="btn btn-purple">Submit</a>
                                    </button>
                                    <br><br>
                                </form>

                            <?php }else{ ?>

                                <p>Saldo Anda tidak mencukupi untuk pendanaan ini.</p>
                                <a href="javascript:;" class="btn btn-default" disabled>Submit</a><br><br>
                            <?php } ?>

                            <p class="note muted"><i>* Catatan : Saldo Anda akan dikurangi tagihan Anda jika menekan tombol submit. Jika saldo Anda tidak mencukupi harap segera melakukan <strong>Top Up</strong> terlebih dahulu.</i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>