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
                    <div class="content">

                    Nama: <?php echo $nama; ?>
                    <br>
                    Nominal : Rp <?php echo number_format($detail['redeem_amount']); ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>