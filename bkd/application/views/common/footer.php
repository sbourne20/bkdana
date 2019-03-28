<?php 
$classfooter = '';
$login_status = isset($_SESSION['_bkdlog_'])? htmlentities(strip_tags($_SESSION['_bkdlog_'])) : '0';

if ($login_status == 1)
{
    $classfooter = 'class="overflow-wrapp"';    
}
?>

<!-- Overflow Content -->
<div class="wrapper-content overflow-wrapp">
    <div class="section-widget-register">

        <div class="title-wrapp">
            <div class="row">
                <div class="col-sm-12">
					<img src="<?php echo base_url(); ?>assets/images/logo.png" class="img-responsive" alt="Keyword" title="Keyword" style="width:200px" />
                    <h3>Partner Bisnis Keuangan Anda</h3>
                </div>
            </div>
        </div>
        <div class="area-wrapp">
            <div class="row">
                <div class="col-sm-4">
                    <img src="<?php echo base_url(); ?>assets/images/widget-register-1.jpg" class="img-responsive" />
                </div>
                <div class="col-sm-4 mobile-hide">
                    <img src="<?php echo base_url(); ?>assets/images/widget-register-2.jpg" class="img-responsive" />
                </div>
                <div class="col-sm-4 mobile-hide">
                    <img src="<?php echo base_url(); ?>assets/images/widget-register-3.jpg" class="img-responsive" />
                </div>
            </div>
        </div>
        <!-- <div class="signup-wrapp">
            <div class="row">
                <div class="col-sm-offset-7 col-sm-3 drag-this-up drag-up">
                    <div class="signup">
                        <h3>Daftar Sebagai Pendana</h3>
                        <form>
                            <input name="fullname" placeholder="* Nama Lengkap" type="text">
                            <input name="email" placeholder="* E-mail" type="email">
                            <input name="handphone" placeholder="* No. Handphone" type="text">
                            <input name="password" placeholder="* Password" type="password">
                            <input name="confirm_password" placeholder="* Konfirmasi password" type="password">
                            <input id="agreeWithTerms" type="checkbox">
                            <label for="agreeWithTerms">Saya telah membaca dan saya setuju dengan <br> Kebijakan Privasi & Syarat Ketentuan</label>
                            <br><br>
                            <a href="#" class="btn btn-purple">Daftar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->

    </div>
</div>


<!-- Overflow Content -->
<div class="wrapper-content overflow-wrapp">
    <div class="section-info">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="description">
                        <h2>Perhatian</h2>
                            <ol>
                                <li>BKDana adalah platform Layanan Pinjam Meminjam Uang Berbasis Teknologi Informasi
                                (Fintech Lending) yang hanya menfasilitasi bertemunya Pemberi Pinjaman dan
                                Peminjam.
                                </li>
                                <li>Kesepakatan yang ada di BKDana merupakan hubungan dan kesepakatan perdata
                                secara langsung antara Pemberi Pinjaman dengan Penerima Pinjaman, sehingga
                                segala resiko dan akibat hukum daripadanya ditanggung sepenuhnya oleh masing-masing
                                pihak yang berkontrak.
                                </li>
                                <li>Apabila terjadi Resiko Kredit atau Gagal Bayar dan seluruh kerugian dari atau terkait
                                dengan kesepakatan pinjam meminjam, akan ditanggung sepenuhnya oleh Pemberi
                                Pinjaman. Tidak ada lembaga atau otoritas negara yang bertanggung jawab atas resiko
                                gagal bayar dan kerugian tersebut.
                                </li>
                                <li>Pemberi Pinjaman yang belum memiliki pengetahuan dan pengalaman sebagai
                                pengguna layanan Pinjam-Meminjam atau Fintech lending, disarankan tidak
                                menggunakan layanan ini.
                                </li>
                                <li>Peminjam yang belum pernah memanfaatkan Fintech Lending, wajib
                                mempertimbangkan tingkat bunga pinjaman dan biaya-biaya lainnya sesuai dengan
                                kemampuannya dalam melunasi pinjaman.
                                </li>
                                <li>Setiap bentuk kecurangan akan tercatat secara digital di dunia maya dan dapat
                                diketahui masyarakat luas melalui media social, serta dapat menjadi alat bukti hukum
                                yang sah menurut peraturan mengenai informasi dan transaksi elektronik dalam proses
                                penyelesaian sengketa dan penegakan hukum.
                                </li>
                                <li>Masyarakat Pengguna wajib membaca dan memahami informasi ini secara teliti
                                sebelum mengambil keputusan sebagai Pemberi Pinjaman maupun Penerima Pinjaman.
                                </li>
                            </ol>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="description">
                            <br><br>
                            <p>PT Berkah Kelola Dana ("BKDana") sedang memproses pendaftaran di Otoritas Jasa
                            Keuangan ("OJK") sebagai Penyelenggara Layanan Pinjam Meminjam Uang Berbasis
                            Teknologi Informasi dengan Surat Tanda Terima dari OJK Nomor OJK-011249 tanggal 27
                            Februari 2018 sehingga setelah nanti mendapat status terdaftar maka pelaksanaan kegiatan
                            usaha kami akan diawasi secara ketat oleh OJK berdasarkan Peraturan Otoritas Jasa
                            Keuangan Nomor 77/POJK.01/2016 tentang Layanan Pinjam Meminjam Uang Berbasis
                            Teknologi Informasi.</p>
                            <p>Isi dan materi yang tersedia pada situs bkdana.id dimaksudkan untuk memberikan informasi
                            dan tidak dianggap sebagai sebuah penawaran, permohonan, undangan, saran, maupun
                            rekomendasi untuk menginvestasikan sekuritas, produk pasar modal, atau jasa keuangan
                            lainya. Perusahaan dalam memberikan jasanya hanya terbatas pada fungsi administratif.
                            <p>Pendanaan dan pinjaman yang ditempatkan di rekening BKDana adalah tidak akan dianggap
                            sebagai simpanan yang diselenggarakan oleh Perusahaan seperti diatur dalam Peraturan
                            Perundang-Undangan tentang Perbankan di Indonesia. Perusahaan atau setiap Direktur,
                            Pegawai, Karyawan, Wakil, Afiliasi, atau Agen-Agennya tidak memiliki tanggung jawab terkait
                            dengan setiap gangguan atau masalah yang terjadi atau yang dianggap terjadi yang
                            disebabkan oleh minimnya persiapan atau publikasi dari materi yang tercantum pada situs
                            Perusahaan.</p>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<div class="section-ojk">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 text-center">
					<h4>Terdaftar dan diawasi oleh</h4>
					<img src="<?php echo base_url(); ?>assets/images/OJK.png" class="img-responsive" style="width:200px;"/>        
				</div>
			</div>
		</div>
	</div>
</div>


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
                        <div class="col-sm-4">
                            <div class="img-wrapp">
                                <a href="<?php echo site_url('register-pinjaman-kilat'); ?>" title="Daftar Pinjaman Kilat">
                                    <img src="<?php echo base_url(); ?>assets/images/icon-register-1.png" class="img-responsive" alt="Daftar Pinjaman Kilat" title="Daftar Pinjaman Kilat" />
                                </a>
                            </div>
                            <a href="<?php echo site_url('register-pinjaman-kilat'); ?>">Daftar BKDana Kilat</a>
                            <p>Butuh dana Kilat 1 - 2 juta? Seperti biaya Rumah Sakit, Sekolah, Kontrakan, dll. Proses persetujuan hanya 15 menit!</p>
                        </div>
                        <div class="col-sm-4">
                            <div class="img-wrapp">
                                <a href="<?php echo site_url('register-pinjaman-mikro'); ?>" title="Daftar Pinjaman Mikro">
                                    <img src="<?php echo base_url(); ?>assets/images/icon-register-2.png" class="img-responsive" alt="Daftar Pinjaman Mikro" title="Daftar Pinjaman Mikro" />
                                </a>
                            </div>
                            <a href="<?php echo site_url('register-pinjaman-mikro'); ?>">Daftar BKDana Mikro</a>
                            <p>Pinjaman Mikro (Usaha Kecil) untuk solusi Bisnis anda. Platform maksimal sampai dengan 50 juta!</p>
                        </div>
                        <div class="col-sm-4">
                            <div class="img-wrapp">
                                <a href="<?php echo site_url('register-pinjaman-agri'); ?>" title="Daftar Pinjaman Agri">
                                    <img src="<?php echo base_url(); ?>assets/images/icon-register-3.png" class="img-responsive" alt="Daftar Pinjaman Agri" title="Daftar Pinjaman Agri" />
                                </a>
                            </div>
                            <a href="<?php echo site_url('register-pinjaman-agri'); ?>">Daftar BKDana Agri</a>
                            <p>Pinjaman Agri merupakan solusi bagi Petani. Platform maksimal sampai dengan 100 juta!</p>
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

    unset($_SESSION['message']);
}

if (isset($_SESSION['message_type'])) {
    unset($_SESSION['message_type']);
    $this->session->unset_userdata('message_type');
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

<!-- AutoNumeric --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/1.8.2/autoNumeric.js"></script>

<!-- Daftar Form wizard -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<?php echo $bottom_js; ?>

<script type="text/javascript">
	var txtAmount = $("#txtAmount");
	var selInterest = $("#selInterest");
	var selTenor = $("#selTenor");
	var selResult = $("#lblResult");

	txtAmount.autoNumeric('init',{
		aSep: '.', 
        aDec: ',',
        aForm: true,
        vMax: '999999999',
        vMin: '0'
	});

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	function calculate(){
		var amount = txtAmount.val().replace(/\./g,'');
		var interest = selInterest.val();
		var tenor = selTenor.val();
		var result = 0;
		console.log(amount);
		if(amount > 0 && interest > 0 && tenor > 0){
			result = parseFloat(amount) * (parseFloat(interest)/100/360) * parseFloat(tenor)
			result = parseInt(parseFloat(amount) + parseFloat(result));
			selResult.html("Rp " + numberWithCommas(result) + ",-"); 
		}
	}

	txtAmount.on('keyup',function(){
		calculate();
	})
	selInterest.on('change',function(){
		calculate();
	})
	selTenor.on('change',function(){
		calculate();
	})
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/vendor.js"></script>
</body>
</html>
