<style type="text/css">
    .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('<?php echo base_url(); ?>assets/images/loading_icon.gif') 50% 50% no-repeat rgb(249,249,249);
}
</style>
<div class="loader"></div>
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
                                    <h1 class="wizard__title" >Input OTP</h1>
                                    <!-- <p class="wizard__subheading">Beberapa langkah lagi untuk menjadi peminjam di BKD. <br>Silhakan lengkapi informasi dan data-data Anda terlebih dahulu.</p> -->
                                </div>
                                <div class="wizard__steps">
                                    <nav class="steps">
                                        <div class="step -completed">
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
                                        <div class="step">
                                            <div class="step__content">
                                                <p class="step__number">2</p>
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                                </svg>
                                                <div class="lines">
                                                    <div class="line -background"></div>
                                                    <div class="line -progress"></div>
                                                </div> 
                                            </div>
                                        </div>
                                        
                                    </nav>
                                </div>
                            </div>

                            <?php 
                            if ($referal_url == site_url('login'))
                            {
                                $is_readonly = 'readonly="readonly"';
                            }else{
                                $is_readonly = '';
                            }
                            ?>
                            <form id="sign-in-form" action="#">                             
                            <div class="panels" style="height: 225px;">   
                                    <div class="panel">
                                        <div class="panel__header">
                                            <h2 class="panel__title">2. Aktivasi Nomor Handphone</h2>
                                            <p class="panel__subheading">Kami akan mengirimkan kode SMS OTP ke nomor HP Anda.</p>
                                        </div>
                                        <h3 class="panel__content">
                                        <input class="panel__content mdl-textfield__input" type="text" pattern="\+[0-9\s\-\(\)]+" id="phone-number" value="<?php echo (isset($member['mum_telp']))? $member['mum_telp'] : ''; ?>" <?php echo $is_readonly; ?>></h3><br>
                                    </div>
                            </div>

                            <div class="wizard__footer">
                                <button class="btn btn-purple" id="sign-in-button">Kirim</button>
                                <img id="img_loading" src="<?php echo base_url(); ?>assets/images/loading-text.gif" style="width: 70px;margin: 0 23px;position: relative;top: -8px; display: none;" />
                            </div>
                            </form>



                            <form id="verification-code-form" action="#">                           
                            <div class="panels" style="height: 225px; padding: 50px 0px 5px 50px;">   
                                
                                    <div class="panel__header">
                                        <h2 class="panel__title">3. OTP</h2>
                                        <p class="panel__subheading">Kami telah mengirimkan kode OTP via SMS ke nomor Anda</p>
                                    </div>
                                    <!-- Input to enter the verification code -->
                                    <p class="panel__content">Masukkan Kode OTP :</p>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <div class="form-group">
                                          <input class="form-control mdl-textfield__input" type="text" id="verification-code" maxlength="6">
                                      </div>
                                    </div>
                            </div>

                            <div class="alert alert-danger" id="otp_msg" style="display: none;"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> Kode OTP tidak valid!</div>

                            <div class="wizard__footer">
                                <!-- Button that triggers code verification -->
                                <input type="submit" class="btn btn-purple mdl-button mdl-js-button mdl-button--raised" id="verify-code-button" value="Verify Code"/>
                                <!-- Button to cancel code verification -->
                                <button class="btn btn-purple mdl-button mdl-js-button mdl-button--raised" id="cancel-verify-code-button">Cancel</button>

                                <img id="img_loading_otp" src="<?php echo base_url(); ?>assets/images/loading-text.gif" style="width: 70px;margin: 0 23px;position: relative;top: -8px; display: none;" />
                            </div>

                            <input type="hidden" id="redir-url" value="<?php echo $redirect_uri; ?>">

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>  