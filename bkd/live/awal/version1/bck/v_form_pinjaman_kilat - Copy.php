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
                                                    <div class="line -background"></div>
                                                    <div class="line -progress"></div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="step">
                                            <div class="step__content">
                                                <p class="step__number">3</p>
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                                </svg>
                                                <div class="lines">
                                                    <div class="line -background"></div>
                                                    <div class="line -progress"></div>
                                                </div> 
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>                            

                            <form id="pinjam_kilat" method="POST" enctype="multipart/form-data" action="<?php echo site_url('submit-pinjaman-kilat') ?>">
                            <div class="panels">
                                <div class="panel" id="panel1">
                                    <div class="panel__header">

                                        <?php 
                                        if ($this->session->userdata('message')) {
                                            $message_show = $this->session->userdata('message');
                                        ?>
                                        <div class="alert alert-danger"><img src="<?php echo base_url(); ?>assets/images/error_icon.png" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                                        <?php } ?>
                                
                                        <h2 class="panel__title">1. Form Pendaftaran</h2>
                                        <p class="panel__subheading">Silahkan masukan data diri Anda sebenar - benarnya.</p>
                                    </div>
                                    <div class="panel__content">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                
                                                    <div class="form-group">
                                                        <label for="fullname">* Nama Lengkap</label>
                                                        <input type="text" class="form-control" name="fullname" id="fullname" required>
                                                        <p class="help-block">* Nama lengkap sesuai KTP</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">* Tempat Lahir</label>
                                                        <input type="email" class="form-control" name="tempat_lahir" id="tempat_lahir" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="handphone">* Tanggal Lahir</label>
                                                        <input type="text" class="form-control datepicker-dob" name="tgl_lahir" id="tgl_lahir_pinjam" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="handphone">* Jenis Kelamin</label>
                                                        <select name="gender" class="form-control">
                                                            <option value="pria">Pria</option>
                                                            <option value="wanita">Wanita</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="handphone">* Alamat</label>
                                                        <input type="text" class="form-control" name="alamat" id="alamat" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="handphone">* Kota</label>
                                                        <input type="text" class="form-control" name="kota" id="kota" required>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="handphone">* Provinsi</label>
                                                        <input type="text" class="form-control" name="provinsi" id="provinsi" required>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="handphone">* Kode Pos</label>
                                                        <input type="text" class="form-control" name="kodepos" id="kodepos">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="handphone">* Email</label>
                                                        <input type="text" class="form-control" name="email" id="email" value="<?php echo $member['mum_email']; ?>" required>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label for="handphone">* Nomor Telepon</label>
                                                        <input type="text" class="form-control" name="telp" id="telp" value="<?php echo $member['mum_telp']; ?>" required>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel" id="panel2">
                                    <div class="panel__header">
                                        <h2 class="panel__title">2. Upload Dokumen</h2>
                                        <p class="panel__subheading">Upload dokumen - dokumen Anda sebagai tolak ukur kredit skor anda.</p>
                                    </div>
                                    <div class="panel__content">
                                        
                                            <div class="form-group">
                                                <label for="handphone">* Upload Foto</label>
                                                <input type="file" name="foto_file" id="foto_file">
                                                <p class="help-block">* maksimum size 500kb dengan jpg, png, gif</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="handphone">* Pekerjaan</label>
                                                <select class="form-control" name="pekerjaan" id="pekerjaan">
                                                    <option value=""> -- Pilih --</option>
                                                    <option value="1">PNS</option>
                                                    <option value="2">BUMN</option>
                                                    <option value="3">Swasta</option>
                                                    <option value="4">Wiraswasta</option>
                                                    <option value="5">Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="handphone">* Nomor KTP</label>
                                                <input type="text" class="form-control" name="nomor_ktp" id="nomor_ktp" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="handphone">* Upload KTP File</label>
                                                <input type="file" name="ktp_file" id="ktp_file">
                                                <p class="help-block">* maksimum size 500kb dengan jpg, png, gif</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="handphone">* Nomor Rekening</label>
                                                <input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="handphone">* Jumlah Pinjaman (Rp)</label>
                                                <input type="text" class="form-control" name="jumlah_pinjam" id="jumlah_pinjam" required>
                                            </div>
                                            <br>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="agreement" id="iAgree"> Saya telah membaca dan saya setuju dengan Kebijakan Privasi &amp; Syarat Ketentuan
                                                </label>
                                                <div class="alert alert-danger" id="agree_message" style="display: none;">Anda harus mencentang checkbox Syarat Ketentuan</div>
                                          </div>
                                      
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            <div class="wizard__footer">
                                <button class="btn btn-blue previous">Sebelumnya</button>
                                <button class="btn btn-purple next">Selanjutnya</button>
                            </div>
                            
                        </div>
                        <h1 class="wizard__congrats-message">
                            <img src="assets/images/loading.gif" class="img-responsive img-reset loading" />
                            <span>Tunggu Sebentar Permintaan Anda Sedang Kami Proses ... </span>
                        </h1>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>