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
    $foto_profil = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/foto/'. $memberdata['images_foto_name']);
}
if ($memberdata['images_ktp_name'] != '')
{
    $foto_ktp = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/ktp/'. $memberdata['images_ktp_name']);
}
if ($memberdata['images_usaha_name'] != '')
{
    $foto_usaha = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/usaha/'. $memberdata['images_usaha_name']);
}
if ($memberdata['images_usaha_name2'] != '')
{
    $foto_usaha2 = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/usaha2/'. $memberdata['images_usaha_name2']);
}
if ($memberdata['images_usaha_name3'] != '')
{
    $foto_usaha3 = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/usaha3/'. $memberdata['images_usaha_name3']);
}
if ($memberdata['images_usaha_name4'] != '')
{
    $foto_usaha4 = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/usaha4/'. $memberdata['images_usaha_name4']);
}
if ($memberdata['images_usaha_name5'] != '')
{
    $foto_usaha5 = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/usaha5/'. $memberdata['images_usaha_name5']);
}

// -----tambahan baru-----

if ($memberdata['foto_surat_keterangan_bekerja'] != '')
{
    $foto_surat_keterangan_bekerja = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/surat_keterangan_bekerja/'. $memberdata['foto_surat_keterangan_bekerja']);
}
if ($memberdata['foto_slip_gaji'] != '')
{
    $foto_slip_gaji= site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/slip_gaji/'. $memberdata['foto_slip_gaji']);
}
if ($memberdata['foto_pegang_ktp'] != '')
{
    $foto_pegang_ktp = site_url('fileload?p=') . urlencode('member/'.$memberdata['id_mod_user_member']. '/pegang_ktp/'. $memberdata['foto_pegang_ktp']);
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
                        <form class="form-horizontal form-validation"  method="POST" id="formID" name='formpeminjam'>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Lengkap</label>
                                <div class="col-sm-6">
                                    <input type="text" name="fullname" id="nama" class="form-control" value="<?php echo $memberdata['Nama_pengguna']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" id="email" class="form-control" value="<?php echo $memberdata['mum_email']; ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telp" id="telp" class="form-control" value="<?php echo $memberdata['mum_telp']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tempat Lahir</label>
                                <div class="col-sm-6">
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="<?php echo $memberdata['Tempat_lahir']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tanggal Lahir</label>
                                <div class="col-sm-6">
                                    <input type="text" name="tgl_lahir" id="tgl_lahir_pinjam" class="form-control datepicker-dob" value="<?php echo ($memberdata['Tanggal_lahir']=='0000-00-00')? '' : date('d-m-Y', strtotime($memberdata['Tanggal_lahir'])); ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis Kelamin</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="gender" id="gender" data-value="<?php echo $memberdata['Jenis_kelamin']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                        <option value=""> -- Pilih --</option>
                                        <?php foreach ($gender as $key) {   ?>
                                        <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>"<?php if($memberdata['Jenis_kelamin']==$key['Option_value']){echo "selected";}?>> <?php echo $key['Option_label']; ?> 
                                        </option>
                                    <?php } ?>
                                    </select>
                                </div>     
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alamat sesuai dengan KTP</label>
                                <div class="col-sm-6">
                                    <input type="text" name="alamat" id="alamat" class="form-control" value="<?php echo $memberdata['Alamat']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Provinsi sesuai dengan KTP</label>
                                <div class="col-sm-6">
                                   <select class="form-control" name="provinsi" id="provinsi" data-validation-engine="validate[required]" data-errormessage-value-missing="Provinsi harus diisi!" >
                                        <option value=""> -- Pilih Provinsi--</option>
                                        <?php foreach ($provinsi as $key) {
                                        ?>
                                        <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>"<?php if($memberdata['Provinsi']==$key['Option_value']){echo "selected";}?>> <?php echo $key['Option_label']; ?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kota sesuai dengan KTP</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="kota" id="kota" data-value="<?php echo $memberdata['Kota']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Kota harus diisi!" >
                                        <option value=""> -- Pilih Kota--</option>
                                        </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kode Pos</label>
                                <div class="col-sm-6">
                                    <input type="text" name="kodepos" id="kodepos" class="form-control" value="<?php echo $memberdata['Kodepos']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
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
                            <div class="form-group" id="hiddendomisili3">
                                    <label class="col-sm-2 control-label">* Provinsi Domisili</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="provinsidomisili" id="provinsidomisili" data-validation-engine="validate[required]" data-errormessage-value-missing="Provinsi Domisili harus diisi!" >
                                            <option value=""> -- Pilih Provinsi--</option>
                                            <?php foreach ($provinsi as $key) {
                                            ?>
                                            <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>"<?php if($memberdata['Provinsi_Domisili']==$key['Option_value']){echo "selected";}?>> <?php echo $key['Option_label']; ?> </option>
                                            <?php
                                            }
                                            ?>
                                            </select>
                                    </div>
                            </div>
                            <div class="form-group" id="hiddendomisili2">
                                
                                    <label class="col-sm-2 control-label">* Kota Domisili</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="kotadomisili" id="kotadomisili" data-value="<?php echo $memberdata['Kota_Domisili']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Kota Domisili harus diisi!" >
                                            <option value=""> -- Pilih Kota--</option>
                                            </select>
                                    </div>
                                
                            </div>    
                            <div class="form-group" id="hiddendomisili4">
                                
                                    <label class="col-sm-2 control-label">* Kode Pos Domisili</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="kodeposdomisili" id="kodeposdomisili" value="<?php echo $memberdata['Kodepos_Domisili']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!" >
                                    </div>
                                
                            </div>
                        
                                    <!-- BATAS ALAMAT DOMISILI -->
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pekerjaan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="pekerjaan" id="pekerjaan"    data-value="<?php echo $memberdata['Pekerjaan']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                    <option value=""> -- Pilih --</option>
                                    <?php foreach ($pekerjaan as $key) {
                                    ?>
                                    <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>"<?php if($memberdata['Pekerjaan']==$key['Option_value']){echo "selected";}?>> <?php echo $key['Option_label']; ?> 
                                            </option>
                                    <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Diri / Selfie</label>
                                <div class="col-sm-6">
                                   <input type="file" id="foto_file" data-show-upload="false" onchange='onFileUpload()' namafile="<?php echo $foto_profil; ?>" >
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="foto_file_hidden" name="foto_file_hidden"/>                   
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nomor KTP</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nomor_ktp" id="nomor_ktp" class="form-control" value="<?php echo $memberdata['Id_ktp']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto KTP</label>
                                <div class="col-sm-6">
                                   <input type="file" name="ktp_file" id="ktp_file" data-show-upload="false" onchange='onFileUpload()'namafile="<?php echo $foto_ktp; ?>" >
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="ktp_file_hidden" name="ktp_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Rekening</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control" value="<?php echo $memberdata['Nomor_rekening']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bank</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="nama_bank" id="nama_bank"    data-value="<?php echo $memberdata['nama_bank']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                        <option value=""> -- Pilih --</option>
                                        <?php foreach ($nama_bank as $key) {
                                        ?>
                                        <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>"<?php if($memberdata['nama_bank']==$key['Option_value']){echo "selected";}?>> <?php echo $key['Option_label']; ?> 
                                        </option>
                                    <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>

                            <!-- KILAT -->
                            <?php if ($memberdata['mum_type_peminjam']=='1') { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pendidikan</label>
                                <div class="col-sm-6">
                                <select class="form-control" name="pendidikan" id="pendidikan" data-value="<?php echo $memberdata['Pendidikan']; ?>">
                                    <option value=""> -- Pilih --</option>
                                    <?php foreach ($pendidikan as $key) {
                                        ?>
                                        <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>" <?php if ($memberdata['Pendidikan'] == $key['Option_value']) {
                                                                                                                                                    echo "selected";
                                                                                                                                                } ?>> <?php echo $key['Option_label']; ?>
                                        </option>
                                    <?php
                                }
                                ?>
                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Perusahaan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" value="<?php echo $memberdata['nama_perusahaan']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Telepon Tempat Bekerja</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telepon_perusahaan" id="telepon_perusahaan" class="form-control" value="<?php echo $memberdata['telepon_tempat_bekerja']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Karyawan</label>
                                <div class="col-sm-6">
                                    <select name="status_karyawan" class="form-control" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                        <option value=""> -- Pilih --</option>
                                         <option value="1" <?php echo ($memberdata['status_karyawan']=='1')? 'selected="selected"' : '';  ?> > Kontrak</option>
                                        <option value="2" <?php echo ($memberdata['status_karyawan']=='2')? 'selected="selected"' : '';  ?> > Tetap</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lama Bekerja</label>
                                <div class="col-sm-6">
                                    <input type="text" name="lama_bekerja" id="lama_bekerja" class="form-control" value="<?php echo $memberdata['lama_bekerja']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Atasan Langsung</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nama_atasan_langsung" id="nama_atasan_langsung" class="form-control" value="<?php echo $memberdata['nama_atasan_langsung']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon Atasan Langsung</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telp_atasan_langsung" id="telp_atasan_langsung" class="form-control" value="<?php echo $memberdata['telp_atasan_langsung']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Referensi Teman / Saudara 1</label>
                                <div class="col-sm-6">
                                    <input type="text" name="referensi_teman_1" id="referensi_teman_1" class="form-control" value="<?php echo $memberdata['referensi_teman_1']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon Teman / Saudara 1</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telp_teman_1" id="telp_teman_1" class="form-control" value="<?php echo $memberdata['telp_referensi_teman_1']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Referensi Teman / Saudara 2</label>
                                <div class="col-sm-6">
                                    <input type="text" name="referensi_teman_2" id="referensi_teman_2" class="form-control" value="<?php echo $memberdata['referensi_teman_2']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">No Telepon Teman / Saudara 2</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telp_teman_2" id="telp_teman_2" class="form-control" value="<?php echo $memberdata['telp_referensi_teman_2']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Surat Keterangan Bekerja</label>
                                <div class="col-sm-6">
                                    <input type="file" id="surat_keterangan_bekerja_file" data-show-upload="false" onchange='onFileUpload()' namafile="<?php  echo $foto_surat_keterangan_bekerja; ?>">
                                        <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                        <input type="hidden" class="input_file_hidden" id="surat_keterangan_bekerja_file_hidden" name="surat_keterangan_bekerja_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Gaji Bulanan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="gaji_bulanan" id="gaji_bulanan" class="form-control" value="<?php echo $memberdata['gaji_bulanan']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Slip Gaji</label>
                                <div class="col-sm-6">
                                    <input type="file" id="slip_gaji_file" data-show-upload="false" onchange='onFileUpload()' namafile="<?php echo  $foto_slip_gaji; ?>">
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="slip_gaji_file_hidden" name="slip_gaji_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pegang ID CARD / KTP</label>
                                <div class="col-sm-6">
                                   <input type="file" id="pegang_ktp_file" data-show-upload="false" onchange='onFileUpload()' namafile="<?php echo  $foto_pegang_ktp;?>" >
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
                                    <input type="text" name="usaha" id="usaha" class="form-control" value="<?php echo $memberdata['What_is_the_name_of_your_business']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Deskripsi Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="deskripsi_usaha" id="deskripsi_usaha" class="form-control" value="<?php echo $memberdata['deskripsi_usaha']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 1</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file" data-show-upload="false"  onchange='onFileUpload()'  namafile="<?php echo $foto_usaha; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file_hidden" name="usaha_file_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 2</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file2" data-show-upload="false"  onchange='onFileUpload()' namafile="<?php echo $foto_usaha2; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file2_hidden" name="usaha_file2_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 3</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file3" data-show-upload="false"  onchange='onFileUpload()' namafile="<?php echo $foto_usaha3; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file3_hidden" name="usaha_file3_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 4</label>
                                <div class="col-sm-6">
                                    <input type="file" id="usaha_file4" data-show-upload="false"  onchange='onFileUpload()' namafile="<?php echo $foto_usaha4; ?>" multiple>
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" id="usaha_file4_hidden" name="usaha_file4_hidden"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Usaha 5</label>
                                <div class="col-sm-6">
                                   <input type="file" id="usaha_file5" data-show-upload="false"  onchange='onFileUpload()' namafile="<?php echo $foto_usaha5; ?>" multiple>
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
                                    <input type="text" name="omzet_usaha" id="omzet_usaha" class="form-control" value="<?php echo $memberdata['omzet_usaha'];?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Modal Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="modal_usaha" id="modal_usaha" class="form-control" value="<?php echo $memberdata['modal_usaha'];?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Margin Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="margin_usaha" id="margin_usaha" class="form-control" value="<?php echo $memberdata['margin_usaha'];?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Biaya Operasional</label>
                                <div class="col-sm-6">
                                    <input type="text" name="biaya_operasional" id="biaya_operasional" class="form-control" value="<?php echo $memberdata['biaya_operasional'];?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Laba Usaha</label>
                                <div class="col-sm-6">
                                    <input type="text" name="laba_usaha" id="laba_usaha" class="form-control" value="<?php echo $memberdata['laba_usaha'];?>"data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            
                            <?php } ?>
                            <!-- End MIKRO -->

                            <!-- Agri -->
                            <?php if ($memberdata['mum_type_peminjam']=='3') { ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pendidikan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="pendidikan" id="pendidikan" data-value="<?php echo $memberdata['Pendidikan']; ?>">
                                    <option value=""> -- Pilih --</option>
                                    <?php foreach ($pendidikan as $key) {
                                        ?>
                                        <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>" <?php if ($memberdata['Pendidikan'] == $key['Option_value']) {
                                                                                                                                                    echo "selected";
                                                                                                                                                } ?>> <?php echo $key['Option_label']; ?>
                                        </option>
                                    <?php
                                }
                                ?>
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
  <!--                               <div class="form-group" id="hiddendomisili2">
                                    <label class="col-sm-2 control-label">* coba agama</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="kotadomisili" id="kotadomisili" value="<?php echo $memberdataagama['Option_']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Kota harus diisi!" >
                                    </div>
                                
                            </div> --> 
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Agama</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="agama" id="agama" data-value="<?php echo $memberdata['Agama']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                        <option value=""> -- Pilih --</option>
                                        <?php foreach ($agama as $key) {
                                        ?>
                                        <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>"<?php if($memberdata['Agama']==$key['Option_value']){echo "selected";}?>> <?php echo $key['Option_label']; ?> 
                                        </option>
                                         <?php
                                         }
                                         ?>
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
                                    <select class="form-control" name="status_tempat_tinggal" id="status_tempat_tinggal" data-value="<?php echo $memberdata['status_tempat_tinggal']; ?>">
                                                    <option value=""> -- Pilih --</option>
                                                    <?php foreach ($status_tempat_tinggal as $key) {
                                                        ?>
                                                        <option value="<?php echo $key['Option_value'] ?>" data-member="<?php echo $key['Option_value']; ?>" <?php if ($memberdata['status_tempat_tinggal'] == $key['Option_value']) {
                                                                                                                                                                    echo "selected";
                                                                                                                                                                } ?>> <?php echo $key['Option_label']; ?>
                                                        </option>
                                                    <?php
                                                }
                                                ?>
                                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Pegang ID Card/ KTP</label>
                                <div class="col-sm-6">
                                    <input type="file" id="pegang_ktp_file" data-show-upload="false"  onchange='onFileUpload()'  namafile="<?php echo  $foto_pegang_ktp;?>" >
                                    <p class="help-block">* maksimum size 1 MB dengan jpg, png, gif</p>
                                    <input type="hidden" class="input_file_hidden" name="pegang_ktp_file_hidden" id="pegang_ktp_file_hidden"/>
                                </div>
                            </div>

                            <?php } ?>
                            <!-- End Agri -->
                            <!-- PENDANA -->
                                <?php if ($memberdata['mum_type']=='2') { // PENDANA ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">* Status Pernikahan</label>
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline">
                                            <input type="radio" name="status_kawin" value="belum menikah" <?php echo ($memberdata['status_nikah']=='belum menikah')? 'checked="checked"' : ''; ?> > Belum Menikah
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="status_kawin" value="menikah" <?php echo ($memberdata['status_nikah']=='menikah')? 'checked="checked"' : ''; ?> > Menikah
                                        </label>
                                    </div>                                                
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">* Jumlah Tanggungan</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="jml_tanggungan" id="jml_tanggungan" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Tanggungan harus diisi!">
                                            <option value=""> -- Pilih --</option>

                                            <?php for ($i=0; $i <= 10 ; $i++) { 
                                                $selected = ($memberdata['How_many_people_do_you_financially_support']==$i)? 'selected="selected"' : '';
                                            ?>
                                            <option value="<?php echo $i; ?>" <?php echo $selected; ?>> <?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">* Pendidikan Terakhir</label>
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
                                    <label class="col-sm-2 control-label">* Jumlah Penghasilan (Rp)</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control numeric" name="jumlah_penghasilan" id="jumlah_penghasilan" value="<?php echo (empty($memberdata['average_monthly_salary']))? '': $memberdata['average_monthly_salary']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="jumlah penghasilan harus diisi!">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">* NPWP </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="npwp" id="npwp" value="<?php echo $memberdata['npwp']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="NPWP harus diisi!">
                                    </div>
                                </div>
                                <!-- End PENDANA -->
                                <?php } ?>

                                <input type="hidden" name="old_foto" value="<?php echo $memberdata['images_foto_name']; ?>">
                                <input type="hidden" name="old_ktp" value="<?php echo $memberdata['images_ktp_name']; ?>">
                                <input type="hidden" name="old_usaha" value="<?php echo $memberdata['images_usaha_name']; ?>">
                                <input type="hidden" name="old_usaha2" value="<?php echo $memberdata['images_usaha_name2']; ?>">
                                <input type="hidden" name="old_usaha3" value="<?php echo $memberdata['images_usaha_name3']; ?>">
                                <input type="hidden" name="old_usaha4" value="<?php echo $memberdata['images_usaha_name4']; ?>">
                                <input type="hidden" name="old_usaha5" value="<?php echo $memberdata['images_usaha_name5']; ?>">
                                <input type="hidden" name="old_surat_keterangan_bekerja" value="<?php echo $memberdata['foto_surat_keterangan_bekerja']; ?>">
                                <input type="hidden" name="old_slip_gaji" value="<?php echo $memberdata['foto_slip_gaji']; ?>">
                                <input type="hidden" name="old_pegang_ktp" value="<?php echo $memberdata['foto_pegang_ktp']; ?>">

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
    var parent = el.parentNode.parentNode.parentNode;
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