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
                                            $status_bayar = 'Pembayaran Cicilan';
                                        }else if ($transaksi['Master_loan_status'] == 'review') {
                                            $status_bayar = 'Proses Review';
                                        }else if ($transaksi['Master_loan_status'] == 'approve') {
                                            $status_bayar = 'Approve<br>Menunggu Pendanaan';
                                        }else{
                                            $status_bayar = $transaksi['Master_loan_status'];
                                        }

                                        //$tenor_label = 'Bulan';
                                        $max_looping = $transaksi['Loan_term'];
                                        
                                        if ($transaksi['type_of_business_id'] == 1)
                                        {
                                            // Pinjaman Kilat
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
											
											
											
                                            //$tenor_label = 'Hari';
                                            //$max_looping = 1;
                                            //$submit_url  = site_url('submit-bayar-cicilan-kilat');
                                        }else if($transaksi['type_of_business_id'] == 3){
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
                                        }else{
                                            // Agri
                                            if($transaksi['type_of_interest_rate']==1){
                                                $tenor_label = 'Hari';
                                                $submit_url  = site_url('submit-bayar-cicilan-agri');  
                                            }
                                            if($transaksi['type_of_interest_rate']==2){
                                                $tenor_label = 'Bulan';
                                                $submit_url  = site_url('submit-bayar-cicilan-agri');   
                                            }
                                            if($transaksi['type_of_interest_rate']==3){
                                                $tenor_label = 'Minggu';                                
                                                $submit_url  = site_url('submit-bayar-cicilan-agri');   
                                            }
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
                                            if (count($repayment) > 0) {
                                                   
                                                    foreach ($repayment as $data) {

                                            $k = $data['notes_cicilan'];

                                         
                                                if ($data['status_cicilan']=='lunas') {
                                                    $class = 'done';
                                                    $icon = '<i class="fas fa-check"></i>';
                                                    $status_cicilan = 'Lunas';
                                                }else{
                                                    $class = '';
                                                    $icon = '<i class="far fa-clipboard"></i>';
                                                    $status_cicilan = '';
                                                }


                                               
                                                if ($transaksi['Master_loan_status'] == 'complete') {
                                                   
                                                    $cicilan_duedate = $data['tgl_jatuh_tempo'];
                                                }else{
                                                    $cicilan_duedate = '';
                                                }
                                            ?>
                                            <li>
                                                <time class="cbp_tmtime"><span>Jatuh Tempo</span> <span>
                                                    <?php echo $cicilan_duedate; ?>
                                                </span></time>
                                                <div class="cbp_tmicon <?php echo $class; ?>"><?php echo $icon; ?></div>
                                                <div class="cbp_tmlabel">
                                                     <h4>Pembayaran ke <?php echo $k; ?></h4>
                                                     <p><?php echo number_format($cicilan['Total_loan_outstanding'], 2); ?> IDR</p>
                                                    <?php
                                                    $nowdatetime = date('Y-m-d H:i:s');
                                                    $nowdate = date('Y-m-d');
                                                    $denda1 = str_replace('/', '-', $cicilan_duedate);
                                                    $denda2 = date('Y-m-d', strtotime($denda1));
                                                    $date1=date_create("$denda2");
                                                    $date2=date_create("$nowdate");
                                                    $diff=date_diff($date1,$date2);
                                                    $diff1 = $diff->format("%R%a");
                                                    if(number_format($diff1 > 0)){
                                                        $bayar_denda = $data['jml_denda'];
                                                    }else if(number_format($diff1 < 0)){

                                                        $bayar_denda = $data['jml_denda'];
                                                    }

                                                     ?>
                                                    <h4>Denda Keterlambatan</h4>
                                                    <p><?php echo number_format($data['jml_denda']); ?> IDR <a data-toggle="modal" data-target="#modalRiwayatDenda<?=$data['notes_cicilan']?>" href="#" class="" title="Riwayat Denda"> <i class='fas fa-info-circle'></i></a>
                                                    <div class="modal fade" id="modalRiwayatDenda<?=$data['notes_cicilan']?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-sm" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    Riwayat Denda Cicilan ke <?=$data['notes_cicilan']?>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
                                                                </div>
                                                                <div class="modal-body">      
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
        
                                                                            <table class="display table table-bordered table-striped" id="dynamic-table">
                                                                                <thead>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td><span>Tanggal</span></td>
                                                                                     <td><span>Denda</span></td>
                                                                                </tr>
                                                                                <?php
                                                                                foreach($record_repayment_log as $data1){
                                                                                    if($data1['notes_cicilan']==$data['notes_cicilan']){
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo date_format(date_create($data1['tgl_record_repayment_log']), "Y-m-d"); ?></td>
                                                                                    <td><?php echo number_format($data1['jml_denda']); ?> IDR</td>
                                                                                </tr>
                                                                                 <?php
                                                                                 }
                                                                                 }
                                                                                 ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </p>
                                                <span class="<?php echo $class; ?>">Status : <?php echo $status_cicilan; ?></span>
                                                </div>
                                            </li>
                                            <?php //$k=$k+1; 
                                            }
                                            }
                                        //} ?>
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
                    <?php
                     // if (count($repaymentdenda) > 0) {
                            //$i = 1;
                                // foreach ($repaymentdenda as $data) { 
                                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="balance">Saldo : <?php echo number_format($total_saldo['Amount']); ?> IDR</div>
                            <div class="bill">Tagihan : <?php echo number_format($cicilan['Total_loan_outstanding'], 2); ?> IDR</div>
                            <div class="bill">Denda : <?php echo number_format($data['jml_denda']); ?> IDR</div>
                            <div class="bill">Bunga : <?php echo number_format($total_bunga); ?> IDR</div>
                            <br><br>
                            <?php
        
                            ?>

                            <?php
                            // $datediff = round((strtotime($nowdatetime) - strtotime($data['tgl_jatuh_tempo']))/86400);
                            // $leave_start = DateTime::createFromFormat('Y-m-d', $leave_start);
                            // $leave_end = DateTime::createFromFormat('Y-m-d', $leave_end);
                            // $diffDays = $leave_end->diff($leave_start)->format("%a");

                            if ($jml_cicilan <= $total_saldo['Amount']) { 
                                ?>
                                <form id="form_pembayaran" method="POST" action="<?php echo $submit_url; ?>">
                                    <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['Master_loan_id']; ?>">
                                    <input type="hidden" name="jatuh_tempo" value="<?php echo $data['tgl_jatuh_tempo']; ?>">
                                    <input type="text" name="jml_pinjaman" value="<?php echo $total_bayar; ?>">
                                    <input type="text" name="jml_cicilan" value="<?php echo $cicilan['Amount']; ?>">
                                    <input type="text" name="total_bunga" value="<?php echo $total_bunga; ?>">
                                    <!-- <input type="text" name="datediff" value="<?php echo $total_bunga; ?>">  -->
                                     <input type="hidden" name="bayar_denda" value="<?php echo $data['jml_denda']; ?>">
                                    <input type="hidden" name="id_peminjam" value="<?php echo $transaksi['Id_pengguna']; ?>">
                                    <input type="hidden" name="id_peminjam_member" value="<?php echo $transaksi['id_mod_user_member']; ?>">
                                    <div class="form-group">
                                        <label for="handphone">Jumlah Pembayaran</label>
                                        <input type="text" name="jml_bayar" class="form-control text-center numeric" value="<?php echo $cicilan['Total_loan_outstanding']+$data['jml_denda']+$total_bunga; ?>">
                                    </div>
                                    <button type="button" id="submit_bayarcicilan" style="background: transparent; border: none;">
                                    <a href="javascript:;" data-dismiss="modal" class="btn btn-purple">Submit</a>
                                    </button>
                                    <br><br>
                                </form>

                            <?php } else{ ?>

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