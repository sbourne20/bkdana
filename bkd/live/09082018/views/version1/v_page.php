<!-- Header -->
<header>
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<!-- Content -->
<div class="container">
    <div class="wrapper-content">
        
        <div class="section-dynamic-page">
            <div class="row">
                <div class="col-sm-12">
                    <div class="title">
                        <h1><?php echo $datapage['p_title']; ?></h1>
                        <p><?php echo $datapage['p_subtitle']; ?></p>
                        <div class="addthis_inline_share_toolbox"></div> 
                    </div>
                    <div class="content">
                        <?php
                        if($datapage['p_images'] != NULL){
                        ?>
                        <img src="<?php echo $this->config->item('page_img_uri') . $datapage['p_id'] .'/'.$datapage['p_images']; ?>" class="img-responsive img-reset" width="600" height="400" />
                        <?php /* <img src="<?php echo $this->config->item('page_img_uri') . $datapage['p_id'] .'/'.$datapage['p_images']; ?>" class="img-responsive img-reset" width="600" height="400" onError="this.src='<?php echo base_url(); ?>assets/images/borrower.jpg'" /> */?>
                        <?php } ?>
                        <div class="description">
                            <?php echo html_entity_decode($datapage['p_content']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>