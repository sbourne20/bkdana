<!-- <?php 
// switch ($transaksi['Pekerjaan']) {
//     case '1':
//         $label_pekerjaan = 'PNS';
//         break;
//     case '2':
//         $label_pekerjaan = 'BUMN';
//         break;
//     case '3':
//         $label_pekerjaan = 'Swasta';
//         break;
//     case '4':
//         $label_pekerjaan = 'wiraswasta';
//         break;
    
//     default:
//         $label_pekerjaan = 'lain-lain';
//         break;
// }

// switch ($transaksi['How_many_years_have_you_been_in_business']) {
//     case '0':
//         $label_lama_usaha = 'Kurang dari setahun';
//         break;
//     case '11':
//         $label_lama_usaha = 'Lebih dari 10 tahun';
//         break;
    
//     default:
//         $label_lama_usaha = $transaksi['How_many_years_have_you_been_in_business'] . ' tahun';
//         break;
// }
?> -->

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
                            <h1>Rekening Koran</h1>
                            <div class="sub-title">Detail Rekening Koran</div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Transaksi</div>
                                        <div class="panel-body">
                                            <table class="table-custom">
                                                <tr>
                                                    <td><span>Tanggal Transaksi</span> <?php echo $walletkoran['Date_transaction']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>No. Transaksi</span> <?php echo $walletkoran['kode_transaksi']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Detail wallet ID</span> <?php echo $walletkoran['Detail_wallet_id']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Detail wallet ID</span> <?php echo $walletkoran['Balance']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td> <?php echo $walletkoran['Notes'].' '.'senilai'.' ';?> IDR<?php echo number_format($walletkoran['Amount']); ?></td>
                                                </tr>
                                                
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
                                
                                
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Informasi Peminjam</div>
                                        <div class="panel-body">
                                            <table class="table-custom">
                                                <tr>
                                                    <td><span>Pekerjaan</span> <?php  ?></td>
                                                    <td><span>Alamat</span> <?php  ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Usaha</span> <?php  ?></td>
                                                    <td><span>Lama Usaha</span> <?php  ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span>Lokasi (Kota)</span> <?php  ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php 
                                                        ?>
                                                        <span>Foto Profil</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="" alt="" />
                                                        <?php ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        ?>
                                                        <span>Foto Usaha</span><br>
                                                        <img class="img-pinjam" width="40" height="40" src="" alt="" />
                                                        <?php ?>
                                                    </td>
                                                </tr>
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
                    
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="balance">Saldo : <?php  ?> IDR</div>
                            <div class="bill">Tagihan : <?php  ?> IDR</div>
                            <br><br>

                            <?php if ($total_saldo['Amount'] >= 100000) {  
                                ?>
                                <form id="form_pembiayaan" method="POST" action="">
                                    <input type="hidden" name="transaksi_id" value="">
                                    <input type="hidden" name="jml_pinjaman" value="">
                                    <input type="hidden" name="jml_pinj_disetujui" value="">
                                    <input type="hidden" name="id_peminjam" value="">
                                    <input type="hidden" name="id_peminjam_member" value="">
                                    <input type="hidden" name="jml_kredit" value="">
                                    <input type="hidden" name="jml_kekurangan" value="">

                                    <div class="form-group">
                                        <label for="handphone">Jumlah Pendanaan</label>
                                        <input type="text" name="jml_pendanaan" class="form-control text-center numeric" value="">
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