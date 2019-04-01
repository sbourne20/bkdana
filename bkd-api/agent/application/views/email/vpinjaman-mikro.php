<!DOCTYPE html>
<html>
<head>
	<title>PERJANJIAN PINJAMAN BKDANA MIKRO</title>
</head>
<body>

<div class="col-md-12">
	<div style="text-align: center !important;">
		<h3>LAMPIRAN II FORM PERJANJIAN PINJAMAN BKDANA MIKRO</h3>
		<h4>PERJANJIAN PINJAMAN No: PKS-<?php echo $member['Master_loan_id']; ?> Tertanggal <?php echo $tgl_order; ?></h4>
		<p>antara</p>
		<p><?php echo html_entity_decode($member['Nama_pengguna']); ?></p>
		<p>dan</p>
		<p>PT BERKAH KELOLA DANA</p>
	</div>
	<hr>
	<p><strong>PERJANJIAN PINJAMAN </strong>ini (selanjutnya disebut sebagai &ldquo;<strong>Perjanjian Pinjaman</strong>&rdquo;) dibuat dan ditandatangani tanggal <?php echo $tgl['day'] .' '. $tgl['month_ind_name'] .' '. $tgl['year']; ?> oleh dan antara:</p>
	<ol>
		<li>( <?php echo html_entity_decode($member['Nama_pengguna']); ?>) Warga Negara Indonesia, pemegang Kartu Tanda Penduduk nomor <?php echo $member['Id_ktp']; ?> yang beralamat di <?php echo $member['Alamat'].', '.$member['Kota'].', '.$member['Provinsi']; ?> yang memiliki usaha <?php echo $member['What_is_the_name_of_your_business']; ?> beralamat di <?php echo $member['Alamat'].', '.$member['Kota'].', '.$member['Provinsi']; ?> (untuk selanjutnya disebut sebagai &ldquo;<strong>Penerima Pinjaman</strong>&rdquo;) ]
		</li>
	</ol>
	<ol start="2">
	<li><strong>PT BERKAH KELOLA DANA</strong>, suatu perusahaan yang didirikan berdasarkan hukum Negara Republik Indonesia, beralamat di BKD BUILDING, Jl. Guntur 45 Setiabudi Kuningan, Jakarta Selatan 12980, Indonesia, yang dalam hal ini diwakili oleh Widjojo Prawirohardjo dalam kedudukannya selaku Direktur yang dalam hal ini bertindak selaku penerima kuasa dari pemberi pinjaman berdasarkan: 
		<ol>
			<?php foreach ($list_pendana as $pd) {
			?>
			<li>Surat kuasa Nama: <?php echo $pd['Nama_pengguna']; ?>, NIK: <?php echo $pd['Id_ktp']; ?>;</li>
			<?php } ?>
		</ol>
		<p>(selanjutnya disebut sebagai &ldquo;<strong>Pemberi Pinjaman</strong>&rdquo;).</p>
		</li>
	</ol>
	<p>(Pemberi Pinjaman, Penerima Pinjaman, masing-masing disebut sebagai &rdquo;<strong>Pihak</strong>&rdquo; dan secara bersama-sama disebut sebagai &rdquo;<strong>Para Pihak</strong>&rdquo;)</p>
	<p><strong>BAHWA</strong>:</p>
	<ol>
	<li>Penerima Pinjaman bersama dengan PT BERKAH KELOLA DANA (&ldquo;<strong>BKDana</strong>&rdquo;) telah menandatangani Perjanjian Pemberian Fasilitas Pinjaman (&ldquo;<strong>Perjanjian Pemberian Fasilitas Pinjaman</strong>&rdquo;) yang mana BKDana bertindak selaku pengatur transaksi pemberian fasilitas pinjaman melalui Situs atau aplikasi (sebagaimana didefinisikan di bawah ini) yang dikelola BKDana sehingga Penerima Pinjaman bisa memperoleh pinjaman dari satu atau lebih investor;  </li>
	<li>Para Pihak dengan ini sepakat untuk menuangkan kesepakatan Pinjaman yang diberikan oleh Pemberi Pinjaman kepada Penerima Pinjaman melalui sistem dalam Situs atau aplikasi yang dikelola oleh BKDana dalam suatu instrumen hukum yang akan menjadi dasar dari adanya Pinjaman tersebut dari Pemberi Pinjaman kepada Penerima Pinjaman.</li>
	</ol>
	<p>OLEH KARENA ITU, Para Pihak setuju untuk mengadakan Perjanjian Pinjaman ini berdasarkan syarat- syarat dan ketentuan-ketentuan sebagai berikut:</p>
	<ol>
	<li><strong>DEFINISI DAN PENAFSIRAN </strong>
		<p>1.1 <strong>Definisi </strong></p>
		<p>Seluruh istilah-istilah yang digunakan dalam Perjanjian Pinjaman ini memiliki arti sebagaimana sebagai berikut:</p>
		<p>&ldquo;<strong>Jangka Waktu Pinjaman</strong>&rdquo; adalah sebagaimana dimaksud dalam Pasal 3 Perjanjian Pinjaman ini;</p>
		<p>&ldquo;<strong>Jaminan Pribadi</strong>&rdquo; adalah akta jaminan pribadi dan ganti kerugian yang apabila diminta oleh BKDana untuk ditandatangani dan akan dibuat di hadapan notaris di Jakarta, oleh dan antara pemberi jaminan pribadi dan pihak yang bertindak mewakili Pemberi Pinjaman;</p>
		<p>&ldquo;<strong>Klien</strong>&rdquo; adalah klien yang tercatat dalam Lampiran I Perjanjian Pinjaman ini; </p>
		<p>&ldquo;<strong>Pelunasan Dipercepat</strong>&rdquo; adalah sebagaimana dimaksud dalam Pasal 4.2.1 Perjanjian Pinjaman ini;</p>
		<p>&ldquo;<strong>Pinjaman</strong>&rdquo; adalah pinjaman yang diberikan oleh Pemberi Pinjaman kepada Penerima Pinjaman melalui Situs atau aplikasi yang didasarkan pada Piutang yang Memenuhi Syarat;</p>
		<p>&ldquo;<strong>Piutang</strong>&rdquo; adalah yang berkenaan dengan setiap kontrak pekerjaan, piutang yang harus dibayarkan oleh Klien kepada Penerima Pinjaman dalam jumlah yang setara dengan nilai tagihan dari suatu kontrak pekerjaan, termasuk Pajak berdasarkan kontrak pekerjaan tersebut.</p>
		<p>&ldquo;<strong>Piutang Yang Memenuhi Syarat</strong>&rdquo; adalah setiap piutang yang memenuhi kriteria berikut:</p>
		<ol>
			<li>dinyatakan dalam mata uang rupiah;</li>
			<li>terkait dengan Penerima Pinjaman yang tidak melanggar jaminan atau janji apa pun; dan</li>
			<li>terkait dengan suatu kontrak pekerjaan;</li>
			<li>yang disetujui oleh koordinator fasilitas sebagaimana diatur dalam Perjanjian Pemberian Fasilitas Pinjaman. ;</li>
			<li>yang nilai tagihannya, pada saat dijumlahkan dengan jumlah keseluruhan yang masih terutang berdasarkan pinjaman tidak melebihi jumlah maksimum pinjaman yang tersedia;</li>
		</ol>
		<p>&ldquo;<strong>Rekening Pembayaran Pinjaman</strong>&rdquo; adalah sebagaimana dimaksud dalam Pasal 4.1.2 Perjanjian Pinjaman ini;</p>
		<p>&ldquo;<strong>Situs</strong>&rdquo; adalah situs www.BKDana.id yang dikelola oleh BKDana;</p>
		<p>&ldquo;<strong>Tagihan</strong>&rdquo; adalah tagihan Penerima Pinjaman kepada Klien berdasarkan kontrak pekerjaan antara Penerima Pinjaman dan Klien yang merupakan dokumen yang mendasari Pinjaman yang akan diterima oleh Penerima Pinjaman sebagaimana tersebut dalam Lampiran I Perjanjian Pinjaman ini;</p>
		<p>&ldquo;<strong>Tanggal Jatuh Tempo</strong>&rdquo; adalah tanggal yang tercantum dalam Tagihan yang mana tanggal pembayaran Klien kepada Penerima Pinjaman;</p>
		<p>&ldquo;<strong>Wanprestasi</strong>&rdquo; adalah sebagaimana dimaksud dalam Pasal 10 Perjanjian Pinjaman ini;</p>
		<p>1.2 Judul-judul yang digunakan dalam Perjanjian Pinjaman ini hanya untuk kemudahan dan tidak mempunyai pengaruh apapun terhadap konstruksi Perjanjian Pinjaman serta tidak dapat digunakan untuk menafsirkan ketentuan pasal yang bersangkutan.  </p>
		<p>1.3 Kecuali ditentukan lain, referensi pada ketentuan peraturan perundang-undangan adalah ketentuan peraturan perundang-undangan yang bersangkutan beserta perubahannya dari waktu ke waktu.</p>
		<p>1.4 Kecuali disyaratkan lain, acuan terhadap suatu pasal, ayat atau lampiran Perjanjian Pinjaman ini adalah acuan terhadap pasal, ayat atau lampiran adalah acuan terhadap pasal, ayat atau lampiran Perjanjian Pinjaman ini, dan acuan terhadap Perjanjian Pinjaman ini adalah acuan terhadap Perjanjian Pinjaman ini beserta lampirannya.</p>
		</li>
	</ol>
	<ol>
		<li><strong>PENYEDIAAN PINJAMAN </strong> 
			<p>Dengan tunduk pada ketentuan-ketentuan dari Perjanjian Pinjaman ini dan yang terdapat dalam Situs dan Perjanjian Pemberian Fasilitas Pinjaman, Pemberi Pinjaman telah setuju untuk menyediakan suatu Pinjaman sebagaimana dirinci dalam Lampiran I.</p>
		</li>
	</ol>
	<ol start="2">
	<li><strong>JANGKA WAKTU PINJAMAN </strong> 
		<p>Jangka waktu Pinjaman ditetapkan terhitung sejak tanggal pengiriman atas seluruh jumlah Pinjaman secara penuh sampai dengan Tanggal Jatuh Tempo yaitu tanggal :loan_due_date dan dapat diperpanjang sesuai dengan kesepakatan dari Para Pihak (&ldquo;<strong>Jangka Waktu Pinjaman</strong>&rdquo;).</p>
	</li>
	</ol>
	
	<ol start="3">
	<li><strong>PEMBAYARAN PINJAMAN DAN PELUNASAN DIPERCEPAT</strong> 
		<p>4.1 <strong>Pembayaran Pinjaman </strong></p>
		<p>4.1.1 Pinjaman dan jumlah lain yang terutang berdasarkan Perjanjian Pinjaman harus dilunasi oleh Penerima Pinjaman dalam Jangka Waktu Pinjaman sebagaimana diatur dalam Pasal 3 Perjanjian Pinjaman ini.  </p>
		<p>4.1.2 Pembayaran atas Angsuran dilakukan oleh Penerima Pinjaman kepada Pemberi Pinjaman pada Hari Kerja ke rekening bank yang ditentukan pada Lampiran II dari Perjanjian ini (&ldquo; <strong>Rekening Pembayaran Pinjaman</strong>&rdquo;).</p>
		<p>4.1.3 Setiap pembayaran dari Penerima Pinjaman, akan dipergunakan untuk pembayaran dengan urutan sebagai berikut:</p>
		<ol>
		<li>biaya-biaya;</li>
		<li>denda yang belum dibayarkan;</li>
		<li>Suku Bunga; dan</li>
		<li>pokok pinjaman yang terutang.</li>
		</ol>
		<p>4.1.4 Apabila pembayaran atas Angsuran jatuh pada hari libur nasional di Indonesia atau pada hari Sabtu atau Minggu, maka pembayaran harus dilakukan pada Hari Kerja sebelumnya.</p>
		<p>4.1.5 Pelunasan Pinjaman apa pun dan jumlah lain apa pun yang harus dibayarkan harus dilakukan dengan jumlah hasil piutang yang terkait dengan Pinjaman tersebut. Apabila dengan alasan apa pun, Pemberi Pinjaman tidak menerima jumlah tersebut dalam Rekening Pembayaran Pinjaman sampai dengan Tanggal Jatuh Tempo, Pemberi Pinjaman berwenang untuk melakukan tindakan yang diperlukan berdasarkan Perjanjian Pinjaman ini dan/atau Perjanjian Pemberian Fasilitas Pinjaman.</p>
		<p>4.2 <strong>Pelunasan Dipercepat </strong></p>
		<p>4.2.1 Penerima Pinjaman diperkenankan untuk melakukan pembayaran seluruh Pinjaman lebih cepat dari waktu yang ditetapkan dengan melakukan pemberitahuan tertulis sedikitnya 5 (lima) hari kerja sebelum tanggal pelunasan dipercepat yang direncakan (&ldquo;<strong>Pelunasan Dipercepat</strong>&rdquo;) kepada Pemberi Pinjaman.</p>
		<p>4.2.2 Penerima Pinjaman tidak dikenakan denda terhadap Pelunasan Dipercepat, namun Penerima Pinjaman diwajibkan melunasi bunga kepada Pemberi Pinjaman senilai nominal yang akan terhutang apabila seolah-olah Penerima Pinjaman tidak melakukan pembayaran dipercepat secara sukarela.  </p>
		<p>4.3 <strong>Pembayaran Sebagian </strong> Apabila Pemberi Pinjaman menerima pembayaran yang tidak mencukupi untuk melunasi semua jumlah yang pada saat itu telah jatuh tempo dan harus dibayarkan oleh Penerima Pinjaman, Pemberi Pinjaman akan memotong pembayaran tersebut dari kewajiban-kewajiban Penerima Pinjaman tersebut dengan urutan sebagai berikut:</p>
		<p><strong>pertama</strong>, pada saat atau menjelang pembayaran secara pro rata atas setiap biaya, ongkos dan pengeluaran yang belum dibayarkan kepada Pemberi Pinjaman dan agen lain yang ditunjuk oleh Pemberi Pinjaman;</p>
		<p><strong>kedua</strong>, pada saat atau menjelang pembayaran secara pro rata atas setiap bunga yang terakumulasi, biaya, komisi, ongkos, ganti rugi dan pengeluaran (selain yang ditentukan dalam poin (a) Pasal 4.3 di atas) yang telah jatuh tempo tetapi belum dibayarkan berdasarkan Perjanjian Pinjaman ini;</p>
		<p><strong>ketiga</strong>, pada saat atau menjelang pembayaran secara pro rata atas setiap jumlah pokok yang telah jatuh tempo tetapi belum dibayarkan berdasarkan Perjanjian Pinjaman ini; dan</p>
		<p><strong>keempat</strong>, pada saat atau menjelang pembayaran secara pro rata atas jumlah lain apa pun yang telah jatuh tempo tetapi belum dibayarkan.</p>
	</li>
	</ol>
	
<ol start="5">
<li><strong> SUKU BUNGA, BIAYA-BIAYA DAN DENDA KETERLAMBATAN </strong>
	<p>5.1 <strong>Suku Bunga </strong> Suku bunga yang digunakan terhadap Pinjaman adalah sebagaimana disebutkan dalam term sheet dan Lampiran I Perjanjian Pinjaman ini.</p>
	<p>5.2 <strong>Biaya-biaya</strong><strong> </strong>Atas Pinjaman yang diberikan oleh Pemberi Pinjaman, Penerima Pinjaman wajib membayar  biaya dan pengeluaran sebagai berikut :</p>
	<ol>
	<li>Penerima Pinjaman setuju untuk membayar seluruh biaya-biaya (termasuk biaya hukum) sehubungan dengan penandatanganan, pelaksanaan termasuk eksekusi dari Perjanjian, atau perjanjian lainnya yang disebutkan di sini yang pembayarannya akan dilakukan dengan cara pengurangan langsung dari jumlah yang ditarik atau cara lain yang merupakan diskresi dari Pemberi Pinjaman.  </li>
	<li>Apabila Penerima Pinjaman meminta perubahan, Penerima Pinjaman harus, dalam jangka waktu 5 (lima) Hari Kerja setelah diminta, memberikan penggantian biaya kepada Pemberi Pinjaman atas jumlah dari semua biaya dan pengeluaran (termasuk biaya hukum) yang ditanggung secara wajar oleh pihak tersebut dalam menanggapi, mengevaluasi, merundingkan atau memenuhi permintaan atau persyaratan tersebut.</li>
	</ol>
	<p>5.3 <strong>Denda Keterlambatan</strong><strong> </strong>Pemberi Pinjaman dapat mengenakan denda keterlambatan kepada Penerima Pinjaman  sebagaimana diatur pada ketentuan-ketentuan pinjaman Lampiran I Perjanjian Pinjaman ini.</p>
	</li>
</ol>
<ol start="6">
<li><strong>JAMINAN </strong>
	<p>6.1 Apabila disyaratkan oleh BKDana, Penerima Pinjaman akan memberikan Jaminan Pribadi kepada Penerima Pinjaman guna menjamin pelaksanaan pembayaran Pinjaman yang diterima oleh Penerima Pinjaman berdasarkan Perjanjian Pinjaman ini;</p>
	<p>6.2 Selain Jaminan Pribadi, selama masih terdapat jumlah yang belum dibayarkan oleh Penerima Pinjaman berdasarkan Perjanjian Pinjaman ini, apabila disyaratkan, Penerima Pinjaman wajib memberikan jaminan sebagai agunan kepada Pemberi Pinjaman dalam bentuk, jumlah, nilai serta dengan cara dan persyaratan yang ditentukan oleh Pemberi Pinjaman, termasuk namun tidak terbatas pada jaminan tambahan atau jaminan pengganti segera setelah diminta oleh Pemberi Pinjaman.</p>
	<p>6.3  Apabila ada jaminan tambahan atau jaminan pengganti yang diminta oleh Pemberi Pinjaman, maka Para Pihak, sehubungan dengan pemberian jaminan tersebut, dengan adanya persetujuan tertulis terlebih dahulu dari pihak-pihak yang berwenang, akan membuat dan menandatangani suatu perjanjian jaminan bersama dan/atau dokumen jaminan lainnya.</p>
	<p>6.4 Dalam hal terdapat jaminan maka akan dikuasakan kepada Loan Organizer.</p>
</li>
</ol>
<ol start="7">
<li><strong>PEMULIHAN PINJAMAN </strong>
<p>Untuk lebih menjamin ketertiban pembayaran kembali atas segala apa yang terutang oleh Penerima Pinjaman kepada Pemberi Pinjaman baik karena utang-utang pokok, bunga, biaya-biaya lain sehubungan dengan Pinjaman yang telah lewat tanggal jatuh tempo, Penerima Pinjaman dengan ini mengizinkan Pemberi Pinjaman atau kuasanya untuk melakukan upaya yang diperlukan oleh Pemberi Pinjaman termasuk namun tidak terbatas pada (i) menghubungi Penerima Pinjaman (ii) menggunakan jasa pihak ketiga untuk melakukan penagihan atas segala yang terutang dan telah melewati tanggal jatuh tempo.</p>
</li>
</ol>

<ol start="8">
<li><strong>HAL YANG DILARANG </strong>
	<p>8.1 Kecuali ditentukan lain oleh Pemberi Pinjaman atau kuasanya, terhitung sejak tanggal Perjanjian Pinjaman sampai dengan dilunasinya seluruh kewajiban yang terutang oleh Penerima Pinjaman kepada Pemberi Pinjaman, Penerima Pinjaman dilarang mengalihkan setiap hak dan kewajiban di Perjanjian Pinjaman dan Perjanjian Pemberian Fasilitas Pinjaman (termasuk juga hak dan kewajiban dan setiap dokumen pelengkapnya) kepada pihak manapun.</p>
	<p>8.2 Penerima Pinjaman menyatakan dan menjamin kepada Pemberi Pinjaman bahwa Penerima Pinjaman tidak akan mengalihkan, menjual, menganjakpiutangkan, menjaminkan atau menggunakan Tagihan sebagai dasar untuk memperoleh pinjaman dari pihak ketiga manapun atas Tagihan. </p>
</li>
</ol>
<ol start="9">
	<li><strong>PERNYATAAN DAN JAMINAN</strong>
	<p>Penerima Pinjaman dengan ini berjanji, menyatakan dan menjamin kepada Pemberi Pinjaman sebagai  berikut:</p>
	</li>
</ol>

<ol>
<li>Penerima Pinjaman memiliki hak yang sah, kekuasaan dan kewenangan penuh untuk menandatangani, pelaksanaan dan pemenuhan Perjanjian Pinjaman ini. Penandatanganan dan pemenuhan Perjanjian Pinjaman ini adalah sah dan mengikat untuk dilaksanakan dalam segala hal terhadap Penerima Pinjaman;</li>
<li>Perjanjian Pinjaman ini dan dokumen lain yang disebutkan dalam Perjanjian Pinjaman ini, merupakan kewajiban yang sah dan mengikat untuk dilaksanakan sesuai dengan ketentuannya masing-masing;</li>
<li>tidak terdapat perkara di pengadilan atau tidak terdapat gugatan atau kemungkinan perkara terhadap Penerima Pinjaman termasuk juga perkara apapun yang berhubungan dengan badan pemerintahan atau badan administratif lainnya atau hal-hal lainnya yang mengancam Penerima Pinjaman yang apabila terjadi dan diputuskan tidak memihak kepada Penerima Pinjaman akan mempengaruhi kemampuan keuangan Penerima Pinjaman atau kemampuannya untuk membayar secara tepat waktu setiap jumlah terutang berdasarkan Perjanjian Pinjaman dan/atau dokumen lainnya atau setiap perubahan atau pelengkapnya;</li>
<li>Penandatanganan dan pelaksanaan Perjanjian Pinjaman ini oleh Penerima Pinjaman, dan transaksi-transaksi yang diatur dalam Perjanjian tersebut, tidak dan tidak akan bertentangan dengan: (i) undang-undang atau peraturan yang berlaku; atau (ii) setiap perjanjian atau instrumen yang mengikat atas Penerima Pinjaman atau salah satu aset miliknya atau merupakan suatu Wanprestasi atau peristiwa pengakhiran berdasarkan setiap perjanjian atau instrumen apapun yang memiliki atau secara wajar kemungkinan memiliki suatu dampak yang bersifat material terhadap Penerima Pinjaman;</li>
<li>Penerima Pinjaman menyatakan dan menjamin kepada Pemberi Pinjaman bahwa Penerima Pinjaman tidak akan mengalihkan, menjual, menganjakpiutangkan, menjaminkan atau menggunakan Tagihan sebagai dasar untuk memperoleh pinjaman dari pihak ketiga manapun atas Tagihan;</li>
<li>Penerima Pinjaman akan segera memberitahu kepada Pemberi Pinjaman setiap terjadinya Wanprestasi kejadian lain yang dengan diberitahukan atau lewatnya waktu atau keduanya akan merupakan Wanprestasi;</li>
<li>Penerima Pinjaman tidak sedang dan tidak akan mengajukan permohonan penundaan pembayaran (surenseance van betaling) terhadap Fasiltas Pinjaman yang diberikan berdasarkan Perjanjian ini dan tidak menjadi insolvent atau dinyatakan pailit dan tidak kehilangan haknya untuk mengurus atau menguasai harta bendanya;</li>
<li>semua informasi baik tertulis maupun tidak tertulis yang diberikan kepada Pemberi Pinjaman melalui Situs oleh Penerima Pinjaman dan perwakilannya, sewaktu diberikan dan setiap saat setelahnya berdasarkan pengetahuan terbaiknya adalah benar, lengkap dan tepat serta tidak menyesatkan dalam hal apapun dan tidak ada fakta yang tidak diungkapakan yang memuat setiap informasi yang diberikan kepada Pemberi Pinjaman atau kuasanya oleh Penerima Pinjaman menjadi tidak tepat atau menyesatkan. Dalam hal terdapat perubahan atas dokumen persyaratan-persyaratan Penerima Pinjaman diwajibkan untuk melakukan pembaharuan dan/atau pengkinian atas informasi yang tersedia pada akun Penerima Pinjaman dan mengirimkan dokumen-dokumen tersebut kepada Pemberi Pinjaman.</li>
</ol>
<ol start="10">
	<li><strong>WANPRESTASI </strong>
	<p>Dengan memperhatikan ketentuan dalam Perjanjian Pinjaman ini, dengan terjadinya salah satu dari kejadian-kejadian di bawah ini (selanjutnya disebut sebagai "Wanprestasi") maka seluruh jumlah yang terutang berdasarkan Perjanjian Pinjaman ini akan menjadi jatuh tempo dan harus dibayar oleh Penerima Pinjaman kepada Pemberi Pinjaman dan Pemberi Pinjaman dapat melakukan tindakan apapun juga yang dianggap perlu berdasarkan Perjanjian Pinjaman dan/atau Perjanjian Pemberian Fasilitas Pinjaman, perjanjian lainnya yang dilakukan oleh Penerima Pinjaman dan Pemberi Pinjaman, sesuai dengan peraturan perundang-undangan yang berlaku untuk menjamin pembayaran atas padanya:</p>
	<ol>
	<li>Penerima Pinjaman tidak melaksanakan kewajibannya berdasarkan Perjanjian ini dan/atau perjanjian lainnya yang dilakukan antara Pemberi Pinjaman dan Penerima Pinjaman yang mengakibatkan berakhirnya Perjanjian Pinjaman dan/atau Perjanjian Pemberian Fasilitas Pinjaman, ini dan perjanjian lainnya yang dilakukan antara Pemberi Pinjaman dan Penerima Pinjaman;</li>
	<li>apabila pernyataan, jaminan dan janji Penerima Pinjaman dalam Perjanjian Pinjaman ini dan perjanjian lainnya yang dilakukan antara Pemberi Pinjaman dan Penerima Pinjaman yang disebutkan di sini menjadi atau dapat dibuktikan menjadi tidak benar, tidak akurat atau menyesatkan;  </li>
	<li>Penerima Pinjaman (i) mengajukan permohonan pernyataan kepailitan atas dirinya atau (ii) memiliki tindakan atas dirinya yang apabila tidak dihentikan dalam waktu 30 (tiga puluh) hari kalender dapat mengarah kepada pernyataan tidak mampu membayar utang atau pailit oleh Penerima Pinjaman;  </li>
	<li>terjadinya gangguan di dalam pasar keuangan atau situasi ekonomi atau perubahan lainnya yang berdampak negatif termasuk dan tidak terbatas pada setiap tindakan dari pihak yang berwenang untuk melikuidasi atau menghentikan usaha bisnis atau pekerjaan Penerima Pinjaman yang menurut pendapat Pemberi Pinjaman dapat menghalangi, menunda atau membuat Penerima Pinjaman tidak mampu memenuhi kewajiban-kewajibannya dalam Perjanjian ini.  </li>
	</ol>
	</li>
</ol>
<ol start="11">
	<li><strong>HUKUM YANG BERLAKU DAN PENYELESAIAN SENGKETA </strong>
	<p>11.1 Perjanjian ini dan pelaksanaanya ini diatur oleh dan ditafsirkan sesuai dengan hukum yang  berlaku di Republik Indonesia.  </p>
	<p>11.2 Apabila terjadi perselisihan antara Para Pihak sehubungan dengan pelaksanaan Perjanjian Pinjaman ini, Para Pihak sepakat untuk menyelesaikannya secara musyawarah. Apabila cara musyawarah tidak tercapai, maka Para Pihak sepakat untuk menyerahkan penyelesaiannya melalui Arbitrase yang akan dilaksanakan di Jakarta, pada Kantor Badan Arbitrase Nasional Indonesia (&ldquo;BANI&rdquo;), oleh 3 (tiga) Arbitrator yang ditunjuk sesuai dengan ketentuan peraturan dan prosedur yang diberlakukan BANI. Keputusan arbiter adalah keputusan yang final, mengikat dan terhadapnya tidak diperbolehkan upaya hukum perlawanan, banding atau kasasi.  </p>
	</li>
</ol>
<ol start="12">
<li><strong>KETENTUAN LAIN </strong> 
	<p>12.1 Setiap komunikasi yang akan dilakukan antara Para Pihak berdasarkan atau sehubungan dengan Perjanjian ini dapat dilakukan melalui surat elektronik atau media elektronik lainnya, apabila Para Pihak:</p>
	<ol>
	<li>menyetujui bahwa, kecuali dan sampai diberikan pemberitahuan yang bertentangan, surat elektronik atau media elektronik tersebut akan menjadi bentuk komunikasi yang diterima;  </li>
	<li>memberitahukan secara tertulis kepada satu sama lain alamat surat elektronik mereka dan/atau informasi lain apa pun yang diperlukan untuk memungkinkan pengiriman dan  penerimaan informasi melalui media tersebut; dan</li>
	<li>memberitahukan kepada satu sama lain setiap perubahan pada alamat surat elektronik (email) mereka atau informasi lain apa pun yang diserahkan oleh mereka.</li>
	</ol>
	<p>Setiap Pihak akan memberitahukan kepada Pihak lain segera setelah mengetahui bahwa sistem surat elektronik miliknya tidak berfungsi karena adanya kerusakan teknis (dan kerusakan tersebut akan berlanjut atau mungkin akan berlanjut selama lebih dari 24 jam). Setelah disampaikannya pemberitahuan tersebut, sampai Pihak tersebut memberitahukan kepada Pihak lainnya bahwa kerusakan teknis itu telah diperbaiki, semua pemberitahuan antara Para Pihak tersebut akan dikirimkan melalui faks atau surat sesuai dengan Pasal 12.1 ini. Pemberitahuan dan komunikasi sehubungan dengan Perjanjian ini akan disampaikan kepada Para Pihak dengan alamat sebagai berikut:</p>
	<p>
		<table width="800">
		<tr><td width="50%">Pemberi Pinjaman:</td><td width="50%">Penerima Pinjaman</td></tr>
		<tr><td>U.p: Widjojo Prawirohardjo</td><td>U.p: <?php echo html_entity_decode($member['Nama_pengguna']); ?></td></tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>No. Telp: <?php echo $this->config->item('bkd_telp'); ?></td><td>No. Telp: <?php echo ($member['Mobileno']=='')? '' : $member['Mobileno']; ?> </td></tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td>Alamat Surat Elektronik: <br><?php echo $this->config->item('bkd_email'); ?></td><td>Alamat Surat Elektronik: <br><?php echo $member['mum_email']; ?> </td></tr>
		</table>
	</p>
	<p>12.2 Setiap syarat atau ketentuan dari Perjanjian Pinjaman ini dapat dikesampingkan setiap saat oleh Pihak yang berhak atas manfaat daripadanya, tetapi pengesampingan tersebut tidak akan efektif kecuali dituangkan dalam bentuk tertulis yang dilaksanakan sebagaimana mestinya oleh atau atas nama Pihak yang mengesampingkan syarat atau ketentuan tersebut. Tidak ada pengesampingan oleh Pihak manapun akan syarat atau ketentuan apapun dalam Perjanjian Pinjaman ini, dalam satu atau lebih hal, harus dianggap atau ditafsirkan sebagai pengesampingan akan syarat dan ketentuan yang sama ataupun lain dari Perjanjian Pinjaman ini pada setiap kesempatan di masa depan. Semua upaya hukum, baik berdasarkan Perjanjian Pinjaman ini atau oleh Hukum atau lainnya yang dapat diberikan, akan berlaku secara kumulatif dan bukan alternatif.</p>
	<p>12.3 Tidak ada perubahan, amandemen atau pengesampingan Perjanjian Pinjaman ini yang akan berlaku atau mengikat kecuali dibuat secara tertulis dan, dalam hal perubahan atau amandemen, ditandatangani oleh Para Pihak dan dalam hal pengesampingan, oleh Pihak yang mengesampingkan terhadap siapa pengesampingan akan dilakukan. Setiap pengesampingan oleh salah satu Pihak akan hak apapun dalam Perjanjian Pinjaman ini atau setiap pelanggaran Perjanjian Pinjaman ini oleh Pihak lain tidak dapat diartikan sebagai diabaikannya hak lainnya atau bentuk pelanggaran lainnya oleh Pihak lain tersebut, baik dengan sifat yang sama atau sifat berbeda daripadanya.</p>
	<p>12.4 Mengenai Perjanjian Pinjaman ini Penerima Pinjaman dan Pemberi Pinjaman sepakat untuk melepaskan ketentuan Pasal 1266 dari Kitab Undang-undang Hukum Perdata Indonesia.</p>
	<p>12.5 Seluruh lampiran-lampiran, perubahan, penambahan dan/atau addendum dari Perjanjian Pinjaman ini merupakan satu kesatuan dan tidak dapat dipisahkan.</p>
	<p><strong>DEMIKIAN</strong>, Perjanjian Pinjaman ini ditandatangani dengan menggunakan tanda tangan elektronik sebagaimana diatur dalam Undang-undang Republik Indonesia No.11 Tahun 2008 tentang Informasi dan Transaksi Elektronik oleh Para Pihak atau perwakilannya yang sah pada tanggal sebagaimana disebutkan di bagian awal Perjanjian Pinjaman ini dan akan mempunyai kekuatan hukum yang sama dengan Perjanjian yang dibuat dan ditandatangani secara basah.</p>
	</li>
</ol>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	
		<table width="800" style="padding-left: 40px !important;">
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