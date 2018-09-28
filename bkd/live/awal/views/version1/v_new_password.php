<!-- Header -->
<header>
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<!-- Content -->
<div class="container">
    <div class="wrapper-content">
        
        <div class="section-login">
            <div class="row">
                <div class="col-sm-12">
                    <div class="header">
                        <h1>Reset Password</h1>
                        <p></p>
                    </div>
                    <div class="content">
                        <?php 
                        if ($this->session->userdata('message_reset')) {
                            $message_show = $this->session->userdata('message_reset');
                            $this->session->unset_userdata('message_reset');
                        ?>
                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                        <?php } ?>

                        <form class="form-validation" method="POST" action="<?php echo site_url('submit-new-password'); ?>">
                            <input type="hidden" name="ref" value="<?php echo $ref; ?>">
                            <div class="form-group">
                                <label for="password"> New Password</label>
                                <input class="form-control" id="password" type="password" name="password" data-validation-engine="validate[required, minSize[6]]" 
                                data-errormessage-value-missing="Minimal 6 karakter"
                                data-errormessage-range-underflow="min[6]" autofocus>
                                <p class="help-block">* Minimal 6 karakter</p>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"> Re-type New Password</label>
                                <input class="form-control" id="confirm_password" type="password" name="confirm_password" data-validation-engine="validate[required, equals[password]]" 
                                data-errormessage-value-missing="Ketik ulang password disini" autofocus>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-blue">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>