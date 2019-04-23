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
                                               
                                            </tr>
                                            <tr>
                                                <td><span>Pinjaman Disetujui</span> <?php echo number_format($transaksi['Amount']); ?> IDR</td>
                                                <td><span>Dana Cair</span> <?php echo number_format($total_bayar); ?> IDR</td>
                                            </tr>
                                           
                                        </table>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="panel panel-default">
                                
                                    <div class="panel-body">
                                        
                                        <div class="text-center">
                                            <a href="" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>

                                            
                                            <a data-toggle="modal" data-target="#modalApprove" href="#" class="btn btn-purple" title="Approve">Approve</a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php 
$approval_update = site_url('approval-agri');
?>

<!-- Modal bayar -->
<div class="modal fade" id="modalApprove" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-payment-process">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Proses Persetujuan Hasil Analisis Pinjaman</h2><br>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <p>Apakah Anda setuju dengan tenor dan jumlah pinjaman yang diberikan ?</p>
                            <br><br>
                            <form id="form_approval" method="POST" action="<?php echo $approval_update; ?>">
                                 <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['Master_loan_id']; ?>">
                                 <input type="hidden" name="Master_loan_status" value="approve">
                                <button type="button" id="approve_agri" style="background: transparent; border: none;">
                                    <a href="javascript:;" data-dismiss="modal" class="btn btn-purple">Approve</a>
                                    </button>
                            </form>
                                   
                                    <br><br>

                            <p class="note muted"><i>* Catatan : Anda akan dianggap menyetujui hasil pertimbangan yang Analis ajukan Jika anda menekan tombol Approve.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>