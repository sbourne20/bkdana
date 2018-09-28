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

                    <form class="form-validation" method="POST" action="<?php echo site_url('rekening_saya/submit'); ?>">
                        <div class="form-group">
                            <label for="email">No. Transaksi</label>
                            <input class="form-control" type="text" name="no_transaksi" autofocus>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-blue"> Submit</button> 
                    </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>