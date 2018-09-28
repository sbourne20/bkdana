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
                            <h1>Redeem</h1>
                            <div class="sub-title"></div>
                            <a data-toggle="modal" data-target="#modalredeem" href="#" title="Transfer Manual">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="payment-wrapp">
                                            <div class="row">
                                                <div class="col-sm-1 col-xs-6 text-center">
                                                    <img src="<?php echo base_url(); ?>assets/images/transfer-bank-ico.jpg" class="img-circle">
                                                </div>
                                                <div class="col-sm-10 col-xs-6 no-padding">
                                                    <h5 class="title">Ajukan Redeem</h5>
                                                    <small class="text-muted">Klik untuk pengajuan Redeem Saldo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="sub-title">Histori Redeem</div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No. Transaksi</th>
                                                    <th>Tanggal</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php 
                                                $useclass = FALSE;
                                                $inclass  = '';

                                                if (count($list_redeem) > 0) {
                                                foreach ($list_redeem as $rd) {
                                                    if ($useclass) { $inclass='class="warning"'; }

                                                    switch ($rd['redeem_status']) {
                                                        case 'approve':
                                                            $status = '<i><span class="text-success">Approve</span></i>';
                                                            break;
                                                        case 'reject':
                                                            $status = '<i><span class="text-danger">Reject</span></i>';
                                                            break;
                                                        case 'pending':
                                                            $status = '<i><span class="text-warning">Pending</span></i>';
                                                            break;
                                                        
                                                        default:
                                                            $status = '<i><span class="text-primary">'.$rd['redeem_status'].'</span></i>';
                                                            break;
                                                    }
                                                ?>
                                                <tr>
                                                    <td <?php echo $inclass; ?>><?php echo $rd['redeem_kode']; ?></td>
                                                    <td <?php echo $inclass; ?>><?php echo date('d/m/Y', strtotime($rd['redeem_date'])); ?></td>
                                                    <td <?php echo $inclass; ?>><?php echo number_format($rd['redeem_amount']); ?></td>
                                                    <td <?php echo $inclass; ?>> <?php echo $status; ?></td>
                                                </tr>
                                                <?php 
                                                    if ($useclass === FALSE)
                                                    {
                                                        $useclass = TRUE;
                                                    }else{
                                                        $useclass = FALSE;
                                                    }
                                                } 
                                            } else { ?>
                                                <tr><td colspan="4" class="text-center"><em>Tidak ada data</em></td></tr>
                                            <?php } ?>
                                                
                                            </tbody> 
                                        </table> 
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

<!-- Modal Top Up Transfer Manual -->
<div class="modal fade" id="modalredeem" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-register">    
                    <?php if ($total_saldo['Amount'] >= 100000) { ?>

                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Formulir Redeem</h2><br><br>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-offset-1 col-sm-9">
                            <form class="text-left form-validation" method="POST" action="<?php echo site_url('redeem/submit_'); ?>">
                                <div class="form-group">
                                    <label for="accounta_bank_no">* Nomor Rekening Tujuan</label>
                                    <input class="form-control" name="nomor_rekening" id="nomor_rekening" type="text" value="<?php echo $memberdata['Nomor_rekening']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Nomor Rekening Tujuan harus diisi!">
                                </div>
                                <div class="form-group">
                                    <label for="bank">* Bank Tujuan</label>
                                    <select class="form-control" name="bank_name" id="bank_name" data-validation-engine="validate[required]" data-errormessage-value-missing="Bank Tujuan harus diisi">
                                        <option value="" selected="selected">- Pilih Bank -</option>
                                        <option value="Bank Mandiri">Bank Mandiri</option>
                                        <option value="Bank BNI 46">Bank BNI 46</option>
                                        <option value="Bank BRI">Bank BRI</option>
                                        <option value="Bank BCA">Bank BCA</option>
                                        <option value="Bank Danamon">Bank Danamon</option>
                                        <option value="Bank Mega">Bank Mega</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="handphone">* Jumlah Redeem Rp</label>
                                    <input class="form-control numeric" name="jml_redeem" id="total" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Top Up harus diisi">
                                </div>

                                <br><button type="submit" class="btn btn-purple">Submit</button><br><br>
                            </form>
                        </div>
                    </div>

                    <?php }else { ?>

                    <div>Saldo Anda Rp <?php echo number_format($total_saldo['Amount']); ?>. Anda tidak bisa melakukan transaksi Redeem.</div>
                    <br><br>
                    <?php }?>

                </div>
            </div>
        </div>
    </div>
</div>
