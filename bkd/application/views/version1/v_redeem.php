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
                            <h1>Tarik Tunai</h1>
                            <div class="sub-title"></div>

                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php if ($total_saldo['Amount'] >= 100000) { ?>
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <form class="text-left form-validation" id="form_redeem" method="POST" action="<?php echo site_url('redeem/submit_r'); ?>">
                                                <input class="form-control" name="nomor_rekening" id="nomor_rekening" type="hidden" value="<?php echo $memberdata['Nomor_rekening']; ?>">
                                                <input class="form-control" name="bank_name" id="bank_name" type="hidden" value="<?php echo $memberdata['nama_bank']; ?>">
                                                
                                                <?php /*<!-- <div class="form-group">
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
                                                </div> -->
                                                */?>
                                                <div class="form-group">
                                                    <label for="accounta_bank_no">* Bank Profil Anda</label>
                                                    <input class="form-control" id="accounta_bank_no" value="<?php echo $memberdata['nama_bank']; ?> - <?php echo $memberdata['Nomor_rekening']; ?>" disabled="" type="text">
                                                    <p class="help-block small">Untuk merubah Bank Profil Terdapat di menu Ubah Profil</p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="handphone">* Jumlah Tarik Tunai Rp</label>
                                                    <input class="form-control numeric" name="jml_redeem" id="total" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Nominal harus diisi">
                                                </div>

                                                <br><button type="button" id="submit_redeem" class="btn btn-purple">Submit</button>
                                                <img id="img_loading" src="<?php echo base_url(); ?>assets/images/loading-text.gif" style="width: 75px; display: none;">
                                                <br><br>
                                            </form>
                                        </div>
                                    </div>
                                    <?php }else { ?>
                                    <div>Saldo Anda Rp <?php echo number_format($total_saldo['Amount']); ?>. Anda tidak bisa melakukan transaksi Redeem.</div>
                                    <br><br>
                                    <?php }?>
                                </div>
                            </div>
                            
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="sub-title">Histori Tunai</div>
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
                                                        $inclass  = '';
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

