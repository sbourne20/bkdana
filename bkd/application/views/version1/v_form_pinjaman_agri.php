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

                        <form id="form-pinj-agri" name="form_name1" class="form-validation" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label for="handphone">* Jumlah Pinjaman (Rp)</label>
                                <input type="text" class="form-control numeric" name="jumlah_pinjam" id="jumlah_pinjam" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi jumlah pinjaman Anda!">
                            </div>
                            <div class="form-group">
                                <label for="product">* Tenor (hari)</label>
								<input type="text" class="form-control text" name="product" id="product" data-validation-engine="validate[required]" data-errormessage-value-missing="Isi tenor Anda!">
                                <!-- <select class="form-control" name="product" id="product" data-validation-engine="validate[required]" data-errormessage-value-missing="Pilih Tenor Pinjaman!">
                                    <option value=""> -- Pilih --</option>
                                    <?php foreach ($products as $prod) { ?>
                                    <option value="<?php echo $prod['Product_id']; ?>"> <?php echo $prod['Loan_term']; ?> Bulan</option>
                                    <?php } ?>
                                </select> -->
                            </div> 
                            <div class="form-group">
                                <label for="handphone">* Upload CF</label>
                                <input type="file" id="cf_file" accept="image/*" capture onchange='onFileUpload()'>
                                <input type="hidden" class="input_file_hidden" name="cf_file_hidden" id="cf_file_hidden"/>
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                            </div>
                            <div class="form-group">
                                <label for="handphone">* Upload Progress Report</label>
                                <input type="file" id="progress_report_file" accept="image/*" capture onchange='onFileUpload()'>
                                <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                                <input type="hidden" class="input_file_hidden" name="progress_report_file_hidden" id="progress_report_file_hidden"/>
                            </div>
                            <!-- <div class="form-group">
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
                            </div> -->
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

<script type="text/javascript">

window.onFileUpload = function() {
    var file = event.target.files[0];
    var el = event.target;
    var parent = el.parentNode.parentNode.parentNode.parentNode.parentNode;
    var hiddenInput = parent.getElementsByClassName('input_file_hidden')[0];
    console.log(parent);
    ImageTools.resize(file, {
        width: 1024, // maximum width
        height: 800 // maximum height
    }, function(blob, didItResize) {
        // didItResize will be true if it managed to resize it, otherwise false (and will return the original file as 'blob')
         
        var reader = new FileReader();
        reader.readAsDataURL(blob); 
        reader.onloadend = function() {
            base64data = reader.result;
            hiddenInput.value = base64data;                
            console.log(base64data);
        }
        // you can also now upload this blob using an XHR.
    });
};

</script>

