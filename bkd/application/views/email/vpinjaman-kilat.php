<!DOCTYPE html>
<html>
<head>
	<title>PERJANJIAN PINJAMAN BKDANA KILAT</title>
	<link href="<?php echo base_url(); ?>static/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>static/css/bootstrap-reset.min.css" rel="stylesheet">
</head>
<body>
<div class="col-md-12">
	<div style="text-align: center !important;">
		<h4>LAMPIRAN I FORM PERJANJIAN PINJAMAN BKDANA KILAT</h4>
		<h3>PERJANJIAN PINJAMAN No: PKS-<?php echo $member['Master_loan_id']; ?></h3>
	</div>
	<hr>
	<p>PERJANJIAN PINJAMAN  ini (selanjutnya disebut sebagai “Perjanjian Pinjaman”) dibuat dan ditandatangani pada hari <?php echo $tgl['day_ind_name']; ?>, tanggal <?php echo $tgl['day'] .' '. $tgl['month_ind_name'] .' '. $tgl['year']; ?> oleh dan antara: </p>
	<ol>
		<li>PT BERKAH KELOLA DANA, suatu Perseroan Terbatas yang didirikan berdasarkan hukum Negara Republik Indonesia, yang beralamat kantor di BKD BUILDING Jl. Guntur 45 Setiabudi Kuningan, Jakarta Selatan 12980  yang dalam hal ini diwakili oleh Widjojo Prawirohardjo dalam kedudukannya selaku Direktur Utama yang dalam hal ini bertindak selaku penerima kuasa dari pemberi pinjaman berdasarkan :
			<ol>
				<?php foreach ($list_pendana as $pd) {
				?>
				<li>Surat kuasa Nama: <?php echo $pd['Nama_pengguna']; ?>, NIK: <?php echo $pd['Id_ktp']; ?></li>
				<?php } ?>
			</ol>
		<p>(selanjutnya disebut sebagai “Pemberi Pinjaman”).</p>
		</li>
		<li>(<?php echo html_entity_decode($member['Nama_pengguna']); ?>) Warga Negara Indonesia, pemegang Kartu Tanda Penduduk nomor <?php echo $member['Id_ktp']; ?> yang beralamat di <?php echo $member['Alamat'].', '.$member['Kota'].', '.$member['Provinsi']; ?> (untuk selanjutnya disebut sebagai “Penerima Pinjaman”)</li>
	</ol>

	<p>(Pemberi Pinjaman, Penerima Pinjaman, masing-masing disebut sebagai ”Pihak” dan secara bersama-sama disebut sebagai ”Para Pihak”)<p> 
	<strong>BAHWA:</strong>
	<ol>
		<li>Penerima Pinjaman memiliki keinginan untuk meminjam dana sejumlah maksimum Rp <?php echo number_format($member['Jml_permohonan_pinjaman_disetujui']); ?> dari Pemberi Pinjaman.</li>
		<li>Pemberi Pinjaman telah setuju untuk meminjamkan Fasilitas Pinjaman (sebagaimana didefinisikan dibawah ini) kepada Penerima Pinjaman dan Para Pihak berniat untuk mencatat pinjaman tersebut di dalam suatu instrumen hukum yang akan menjadi dasar dari adanya pinjaman tersebut dari Pemberi Pinjaman kepada Penerima Pinjaman.</li>
	</ol>
	<p>
	OLEH KARENA ITU, Para Pihak setuju untuk mengadakan Perjanjian Pinjaman ini berdasarkan syarat-syarat dan ketentuan-ketentuan berikut:
	</p>

	<ol>
		<li><p><strong>DEFINISI DAN INTERPRETASI</strong></p>
			<p>1.1 Seluruh istilah-istilah yang digunakan dalam Perjanjian Pinjaman ini memiliki arti sebagaimana sebagai berikut: </p>
			<p><strong>“Akun Penerima Pinjaman”</strong> berarti suatu akun yang dibuat oleh Penerima Pinjaman pada Situs yang memuat informasi antara lain (i) informasi Penerima Pinjaman (ii) jumlah pinjaman yang akan diajukan (iii) jangka waktu pinjaman dan (iv) informasi lainnya; </p>
			<p><strong>“Angsuran”</strong> adalah jumlah pembayaran cicilan tetap secara bulanan yang wajib dibayar oleh Penerima Pinjaman selama Jangka Waktu Fasilitas Pinjaman yang besarnya dan tanggal jatuh tempo angsuran diatur berdasarkan Perjanjian Pinjaman; </p>
			<p><strong>“Biaya Layanan Platform BKDana”</strong> adalah biaya yang dikenakan sehubungan dengan penggunaan layanan Platform BKDana; </p>
			<p><strong>“Fasilitas Pinjaman”</strong> adalah fasilitas pinjaman yang diberikan oleh Pemberi Pinjaman melalui BKDana kepada Penerima Pinjaman sebesar Rp <?php echo number_format($member['Jml_permohonan_pinjaman_disetujui']); ?>; </p>
			<p><strong>“Hari Kerja”</strong> adalah hari, selain hari Sabtu, Minggu dan hari libur resmi nasional, dimana bank buka untuk melakukan kegiatan usahanya sesuai dengan ketentuan Bank Indonesia; </p>
			<p><strong>“Informasi Rahasia”</strong> berarti informasi apapun mengenai Perjanjian Pinjaman serta informasi apapun yang saling dipertukarkan di antara para pihak dan perwakilannya masing-masing sehubungan dengan Perjanjian Pinjaman ini atau menurut Perjanjian Pinjaman ini. Informasi Rahasia tidak meliputi informasi yang dapat atau akan dapat diakses secara publik (selain karena penggunaan atau publikasi yang tidak sah) atau informasi apa pun yang diberikan ke salah satu Pihak oleh pihak ketiga yang diberikan wewenang untuk memberikan informasi tersebut kecuali informasi yang diberikan tersebut dinyatakan sebagai rahasia; </p>
			<p><strong>“BKDana”</strong> adalah PT BERKAH KELOLA DANA sebuah perseroan terbatas yang didirikan berdasarkan hukum Negara Republik Indonesia, beralamat di BKD BUILDING Jl. Guntur 45 Setiabudi Kuningan, Jakarta Selatan 12980;</p>
			<p><strong>“Jangka Waktu Fasilitas Pinjaman”</strong> adalah jangka waktu dari suatu fasilitas pinjaman sejak ditandatanganinya Perjanjian Pinjaman sampai dengan Tanggal Jatuh Tempo; </p>
			<p><strong>“Ketentuan-ketentuan Pinjaman”</strong> adalah ketentuan-ketentuan yang disetujui dari Permohonan Pinjaman yang telah diajukan termasuk informasi sehubungan dengan, antara lain, (i) pagu maksimum pinjaman (ii) Jangka Waktu Fasilitas Pinjaman (iii) Suku Bunga (iv) denda keterlambatan dan (v) kesediaan Penerima Pinjaman untuk menerima Fasilitas Pinjaman apabila dana yang terkumpul selama masa penawaran sedikitnya 80% dari nilai pinjaman yang diajukan oleh Penerima Pinjaman dan Lampiran III Perjanjian Pinjaman ini. </p>
			<p><strong>“Pelunasan Dipercepat”</strong> berarti sebagaimana yang didefinisikan pada Pasal 4.2.1; </p>
			<p><strong>“Pemberi Pinjaman”</strong> adalah pihak yang bertindak selaku pemberi pinjaman atas Fasilitas Pinjaman atau melalui kuasanya yang ditunjuk yang diatur dalam Perjanjian Pinjaman ini; </p>
			<p><strong>“Penawaran Pinjaman”</strong> berarti yang suatu penawaran Fasilitas Pinjaman yang diajukan oleh seorang Pemberi Pinjaman kepada Penerima Pinjaman; </p>
			<p><strong>“Penerima Pinjaman”</strong> adalah perseroangan yang menerima Fasilitas Pinjaman dari Pemberi Pinjaman melalui BKDana; </p>
			<p><strong>“Platform BKDana”</strong> adalah suatu laman yang tersedia pada Situs yang menyediakan berbagai informasi antara lain Permohonan Pinjaman, Akun Penerima Pinjaman; </p>
			<p><strong>“Rekening BKDana”</strong> adalah suatu rekening bank yang terdaftar atas nama BKDana; “Rekening Pembayaran Fasilitas Pinjaman” sebagaimana yang didefinisikan pada Pasal 4.1.2; </p>
			<p><strong>“Situs”</strong> adalah situs www.BKDana.id yang dikelola oleh PT BERKAH KELOLA DANA; </p>
			<p><strong>“Suku Bunga”</strong> adalah persentase bunga dalam jangka waktu tertentu yang bersifat tetap selama masa pinjaman dan dihitung dari besar Fasilitas Pinjaman; </p>
			<p><strong>“Syarat dan Ketentuan Umum”</strong> adalah syarat dan ketentuan umum penggunaan perjanjian pinjaman yang terdapat pada Situs. </p>
			<p><strong>“Tanggal Jatuh Tempo”</strong> adalah berarti tanggal yang ditetapkan di Lampiran I kecuali apabila hari tersebut jatuh pada bukan Hari Kerja, maka dalam hal ini Tanggal Jatuh Tempo adalah Hari Kerja sebelumnya, atau tanggal lain di mana pembayaran terakhir pokok Pinjaman jatuh tempo dan harus dibayar sebagaimana ditetapkan dalam Perjanjian ini dan berdasarkan Perjanjian Pinjaman ini. </p>
			<p>1.2  Judul-judul yang digunakan dalam Perjanjian Pinjaman ini hanya untuk kemudahan dan tidak mempunyai pengaruh apapun terhadap konstruksi Perjanjian Pinjaman serta tidak dapat digunakan untuk menafsirkan ketentuan pasal yang bersangkutan.  </p>
			<p>1.3  Kecuali ditentukan lain, referensi pada ketentuan peraturan perundang-undangan adalah ketentuan peraturan perundang-undangan yang bersangkutan beserta perubahannya dari waktu ke waktu.  </p>
			<p>1.4  Kecuali disyaratkan lain, acuan terhadap suatu pasal, ayat atau lampiran Perjanjian Pinjaman ini adalah acuan terhadap pasal, ayat atau lampiran adalah acuan terhadap pasal, ayat atau lampiran Perjanjian Pinjaman ini, dan acuan terhadap Perjanjian Pinjaman ini adalah acuan terhadap Perjanjian Pinjaman ini beserta lampirannya.
			</p>
		</li>
		<li><p><strong>PENYEDIAAN FASILITAS PINJAMAN DAN TUJUAN PENGGUNAAN FASILITAS PINJAMAN</strong></p>
			<p>Dengan tunduk pada ketentuan-ketentuan dari Perjanjian Pinjaman ini dan yang terdapat dalam Situs termasuk Syarat dan Ketentuan Umum, Pemberi Pinjaman telah setuju untuk menyediakan suatu Fasilitas Pinjaman dalam jumlah sebesar Rp <?php echo number_format($member['Jml_permohonan_pinjaman_disetujui']); ?>.</p>
		</li>
		<li><p><strong>JANGKA WAKTU FASILITAS PINJAMAN</strong></p>
			<p>
			Jangka waktu Fasilitas Pinjaman ditetapkan selama <?php echo $jml_hari; ?> hari terhitung sejak tanggal pengiriman atas seluruh jumlah Fasilitas Pinjaman secara penuh dan dapat diperpanjang sesuai dengan kesepakatan dari Para Pihak (“Jangka Waktu Fasilitas Pinjaman”).
			</p>
		</li>
		<li><p><strong>PEMBAYARAN FASILITAS PINJAMAN DAN PELUNASAN DIPERCEPAT</strong></p>
			<p>4.1  Pembayaran Fasilitas Pinjaman</p>
			<p>4.1.1  Fasilitas Pinjaman dan jumlah lain yang terutang berdasarkan Perjanjian Pinjaman harus dilunasi oleh Penerima Pinjaman dalam Jangka Waktu Fasilitas Pinjaman sesuai dengan jadwal pembayaran pinjaman yang dimuat pada Lampiran I  </p>
			<p>4.1.2	Pembayaran atas Angsuran dilakukan oleh Penerima Pinjaman kepada Pemberi Pinjaman pada Hari Kerja ke rekening bank yang ditentukan pada Lampiran II dari Perjanjian ini (“ Rekening Pembayaran Fasilitas Pinjaman”).  </p>
			<p>4.1.3	 Setiap pembayaran dari Penerima Pinjaman, akan dipergunakan untuk pembayaran dengan urutan sebagai berikut:</p>
			<div style="padding-left: 30px">
				<p>a. biaya-biaya;</p>
				<p>b. denda yang belum dibayarkan;</p>
				<p>c. Suku Bunga; dan </p>
				<p>d. pokok pinjaman yang terutang.</p>
			</div>
			<p>4.1.4  Apabila pembayaran atas Angsuran jatuh pada hari libur nasional di Indonesia atau pada hari Sabtu atau Minggu, maka pembayaran harus dilakukan pada Hari Kerja sebelumnya.</p>
			<p>4.2  Pelunasan Dipercepat</p>
			<p>4.2.1  Penerima Pinjaman diperkenankan untuk melakukan pembayaran seluruh Fasilitas Pinjaman lebih cepat dari waktu yang ditetapkan dengan melakukan permohonan untuk itu melalui Akun Penerima Pinjaman sedikitnya 3 (tiga) Hari Kerja sebelum tanggal pelunasan dipercepat yang direncakan (“Pelunasan Dipercepat”) </p>
			<p>4.2.2  Penerima Pinjaman tidak diperkenankan untuk melakukan pelunasan dipercepat sebagian melainkan harus untuk seluruh Fasilitas Pinjaman yang diterimanya dari Pemberi Pinjaman yang memberikan.</p>
			<p>4.2.3  Pelunasan Dipercepat dikenakan biaya sebesar dua bulan bunga tanpa memperhitungkan masa angsuran berjalan ditambah biaya administrasi sebesar Rp. 100.000 (seratus ribu Rupiah);  Ketentuan mengenai pembayaran di atas dapat berubah sewaktu-waktu sesuai dengan  kebijaksanaan BKDana, dengan pemberitahuan terlebih dahulu kepada Penerima Pinjaman.</p>
		</li>
		<li><p><strong>SUKU BUNGA, BIAYA-BIAYA DAN DENDA KETERLAMBATAN</strong><p>
			<p>5.1  Suku Bunga </p>
			<p>5.1.1  Suku Bunga yang digunakan terhadap suatu Fasilitas Pinjaman adalah Suku Bunga berlaku pada hari terakhir Periode Penawaran pada pukul 17:00 Waktu Indonesian Barat yang tersedia pada laman Platform BKDana.  </p>
			<p>5.1.2  Suku Bunga atas Fasilitas Pinjaman akan diperhitungkan secara harian dengan ketentuan 1 (satu) tahun sama dengan 360 (tiga ratus enam puluh) hari.  </p>
			<p>5.2  Biaya-biaya Atas Fasilitas Pinjaman yang diberikan oleh Pemberi Pinjaman, Penerima Pinjaman wajib  membayar biaya dan pengeluaran sebagai berikut : </p>
			<div style="padding-left: 30px">
				<p>a.	Penerima Pinjaman akan membayar kepada BKDana Biaya Layanan Platform BKDana sebesar 5% (lima persen) dari jumlah Fasilitas Pinjaman yang pembayarannya akan dilakukan dengan cara pengurangan langsung dari jumlah Fasilitas Pinjaman.  </p>
				<p>b.	Penerima Pinjaman setuju untuk membayar seluruh biaya-biaya (termasuk biaya hukum) sehubungan dengan penandatanganan, pelaksanaan termasuk eksekusi dari Perjanjian, atau perjanjian lainnya yang disebutkan di sini yang pembayarannya akan dilakukan dengan cara pengurangan langsung dari jumlah yang ditarik atau cara lain yang merupakan diskresi dari BKDana.  </p>
			</div>
			<p>5.3  Denda Keterlambatan Pemberi Pinjaman dapat mengenakan denda keterlambatan kepada Penerima Pinjaman  sebagaimana diatur pada Ketentuan-ketentuan Pinjaman. </p>

		</li>
		<li><p><strong>PEMULIHAN FASILITAS PINJAMAN</strong></p>
			<p>Untuk lebih menjamin ketertiban pembayaran kembali atas segala apa yang terutang oleh Penerima Pinjaman kepada Pemberi Pinjaman baik karena utang-utang pokok, bunga, biayabiaya lain sehubungan dengan Fasilitas Pinjaman yang telah lewat Tanggal Jatuh Tempo, Penerima Pinjaman dengan ini mengizinkan Pemberi Pinjaman atau melalui BKDana selaku kuasanya untuk melakukan upaya yang diperlukan oleh Pemberi Pinjaman termasuk namun tidak terbatas pada (i) menghubungi Penerima Pinjaman (ii) menggunakan jasa pihak ketiga untuk melakukan penagihan atas segala yang terutang dan telah melewati Tanggal Jatuh Tempo. </p>
		</li>
		<li><p><strong>HAL YANG DILARANG </strong></p>
			<p>Kecuali ditentukan lain oleh Pemberi Pinjaman atau kuasanya, terhitung sejak tanggal Perjanjian Pinjaman sampai dengan dilunasinya seluruh kewajiban yang terutang oleh Penerima Pinjaman kepada Pemberi Pinjaman, Penerima Pinjaman dilarang mengalihkan setiap hak dan kewajiban di Perjanjian Pinjaman dan Syarat dan Ketentuan Umum (termasuk juga hak dan kewajiban dan setiap dokumen pelengkapnya) kepada pihak manapun</p>
		</li>
		<li><p><strong>PERNYATAAN DAN JAMINAN</strong></p>
			<p>Penerima Pinjaman dengan ini berjanji, menyatakan dan menjamin kepada Pemberi Pinjaman sebagai berikut:</p>
				<div style="padding-left: 30px">
					<p>a. Penerima Pinjaman memiliki hak yang sah, kekuasaan dan kewenangan penuh untuk menandatangani, pelaksanaan dan pemenuhan Perjanjian Pinjaman ini. Penandatanganan dan pemenuhan Perjanjian Pinjaman ini adalah sah dan mengikat untuk dilaksanakan dalam segala hal terhadap Penerima Pinjaman;</p>  
					<p>b. Perjanjian Pinjaman ini dan dokumen lain yang disebutkan dalam Perjanjian Pinjaman ini, merupakan kewajiban yang sah dan mengikat untuk dilaksanakan sesuai dengan ketentuannya masing-masing;</p>  
					<p>c. tidak terdapat perkara di pengadilan atau tidak terdapat gugatan atau kemungkinan perkara terhadap Penerima Pinjaman termasuk juga perkara apapun yang berhubungan dengan badan pemerintahan atau badan administratif lainnya atau hal-hal lainnya yang mengancam Penerima Pinjaman yang apabila terjadi dan diputuskan tidak memihak kepada Penerima Pinjaman akan mempengaruhi kemampuan keuangan Penerima Pinjaman atau kemampuannya untuk membayar secara tepat waktu setiap jumlah terutang berdasarkan Perjanjian Pinjaman dan/atau dokumen lainnya atau setiap perubahan atau pelengkapnya;</p>  
					<p>d. Penandatanganan dan pelaksanaan Perjanjian Pinjaman ini oleh Penerima Pinjaman, dan transaksi-transaksi yang diatur dalam Perjanjian tersebut, tidak dan tidak akan bertentangan dengan: (i) undang-undang atau peraturan yang berlaku; atau (ii) setiap perjanjian atau instrumen yang mengikat atas Penerima Pinjaman atau salah satu aset miliknya atau merupakan suatu Wanprestasi atau peristiwa pengakhiran berdasarkan setiap perjanjian atau instrumen apapun yang memiliki atau secara wajar kemungkinan memiliki suatu dampak yang bersifat material terhadap Penerima Pinjaman;</p>
					<p>e. Penerima Pinjaman akan segera memberitahu kepada Pemberi Pinjaman setiap terjadinya Wanprestasi kejadian lain yang dengan diberitahukan atau lewatnya waktu atau keduanya akan merupakan Wanprestasi;</p> 
					<p>f. Penerima Pinjaman tidak sedang dan tidak akan mengajukan permohonan penundaan pembayaran (surenseance van betaling) terhadap Fasiltas Pinjaman yang diberikan berdasarkan Perjanjian ini dan tidak menjadi insolvent atau dinyatakan pailit dan tidak kehilangan haknya untuk mengurus atau menguasai harta bendanya;</p>
					<p>g. semua informasi baik tertulis maupun tidak tertulis yang diberikan kepada Pemberi Pinjaman melalui Situs oleh Penerima Pinjaman dan perwakilannya, sewaktu diberikan dan setiap saat setelahnya berdasarkan pengetahuan terbaiknya adalah benar, lengkap dan tepat serta tidak menyesatkan dalam hal apapun dan tidak ada fakta yang tidak diungkapakan yang memuat setiap informasi yang diberikan kepada Pemberi Pinjaman atau BKDana oleh Penerima Pinjaman menjadi tidak tepat atau menyesatkan. Dalam hal terdapat perubahan atas dokumen persyaratan-persyaratan Penerima Pinjaman diwajibkan untuk melakukan pembaharuan dan/atau pengkinian atas informasi yang tersedia pada Akun Penerima Pinjaman dan mengirimkan dokumen-dokumen tersebut kepada BKDana. </p>
				</div>
			</li>
			<li>
				<p><strong>WANPRESTASI</strong></p>
				<p>Dengan memperhatikan ketentuan dalam Perjanjian Pinjaman ini, dengan terjadinya salah satu dari kejadian-kejadian di bawah ini (selanjutnya disebut sebagai "Wanprestasi") maka seluruh jumlah yang terutang berdasarkan Perjanjian Pinjaman ini akan menjadi jatuh tempo dan harus dibayar oleh Penerima Pinjaman kepada Pemberi Pinjaman dan Pemberi Pinjaman dapat melakukan tindakan apapun juga yang dianggap perlu berdasarkan Perjanjian Pinjaman dan/atau Perjanjian Pemberian Fasilitas Pinjaman, perjanjian lainnya yang dilakukan oleh Penerima Pinjaman dan Pemberi Pinjaman, sesuai dengan peraturan perundang-undangan yang berlaku untuk menjamin pembayaran atas padanya: </p>
				<p>a.	Penerima Pinjaman tidak melaksanakan kewajibannya berdasarkan Perjanjian ini dan/atau perjanjian lainnya yang dilakukan antara Pemberi Pinjaman dan Penerima Pinjaman yang mengakibatkan berakhirnya Perjanjian Pinjaman dan/atau Perjanjian Pemberian Fasilitas Pinjaman, ini dan perjanjian lainnya yang dilakukan antara Pemberi Pinjaman dan Penerima Pinjaman;</p>
				<p>b.	apabila pernyataan, jaminan dan janji Penerima Pinjaman dalam Perjanjian Pinjaman ini dan perjanjian lainnya yang dilakukan antara Pemberi Pinjaman dan Penerima Pinjaman yang disebutkan di sini menjadi atau dapat dibuktikan menjadi tidak benar, tidak akurat atau menyesatkan;  </p>
				<p>c.	Penerima Pinjaman (i) mengajukan permohonan pernyataan kepailitan atas dirinya atau (ii) memiliki tindakan atas dirinya yang apabila tidak dihentikan dalam waktu 30 (tiga puluh) hari kalender dapat mengarah kepada pernyataan tidak mampu membayar utang atau pailit oleh Penerima Pinjaman;  </p>
				<p>d.	terjadinya gangguan di dalam pasar keuangan atau situasi ekonomi atau perubahan lainnya yang berdampak negatif termasuk dan tidak terbatas pada setiap tindakan dari pihak yang berwenang untuk melikuidasi atau menghentikan usaha bisnis atau pekerjaan Penerima Pinjaman yang menurut pendapat Pemberi Pinjaman dapat menghalangi, menunda atau membuat Penerima Pinjaman tidak mampu memenuhi kewajiban-kewajibannya dalam Perjanjian ini. </p>
			</li>
			<li><p><strong>HUKUM YANG BERLAKU DAN PENYELESAIAN SENGKETA </strong></p>
				<p>10.1  Perjanjian ini dan pelaksanaanya ini diatur oleh dan ditafsirkan sesuai dengan hukum yang  berlaku di Republik Indonesia.</p>
				<p>10.2  Apabila terjadi perselisihan antara Para Pihak sehubungan dengan pelaksanaan Perjanjian Pinjaman ini, Para Pihak sepakat untuk menyelesaikannya secara musyawarah. Apabila cara musyawarah tidak tercapai, maka Para Pihak sepakat untuk menyerahkan penyelesaiannya  melalui Arbitrase yang akan dilaksanakan di Jakarta, pada Kantor Badan Arbitrase Nasional Indonesia (“BANI”), oleh 3 (tiga) Arbitrator yang ditunjuk sesuai dengan ketentuan peraturan dan prosedur yang diberlakukan BANI. Keputusan arbiter adalah keputusan yang final, mengikat dan terhadapnya tidak diperbolehkan upaya hukum perlawanan, banding atau kasasi.</p>
			</li>
			<li><p><strong>KETENTUAN LAIN </strong></p>
				<p>11.1 Setiap komunikasi yang akan dilakukan antara Para Pihak berdasarkan atau sehubungan dengan Perjanjian ini dapat dilakukan melalui surat elektronik atau media elektronik lainnya, apabila Para Pihak:</p>
				<p>a.	menyetujui bahwa, kecuali dan sampai diberikan pemberitahuan yang bertentangan, surat elektronik atau media elektronik tersebut akan menjadi bentuk komunikasi yang diterima;</p>
				<p>b.	memberitahukan secara tertulis kepada satu sama lain alamat surat elektronik mereka dan/atau informasi lain apa pun yang diperlukan untuk memungkinkan pengiriman dan penerimaan informasi melalui media tersebut; dan</p>
				<p>c.	memberitahukan kepada satu sama lain setiap perubahan pada alamat surat elektronik (email) mereka atau informasi lain apa pun yang diserahkan oleh mereka.</p>
				<p>Setiap Pihak akan memberitahukan kepada Pihak lain segera setelah mengetahui bahwa sistem surat elektronik miliknya tidak berfungsi karena adanya kerusakan teknis (dan kerusakan tersebut akan berlanjut atau mungkin akan berlanjut selama lebih dari 24 jam). Setelah disampaikannya pemberitahuan tersebut, sampai Pihak tersebut memberitahukan kepada Pihak lainnya bahwa kerusakan teknis itu telah diperbaiki, semua pemberitahuan antara Para Pihak tersebut akan dikirimkan melalui faks atau surat sesuai dengan Pasal 11.1 ini. Pemberitahuan dan komunikasi sehubungan dengan Perjanjian ini akan disampaikan kepada Para Pihak dengan alamat sebagai berikut:<p>
				<p>
					<table width="800">
					<tr><td width="50%">Pemberi Pinjaman:</td><td width="50%">Penerima Pinjaman</td></tr>
					<tr><td>U.p: Widjojo Prawirohardjo</td><td>U.p: <?php echo html_entity_decode($member['Nama_pengguna']); ?></td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr><td>No. Telp: <?php echo $this->config->item('bkd_telp'); ?></td><td>No. Telp: <?php echo ($member['Mobileno']=='')? '' : $member['Mobileno']; ?> </td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr><td>Alamat Surat Elektronik:<br><?php echo $this->config->item('bkd_email'); ?></td><td>Alamat Surat Elektronik:<br><?php echo $member['mum_email']; ?> </td></tr>
					</table>
				</p>

				<p>11.2 Setiap syarat atau ketentuan dari Perjanjian Pinjaman ini dapat dikesampingkan setiap saat oleh Pihak yang berhak atas manfaat daripadanya, tetapi pengesampingan tersebut tidak akan efektif kecuali dituangkan dalam bentuk tertulis yang dilaksanakan sebagaimana mestinya oleh atau atas nama Pihak yang mengesampingkan syarat atau ketentuan tersebut. Tidak ada pengesampingan oleh Pihak manapun akan syarat atau ketentuan apapun dalam Perjanjian Pinjaman ini, dalam satu atau lebih hal, harus dianggap atau ditafsirkan sebagai pengesampingan akan syarat dan ketentuan yang sama ataupun lain dari Perjanjian Pinjaman ini pada setiap kesempatan di masa depan. Semua upaya hukum, baik berdasarkan Perjanjian Pinjaman ini atau oleh Hukum atau lainnya yang dapat diberikan, akan berlaku secara kumulatif dan bukan alternatif.</p>
				<p>11.3  Tidak ada perubahan, amandemen atau pengesampingan Perjanjian Pinjaman ini yang akan berlaku atau mengikat kecuali dibuat secara tertulis dan, dalam hal perubahan atau amandemen, ditandatangani oleh Para Pihak dan dalam hal pengesampingan, oleh Pihak yang mengesampingkan terhadap siapa pengesampingan akan dilakukan. Setiap pengesampingan oleh salah satu Pihak akan hak apapun dalam Perjanjian Pinjaman ini atau setiap pelanggaran Perjanjian Pinjaman ini oleh Pihak lain tidak dapat diartikan sebagai diabaikannya hak lainnya atau bentuk pelanggaran lainnya oleh Pihak lain tersebut, baik dengan sifat yang sama atau sifat berbeda daripadanya.</p>
				<p>11.4  Mengenai Perjanjian Pinjaman ini Penerima Pinjaman dan Pemberi Pinjaman sepakat untuk melepaskan ketentuan Pasal 1266 dari Kitab Undang-undang Hukum Perdata Indonesia.</p>
				<p>11.5  Seluruh lampiran-lampiran, perubahan, penambahan dan/atau addendum dari Perjanjian Pinjaman ini merupakan satu kesatuan dan tidak dapat dipisahkan.</p>
				</li>
	</ol>
	<p>DEMIKIAN, Perjanjian Pinjaman ini ditandatangani dengan menggunakan tanda tangan elektronik sebagaimana diatur dalam Undang-undang Republik Indonesia No.11 Tahun 2008 tentang Informasi dan Transaksi Elektronik oleh Para Pihak atau perwakilannya yang sah pada tanggal sebagaimana disebutkan di bagian awal Perjanjian Pinjaman ini dan akan mempunyai kekuatan hukum yang sama dengan Perjanjian yang dibuat dan ditandatangani secara basah.</p>
	<p><strong>Untuk dan atas nama</strong></p>
	<table width="800">
		<tr><td width="50%">PEMBERI PINJAMAN</td><td width="50%">PENERIMA PINJAMAN</td></tr>
		<tr><td>PT BERKAH KELOLA DANA </td><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>--------------------------------</td><td>-------------------------------</td></tr>
		<tr><td>Nama : Widjojo Prawirohardjo</td><td>Nama : <?php echo html_entity_decode($member['Nama_pengguna']); ?></td></tr>
	</table>


</div>

</body>
</html>