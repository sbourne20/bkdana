<style type="text/css">
    .fileinput-remove-button, .btn-file { line-height: 0.9; position: relative;top: -1px !important; }
    .section-member-page .content .panel .counter, .section-member-page .content .panel .no-counter, .section-member-page .content .panel span { font-size: 13px; color:#FFF; }
</style>

<!-- Header -->
<header class="overflow-wrapp">
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<?php 
$foto_profil = '';
$foto_ktp    = '';
$foto_usaha  = '';

if ($memberdata['images_foto_name'] != '')
{
    $foto_profil = $this->config->item('images_uri') . '/member/'.$memberdata['id_mod_user_member']. '/foto/'. $memberdata['images_foto_name'];
}
if ($memberdata['images_ktp_name'] != '')
{
    $foto_ktp = $this->config->item('images_uri') . '/member/'.$memberdata['id_mod_user_member']. '/ktp/'. $memberdata['images_ktp_name'];
}
if ($memberdata['images_usaha_name'] != '')
{
    $foto_usaha = $this->config->item('images_uri') . '/member/'.$memberdata['id_mod_user_member']. '/usaha/'. $memberdata['images_usaha_name'];
}
?>

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
                            <h1><?php echo $page_title; ?></h1>
                            <div class="sub-title">Isi Profil Anda Sesuai Dengan Identitas Anda</div>
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

                                    <form class="form-validation" method="POST" enctype="multipart/form-data" action="<?php echo site_url('submit-ubah-profil'); ?>">
                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input class="form-control" id="email" type="email" value="<?php echo $memberdata['mum_email']; ?>" disabled="disabled">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">* Nama Lengkap</label>
                                            <input class="form-control" id="nama" type="text" name="fullname" value="<?php echo $memberdata['Nama_pengguna']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Nama Lengkap harus diisi!" >
                                        </div>
                                        <div class="form-group">
                                            <label for="telp">* Nomor Telepon</label>
                                            <input class="form-control" id="telp" type="text" name="telp" value="<?php echo $memberdata['mum_telp']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="No.Telepon harus diisi!" >
                                        </div>
                                        <div class="form-group">
                                            <label for="tempat_lahir">* Tempat Lahir</label>
                                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="<?php echo $memberdata['Tempat_lahir']; ?>" >
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Tanggal Lahir</label>
                                            <input type="text" class="form-control datepicker-dob" name="tgl_lahir" id="tgl_lahir_pinjam" value="<?php echo ($memberdata['Tanggal_lahir']=='0000-00-00')? '' : date('d-m-Y', strtotime($memberdata['Tanggal_lahir'])); ?>" >
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
                                            <input type="text" class="form-control" name="alamat" id="alamat" value="<?php echo $memberdata['Alamat']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Alamat harus diisi!" >
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Kota</label>
                                            <input type="text" class="form-control" name="kota" id="kota" value="<?php echo $memberdata['Kota']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Kota harus diisi!" >
                                        </div>  
                                        <div class="form-group">
                                            <label for="handphone">* Provinsi</label>
                                            <select class="form-control" name="provinsi" id="provinsi" data-validation-engine="validate[required]" data-errormessage-value-missing="Provinsi harus diisi!" >
                                                <option value=""> -- Pilih -- </option>
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
                                            <input type="text" class="form-control" name="kodepos" id="kodepos" value="<?php echo $memberdata['Kodepos']; ?>" >
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Pekerjaan</label>
                                            <select class="form-control" name="pekerjaan" id="pekerjaan" data-validation-engine="validate[required]" data-errormessage-value-missing="Pekerjaan harus diisi!" >
                                                <option value=""> -- Pilih --</option>
                                                <option value="1" <?php echo ($memberdata['Pekerjaan']=='1')? 'selected="selected"' : ''; ?>>PNS</option>
                                                <option value="2" <?php echo ($memberdata['Pekerjaan']=='2')? 'selected="selected"' : ''; ?>>BUMN</option>
                                                <option value="3" <?php echo ($memberdata['Pekerjaan']=='3')? 'selected="selected"' : ''; ?>>Swasta</option>
                                                <option value="4" <?php echo ($memberdata['Pekerjaan']=='4')? 'selected="selected"' : ''; ?>>Wiraswasta</option>
                                                <option value="5" <?php echo ($memberdata['Pekerjaan']=='5')? 'selected="selected"' : ''; ?>>Lain-lain</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>* Upload Foto Profil</label>        
                                                <input type="file" name="foto_file" id="foto_file" data-show-upload="false" namafile="<?php echo $foto_profil; ?>" >
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Nomor NIK</label>
                                            <input type="text" class="form-control" name="nomor_ktp" id="nomor_ktp" value="<?php echo $memberdata['Id_ktp']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Nomor NIK harus diisi!" >
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Foto NIK</label>
                                                <input type="file" name="ktp_file" id="ktp_file" data-show-upload="false" namafile="<?php echo $foto_ktp; ?>" >
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="handphone">* Nomor Rekening</label>
                                            <input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" value="<?php echo $memberdata['Nomor_rekening']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Nomor Rekening harus diisi!">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_bank">* Bank</label>
                                            <select class="form-control" name="nama_bank" id="nama_bank" data-validation-engine="validate[required]" data-errormessage-value-missing="Bank harus diisi!">
                                                <option value="Bank Mandiri" <?php echo ($memberdata['nama_bank']=='Bank Mandiri')? 'selected="selected"' : ''; ?> > Bank Mandiri</option>
                                                <option value="Bank BNI 46" <?php echo ($memberdata['nama_bank']=='Bank BNI 46')? 'selected="selected"' : ''; ?> >Bank BNI 46</option>
                                                <option value="Bank BRI" <?php echo ($memberdata['nama_bank']=='Bank BRI')? 'selected="selected"' : ''; ?> >Bank BRI</option>
                                                <option value="Bank BCA" <?php echo ($memberdata['nama_bank']=='Bank BCA')? 'selected="selected"' : ''; ?> >Bank BCA</option>
                                                <option value="Bank CIMB" <?php echo ($memberdata['nama_bank']=='Bank CIMB')? 'selected="selected"' : ''; ?> >Bank CIMB</option>
                                            </select>
                                        </div>


                                        <!-- MIKRO -->

                                        <?php if ($memberdata['mum_type_peminjam']=='2' OR $memberdata['What_is_the_name_of_your_business'] !='') { ?>

                                        <div class="form-group">
                                                <label for="handphone">* Usaha</label>
                                                <input type="text" class="form-control" name="usaha" id="usaha" value="<?php echo $memberdata['What_is_the_name_of_your_business']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Usaha harus diisi!">
                                            </div>
                                            <div class="form-group">
                                                <label for="handphone">* Upload Foto Usaha</label>
                                                <input type="file" name="usaha_file" id="usaha_file" data-show-upload="false" namafile="<?php echo $foto_usaha; ?>">
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="lama_usaha">* Lama Usaha</label>
                                                <select class="form-control" name="lama_usaha" id="lama_usaha" data-validation-engine="validate[required]" data-errormessage-value-missing="Lama Usaha harus diisi!">
                                                    <option value=""> -- Pilih --</option>

                                                    <option value="0" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='0')? 'selected="selected"' : ''; ?> > Kurang dari 1 tahun</option>
                                                    <option value="1" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='1')? 'selected="selected"' : ''; ?> > 1 tahun</option>
                                                    <option value="2" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='2')? 'selected="selected"' : ''; ?> > 2 tahun</option>
                                                    <option value="3" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='3')? 'selected="selected"' : ''; ?> > 3 tahun</option>
                                                    <option value="4" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='4')? 'selected="selected"' : ''; ?> > 4 tahun</option>
                                                    <option value="5" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='5')? 'selected="selected"' : ''; ?> > 5 tahun</option>
                                                    <option value="6" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='6')? 'selected="selected"' : ''; ?> > 6 tahun</option>
                                                    <option value="7" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='7')? 'selected="selected"' : ''; ?> > 7 tahun</option>
                                                    <option value="8" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='8')? 'selected="selected"' : ''; ?> > 8 tahun</option>
                                                    <option value="9" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='9')? 'selected="selected"' : ''; ?> > 9 tahun</option>
                                                    <option value="10" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='10')? 'selected="selected"' : ''; ?> > 10 tahun</option>
                                                    <option value="11" <?php echo ($memberdata['How_many_years_have_you_been_in_business']=='11')? 'selected="selected"' : ''; ?> > Lebih dari 10 tahun</option>
                                                </select>
                                            </div>

                                            <?php } ?>
                                            <!-- End MIKRO -->

                                            <!-- PENDANA -->
                                            <?php if ($memberdata['mum_type']=='2') { // PENDANA ?>
                                            <div class="form-group">
                                                <label>* Status Pernikahan</label>
                                                <br>
                                                <label class="checkbox-inline">
                                                    <input type="radio" name="status_kawin" value="belum menikah" <?php echo ($memberdata['status_nikah']=='belum menikah')? 'checked="checked"' : ''; ?> > Belum Menikah
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="radio" name="status_kawin" value="menikah" <?php echo ($memberdata['status_nikah']=='menikah')? 'checked="checked"' : ''; ?> > Menikah
                                                </label>                                                
                                            </div>

                                            <div class="form-group">
                                                <label for="handphone">* Jumlah Tanggungan</label>
                                                <select class="form-control" name="jml_tanggungan" id="jml_tanggungan" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Tanggungan harus diisi!">
                                                    <option value=""> -- Pilih --</option>

                                                    <?php for ($i=0; $i <= 10 ; $i++) { 
                                                        $selected = ($memberdata['How_many_people_do_you_financially_support']==$i)? 'selected="selected"' : '';
                                                    ?>
                                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>> <?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="handphone">* Pendidikan Terakhir</label>
                                                <select class="form-control" name="pendidikan" id="pendidikan">
                                                    <option value=""> -- Pilih --</option>
                                                    <option value="1" <?php echo ($memberdata['Pendidikan']=='1')? 'selected="selected"' : '';  ?> > SD</option>
                                                    <option value="2" <?php echo ($memberdata['Pendidikan']=='2')? 'selected="selected"' : '';  ?> > SLTP</option>
                                                    <option value="3" <?php echo ($memberdata['Pendidikan']=='3')? 'selected="selected"' : '';  ?> > SLTA</option>
                                                    <option value="4" <?php echo ($memberdata['Pendidikan']=='4')? 'selected="selected"' : '';  ?> > Diploma</option>
                                                    <option value="5" <?php echo ($memberdata['Pendidikan']=='5')? 'selected="selected"' : '';  ?> > Sarjana</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="jumlah_penghasilan">* Jumlah Penghasilan (Rp)</label>
                                                <input type="text" class="form-control numeric" name="jumlah_penghasilan" id="jumlah_penghasilan" value="<?php echo (empty($memberdata['average_monthly_salary']))? '': $memberdata['average_monthly_salary']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="jumlah penghasilan harus diisi!">
                                            </div>
                                            <!-- End PENDANA -->
                                            <?php } ?>
                                        <br>

                                        <input type="hidden" name="old_foto" value="<?php echo $memberdata['images_foto_name']; ?>">
                                        <input type="hidden" name="old_ktp" value="<?php echo $memberdata['images_ktp_name']; ?>">
                                        <input type="hidden" name="old_usaha" value="<?php echo $memberdata['images_usaha_name']; ?>">

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