

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
                        <h1>Input OTP</h1>
                        <p>Kami akan mengirimkan kode SMS OTP ke handphone Anda</p>
                    </div>
                    <div class="content">
                        <?php 
                        if ($this->session->userdata('message_login')) {
                            $message_show = $this->session->userdata('message_login');
                            unset($_SESSION["message_login"]);
                        ?>
                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                        <?php } ?>

                        <form class="form-horizontal form-validation" >
                            <div class="form-group">
                                <label class="control-label col-sm-3" style="margin-right:-26px;">Kode Negara</label>
                                  <div class="col-md-2" style="margin-right:-20px;">
                                        <input type="text" class="form-control" id="country_code" value="<?php echo $kode_negara; ?>" maxlength="3" disabled>
                                  </div>

                                  <label class="control-label col-sm-2" style="margin-right:-26px;">No.HP</label>
                                  <div class="col-md-4">
                                        <input type="text" class="form-control" id="phone_num" value="<?php echo $notelp; ?>" maxlength="12" disabled>
                                  </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="col-md-3"> &nbsp;</label>
                                <button type="button" class="btn btn-blue" onclick="phone_btn_onclick();">Kirim SMS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://sdk.accountkit.com/id_ID/sdk.js"></script>
<script type="text/javascript">
    // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
    AccountKit.init(
      {
        appId:<?php echo $this->config->item('fb_app_id'); ?>, 
        state:"8000123GThui245", 
        version:"v1.1",
        fbAppEventsEnabled:true,
        display: 'modal',
      }
    );
  };

  // login callback
  function loginCallback(response) {
    console.log(response);
    if (response.status === "PARTIALLY_AUTHENTICATED") {
      window.location.replace(BASEURL +'otp-login');
      /*document.getElementById("code").value = response.code;
      document.getElementById("csrf_nonce").value = response.state;
      document.getElementById("my_form").submit();*/
    }
    else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
    }
    else if (response.status === "BAD_PARAMS") {
      // handle bad parameters
    }
  }

  // phone form submission handler
  function phone_btn_onclick() {
    var country_code = document.getElementById("country_code").value;
    var ph_num = document.getElementById("phone_num").value;
    AccountKit.login('PHONE', 
      {countryCode: country_code, phoneNumber: ph_num}, // will use default values if this is not specified
      loginCallback);
  }


  // email form submission handler
  function email_btn_onclick() {
    var email_address = document.getElementById("email").value;

    AccountKit.login('EMAIL', {emailAddress: email_address}, loginCallback);
  }
</script>