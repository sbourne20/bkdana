<?php 
    if ($message_type == 'error'){
        $class = 'alert alert-danger';
        $icon  = 'error_icon.png';
        
    }else if ($message_type == 'success'){
        $class = 'alert alert-success';
        $icon  = 'success_icon.png';
    
    }else if ($message_type == 'warning'){
        $class = 'alert alert-warning';
        $icon  = 'info_icon.png';

    }else{
        $class = 'alert alert-info';
        $icon  = 'info_icon.png';
        
    }    
?>

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
                            <?php echo $message_title; ?>
                        </h1>
                        <p></p>
                    </div>
                    <div class="content">
                        
                        <div class="<?php echo $class; ?>">
                            <img src="<?php echo base_url(); ?>assets/images/<?php echo $icon; ?>" style="float:left;width:25px;top: -1px;position: relative;margin-right: 16px;"> 
                            <div><?php echo $message_body; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>