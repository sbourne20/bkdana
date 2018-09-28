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
                        <h1>Pengajuan Pinjaman Kilat</h1>
                        <p>Silakan Pilih Pinjaman dan Tenor</p>
                    </div>
                    <div class="content">
                        <?php 
                        if ($this->session->userdata('message_reset')) {
                            $message_show = $this->session->userdata('message_reset');
                            $this->session->unset_userdata('message_reset');
                        ?>
                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                        <?php } ?>

                        <form id="form-pinj-kilat" class="form-validation" method="POST">
                            <div class="form-group">
                                <label for="jumlah_pinjam">* Jumlah Pinjaman (Rp)</label>
                                <select class="form-control" name="jumlah_pinjam" id="jumlah_pinjam" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Pinjaman harus diisi!">
                                    <option value=""> -- Pilih --</option>
                                    <?php foreach ($harga as $rp) {
                                    ?>
                                    <option value="<?php echo $rp['h_harga'] ?>" data-hargaid="<?php echo $rp['h_id']; ?>"> <?php echo number_format($rp['h_harga']); ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product">* Tenor</label>
                                <select class="form-control" name="product" id="product" data-validation-engine="validate[required]" data-errormessage-value-missing="Tenor harus diisi!">
                                    <option value=""> -- Pilih --</option>
                                </select>
                            </div> 
                            <div class="form-group">
                                <label for="password">* Password Anda Saat ini</label>
                                <input type="password" class="form-control" id="password" name="password" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi dengan Password Anda!">
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
