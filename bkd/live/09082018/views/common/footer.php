<?php 
$classfooter = '';
$login_status = isset($_SESSION['_bkdlog_'])? htmlentities(strip_tags($_SESSION['_bkdlog_'])) : '0';

if ($login_status == 1)
{
    $classfooter = 'class="overflow-wrapp"';    
}
?>

<footer <?php echo $classfooter; ?>>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="left">
                    <a href="<?php echo site_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/logo2.png" class="img-responsive img-reset" /></a>
                    <div class="copyright">Copyright <?php echo date('Y'); ?> © PT. Berkah Kelola Dana. All Rights Reserved</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="right text-right">
                    <ul class="socmed"> 
                        <li><a href="#" title="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#" title="Twitter" target="_blank"><i class="fab fa-twitter"></i></a></li>
                    </ul>
                    <div class="footer-menu">
                        <a href="<?php echo site_url('bantuan'); ?>" title="Bantuan (FAQ)">Bantuan (FAQ)</a>  | 
                        <a href="<?php echo site_url('syarat-ketentuan'); ?>" title="Syarat & Ketentuan">Syarat & Ketentuan</a>  |  
                        <a href="<?php echo site_url('kebijakan-privasi'); ?>" title="Kebijakan Privasi">Kebijakan Privasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
    $login_status = isset($_SESSION['_bkdlog_'])? htmlentities(strip_tags($_SESSION['_bkdlog_'])) : '0';
    $username = isset($_SESSION['_bkduser_'])? htmlentities(strip_tags($_SESSION['_bkduser_'])) : '0';

    if ( $login_status != 1 && (trim($username)=='' OR empty($username))) {
        $url_pinjam_kilat = 'daftar-pinjaman-kilat';
        $url_pinjam_mikro = 'daftar-pinjaman-mikro';
        $url_pinjam_usaha = 'daftar-pinjaman-usaha';
    }else{
        $url_pinjam_kilat = 'formulir-pinjaman-kilat';
        $url_pinjam_mikro = 'formulir-pinjaman-mikro';
        $url_pinjam_usaha = 'formulir-pinjaman-usaha';
    }
?>
<!-- Modal Daftar Peminjam -->
<div class="modal fade" id="modalRegister" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-register">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Jenis Pinjaman di BKDana</h2><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="img-wrapp">
                                <a href="<?php echo site_url('register-pinjaman-kilat'); ?>" title="Daftar Pinjaman Kilat">
                                    <img src="<?php echo base_url(); ?>assets/images/icon-register-1.png" class="img-responsive" alt="Daftar Pinjaman Kilat" title="Daftar Pinjaman Kilat" />
                                </a>
                            </div>
                            <a href="<?php echo site_url('register-pinjaman-kilat'); ?>">Daftar BKDana Kilat</a>
                            <p>Butuh dana Kilat 1 - 2 juta? Seperti biaya Rumah Sakit, Sekolah, Kontrakan, dll. Proses persetujuan hanya 15 menit!</p>
                        </div>
                        <div class="col-sm-6">
                            <div class="img-wrapp">
                                <a href="<?php echo site_url('register-pinjaman-mikro'); ?>" title="Daftar Pinjaman Mikro">
                                    <img src="<?php echo base_url(); ?>assets/images/icon-register-2.png" class="img-responsive" alt="Daftar Pinjaman Mikro" title="Daftar Pinjaman Mikro" />
                                </a>
                            </div>
                            <a href="<?php echo site_url('register-pinjaman-mikro'); ?>">Daftar BKDana Mikro</a>
                            <p>Pinjaman Mikro (Usaha Kecil) untuk solusi Bisnis anda. Platform maksimal sampai dengan 50 juta!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
if ($this->session->userdata('message')) {
	$this->session->unset_userdata('message_type');
	$this->session->unset_userdata('message');
}
?>
<script type="text/javascript">
    var BASEURL = "<?php echo base_url(); ?>";
</script>
<!-- JavaScript -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<!-- jQuery CounterUp -HOME -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.counterup.min.js"></script>

<!-- Daftar Form wizard -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<?php echo $bottom_js; ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/vendor.js"></script>
</body>
</html>
