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
                                                    <td><span>Total Pinjaman</span> <?php echo number_format($total_bayar); ?> IDR</td>
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
                                    $label_tenor = 'Hari';
                                }else{
                                    $label_tenor = 'Bulan';
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
                                                    <td><span>Tipe Industri</span> <?php echo $transaksi['What_is_the_name_of_your_business']; ?></td>
                                                    <td><span>Lama Usaha</span> <?php echo $transaksi['How_many_years_have_you_been_in_business']; ?> Tahun</td>
                                                </tr>
                                                <tr>
                                                    <td><span>Lokasi (Kota)</span> <?php echo $transaksi['Kota']; ?></td>
                                                </tr>
                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <a href="javascript:;" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>

                                <?php if ($kuota_dana < 100 && $transaksi['Master_loan_status']=='pending') { ?>
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
                        $penawaran = $total_bayar;
                    }else{
                        $penawaran = $total_bayar - $transaksi['jml_kredit'];
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

                            <?php if ($total_saldo['Amount'] >= 100000) {  
                                ?>
                                <form id="form_pembiayaan" method="POST" action="<?php echo site_url('submit-pembiayaan-pinjaman'); ?>">
                                    <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['Master_loan_id']; ?>">
                                    <input type="hidden" name="jml_pinjaman" value="<?php echo $total_bayar; ?>">
                                    <input type="hidden" name="id_peminjam" value="<?php echo $transaksi['Id_pengguna']; ?>">
                                    <input type="hidden" name="id_peminjam_member" value="<?php echo $transaksi['id_mod_user_member']; ?>">
                                    <input type="hidden" name="jml_kredit" value="<?php echo $transaksi['jml_kredit']; ?>">
                                    <input type="hidden" name="jml_kekurangan" value="<?php echo $jmltagihan; ?>">
                                    <input type="hidden" name="laba" value="<?php echo $laba; ?>">

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