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
                        <h1>Masuk</h1>
                        <p>Silakan masukan email dan password akun BKD Anda</p>
                    </div>
                    <div class="content">
                        <?php 
                        if ($this->session->userdata('message_login')) {
                            $message_show = $this->session->userdata('message_login');
                            unset($_SESSION["message_login"]);
                        ?>
                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                        <?php } ?>

                        <form class="form-validation" method="POST" action="<?php echo site_url('submit-login'); ?>">
                            <div class="form-group">
                                <label for="email">* E-mail / Phone Number</label>
                                <input class="form-control" id="email" type="email1" name="email" data-validation-engine="validate[required]" data-errormessage-value-missing="Email / Phone Number harus diisi!" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">* Password</label>
                                <input class="form-control" id="password" type="password" name="password" data-validation-engine="validate[required]" data-errormessage-value-missing="Password harus diisi!">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-blue">Masuk</button> &nbsp;
                            <a href="<?php echo site_url('password_reset'); ?>" class="link" title="Lupa Password Anda?">Lupa Password Anda?</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>