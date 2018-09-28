<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="shortcut icon" href="<?php echo base_url(); ?>static/images/logo-login.png"> -->

    <title>Login Admin Panel</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />

    <!--Core CSS -->
    <link href="<?php echo base_url(); ?>static/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/bootstrap-reset.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>static/css/styles.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/style-responsive.min.css" rel="stylesheet" />
    <!-- Gritter Notif -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/plugins/gritter/css/jquery.gritter.css" />
</head>

  <body class="login-body">

    <div class="container">
      <form method="POST" id="loginForm" class="form-signin cmxform" action="<?php echo base_url('log/in') ?>" autocomplete="off">
        <h2 class="form-signin-heading"><img src="<?php echo base_url(); ?>static/images/logo-login.png" alt="Logo" /></h2>
        <div class="login-wrap">
            <div class="user-login-info">
                <!-- notif -->
                <?php 
                  $error = validation_errors('<span>','</span>');
                  if (!empty($error)):
                ?>
                <div class="alert alert-block alert-danger fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                    <strong>Oh snap!<br /></strong><?php echo $error; ?>.
                </div>
                <?php endif; ?>
                <!-- end notif -->
                <div class="form-group ">
                  <input type="text" class="form-control" name="username" placeholder="Username" autofocus required />
                </div>
                <div class="form-group ">
                  <input type="password" class="form-control" name="password" placeholder="Password" required />
                </div>
            </div>
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
        </div>
      </form>
    </div>

    <!--Core js-->
    <script src="<?php echo base_url(); ?>static/plugins/jquery.js"></script>
    <script src="<?php echo base_url(); ?>static/bs3/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>static/plugins/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>static/plugins/validation-init.js"></script>

    <?php $this->load->view('vnotif'); ?>
  </body>
</html>
