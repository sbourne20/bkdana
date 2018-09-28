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
                        <p>Silakan masukan Email Anda yang terdaftar di BKDana untuk menerima link reset password</p>
                    </div>
                    <div class="content">
                        <?php 
                        if ($this->session->userdata('message_reset')) {
                            $message_show = $this->session->userdata('message_reset');
                            $this->session->unset_userdata('message_reset');
                        ?>
                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                        <?php } ?>

                        <form id="form-resetp" class="form-validation" method="POST" action="<?php echo site_url('submit-reset-password'); ?>">
                            <div class="form-group">
                                <label for="email">* E-mail</label>
                                <input class="form-control" id="email" type="email" name="email" data-validation-engine="validate[required,custom[email]]" 
                                data-errormessage-value-missing="Email harus diisi!"
                                              data-errormessage-custom-error="Contoh Email format: bkd@example.com"
                                              data-errormessage="This is the fall-back error message." autofocus>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-blue" id="btnSubmit">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
