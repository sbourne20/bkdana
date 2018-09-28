<!-- Header -->
<header>
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<!-- Content -->
<div class="container">
    <div class="wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                
                <div class="form-wizard">
                    <div id="wizard" class="wizard">
                        <div class="wizard__content">
                            <div class="wizard__header">
                                <div class="wizard__header-overlay"></div>
                                <div class="wizard__header-content">
                                    <h1 class="wizard__title" >Daftar Pinjaman Kilat</h1>
                                    <p class="wizard__subheading">Beberapa langkah lagi untuk menjadi peminjam di BKD. <br>Silahkan lengkapi informasi dan data-data Anda terlebih dahulu.</p>
                                </div>
                                <div class="wizard__steps">
                                    <nav class="steps">
                                        <div class="step">
                                            <div class="step__content">
                                                <p class="step__number">1</p>
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                                </svg>
                                                <div class="lines">
                                                    <div class="line -start"></div>
                                                    <div class="line -background"></div>
                                                    <div class="line -progress"></div>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="step">
                                            <div class="step__content">
                                                <p class="step__number">2</p>
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                                </svg>
                                                <div class="lines">
                                                    <div class="line -start"></div>
                                                    <div class="line -background"></div>
                                                    <div class="line -progress"></div>
                                                </div>  
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>                            

                            <div class="panels">
                                <div class="panel" id="panel1">
                                    <div class="panel__header">

                                        <?php 
                                        if ($this->session->userdata('message')) {
                                            $message_show = $this->session->userdata('message');
                                        ?>
                                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                                        <?php } ?>
                                
                                        <h2 class="panel__title">Pinjaman Kilat</h2>
                                        <p class="panel__subheading">&nbsp;</p>
                                    </div>
                                    <div class="panel__content">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <form name="form_name1" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="jumlah_pinjam">* Jumlah Pinjaman (Rp)</label>
                                                        <select class="form-control" name="jumlah_pinjam" id="jumlah_pinjam" required>
                                                            <option value=""> -- Pilih --</option>
                                                            <option value="500000"> Rp 500.000</option>
                                                            <option value="1000000"> Rp 1.000.000</option>  
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="product">* Tenor</label>
                                                        <select class="form-control" name="product" id="product">
                                                            <option value=""> -- Pilih --</option>
                                                            <?php foreach ($products as $prod) { ?>
                                                            <option value="<?php echo $prod['Product_id']; ?>"> <?php echo $prod['Loan_term']; ?> Hari</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>                                               
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel" id="panel2">
                                    <div class="panel__header">
                                        <h2 class="panel__title">2. Masukkan Password</h2>
                                        <p class="panel__subheading">&nbsp;</p>
                                    </div>
                                    <div class="panel__content">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <form name="form_name2" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="password">* Password Anda Saat ini</label>
                                                        <input type="password" class="form-control" id="password" name="password">
                                                        <p class="help-block" id="passwordmsg">* Minimum 6 huruf</p>
                                                    </div>
                                                    <br>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="agreement" id="iAgree"> Saya telah membaca dan saya setuju dengan Kebijakan Privasi &amp; Syarat Ketentuan
                                                        </label>
                                                        <div class="alert alert-danger" id="agree_message" style="display: none;">Anda harus mencentang checkbox Syarat Ketentuan</div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wizard__footer">
                                <button class="btn btn-blue previous">Sebelumnya</button>
                                <button class="btn btn-purple next">Selanjutnya</button>
                            </div>
                            
                        </div>
                        <h1 class="wizard__congrats-message">
                            <img src="<?php echo base_url(); ?>assets/images/loading.gif" class="img-responsive img-reset loading" />
                            <span>Tunggu Sebentar Permintaan Anda Sedang Kami Proses ... </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>