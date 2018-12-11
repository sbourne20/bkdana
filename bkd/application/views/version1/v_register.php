<!-- Header -->
<header>
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<!-- Content -->
<div class="container">
    <div class="wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                
                <div class="form-wizard">
                    <div id="wizard" class="wizard">
                        <div class="wizard__content">
                            <div class="wizard__header">
                                <div class="wizard__header-overlay"></div>
                                <div class="wizard__header-content">
                                    <h1 class="wizard__title" >Daftar Peminjam atau Pendana </h1>
                                    <p class="wizard__subheading">Beberapa langkah lagi untuk menjadi peminjam atau pendana di BKD. <br>Silahkan lengkapi informasi dan data-data Anda terlebih dahulu.</p>
                                </div>
                                <div class="wizard__steps">
                                    <nav class="steps">
                                        <div class="step">
                                            <div class="step__content">
                                                <p class="step__number">1</p>
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                                </svg>
                                                <div class="lines">
                                                    <div class="line -start"></div>
                                                    <div class="line -background"></div>
                                                    <div class="line -progress"></div>
                                                </div>  
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>                            

                            <div class="panels">
                                <div class="panel" id="panel1">
                                    <div class="panel__header">

                                        <?php 
                                        if ($this->session->userdata('message')) {
                                            $message_show = $this->session->userdata('message');
                                        ?>
                                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                                        <?php } ?>
                                
                                        <h2 class="panel__title">Form Pendaftaran</h2>
                                    </div>
                                    <div class="panel__content">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <form name="form_name1" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="fullname">* Nama Lengkap</label>
                                                        <input type="text" class="form-control" name="fullname" id="fullname" required>
                                                        <p class="help-block">* Nama lengkap sesuai KTP</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="handphone">* Email</label>
                                                        <input type="text" class="form-control" name="email" id="email" value="" >
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="handphone">* Nomor Telepon</label>
                                                        <input type="text" class="form-control" name="telp" id="telp" value="+62" required>
                                                        <p class="help-block">* Contoh: +6281111111</p>
                                                        <?php /*<p class="help-block alert-danger" id="telperror" style="display: none;"></p>*/?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password">* Password</label>
                                                        <input type="password" class="form-control" id="password" name="password">
                                                        <p class="help-block" <?php /*id="passwordmsg" */?>>* Minimum 6 karakter. Kombinasi Huruf dan Angka</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="confirm_password">* Konfirmasi Password</label>
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                                        <?php /*<p class="help-block" id="cpmsg"></p>*/?>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="confirm_password">* Tipe Member</label>
                                                        <select name="tipe_member" class="form-control">
                                                            <option value=""> -- Pilih --</option>
                                                            <option value="1">  Peminjam </option>
                                                            <option value="2">  Pendana </option>
                                                        </select>
                                                    </div>
                                                    <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="agreement" id="iAgree"> Saya telah membaca dan saya setuju dengan Kebijakan Privasi &amp; Syarat Ketentuan
                                                </label>
                                                <div class="alert alert-danger" id="agree_message" style="display: none;">Anda harus mencentang checkbox Syarat Ketentuan</div>
                                          </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            <div class="wizard__footer">
                                <button class="btn btn-blue previous">Sebelumnya</button>
                                <button class="btn btn-purple next">Selanjutnya</button>
                            </div>
                            
                        </div>
                        <h1 class="wizard__congrats-message">
                            <img src="<?php echo base_url(); ?>assets/images/loading.gif" class="img-responsive img-reset loading" />
                            <span>Tunggu Sebentar Permintaan Anda Sedang Kami Proses ... </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_loading" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <div class="section-register">    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="fa-4x">
                              <i class="fas fa-spinner fa-spin"></i>
                              </div> 
                            Mengirim Email aktivasi ...<br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="myTimer" style="font-size:20px; margin: 20px 0 5px 0;"></div>
                              <button type="button" id="myBtn" class="btnDisable btn btn-default" disabled onclick="resend_email();">Kirim Ulang Email Aktivasi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>