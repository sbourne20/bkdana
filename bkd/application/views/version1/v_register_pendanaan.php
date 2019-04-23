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
                                    <h1 class="wizard__title" >Daftar Pendana</h1>
                                    <p class="wizard__subheading">Beberapa langkah lagi untuk menjadi Pendana di BKD. <br>Silahkan lengkapi informasi dan data-data Anda terlebih dahulu.</p>
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
                                
                                        <h2 class="panel__title">1. Form Pendaftaran</h2>
                                        <p class="panel__subheading">Silahkan masukan data diri Anda sebenar - benarnya.</p>
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
                                                        <input type="text" class="form-control" name="email" id="email" value="" required>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="handphone">* Nomor Telepon</label>
                                                        <input type="text" class="form-control" name="telp" id="telp" value="+62" required>
                                                        <p class="help-block" id="telpmsg">* Contoh: +6281111111</p>
                                                        <?php /*<p class="help-block alert-danger" id="telperror" style="display: none;"></p>*/?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password">* Password</label>
                                                        <input type="password" class="form-control" id="password" name="password">
                                                        <p class="help-block" <?php /* id="passwordmsg" */?> >* Minimum 6 karakter. Kombinasi huruf dan angka, minimal 1 huruf besar.</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="confirm_password">* Konfirmasi Password</label>
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                                    </div>

                                                    <?php /*

                                                    <div class="form-group">
                                                        <label for="nomor_rekening">* Nomor Rekening</label>
                                                        <input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" required>
                                                        <p class="help-block">* Peringatan agar melakukan dengan hati-hati dan pengecekan ulang agar tidak terjadi kesalahan</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nama_bank">* Bank</label>
                                                        <select class="form-control" name="nama_bank" id="nama_bank" required>
                                                            <option value="">- Pilih Bank -</option>
                                                            <option value="Bank Mandiri">Bank Mandiri</option>
                                                            <option value="Bank BNI 46">Bank BNI 46</option>
                                                            <option value="Bank BRI">Bank BRI</option>
                                                            <option value="Bank BCA">Bank BCA</option>
                                                            <option value="Bank CIMB">Bank CIMB</option>
                                                        </select>
                                                    </div>
                                                    */?>
                                                    <div class="form-group">
                                                        <label for="sumberdana">* Sumber Dana</label>
                                                        <input type="text" class="form-control" name="sumberdana" id="sumberdana" required>
                                                    </div>
                                                
                                                    <br>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="agreement" id="iAgree"> Saya telah membaca dan saya setuju dengan <a href="<?php echo site_url('kebijakan-privasi'); ?>">Kebijakan Privasi</a> &amp; <a href="<?php echo site_url('syarat-ketentuan'); ?>">Syarat Ketentuan</a>
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
                    <div class="row" id="loading-spin">
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

<div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header-confirmation">
                <h4 class="modal-title">Konfirmasi Data</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Nama Lengkap</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_fullname"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Email</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_email"></p>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Nomor Telepon</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_telp"></p>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-sm-5 control-label">Password</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_password"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Konfirmasi Password</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_confirm_password"></p>
                        </div>
                    </div> -->

                    <?php /*

                    <div class="form-group">
                        <label class="col-sm-5 control-label">Nomor Rekening</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_nomor_rekening"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Bank</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_nama_bank"></p>
                        </div>
                    </div>
                    */?>

                    <div class="form-group">
                        <label class="col-sm-5 control-label">Sumber Dana</label>
                        <div class="col-sm-6">
                            <p class="form-control-static" id="m_sumber_dana"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <p class="alert alert-danger">* Pastikan data Anda sudah benar. Setelah data di Submit, Anda diharuskan aktivasi akun via Email dan SMS.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-center col-sm-12">
                            <button type="button" class="btn btn-danger" id="btn-back"><i class="fa fa-angle-double-left"></i> Kembali</button>
                            &nbsp; 
                            <button type="button" class="btn btn-primary" id="btn-submit"><i class="fa fa-database"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
