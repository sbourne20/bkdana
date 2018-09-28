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
                        <h1>Pengajuan Pinjaman Mikro</h1>
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

                        <form id="form-pinj-mikro" name="form_name1" class="form-validation" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label for="handphone">* Usaha</label>
                                <input type="text" class="form-control" name="usaha" id="usaha" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi Usaha Anda!">
                            </div>
                            <div class="form-group">
                                <label for="handphone">* Upload Foto Usaha</label>
                                <input type="file" name="usaha_file" id="usaha_file">
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                            </div>
                            <div class="form-group">
                                <label for="lama_usaha">* Lama Usaha</label>
                                <select class="form-control" name="lama_usaha" id="lama_usaha" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi Lama Usaha!">
                                    <option value=""> -- Pilih --</option>
                                    <option value="0"> Kurang dari 1 tahun</option>
                                    <option value="1"> 1 tahun</option>
                                    <option value="2"> 2 tahun</option>
                                    <option value="3"> 3 tahun</option>
                                    <option value="4"> 4 tahun</option>
                                    <option value="5"> 5 tahun</option>
                                    <option value="6"> 6 tahun</option>
                                    <option value="7"> 7 tahun</option>
                                    <option value="8"> 8 tahun</option>
                                    <option value="9"> 9 tahun</option>
                                    <option value="10"> 10 tahun</option>
                                    <option value="11"> Lebih dari 10 tahun</option>
                                </select>
                            </div>

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
                                <label for="password">* Password Anda Saat ini</label>
                                <input type="password" class="form-control" id="password" name="password" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi dengan Password Anda!">
                            </div>
                            <br>
                            <input type="hidden" name="kelengkapan" value="0">
                            <button type="submit" class="btn btn-blue" id="btnSubmit">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>