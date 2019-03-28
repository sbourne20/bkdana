<?php 
//if ($transaksi['Master_loan_status'] == 'expired') 
switch ($transaksi['Master_loan_status']) {
    case 'review':
        $boleh_bayar = FALSE;
        $show_history = FALSE;
        break;
    case 'pending':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    case 'approve':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    case 'expired':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    case 'lunas':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    
    default:
        // complete (sudah didanai)
        $boleh_bayar = TRUE;
        $show_history = TRUE;
        break;
}


$kuota = round(($transaksi['jml_kredit']/$transaksi['Amount']) * 100);
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
                            <h1>Detail Transaksi</h1>
                            <div class="sub-title">Detail Transaksi Anda</div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Detail</div>
                                    <div class="panel-body">

                                        <?php 
                                        // status bayar
                                        if ( $transaksi['Master_loan_status'] == 'complete' && ($transaksi['date_close'] == '0000-00-00' OR $transaksi['date_close'] == '0000-00-00 00:00:00' OR $transaksi['date_close'] == '') ) {
                                            $status_bayar = 'Pembayaran';
                                        }else if ($transaksi['Master_loan_status'] == 'review') {
                                            $status_bayar = 'Proses Review';
                                        }else if ($transaksi['Master_loan_status'] == 'approve') {
                                            $status_bayar = 'Approve<br>Menunggu Pendanaan';
                                        }else{
                                            $status_bayar = $transaksi['Master_loan_status'];
                                        }

                                        $tenor_label = 'Bulan';
                                        $max_looping = $transaksi['Loan_term'];
										
										
                                        
                                        if ($transaksi['type_of_business_id'] == 1)
                                        {
											if($transaksi['type_of_interest_rate']==1){
												$tenor_label = 'Hari';
												$max_looping = 1;
												$submit_url  = site_url('submit-bayar-cicilan-kilat');   
                                            }
                                            if($transaksi['type_of_interest_rate']==2){
												$tenor_label = 'Bulan';
												$max_looping = 1;
												$submit_url  = site_url('submit-bayar-cicilan-kilat');   
                                            }
                                            if($transaksi['type_of_interest_rate']==3){
												$tenor_label = 'Minggu';
												$max_looping = 1;
												$submit_url  = site_url('submit-bayar-cicilan-kilat');   
                                            }
											
                                            // Pinjaman Kilat
                                           // $tenor_label = 'Hari';
                                            //$max_looping = 1;
                                           // $submit_url  = site_url('submit-bayar-cicilan-kilat');
                                       }else{
                                            // Mikro
											if($transaksi['type_of_interest_rate']==1){
												$tenor_label = 'Hari';
												$submit_url  = site_url('submit-bayar-cicilan-mikro');  
                                            }
                                            if($transaksi['type_of_interest_rate']==2){
												$tenor_label = 'Bulan';
												$submit_url  = site_url('submit-bayar-cicilan-mikro');   
                                            }
                                            if($transaksi['type_of_interest_rate']==3){
                                            $tenor_label = 'Minggu';                                
                                            $submit_url  = site_url('submit-bayar-cicilan-mikro');   
                                            }
											
                                           //$submit_url  = site_url('submit-bayar-cicilan-mikro');
                                        }
                                        ?>

                                        <table class="table-custom">
                                            <tr>
                                                <td><span>No. Transaksi</span> <?php echo $transaksi['Master_loan_id']; ?></td>
                                                <td><span>Jenis</span> Pinjaman</td>
                                            </tr>
                                            <tr>
                                                <td><span>Nama Transaksi</span> <?php echo $transaksi['type_business_name']; ?></td>
                                                <td>
                                                    <span>Nama Peminjam</span>
                                                    <a href="javascript:;" class="text-warning"
                                                     data-toggle="tooltip" data-placement="right" data-original-title=""><?php echo $transaksi['Nama_pengguna']; ?> <i class="fas fa-user-circle"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>Tenor</span> <?php echo $transaksi['Loan_term'].' '. $tenor_label; ?></td>
                                                <td><span>Jatuh Tempo</span> <?php echo $jatuh_tempo; ?></td>
                                            </tr>
                                            <tr>
                                                <td><span>Jumlah</span> <?php echo number_format($transaksi['Amount']); ?> IDR</td>
                                                <td><span>Total</span> <?php echo number_format($total_bayar); ?> IDR</td>
                                            </tr>
                                            <tr>
                                                <td><span>Status</span> <i class="text-primary"> <?php echo ucfirst($status_bayar); ?></i></td>
                                                <td>
                                                    <span>Pendanaan</span>
                                                    <i class="text-primary">Kuota  <?php echo $kuota; ?>%</i>
                                                    <div class="progress-custom">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-success" style="width: <?php echo $kuota; ?>%;"></div>
                                                        </div>
                                                        <?php echo $transaksi['total_lender']; ?> Lender mengikuti Pendanaan ini 
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?php if ($show_history === TRUE) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">Riwayat</div>
                                    <div class="panel-body">
                                        <ul class="cbp_tmtimeline">
                                            <?php 
                                            $k = 1;

                                            for ($i=0; $i < $max_looping; $i++) {  

                                                if (isset($detail_transaksi[$i]['Date_repaid'])) {
                                                    $class = 'done';
                                                    $icon = '<i class="fas fa-check"></i>';
                                                    $status_cicilan = 'Lunas';
                                                }else{
                                                    $class = '';
                                                    $icon = '<i class="far fa-clipboard"></i>';
                                                    $status_cicilan = '';
                                                }                                                
                                            ?>
                                            <li>
                                                <time class="cbp_tmtime"><span>Jatuh Tempo</span> <span>
                                                    <?php echo $jatuh_tempo; ?>
                                                </span></time>
                                                <div class="cbp_tmicon <?php echo $class; ?>"><?php echo $icon; ?></div>
                                                <div class="cbp_tmlabel">
                                                    <h4>Pembayaran</h4>
                                                    <p><?php echo number_format($jml_cicilan); ?> IDR</p>
                                                    <span class="<?php echo $class; ?>">Status : <?php echo $status_cicilan; ?></span>
                                                </div>
                                            </li>
                                            <?php $k=$k+1; 
                                            } ?>
                                        </ul>
                                        <div class="text-center">
                                            <a href="" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>

                                            <?php if ($boleh_bayar === TRUE) { ?>
                                            <a data-toggle="modal" data-target="#modalPayment" href="#" class="btn btn-purple" title="Bayar">Bayar</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal bayar -->
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
                            <h2>Proses Pembayaran Cicilan</h2><br>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="balance">Saldo : <?php echo number_format($total_saldo['Amount']); ?> IDR</div>
                            <div class="bill">Tagihan : <?php echo number_format($jml_cicilan); ?> IDR</div>
                            <br><br>

                            <?php if ($jml_cicilan <= $total_saldo['Amount']) {  
                                ?>
                                <form id="form_pembayaran" method="POST" action="<?php echo $submit_url; ?>">
                                    <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['Master_loan_id']; ?>">
                                    <input type="hidden" name="jml_pinjaman" value="<?php echo $total_bayar; ?>">
                                    <input type="hidden" name="jml_cicilan" value="<?php echo $jml_cicilan; ?>">
                                    <input type="hidden" name="id_peminjam" value="<?php echo $transaksi['Id_pengguna']; ?>">
                                    <input type="hidden" name="id_peminjam_member" value="<?php echo $transaksi['id_mod_user_member']; ?>">

                                    <div class="form-group">
                                        <label for="handphone">Jumlah Pembayaran</label>
                                        <input type="text" name="jml_bayar" class="form-control text-center numeric" value="<?php echo $jml_cicilan; ?>">
                                    </div>
                                    <button type="button" id="submit_bayarcicilan" style="background: transparent; border: none;">
                                    <a href="javascript:;" data-dismiss="modal" class="btn btn-purple">Submit</a>
                                    </button>
                                    <br><br>
                                </form>

                            <?php }else{ ?>

                                <p>Saldo Anda tidak mencukupi untuk melakukan pembayaran ini.</p>
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