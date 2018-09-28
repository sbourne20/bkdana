<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>static/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>static/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>static/css/style.css?1" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="lock-screen" onload="startTime()">

    <div class="lock-wrapper">

        <div id="time"></div>

        <?php $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : base64_encode(base_url()) ?>
        <div class="lock-box text-center">
            <div class="lock-name"><?php echo isset($CU->username) ? $CU->username : 'Guest'; ?></div>
            <img src="<?php echo $this->config->item('images_uri'); ?>lock_thumb.jpg" alt="lock avatar"/>
            <div class="lock-pwd">
                <form role="form" class="form-inline" action="<?php echo site_url('/log/in?redirect='.$redirect) ?>" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="username" value="<?= $CU->username ?>">
                        <input type="hidden" name="redirect" value="<?= $redirect ?>">
                        <input type="password" name="password" placeholder="Password" id="exampleInputPassword2" class="form-control lock-input">
                        <button class="btn btn-lock" type="submit">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
        function startTime()
        {
            var today=new Date();
            var h=today.getHours();
            var m=today.getMinutes();
            var s=today.getSeconds();
            // add a zero in front of numbers<10
            m=checkTime(m);
            s=checkTime(s);
            document.getElementById('time').innerHTML=h+":"+m+":"+s;
            t=setTimeout(function(){startTime()},500);
        }

        function checkTime(i)
        {
            if (i<10)
            {
                i="0" + i;
            }
            return i;
        }
    </script>
</body>
</html>
