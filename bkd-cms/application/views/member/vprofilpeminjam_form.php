<?php 
$foto_profil = '';
$foto_ktp    = '';
$foto_usaha  = '';
$foto_usaha2  = '';
$foto_usaha3  = '';
$foto_usaha4  = '';
$foto_usaha5  = '';
// ----- tambahan baru -----
$foto_surat_keterangan_bekerja = '';
$foto_slip_gaji = '';
$foto_pegang_ktp = '';
// ----- batas tambahan baru -----

if ($memberdata['images_foto_name'] != '')
{
    $foto_profil = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/foto/'. $memberdata['images_foto_name'];
}
if ($memberdata['images_ktp_name'] != '')
{
    $foto_ktp = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/ktp/'. $memberdata['images_ktp_name'];
}
if ($memberdata['images_usaha_name'] != '')
{
    $foto_usaha = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/usaha/'. $memberdata['images_usaha_name'];
}
if ($memberdata['images_usaha_name2'] != '')
{
    $foto_usaha2 = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/usaha2/'. $memberdata['images_usaha_name2'];
}
if ($memberdata['images_usaha_name3'] != '')
{
    $foto_usaha3 = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/usaha3/'. $memberdata['images_usaha_name3'];
}
if ($memberdata['images_usaha_name4'] != '')
{
    $foto_usaha4 = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/usaha4/'. $memberdata['images_usaha_name4'];
}
if ($memberdata['images_usaha_name5'] != '')
{
    $foto_usaha5 = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/usaha5/'. $memberdata['images_usaha_name5'];
}

// -----tambahan baru-----

if ($memberdata['foto_surat_keterangan_bekerja'] != '')
{
    $foto_surat_keterangan_bekerja = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/surat_keterangan_bekerja/'. $memberdata['foto_surat_keterangan_bekerja'];
}
if ($memberdata['foto_slip_gaji'] != '')
{
    $foto_slip_gaji= site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/slip_gaji/'. $memberdata['foto_slip_gaji'];
}
if ($memberdata['foto_pegang_ktp'] != '')
{
    $foto_pegang_ktp = site_url('fileload?p=') . 'member/'.$memberdata['id_mod_user_member']. '/pegang_ktp/'. $memberdata['foto_pegang_ktp'];
}

// -----batas tambahan-----

?>    


    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> Peminjam
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" method="POST" id="formID" name='formpeminjam'>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Lengkap</label>
                                <div class="col-sm-6">
                                    <input type="text" name="fullname" class="form-control" value="<?php echo $memberdata['Nama_pengguna']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['mum_email']; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telp" class="form-control" value="<?php echo $memberdata['mum_telp']; ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tempat Lahir</label>
                                <div class="col-sm-6">
                                    <input type="text" name="tempat_lahir" class="form-control" value="<?php echo $memberdata['Tempat_lahir']; ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tanggal Lahir</label>
                                <div class="col-sm-6">
                                    <input type="text" name="tgl_lahir" class="form-control datepicker-dob" value="<?php echo ($memberdata['Tanggal_lahir']=='0000-00-00')? '' : date('d-m-Y', strtotime($memberdata['Tanggal_lahir'])); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis Kelamin</label>
                                <div class="col-sm-6">
                                    <select name="gender" class="form-control">
                                        <option value=""> -- Pilih --</option>
                                        <option value="pria" <?php echo ($memberdata['Jenis_kelamin']=='pria')? 'selected="selected"' : ''; ?>>Pria</option>
                                        <option value="wanita" <?php echo ($memberdata['Jenis_kelamin']=='wanita')? 'selected="selected"' : ''; ?>>Wanita</option>
                                    </select>
                                </div>     
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alamat sesuai dengan KTP</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['Alamat']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kota sesuai dengan KTP</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['Kota']; ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Provinsi sesuai dengan KTP</label>
                                <div class="col-sm-6">
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
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kode Pos</label>
                                <div class="col-sm-6">
                                    <input type="text" name="kodepos" class="form-control" value="<?php echo $memberdata['Kodepos']; ?>">
                                </div>
                            </div>

                            <?php if ($memberdata['mum_type_peminjam']=='3') { ?>
                                                <!--  ALAMAT DOMISILI -->

                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-6">
                                  <label>
                                        <input type="checkbox" name='checkdomisili' id="checkdomisili" onclick='showhidedomisili()' value='1'
                                       <?php if($memberdata['Check_Domisili']=='1'){
                                        echo 'checked';
                                        }
                                      ?> /> Alamat Domisili sesuai dengan KTP
                                   </label>
                               </div>
                            </div>
                            <div class="form-group" id="hiddendomisili">
                                
                                    <label class="col-sm-2 control-label">* Alamat Domisili</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="alamatdomisili" id="alamatdomisili" value="<?php echo $memberdata['Alamat_Domisili'];  ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Alamat Domisili harus diisi!" >
                                    </div>
                                
                            </div>
                            <div class="form-group" id="hiddendomisili2">
                                
                                    <label class="col-sm-2 control-label">* Kota Domisili</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="kotadomisili" id="kotadomisili" value="<?php echo $memberdata['Kota_Domisili']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Kota harus diisi!" >
                                    </div>
                                
                            </div>  
                            <div class="form-group" id="hiddendomisili3">
                                
                                    <label class="col-sm-2 control-label">* Provinsi Domisili</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="provinsidomisili" id="provinsidomisili" data-validation-engine="validate[required]" data-errormessage-value-missing="Provinsi harus diisi!" >
                                            <option value=""> -- Pilih -- </option>
                                            <option value="Aceh" <?php echo ($memberdata['Provinsi_Domisili']=='Aceh')? 'selected="selected"' : ''; ?>>Aceh</option>
                                            <option value="Bali" <?php echo ($memberdata['Provinsi_Domisili']=='Bali')? 'selected="selected"' : ''; ?>>Bali</option>
                                            <option value="Banten" <?php echo ($memberdata['Provinsi_Domisili']=='Banten')? 'selected="selected"' : ''; ?>>Banten</option>
                                            <option value="Bengkulu" <?php echo ($memberdata['Provinsi_Domisili']=='Bengkulu')? 'selected="selected"' : ''; ?>>Bengkulu</option>
                                            <option value="DI Yogyakarta" <?php echo ($memberdata['Provinsi_Domisili']=='DI Yogyakarta')? 'selected="selected"' : ''; ?>>DI Yogyakarta</option>
                                            <option value="DKI Jakarta" <?php echo ($memberdata['Provinsi_Domisili']=='DKI Jakarta')? 'selected="selected"' : ''; ?>>DKI Jakarta</option>
                                            <option value="Gorontalo" <?php echo ($memberdata['Provinsi_Domisili']=='Gorontalo')? 'selected="selected"' : ''; ?>>Gorontalo</option>
                                            <option value="Jambi" <?php echo ($memberdata['Provinsi_Domisili']=='Jambi')? 'selected="selected"' : ''; ?>>Jambi</option>
                                            <option value="Jawa Barat" <?php echo ($memberdata['Provinsi_Domisili']=='Jawa Barat')? 'selected="selected"' : ''; ?>>Jawa Barat</option>
                                            <option value="Jawa Tengah" <?php echo ($memberdata['Provinsi_Domisili']=='Jawa Tengah')? 'selected="selected"' : ''; ?>>Jawa Tengah</option>
                                            <option value="Jawa Timur" <?php echo ($memberdata['Provinsi_Domisili']=='Jawa Timur')? 'selected="selected"' : ''; ?>>Jawa Timur</option>
                                            <option value="Kalimantan Barat" <?php echo ($memberdata['Provinsi_Domisili']=='Kalimantan Barat')? 'selected="selected"' : ''; ?>>Kalimantan Barat</option>
                                            <option value="Kalimantan Selatan" <?php echo ($memberdata['Provinsi_Domisili']=='Kalimantan Selatan')? 'selected="selected"' : ''; ?>>Kalimantan Selatan</option>
                                            <option value="Kalimantan Tengah" <?php echo ($memberdata['Provinsi_Domisili']=='Kalimantan Tengah')? 'selected="selected"' : ''; ?>>Kalimantan Tengah</option>
                                            <option value="Kalimantan Timur" <?php echo ($memberdata['Provinsi_Domisili']=='Kalimantan Timur')? 'selected="selected"' : ''; ?>>Kalimantan Timur</option>
                                            <option value="Kalimantan Utara" <?php echo ($memberdata['Provinsi_Domisili']=='Kalimantan Utara')? 'selected="selected"' : ''; ?>>Kalimantan Utara</option>
                                            <option value="Kepulauan Bangka Belitung" <?php echo ($memberdata['Provinsi_Domisili']=='Kepulauan Bangka Belitung')? 'selected="selected"' : ''; ?>>Kepulauan Bangka Belitung</option>
                                            <option value="Kepulauan Riau" <?php echo ($memberdata['Provinsi_Domisili']=='Kepulauan Riau')? 'selected="selected"' : ''; ?>>Kepulauan Riau</option>
                                            <option value="Lampung" <?php echo ($memberdata['Provinsi_Domisili']=='Lampung')? 'selected="selected"' : ''; ?>>Lampung</option>
                                            <option value="Maluku" <?php echo ($memberdata['Provinsi_Domisili']=='Maluku')? 'selected="selected"' : ''; ?>>Maluku</option>
                                            <option value="Maluku Utara" <?php echo ($memberdata['Provinsi_Domisili']=='Maluku Utara')? 'selected="selected"' : ''; ?>>Maluku Utara</option>
                                            <option value="Nusa Tenggara Barat" <?php echo ($memberdata['Provinsi_Domisili']=='Nusa Tenggara Barat')? 'selected="selected"' : ''; ?>>Nusa Tenggara Barat</option>
                                            <option value="Nusa Tenggara Timur" <?php echo ($memberdata['Provinsi_Domisili']=='Nusa Tenggara Timur')? 'selected="selected"' : ''; ?>>Nusa Tenggara Timur</option>
                                            <option value="Papua" <?php echo ($memberdata['Provinsi_Domisili']=='Papua')? 'selected="selected"' : ''; ?>>Papua</option>
                                            <option value="Papua Barat" <?php echo ($memberdata['Provinsi_Domisili']=='Papua Barat')? 'selected="selected"' : ''; ?>>Papua Barat</option>
                                            <option value="Riau" <?php echo ($memberdata['Provinsi_Domisili']=='Riau')? 'selected="selected"' : ''; ?>>Riau</option>
                                            <option value="Sulawesi Barat" <?php echo ($memberdata['Provinsi_Domisili']=='Sulawesi Barat')? 'selected="selected"' : ''; ?>>Sulawesi Barat</option>
                                            <option value="Sulawesi Selatan" <?php echo ($memberdata['Provinsi_Domisili']=='Sulawesi Selatan')? 'selected="selected"' : ''; ?>>Sulawesi Selatan</option>
                                            <option value="Sulawesi Tengah" <?php echo ($memberdata['Provinsi_Domisili']=='Sulawesi Tengah')? 'selected="selected"' : ''; ?>>Sulawesi Tengah</option>
                                            <option value="Sulawesi Tenggara" <?php echo ($memberdata['Provinsi_Domisili']=='Sulawesi Tenggara')? 'selected="selected"' : ''; ?>>Sulawesi Tenggara</option>
                                            <option value="Sulawesi Utara" <?php echo ($memberdata['Provinsi_Domisili']=='Sulawesi Utara')? 'selected="selected"' : ''; ?>>Sulawesi Utara</option>
                                            <option value="Sumatera Barat" <?php echo ($memberdata['Provinsi_Domisili']=='Sumatera Barat')? 'selected="selected"' : ''; ?>>Sumatera Barat</option>
                                            <option value="Sumatera Selatan" <?php echo ($memberdata['Provinsi_Domisili']=='Sumatera Selatan')? 'selected="selected"' : ''; ?>>Sumatera Selatan</option>
                                            <option value="Sumatera Utara" <?php echo ($memberdata['Provinsi_Domisili']=='Sumatera Utara')? 'selected="selected"' : ''; ?>>Sumatera Utara</option>
                                        </select>
                                    </div>
                                
                            </div>  
                            <div class="form-group" id="hiddendomisili4">
                                
                                    <label class="col-sm-2 control-label">* Kode Pos Domisili</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="kodeposdomisili" id="kodeposdomisili" value="<?php echo $memberdata['Kodepos_Domisili']; ?>" >
                                    </div>
                                
                            </div>
                        
                                    <!-- BATAS ALAMAT DOMISILI -->
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pekerjaan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="pekerjaan" id="pekerjaan" data-validation-engine="validate[required]" data-errormessage-value-missing="Pekerjaan harus diisi!" >
                                        <option value=""> -- Pilih --</option>
                                        <option value="1" <?php echo ($memberdata['Pekerjaan']=='1')? 'selected="selected"' : ''; ?>>PNS</option>
                                        <option value="2" <?php echo ($memberdata['Pekerjaan']=='2')? 'selected="selected"' : ''; ?>>BUMN</option>
                                        <option value="3" <?php echo ($memberdata['Pekerjaan']=='3')? 'selected="selected"' : ''; ?>>Swasta</option>
                                        <option value="4" <?php echo ($memberdata['Pekerjaan']=='4')? 'selected="selected"' : ''; ?>>Wiraswasta</option>
                                        <option value="5" <?php echo ($memberdata['Pekerjaan']=='5')? 'selected="selected"' : ''; ?>>Lain-lain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Diri / Selfie</label>
                                <div class="col-sm-6">
                                   <input type="file" id="foto_file" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php echo $foto_profil; ?>" >
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                                <input type="hidden" class="input_file_hidden" id="foto_file_hidden" name="foto_file_hidden"/>                   
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nomor KTP</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['Id_ktp']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto KTP</label>
                                <div class="col-sm-6">
                                   <input type="file" name="ktp_file" id="ktp_file" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()'namafile="<?php echo $foto_ktp; ?>" >
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                                <input type="hidden" class="input_file_hidden" id="ktp_file_hidden" name="ktp_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Rekening</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['Nomor_rekening']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bank</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="nama_bank" id="nama_bank" data-validation-engine="validate[required]" data-errormessage-value-missing="Bank harus diisi!">
                                        <option value=""> -- Pilih --</option>
                                        <option value="Bank Mandiri" <?php echo ($memberdata['nama_bank']=='Bank Mandiri')? 'selected="selected"' : ''; ?> > Bank Mandiri</option>
                                        <option value="Bank BNI 46" <?php echo ($memberdata['nama_bank']=='Bank BNI 46')? 'selected="selected"' : ''; ?> >Bank BNI 46</option>
                                        <option value="Bank BRI" <?php echo ($memberdata['nama_bank']=='Bank BRI')? 'selected="selected"' : ''; ?> >Bank BRI</option>
                                        <option value="Bank BCA" <?php echo ($memberdata['nama_bank']=='Bank BCA')? 'selected="selected"' : ''; ?> >Bank BCA</option>
                                        <option value="Bank CIMB" <?php echo ($memberdata['nama_bank']=='Bank CIMB')? 'selected="selected"' : ''; ?> >Bank CIMB</option>
                                    </select>
                                </div>
                            </div>

                            <!-- KILAT -->
                            <?php if ($memberdata['mum_type_peminjam']=='1') { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pendidikan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="pendidikan" id="pendidikan">
                                        <option value=""> -- Pilih --</option>
                                        <option value="1" <?php echo ($memberdata['Pendidikan']=='1')? 'selected="selected"' : '';  ?> > SD</option>
                                        <option value="2" <?php echo ($memberdata['Pendidikan']=='2')? 'selected="selected"' : '';  ?> > SLTP</option>
                                        <option value="3" <?php echo ($memberdata['Pendidikan']=='3')? 'selected="selected"' : '';  ?> > SLTA</option>
                                        <option value="4" <?php echo ($memberdata['Pendidikan']=='4')? 'selected="selected"' : '';  ?> > Diploma</option>
                                        <option value="5" <?php echo ($memberdata['Pendidikan']=='5')? 'selected="selected"' : '';  ?> > Sarjana</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Perusahaan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['nama_perusahaan']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Telepon Tempat Bekerja</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['telepon_tempat_bekerja']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Karyawan</label>
                                <div class="col-sm-6">
                                    <select name="status_karyawan" class="form-control">
                                        <option value=""> -- Pilih --</option>
                                         <option value="1" <?php echo ($memberdata['status_karyawan']=='1')? 'selected="selected"' : '';  ?> > Kontrak</option>
                                        <option value="2" <?php echo ($memberdata['status_karyawan']=='2')? 'selected="selected"' : '';  ?> > Tetap</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lama Bekerja</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['lama_bekerja']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Atasan Langsung</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['nama_atasan_langsung']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon Atasan Langsung</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['telp_atasan_langsung']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Referensi Teman / Saudara 1</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['referensi_teman_1']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon Teman / Saudara 1</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['telp_referensi_teman_1']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Referensi Teman / Saudara 2</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['referensi_teman_2']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon Teman / Saudara 2</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['telp_referensi_teman_2']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Surat Keterangan Bekerja</label>
                                <div class="col-sm-6">
                                    <input type="file" id="surat_keterangan_bekerja_file" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php  echo $foto_surat_keterangan_bekerja; ?>">
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                                <input type="hidden" class="input_file_hidden" id="surat_keterangan_bekerja_file_hidden" name="surat_keterangan_bekerja_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Gaji Bulanan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['gaji_bulanan']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Slip Gaji</label>
                                <div class="col-sm-6">
                                    <input type="file" id="slip_gaji_file" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php echo  $foto_slip_gaji; ?>">
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                                <input type="hidden" class="input_file_hidden" id="slip_gaji_file_hidden" name="slip_gaji_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pegang ID CARD / KTP</label>
                                <div class="col-sm-6">
                                   <input type="file" id="pegang_ktp_file" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php echo  $foto_pegang_ktp;?>" >
                                                <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                                <input type="hidden" class="input_file_hidden" id="pegang_ktp_file_hidden" name="pegang_ktp_file_hidden"/>
                                </div>
                            </div>

                            <?php } ?>
                            <!-- End KILAT -->

                            
                            <!-- MIKRO -->
                            <?php if ($memberdata['mum_type_peminjam']=='2' ) { ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['What_is_the_name_of_your_business']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Deskripsi Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['deskripsi_usaha']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 1</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()'  namafile="<?php echo $foto_usaha; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file_hidden" name="usaha_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 2</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file2" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php echo $foto_usaha2; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file2_hidden" name="usaha_file2_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 3</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file3" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php echo $foto_usaha3; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file3_hidden" name="usaha_file3_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 4</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file4" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php echo $foto_usaha4; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file4_hidden" name="usaha_file4_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 5</label>
                                <div class="col-sm-6">
                                   <input type="file" id="usaha_file5" data-show-upload="false" accept="image/*" capture onchange='onFileUpload()' namafile="<?php echo $foto_usaha5; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file5_hidden" name="usaha_file5_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lama Usaha</label>
                                <div class="col-sm-6">
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
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Omzet Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['omzet_usaha'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Modal Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['modal_usaha'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Margin Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['margin_usaha'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Biaya Operasional</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['biaya_operasional'];?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Laba Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $memberdata['laba_usaha'];?>">
                                </div>
                            </div>
                            
                            <?php } ?>
                            <!-- End MIKRO -->

                            <!-- Agri -->
                            <?php if ($memberdata['mum_type_peminjam']=='3') { ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pendidikan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="pendidikan" id="pendidikan">
                                        <option value=""> -- Pilih --</option>
                                        <option value="1" <?php echo ($memberdata['Pendidikan']=='1')? 'selected="selected"' : '';  ?> > SD</option>
                                        <option value="2" <?php echo ($memberdata['Pendidikan']=='2')? 'selected="selected"' : '';  ?> > SLTP</option>
                                        <option value="3" <?php echo ($memberdata['Pendidikan']=='3')? 'selected="selected"' : '';  ?> > SLTA</option>
                                        <option value="4" <?php echo ($memberdata['Pendidikan']=='4')? 'selected="selected"' : '';  ?> > Diploma</option>
                                        <option value="5" <?php echo ($memberdata['Pendidikan']=='5')? 'selected="selected"' : '';  ?> > Sarjana</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bidang Pekerjaan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="bidang_pekerjaan" id="bidang_pekerjaan" data-validation-engine="validate[required]" data-errormessage-value-missing="bidang pekerjaan harus diisi!" >
                                        <option value="agrikultur"> Agrikultur</option>
                                    </select>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Agama</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="agama" id="agama">
                                        <option value=""> -- Pilih --</option>
                                        <option value="islam" <?php echo ($memberdata['Agama']=='islam')? 'selected="selected"' : '';  ?> > Islam</option>
                                        <option value="katolik" <?php echo ($memberdata['Agama']=='katolik')? 'selected="selected"' : '';  ?> > Katolik</option>
                                        <option value="protestan" <?php echo ($memberdata['Agama']=='protestan')? 'selected="selected"' : '';  ?> > Protestan</option>
                                        <option value="budha" <?php echo ($memberdata['Agama']=='budha')? 'selected="selected"' : '';  ?> > Budha</option>
                                        <option value="hindu" <?php echo ($memberdata['Agama']=='hindu')? 'selected="selected"' : '';  ?> > Hindu</option>
                                        <option value="lain-lain" <?php echo ($memberdata['Agama']=='lain-lain')? 'selected="selected"' : '';  ?> > Lain-lain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Pernikahan</label>
                                <div class="col-sm-6">
                                    <label class="checkbox-inline">
                                        <input type="radio" name="status_kawin" value="belum menikah" <?php echo ($memberdata['status_nikah']=='belum menikah')? 'checked="checked"' : ''; ?> > Belum Menikah
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="radio" name="status_kawin" value="menikah" <?php echo ($memberdata['status_nikah']=='menikah')? 'checked="checked"' : ''; ?> > Menikah
                                    </label> 
                                     <label class="checkbox-inline">
                                        <input type="radio" name="status_kawin" value="bercerai" <?php echo ($memberdata['status_nikah']=='bercerai')? 'checked="checked"' : ''; ?> > Bercerai
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah Tanggungan(Istri & Anak)</label>
                                <div class="col-sm-6">
                                   <input type="text" class="form-control" name="jumlah_tanggungan" id="jumlah_tanggungan" value="<?php echo $memberdata['How_many_people_do_you_financially_support']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Tanggungan harus diisi!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Tempat Tinggal</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="status_tempat_tinggal" id="status_tempat_tinggal" data-validation-engine="validate[required]" data-errormessage-value-missing="status tempat tinggal harus diisi!">
                                        <option value=""> -- Pilih --</option>
                                        <option value="1" <?php echo ($memberdata['status_tempat_tinggal']=='1')? 'selected="selected"' : ''; ?> > Milik Keluarga</option>
                                        <option value="2" <?php echo ($memberdata['status_tempat_tinggal']=='2')? 'selected="selected"' : ''; ?> > Milik Sendiri</option>
                                        <option value="3" <?php echo ($memberdata['status_tempat_tinggal']=='3')? 'selected="selected"' : ''; ?> > Sewa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Pegang ID Card/ KTP</label>
                                <div class="col-sm-6">
                                    <input type="file" id="pegang_ktp_file" data-show-upload="false" accept="image/*"  capture onchange='onFileUpload()'  namafile="<?php echo  $foto_pegang_ktp;?>" >
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" name="pegang_ktp_file_hidden" id="pegang_ktp_file_hidden"/>
                                </div>
                            </div>

                            <?php } ?>
                            <!-- End Agri -->
                                <div class="position-center">
                                <button type="submit" class="btn btn-primary">Submit</button> 
                                <button type="button" class="btn btn-white" id="btn-cancel" onclick="history.back(-1)">Cancel</button>
                            </div>  
                            <!-- <script>document.write(theSelect[theSelect.selectedIndex].value)</script>  -->
                        </form>
                    </div>
                </section>
            </div>            
        </div>
    </section>
    <script type="text/javascript">

window.onFileUpload = function() {
    var file = event.target.files[0];
    var el = event.target;
    var parent = el.parentNode.parentNode.parentNode.parentNode.parentNode;
    var hiddenInput = parent.getElementsByClassName('input_file_hidden')[0];
    console.log(parent);
    ImageTools.resize(file, {
        width: 1024, // maximum width
        height: 800 // maximum height
    }, function(blob, didItResize) {
        // didItResize will be true if it managed to resize it, otherwise false (and will return the original file as 'blob')
         
        var reader = new FileReader();
        reader.readAsDataURL(blob); 
        reader.onloadend = function() {
            base64data = reader.result;
            hiddenInput.value = base64data;                
            console.log(base64data);
        }
        // you can also now upload this blob using an XHR.
    });
};



</script>
<script>

function showhidedomisili() {
  // Get the checkbox
  var checkBox = document.getElementById("checkdomisili");
  // Get the output text
  var text  = document.getElementById("hiddendomisili");
  var text2 = document.getElementById("hiddendomisili2");
  var text3 = document.getElementById("hiddendomisili3");
  var text4 = document.getElementById("hiddendomisili4");

  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    text.style.display  = "none";
    text2.style.display = "none";
    text3.style.display = "none";
    text4.style.display = "none";
  } else {
    text.style.display  = "block";
    text2.style.display = "block";
    text3.style.display = "block";
    text4.style.display = "block";
  }
}

showhidedomisili();

</script>