<!-- Header -->
<header class="homepage">
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
        <!-- Slider -->
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="row">
                        <div class="col-sm-6 drag-this-left drag-left" id="position1">
                            <div class="carousel-caption">
                                <h2>Pendanaan <br> Gotong Royong <br> Peer to Peer Lending</h2>
                                <p>Pemberi Pinjaman menginginkan bunga besar dan aman, Peminjam mengharapkan bunga kecil dan ringan bayarnya. BKDana memberikan solusi agar para pihak mendapatkan yang terbaik.</p>
                                <!-- <a href="#" class="btn"><img src="<?php echo base_url(); ?>assets/images/btn-appstore.png"></a>
                                <a href="#" class="btn"><img src="<?php echo base_url(); ?>assets/images/btn-playstore.png"></a> -->
                                <br><br>
                            </div>
                        </div>
                        <div class="col-sm-6 drag-this-right drag-right" id="position2">
                            <img src="<?php echo base_url(); ?>assets/images/header-slider-1.png" class="img-responsive right" alt="" title="" />
                        </div>
                    </div>
                </div>
                <!-- <div class="item">
                    <div class="row">
                        <div class="col-sm-6 drag-this-left drag-left" id="position1">
                            <div class="carousel-caption">
                                <h2>Pilhan Investasi<br> dengan bunga 7%*, mau?</h2>
                                <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mugiat nuesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>
                                <br><br>
                            </div>
                        </div>
                        <div class="col-sm-6 drag-this-right drag-right" id="position2">
                            <img src="<?php echo base_url(); ?>assets/images/header-slider-2.png" class="img-responsive right2" alt="" title="" />
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <a href="#" class="scrolldown"><span></span></a>

    </div>
</header>
<!-- Content -->
<div class="container">
    <div class="wrapper-content">
        
        <div class="section-why">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Mengapa Pilih BKDana</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="img-wrapp">
                        <img src="<?php echo base_url(); ?>assets/images/icon-why-1.png" class="img-responsive" alt="Keyword" title="Keyword" />
                    </div>
                    <h3>Akses di multi perangkat</h3>
                    <p>BKDana hadir di multi platform, baik di Web, Android dan iOS, memudahkan transaksi dimanapun dan kapanpun.</p>
                </div>
                <div class="col-sm-4">
                    <div class="img-wrapp">
                        <img src="<?php echo base_url(); ?>assets/images/icon-why-2.png" class="img-responsive" alt="Keyword" title="Keyword" />
                    </div>
                    <h3>Penggunaan Mudah & Aman</h3>
                    <p>Platform BKDana sangat mudah digunakan dan diawasi oleh OJK</p>
                </div>
                <div class="col-sm-4">
                    <div class="img-wrapp">
                        <img src="<?php echo base_url(); ?>assets/images/icon-why-3.png" class="img-responsive" alt="Keyword" title="Keyword" />
                    </div>
                    <h3>Risiko Terukur & Transparan</h3>
                    <p>BKDana hanya menyalurkan kredit ke pemijam yang tervirifikasi dan dilengkapi analisa credit scoring melalui jejak digital terpercaya.</p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-4">
                    <div class="img-wrapp">
                        <img src="<?php echo base_url(); ?>assets/images/icon-why-4.png" class="img-responsive" alt="Keyword" title="Keyword" />
                    </div>
                    <h3>Pinjaman yang Tersalurkan</h3>
                    <strong class="counter">11,000,000,000</strong> IDR
                </div>
                <div class="col-sm-4">
                    <div class="img-wrapp">
                        <img src="<?php echo base_url(); ?>assets/images/icon-why-5.png" class="img-responsive" alt="Keyword" title="Keyword" />
                    </div>
                    <h3>Investasi tumbuh cepat</h3>
                    <p>Bunga investasi jauh diatas bunga deposito dan besarnya tergantung sektor usaha dan rating peminjam</p>
                </div>
                <div class="col-sm-4">
                    <div class="img-wrapp">
                        <img src="<?php echo base_url(); ?>assets/images/icon-why-6.png" class="img-responsive" alt="Keyword" title="Keyword" />
                    </div>
                    <h3>Investasi telah dikucurkan kepada lebih dari</h3>
                    <strong class="counter">8,000</strong> UMKM
                </div>
            </div>    
        </div>

    </div>
</div>


<!-- Overflow Content -->
<div class="wrapper-content overflow-wrapp">
    <div class="section-aboutus">

        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="video-wrapp">
                        <video id="video1" poster="https://www.bkdana.com/assets/video/video.jpg" onended="run();">
                            <source id="source2" src="https://www.bkdana.com/assets/video/video.mp4" type="video/mp4" />
                            Your browser does not support HTML5 video.
                        </video>
                        <i class="far fa-play-circle" id="video-controls" onclick="playVideo('video1', 'video-controls')"></i>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="description">
                        <h2>Siapa Kami (BKDana)</h2>
                        <p>PT BERKAH KELOLA DANA adalah perusahaan yang mengelola platform layanan
                        pinjam meminjam uang berbasis teknologi, khususnya fasilotator untuk mempertemukan
                        pihak yang sedang membutuhkan pendanaan dan pihak yang bersedia memberi
                        pinjaman.</p>
                        <p>Platform kami berbasis web dan mobile apps yang bisa diakses oleh pemberi pinjaman
                        secara global baik dalam dan luar negeri, yang akan membantu permodalan bagi
                        perorangan maupun Usaha Mikro, Kecil dan Menengah (UMKM) di seluruh wilayah
                        Indonesia.</p>
                        <a href="<?php echo site_url('tentang-kami'); ?>" class="btn btn-blue">Baca Lagi <i class="fas fa-play"></i></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- Content -->
<div class="container">
    <div class="wrapper-content">

        <div class="section-fundrising">          
            <div class="row">
                <div class="col-sm-12">
                    <span>Lihat daftar pinjaman kami dan temukan peluang untuk mendanai hari ini.</span>
                    <h2>Penawaran Pendanaan Terbaru</h2>
                </div>
            </div>
            <div class="list-wrapp">
                <div class="row">
                    <div class="col-sm-4 drag-this-up drag-up">
                        <a href="#">
                            <div class="item-wrapp">
                                <div class="product-type">
                                    <div class="color-wrapp blue"></div>
                                </div>
                                <ul class="product">
                                    <li>Pinjaman : 249,000,000 IDR</li>
                                    <li>Pengembalian : 278,000,000 IDR</li>
                                    <li>Tenor : 76 hari</li>
                                    <li>Lokasi : Jakarta</li>
                                </ul>
                                <div class="description">
                                    PT. Sarimelati Kencana
                                    <div><i>Industri Makanan & Masakan Olahan</i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 drag-this-up drag-up">
                        <a href="#">
                            <div class="item-wrapp">
                                <div class="product-type">
                                    <div class="color-wrapp soft-blue"></div>
                                </div>
                                <ul class="product">
                                    <li>Pinjaman : 48,000,000 IDR</li>
                                    <li>Pengembalian : 75,000,000 IDR</li>
                                    <li>Tenor : 112 hari</li>
                                    <li>Lokasi : Bandung</li>
                                </ul>
                                <div class="description">
                                    PT. Jatrindo Antaransentra
                                    <div><i>Angkutan Laut Domestik</i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 drag-this-up drag-up">
                        <a href="#">
                            <div class="item-wrapp">
                                <div class="product-type">
                                    <div class="color-wrapp soft-green"></div>
                                </div>
                                <ul class="product">
                                    <li>Pinjaman : 2,000,000 IDR</li>
                                    <li>Pengembalian : 2,500,000 IDR</li>
                                    <li>Tenor : 14 hari</li>
                                    <li>Lokasi : Jakarta</li>
                                </ul>
                                <div class="description">
                                    CV. TPI
                                    <div><i>Online Seller Financing</i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="list-wrapp">
                <div class="row">
                    <div class="col-sm-4 drag-this-up drag-up">
                        <a href="#">
                            <div class="item-wrapp">
                                <div class="product-type">
                                    <div class="color-wrapp blue"></div>
                                </div>
                                <ul class="product">
                                    <li>Pinjaman : 249,000,000 IDR</li>
                                    <li>Pengembalian : 278,000,000 IDR</li>
                                    <li>Tenor : 76 hari</li>
                                    <li>Lokasi : Jakarta</li>
                                </ul>
                                <div class="description">
                                    PT. Sarimelati Kencana
                                    <div><i>Industri Makanan & Masakan Olahan</i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 drag-this-up drag-up">
                        <a href="#">
                            <div class="item-wrapp">
                                <div class="product-type">
                                    <div class="color-wrapp soft-blue"></div>
                                </div>
                                <ul class="product">
                                    <li>Pinjaman : 48,000,000 IDR</li>
                                    <li>Pengembalian : 75,000,000 IDR</li>
                                    <li>Tenor : 112 hari</li>
                                    <li>Lokasi : Bandung</li>
                                </ul>
                                <div class="description">
                                    PT. Jatrindo Antaransentra
                                    <div><i>Angkutan Laut Domestik</i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 drag-this-up drag-up">
                        <a href="#">
                            <div class="item-wrapp">
                                <div class="product-type">
                                    <div class="color-wrapp soft-green"></div>
                                </div>
                                <ul class="product">
                                    <li>Pinjaman : 200,000,000 IDR</li>
                                    <li>Pengembalian : 245,000,000 IDR</li>
                                    <li>Tenor : 132 hari</li>
                                    <li>Lokasi : Jakarta</li>
                                </ul>
                                <div class="description">
                                    CV. TPI
                                    <div><i>Online Seller Financing</i></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <a href="<?php echo site_url('login'); ?>" class="btn btn-blue">Selengkapnya <i class="fas fa-play"></i></a>
        </div>

    </div>
</div>


<!-- Overflow Content -->
<div class="wrapper-content overflow-wrapp">
    <div class="section-widget-register">

        <div class="title-wrapp">
            <div class="row">
                <div class="col-sm-12">
                    <span>Partner Bisnis Keuangan Anda</span>
                    <h2>Bingung Mau Investasi?</h2>
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
                                segala resiko dan akibat hukum daripadanya ditanggung sepenuhnya oleh masingmasing
                                pihak yang berkontrak
                                </li>
                                <li>Apabila terjadi Resiko Kredit atau Gagal Bayar dan seluruh kerugian dari atau terkait
                                dengan kesepakatan pinjam meminjam, akan ditanggung sepenuhnya oleh Pemberi
                                Pinjaman. Tidak ada lembaga atau otoritas negara yang bertanggung jawab atas resiko
                                gagal bayar dan kerugian tersebut
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
</div>
