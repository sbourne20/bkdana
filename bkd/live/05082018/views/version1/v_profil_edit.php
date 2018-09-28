<!-- Header -->
<header class="overflow-wrapp">
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<!-- Content -->
<div class="wrapper-content overflow-wrapp">
    <div class="section-member-page">
        
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="box plain left">
                        <?php $this->load->view('version1/v_menu_dashboard'); ?>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="box plain right">
                        <div class="content">
                            <h1>Ubah Profil</h1>
                            <div class="sub-title">Ubah Profil Anda Sesuai Dengan Identitas Anda</div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php 
                                    if ($this->session->userdata('message')) {
                                        $message_show = $this->session->userdata('message');
                                        $msg_type = $this->session->userdata('message_type');
                                        if ($msg_type == 'error') {
                                            $icon  = 'error_icon.png';
                                            $color = 'danger';
                                        }else if ($msg_type == 'success') {
                                            $icon  = 'success_icon.png';
                                            $color = 'success';
                                        }else{
                                            $icon = 'info_icon.png';
                                            $color = 'info';
                                        }
                                    ?>
                                    <div class="col-sm-12">
                                    <div class="alert alert-<?php echo $color; ?> text-center"><img src="<?php echo base_url(); ?>assets/images/<?php echo $icon; ?>" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                                    </div>
                                    <?php } ?>

                                    <form class="form-validation" method="POST" action="<?php echo site_url('submit-ubah-profil'); ?>">
                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input class="form-control" id="email" type="email" value="<?php echo $memberdata['mum_email']; ?>" disabled="disabled">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">* Nama Lengkap</label>
                                            <input class="form-control" id="nama" type="text" name="fullname" value="<?php echo $memberdata['Nama_pengguna']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="telp">* NomorTelepon</label>
                                            <input class="form-control" id="telp" type="text" name="telp" value="<?php echo $memberdata['mum_telp']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="tempat_lahir">* Tempat Lahir</label>
                                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="<?php echo $memberdata['Tempat_lahir']; ?>" >
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Tanggal Lahir</label>
                                            <input type="text" class="form-control datepicker-dob" name="tgl_lahir" id="tgl_lahir_pinjam" value="<?php echo date('d-m-Y', strtotime($memberdata['Tanggal_lahir'])); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Jenis Kelamin</label>
                                            <select name="gender" class="form-control">
                                                <option value="pria" <?php echo ($memberdata['Jenis_kelamin']=='pria')? 'selected="selected"' : ''; ?>>Pria</option>
                                                <option value="wanita" <?php echo ($memberdata['Jenis_kelamin']=='wanita')? 'selected="selected"' : ''; ?>>Wanita</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Alamat</label>
                                            <input type="text" class="form-control" name="alamat" id="alamat" value="<?php echo $memberdata['Alamat']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Kota</label>
                                            <input type="text" class="form-control" name="kota" id="kota" value="<?php echo $memberdata['Kota']; ?>" required>
                                        </div>  
                                        <div class="form-group">
                                            <label for="handphone">* Provinsi</label>
                                            <select class="form-control" name="provinsi" id="provinsi" required>
                                                <option value="Aceh" <?php echo ($memberdata['Provinsi']=='Aceh')? 'selected="selected"' : ''; ?>>Aceh</option>
                                                <option value="Bali" <?php echo ($memberdata['Provinsi']=='Bali')? 'selected="selected"' : ''; ?>>Bali</option>
                                                <option value="Banten" <?php echo ($memberdata['Provinsi']=='Banten')? 'selected="selected"' : ''; ?>>Banten</option>
                                                <option value="Bengkulu" <?php echo ($memberdata['Provinsi']=='Bengkulu')? 'selected="selected"' : ''; ?>>Bengkulu</option>
                                                <option value="DI Yogyakarta" <?php echo ($memberdata['Provinsi']=='DI Yogyakarta')? 'selected="selected"' : ''; ?>>DI Yogyakarta</option>
                                                <option value="DKI Jakarta" <?php echo ($memberdata['Provinsi']=='DKI Jakarta')? 'selected="selected"' : ''; ?>>DKI Jakarta</option>
                                                <option value="Gorontalo" <?php echo ($memberdata['Provinsi']=='Gorontalo')? 'selected="selected"' : ''; ?>>Gorontalo</option>
                                                <option value="Jambi" <?php echo ($memberdata['Provinsi']=='Jambi')? 'selected="selected"' : ''; ?>>Jambi</option>
                                                <option value="Jawa Barat" <?php echo ($memberdata['Provinsi']=='Jawa Barat')? 'selected="selected"' : ''; ?>>Jawa Barat</option>
                                                <option value="Jawa Tengah" <?php echo ($memberdata['Provinsi']=='Jawa Tengah')? 'selected="selected"' : ''; ?>>Jawa Tengah</option>
                                                <option value="Jawa Timur" <?php echo ($memberdata['Provinsi']=='Jawa Timur')? 'selected="selected"' : ''; ?>>Jawa Timur</option>
                                                <option value="Kalimantan Barat" <?php echo ($memberdata['Provinsi']=='Kalimantan Barat')? 'selected="selected"' : ''; ?>>Kalimantan Barat</option>
                                                <option value="Kalimantan Selatan" <?php echo ($memberdata['Provinsi']=='Kalimantan Selatan')? 'selected="selected"' : ''; ?>>Kalimantan Selatan</option>
                                                <option value="Kalimantan Tengah" <?php echo ($memberdata['Provinsi']=='Kalimantan Tengah')? 'selected="selected"' : ''; ?>>Kalimantan Tengah</option>
                                                <option value="Kalimantan Timur" <?php echo ($memberdata['Provinsi']=='Kalimantan Timur')? 'selected="selected"' : ''; ?>>Kalimantan Timur</option>
                                                <option value="Kalimantan Utara" <?php echo ($memberdata['Provinsi']=='Kalimantan Utara')? 'selected="selected"' : ''; ?>>Kalimantan Utara</option>
                                                <option value="Kepulauan Bangka Belitung" <?php echo ($memberdata['Provinsi']=='Kepulauan Bangka Belitung')? 'selected="selected"' : ''; ?>>Kepulauan Bangka Belitung</option>
                                                <option value="Kepulauan Riau" <?php echo ($memberdata['Provinsi']=='Kepulauan Riau')? 'selected="selected"' : ''; ?>>Kepulauan Riau</option>
                                                <option value="Lampung" <?php echo ($memberdata['Provinsi']=='Lampung')? 'selected="selected"' : ''; ?>>Lampung</option>
                                                <option value="Maluku" <?php echo ($memberdata['Provinsi']=='Maluku')? 'selected="selected"' : ''; ?>>Maluku</option>
                                                <option value="Maluku Utara" <?php echo ($memberdata['Provinsi']=='Maluku Utara')? 'selected="selected"' : ''; ?>>Maluku Utara</option>
                                                <option value="Nusa Tenggara Barat" <?php echo ($memberdata['Provinsi']=='Nusa Tenggara Barat')? 'selected="selected"' : ''; ?>>Nusa Tenggara Barat</option>
                                                <option value="Nusa Tenggara Timur" <?php echo ($memberdata['Provinsi']=='Nusa Tenggara Timur')? 'selected="selected"' : ''; ?>>Nusa Tenggara Timur</option>
                                                <option value="Papua" <?php echo ($memberdata['Provinsi']=='Papua')? 'selected="selected"' : ''; ?>>Papua</option>
                                                <option value="Papua Barat" <?php echo ($memberdata['Provinsi']=='Papua Barat')? 'selected="selected"' : ''; ?>>Papua Barat</option>
                                                <option value="Riau" <?php echo ($memberdata['Provinsi']=='Riau')? 'selected="selected"' : ''; ?>>Riau</option>
                                                <option value="Sulawesi Barat" <?php echo ($memberdata['Provinsi']=='Sulawesi Barat')? 'selected="selected"' : ''; ?>>Sulawesi Barat</option>
                                                <option value="Sulawesi Selatan" <?php echo ($memberdata['Provinsi']=='Sulawesi Selatan')? 'selected="selected"' : ''; ?>>Sulawesi Selatan</option>
                                                <option value="Sulawesi Tengah" <?php echo ($memberdata['Provinsi']=='Sulawesi Tengah')? 'selected="selected"' : ''; ?>>Sulawesi Tengah</option>
                                                <option value="Sulawesi Tenggara" <?php echo ($memberdata['Provinsi']=='Sulawesi Tenggara')? 'selected="selected"' : ''; ?>>Sulawesi Tenggara</option>
                                                <option value="Sulawesi Utara" <?php echo ($memberdata['Provinsi']=='Sulawesi Utara')? 'selected="selected"' : ''; ?>>Sulawesi Utara</option>
                                                <option value="Sumatera Barat" <?php echo ($memberdata['Provinsi']=='Sumatera Barat')? 'selected="selected"' : ''; ?>>Sumatera Barat</option>
                                                <option value="Sumatera Selatan" <?php echo ($memberdata['Provinsi']=='Sumatera Selatan')? 'selected="selected"' : ''; ?>>Sumatera Selatan</option>
                                                <option value="Sumatera Utara" <?php echo ($memberdata['Provinsi']=='Sumatera Utara')? 'selected="selected"' : ''; ?>>Sumatera Utara</option>
                                            </select>
                                        </div>  
                                        <div class="form-group">
                                            <label for="handphone">* Kode Pos</label>
                                            <input type="text" class="form-control" name="kodepos" id="kodepos" value="<?php echo $memberdata['Kodepos']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Pekerjaan</label>
                                            <select class="form-control" name="pekerjaan" id="pekerjaan" required>
                                                <option value=""> -- Pilih --</option>
                                                <option value="1" <?php echo ($memberdata['Pekerjaan']=='1')? 'selected="selected"' : ''; ?>>PNS</option>
                                                <option value="2" <?php echo ($memberdata['Pekerjaan']=='2')? 'selected="selected"' : ''; ?>>BUMN</option>
                                                <option value="3" <?php echo ($memberdata['Pekerjaan']=='3')? 'selected="selected"' : ''; ?>>Swasta</option>
                                                <option value="4" <?php echo ($memberdata['Pekerjaan']=='4')? 'selected="selected"' : ''; ?>>Wiraswasta</option>
                                                <option value="5" <?php echo ($memberdata['Pekerjaan']=='5')? 'selected="selected"' : ''; ?>>Lain-lain</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Nomor NIK</label>
                                            <input type="text" class="form-control" name="nomor_ktp" id="nomor_ktp" value="<?php echo $memberdata['Id_ktp']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Nomor Rekening</label>
                                            <input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" value="<?php echo $memberdata['Nomor_rekening']; ?>" required>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-blue">Submit</button> &nbsp; 
                                        <button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>