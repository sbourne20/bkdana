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
                        <h1>Pengajuan Pinjaman Agri</h1>
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

                        <form id="form-pinj-agri"  class="form-validation" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label for="handphone">* Jumlah Pinjaman (Rp)</label>
                                <input type="text" class="form-control numeric" name="jumlah_pinjam" id="jumlah_pinjam" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi jumlah pinjaman Anda!">
                            </div>
                            <div class="form-group">
                                <label for="product">* Tenor</label>
                                <select class="form-control" name="product" id="product" data-validation-engine="validate[required]" data-errormessage-value-missing="Pilih Tenor Pinjaman!">
                                    <option value=""> -- Pilih --</option>
                                    <?php foreach ($products as $prod) { ?>
                                    <option value="<?php echo $prod['Product_id']; ?>"> <?php echo $prod['Loan_term']; ?> Bulan</option>
                                    <?php } ?>
                                </select>
                            </div> 
                            <div class="form-group">
                                <label for="handphone">* Upload CF</label>
                                <input type="file" name="cf_file" id="cf_file" accept="image/*" capture>
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                            </div>
                            <div class="form-group">
                                <label for="handphone">* Upload Progress Report</label>
                                <input type="file" name="progress_report_file" id="progress_report_file" accept="image/*" capture>
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                            </div>
                            <div class="form-group">
                                <label for="handphone">* Upload hasil Panen 1</label>
                                <input type="file" name="hasil_panen_file1" id="hasil_panen_file1" accept="image/*" capture>
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                            </div>
                            <div class="form-group">
                                <label for="handphone">* Upload hasil Panen 2</label>
                                <input type="file" name="hasil_panen_file2" id="hasil_panen_file2" accept="image/*" capture>
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                            </div>
                            <div class="form-group">
                                <label for="handphone">* Upload hasil Panen 3</label>
                                <input type="file" name="hasil_panen_file3" id="hasil_panen_file3" accept="image/*" capture>
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                            </div>
                            <div class="form-group">
                                <label for="password">* Password Anda Saat ini</label>
                                <input type="password" class="form-control" id="password" name="password" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi dengan Password Anda!">
                            </div>
                            <br>
                            <input type="hidden" name="cf_file" value="100">
                            <input type="hidden" name="progress_report_file" value="100">
                            <input type="hidden" name="hasil_panen_file1" value="100">
                            <input type="hidden" name="hasil_panen_file2" value="100">
                            <input type="hidden" name="hasil_panen_file3" value="100">
                            <input type="hidden" name="kelengkapan" value="0">
                            <button type="submit" class="btn btn-blue" id="btnSubmit">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
