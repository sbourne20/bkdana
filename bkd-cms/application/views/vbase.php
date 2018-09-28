<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel">
    <meta name="author" content="MDCREATIVE">
    <!-- <link rel="shortcut icon" href="<?php echo $this->config->item('images_uri'); ?>logo-login.png"> -->
    <title>Admin CMS</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />

    <link href="<?php echo base_url(); ?>static/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/bootstrap-reset.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/validationEngine.jquery.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/plugins/datatables-1-10-3/css/jquery.dataTables.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/plugins/select2/select2.css" rel="stylesheet">
    <?php echo $top_css; ?>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>static/plugins/gritter/css/jquery.gritter.css" />
    <link href="<?php echo base_url(); ?>static/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/style-responsive.min.css" rel="stylesheet"/>

    <style type="text/css">
        .paging_full_numbers { width: 100% !important; }

        #clock {
            font-family: 'Share Tech Mono', monospace;
            color: #ffffff;
            text-align: center;
            /*position: relative;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);*/
            color: #daf6ff;
            text-shadow: 0 0 20px rgba(20, 300, 630, 6), 0 0 20px rgba(20, 300, 630, 0);
        }
    </style>

    <script src="<?php echo base_url(); ?>static/plugins/jquery.js"></script>
    <?php echo $top_js ?>
</head>
<body>
<?php $current_url = isset($_SERVER['REQUEST_URI']) ? base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) : ''; ?>

<section id="container">
<header class="header fixed-top clearfix">
<div class="brand">
    <a href="<?php echo site_url('dashboard'); ?>" class="logo">
        <img src="<?php echo $this->config->item('images_uri'); ?>logo-login.png" alt="" height="35">
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>

<div class="nav notify-row" id="top_menu">
    <ul class="nav top-menu">
        <!-- settings start -->
        <li class="dropdown">            
                <div class="col-md-12" id="clock">                    
                    <span type="text" style='padding-top: 10px; font-family: "Lucida Console", Monaco, monospace'><?php echo date('d F Y') ?> - <span id="time-ticker"><?php echo date('H:i:s') ?></span> WIB</span></div>
        </li>
    </ul>
</div>

<div class="top-nav clearfix">
    <ul class="nav pull-right top-menu">
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="<?php echo $this->config->item('images_uri'); ?>avatar1_small.jpg">
                <span class="username"><?php echo get_username(); ?></span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="<?php echo site_url('user/change_password') ?>"><i class=" fa fa-suitcase"></i>Change Password</a></li>
                <li><a href="<?php echo site_url('dashboard/lock?redirect='.$current_url) ?>"><i class="fa fa-cog"></i> Lock Screen</a></li>
                <li><a href="<?php echo site_url('log/out') ?>"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
    </ul>
</div>
</header>

<!--sidebar start-->
<?php $this->load->view('vsidebar_menu'); ?>
<!--sidebar end-->

<!--main content start-->
<section id="main-content">
    <?php echo $mainContent ?>
</section>
<!--main content end-->

</section>

<script type="text/javascript">
    var baseURL = "<?php echo base_url(); ?>";
    var image_path = "<?php echo base_url(); ?>static/data/images";
    var image_url = "<?php echo $this->config->item('images_posts_uri'); ?>";

    function formatCurrency(num, c, d, t){
    var n = num, 
        c = isNaN(c = Math.abs(c)) ? 2 : c, 
        d = d == undefined ? "." : d, 
        t = t == undefined ? "," : t, 
        s = n < 0 ? "-" : "", 
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
        j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
     };
</script>

<script src="<?php echo base_url(); ?>static/bs3/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/jquery.nicescroll.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/jquery-easing/jquery.easing.min.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/underscore/underscore-min.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/datatables-1-10-3/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/jquery.validationEngine-en.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/jquery.validationEngine.min.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/select2/select2.js"></script>
<script src="<?php echo base_url(); ?>static/plugins/bootbox/bootbox.js"></script>
<!--common script init for all pages-->
<script src="<?php echo $this->config->item('template_uri'); ?>js/scripts.js"></script>
<?php echo $bottom_js; ?>

<?php $this->load->view('vnotif'); ?>

<script>
$(document).ready(function()
{
    var image_backup = '<?php echo base_url(); ?>static/images/'
    $(".picture").error(function(){
        $(this).attr('src', image_backup + '404.png');
    });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
// Create two variable with the names of the months and days in an array
var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

// Create a newDate() object
var newDate = new Date();
// Extract the current date from Date object
newDate.setDate(newDate.getDate());
// Output the day, date, month and year   
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval( function() {
    // Create a newDate() object and extract the seconds of the current time on the visitor's
    var seconds = new Date().getSeconds();
    // Add a leading zero to seconds value
    $("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
    },1000);
    
setInterval( function() {
    // Create a newDate() object and extract the minutes of the current time on the visitor's
    var minutes = new Date().getMinutes();
    // Add a leading zero to the minutes value
    $("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
    },1000);
    
setInterval( function() {
    // Create a newDate() object and extract the hours of the current time on the visitor's
    var hours = new Date().getHours();
    // Add a leading zero to the hours value
    $("#hours").html(( hours < 10 ? "0" : "" ) + hours);
    }, 1000);   
});
</script>
<script type="text/javascript">
    window.onload=function(){
        setInterval("timeticker()", 1000);
    }

    function padleft(val){
        var output  = (val.toString().length==1) ? "0"+val : val;
        return output;
    }

    function timeticker(){
        var gd = new Date();
        var strtime = padleft(gd.getHours()) + ":" + padleft(gd.getMinutes()) + ":" + padleft(gd.getSeconds());
        document.getElementById("time-ticker").innerHTML = strtime;
    }
    </script>
</body>
</html>