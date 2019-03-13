<!-- Header -->
<header class="pendanaan-page">
	<div class="filter"></div>
    <div class="container">
		<?php $this->load->view('common/navigation_all'); ?>
		
		<!-- Slider -->
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="row">
                        <div class="col-md-6 drag-this-left drag-left">
                            <div class="carousel-caption">
                                <h2>Investasikan dana Anda dan dapatkan keuntungan hingga 18% per tahun</h2>
                                <p>Solusi investasi yang terpercaya dengan resiko terukur serta keuntungan yang tinggi. Dengan berinvestasi di Kaspia, anda membantu perekonomian masyarakat untuk mengembangkan usahanya.</p>
                                <br><br>
                            </div>
						</div>
						<div class="col-md-4 col-md-offset-1 col-xs-12 drag-this-right drag-right ">
							<form class="form-pendanaan-kalkulator">
								<div class="form-group text-center">
									<h3>Simulasi Pendanaan</h3>
								</div>
								<div class="form-group">
									<label for="txtAmount">Jumlah pendanaan</label>
									<input type="text" class="form-control" id="txtAmount" aria-describedby="amount" placeholder="10.000.000">
									<small id="amount" class="form-text text-muted">Batas pendanaan 1.000.000 - 999.999.999</small>
								</div>
								<div class="form-group">
									<label for="selInterest">Suku Bunga</label>
									<select class="form-control" id="selInterest">
										<option value="0">-- Pilih suku bunga --</option>
										<option value="10">10%</option>
										<option value="11">11%</option>
										<option value="12">12%</option>
										<option value="13">13%</option>
										<option value="14">14%</option>
										<option value="15">15%</option>
										<option value="16">16%</option>
										<option value="17">17%</option>
										<option value="18">18%</option>
									</select>
								</div>
								<div class="form-group">
									<label for="selTenor">Lama Pendanaan</label>
									<select class="form-control" id="selTenor">
										<option value="0">-- Pilih lama pendanaan --</option>
										<option value="30">30 hari</option>
										<option value="60">60 hari</option>
										<option value="90">90 hari</option>
										<option value="120">120 hari</option>
										<option value="150">150 hari</option>
										<option value="180">180 hari</option>
									</select>
								</div>
								<div class="form-group">
									<label>Pengembalian Dana *</label>
									<h3 id="lblResult">Rp 0,-</h3>
									<h5 class="text-right"><small class="text-muted">*Pengembalian dana belum termasuk pajak</small></h5>
								</div>
								<hr>
								<div class="form-group text-center">
									<a class="btn btn-blue" href="<?php echo site_url('register-pendana');?>">Daftar sebagai Pendana</a>
								</div>
							</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="scrolldown"><span></span></a>
        
    </div>
</header>

<!-- Content -->
<div class="container">
    <div class="wrapper-content"> 
		
		<div class="section-pendanaan-keuntungan">
			<div class="row text-center">
				<h2 class="title-raleway kaspia-color">Apa Keuntungan Investasi di BKDana ?</h2>
			</div>
			<div class="row text-center">
				<div class="col-md-8 col-md-offset-2">
					<div class="col-md-4 col-sm-12">
						<img src="<?php echo base_url(); ?>assets/images/mudah-cepat.png" class="img-responsive">
						<h4>Return Tinggi</h4>
					</div>
					<div class="col-md-4 col-sm-12">
						<img src="<?php echo base_url(); ?>assets/images/tanpa-anggunan.png" class="img-responsive">
						<h4>Mudah dan cepat</h4>
					</div>
					<div class="col-md-4 col-sm-12">
						<img src="<?php echo base_url(); ?>assets/images/bunga-kompetitif.png" class="img-responsive">
						<h4>Mulai Dengan Rp.100.000</h4>
					</div>
				</div>
			</div>
		</div>

		<div class="section-pendanaan-proses">
			<div class="row text-center">
				<h2 class="title-raleway kaspia-color">Proses Menjadi Pendana</h2>
			</div>
			<div class="row text-center">
				<div class="col-md-10 col-md-offset-1">
					<div class="col-md-3 col-sm-12">
						<img src="<?php echo base_url(); ?>assets/images/pendanaan-1.png" class="img-responsive">
						<h4>1. Daftar sebagai pendana</h4>
					</div>
					<div class="col-md-3 col-sm-12">
						<img src="<?php echo base_url(); ?>assets/images/pendanaan-2.png" class="img-responsive">
						<h4>2. Pilih dan danai pinjaman</h4>
					</div>
					<div class="col-md-3 col-sm-12">
						<img src="<?php echo base_url(); ?>assets/images/pendanaan-3.png" class="img-responsive">
						<h4>3. Pengembalian dana oleh peminjam sesuai tenor</h4>
					</div>
					<div class="col-md-3 col-sm-12">
						<img src="<?php echo base_url(); ?>assets/images/pendanaan-4.png" class="img-responsive">
						<h4>4. Pencairan dana kembali ke rekening</h4>
					</div>
				</div>
			</div>
		</div>

		<div class="section-pendanaan-register">
			<div class="row text-center">
				<h2 class="title-raleway kaspia-color">Jadi Pendana Sekarang</h2>
			</div>
			<div class="row text-center">
				<div class="col-md-4 col-md-offset-4">
					<div>
						<a class="btn btn-blue" href="<?php echo site_url('register-pendana');?>">Daftar sebagai Pendana</a>
					</div>
				</div>
			</div>
		</div>
       
    </div>
</div>
