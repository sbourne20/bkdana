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
                    <div class="header" style="padding: 20px 75px 6px 75px;">
                        <h1 style="font-size:16px;">
                            <?php 
                            if ($msg_type == 'failed'){
                                $title = 'Informasi';
                                $class = 'alert alert-danger';
                                $icon  = 'error_icon.png';
                                $message = 'Maaf. Saat ini sistem tidak bisa melakukan pengiriman Email. Tunggu beberapa saat lagi.';
                            }else if ($msg_type == 'success'){
                                $title = 'Informasi';
                                $class = 'alert alert-success';
                                $icon  = 'success_icon.png';
                                $message = 'Silahkan cek Email Anda. Klik link yang telah dikirimkan untuk melakukan Reset Password.';
                            }else{
                                $title = 'Informasi';
                                $class = 'alert alert-danger';
                                $icon  = 'error_icon.png';
                                $message = 'Maaf. Reset Password Gagal. Silahkan coba beberapa saat lagi.';
                            }
                            echo $title;
                            ?>
                        </h1>
                        <p></p>
                    </div>
                    <div class="content">
                        
                        <div class="<?php echo $class; ?>"><img src="<?php echo base_url(); ?>assets/images/<?php echo $icon; ?>" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message; ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>