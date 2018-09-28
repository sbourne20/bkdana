<?php echo doctype('html5'); ?>
<!--[if lt IE 7 ]> <html class="no-js"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> 
<html class="no-js"> <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php echo meta($meta_tag); ?>

    <title><?php echo $title; ?></title>
    <link rel="canonical" href="<?php echo current_url(); ?>" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.png" />

    <?php /*<script src="https://cdn.firebase.com/libs/firebaseui/3.0.0/firebaseui.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/3.0.0/firebaseui.css" />
    */?>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600%7CRaleway:400,600%7CWork+Sans:400,500%7CVarela+Round">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">

    <?php /*
    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.3.0/firebase-database.js"></script>
    */?>
    
    <?php echo $top_css; ?>
    <?php echo $top_js; ?>

    <script type="text/javascript">
    var BASEURL = "<?php echo base_url(); ?>";
    </script>

    <!-- Modernizr lib -->
    <script src="<?php echo base_url(); ?>assets/js/modernizr.custom.js"></script>
    <meta property="fb:app_id" content="1733360370088297" />
</head>
<body>
