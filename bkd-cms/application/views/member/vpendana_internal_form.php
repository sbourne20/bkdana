    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        EDIT KOPERASI
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" enctype="multipart/form-data" method="POST" id="formID">
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fullname</label>
                                <div class="col-sm-6">
                                    <input type="text" name="fullname" class="form-control" value="<?php echo (isset($EDIT['Nama_pengguna'])? $EDIT['Nama_pengguna'] : ''); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['mum_email'])? $EDIT['mum_email'] : ''); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Telpon</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telp" id="telp" value="+62"  class="form-control" value="<?php echo (isset($EDIT['Mobileno'])? $EDIT['Mobileno'] : ''); ?>" >
                                    <p class="help-block" id="telpmsg">* Contoh: +628987654433</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tempat Lahir</label>
                                <div class="col-sm-6">
                                    <input type="text" name="tempat_lahir" class="form-control" value="<?php echo (isset($EDIT['Tempat_lahir'])? $EDIT['Tempat_lahir'] : ''); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tanggal Lahir</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control datepicker-dob" name="tgl_lahir" id="tgl_lahir_pinjam" value="<?php echo date('d-m-Y', strtotime($EDIT['Tanggal_lahir'])); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis Kelamin</label>
                                <div class="col-sm-6">
                                    <select name="gender" class="form-control">
                                        <option value="pria">Pria</option>
                                        <option value="wanita">Wanita</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-6">
                                    <input type="text" name="alamat" class="form-control" value="<?php echo $EDIT['Alamat']; ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kota</label>
                                <div class="col-sm-6">
                                    <input type="text" name="kota" class="form-control" value="<?php echo (isset($EDIT['Kota'])? $EDIT['Kota'] : ''); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Provinsi</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="provinsi" id="provinsi" >
                                                            <option value=""> -- Pilih -- </option>
                                                            <option value="Aceh" <?php echo ($EDIT['Provinsi']=='Aceh')? 'selected="selected"' : ''; ?>>Aceh</option>
                                                            <option value="Bali" <?php echo ($EDIT['Provinsi']=='Bali')? 'selected="selected"' : ''; ?>>Bali</option>
                                                            <option value="Banten" <?php echo ($EDIT['Provinsi']=='Banten')? 'selected="selected"' : ''; ?>>Banten</option>
                                                            <option value="Bengkulu" <?php echo ($EDIT['Provinsi']=='Bengkulu')? 'selected="selected"' : ''; ?>>Bengkulu</option>
                                                            <option value="DI Yogyakarta" <?php echo ($EDIT['Provinsi']=='DI Yogyakarta')? 'selected="selected"' : ''; ?>>DI Yogyakarta</option>
                                                            <option value="DKI Jakarta" <?php echo ($EDIT['Provinsi']=='DKI Jakarta')? 'selected="selected"' : ''; ?>>DKI Jakarta</option>
                                                            <option value="Gorontalo" <?php echo ($EDIT['Provinsi']=='Gorontalo')? 'selected="selected"' : ''; ?>>Gorontalo</option>
                                                            <option value="Jambi" <?php echo ($EDIT['Provinsi']=='Jambi')? 'selected="selected"' : ''; ?>>Jambi</option>
                                                            <option value="Jawa Barat" <?php echo ($EDIT['Provinsi']=='Jawa Barat')? 'selected="selected"' : ''; ?>>Jawa Barat</option>
                                                            <option value="Jawa Tengah" <?php echo ($EDIT['Provinsi']=='Jawa Tengah')? 'selected="selected"' : ''; ?>>Jawa Tengah</option>
                                                            <option value="Jawa Timur" <?php echo ($EDIT['Provinsi']=='Jawa Timur')? 'selected="selected"' : ''; ?>>Jawa Timur</option>
                                                            <option value="Kalimantan Barat" <?php echo ($EDIT['Provinsi']=='Kalimantan Barat')? 'selected="selected"' : ''; ?>>Kalimantan Barat</option>
                                                            <option value="Kalimantan Selatan" <?php echo ($EDIT['Provinsi']=='Kalimantan Selatan')? 'selected="selected"' : ''; ?>>Kalimantan Selatan</option>
                                                            <option value="Kalimantan Tengah" <?php echo ($EDIT['Provinsi']=='Kalimantan Tengah')? 'selected="selected"' : ''; ?>>Kalimantan Tengah</option>
                                                            <option value="Kalimantan Timur" <?php echo ($EDIT['Provinsi']=='Kalimantan Timur')? 'selected="selected"' : ''; ?>>Kalimantan Timur</option>
                                                            <option value="Kalimantan Utara" <?php echo ($EDIT['Provinsi']=='Kalimantan Utara')? 'selected="selected"' : ''; ?>>Kalimantan Utara</option>
                                                            <option value="Kepulauan Bangka Belitung" <?php echo ($EDIT['Provinsi']=='Kepulauan Bangka Belitung')? 'selected="selected"' : ''; ?>>Kepulauan Bangka Belitung</option>
                                                            <option value="Kepulauan Riau" <?php echo ($EDIT['Provinsi']=='Kepulauan Riau')? 'selected="selected"' : ''; ?>>Kepulauan Riau</option>
                                                            <option value="Lampung" <?php echo ($EDIT['Provinsi']=='Lampung')? 'selected="selected"' : ''; ?>>Lampung</option>
                                                            <option value="Maluku" <?php echo ($EDIT['Provinsi']=='Maluku')? 'selected="selected"' : ''; ?>>Maluku</option>
                                                            <option value="Maluku Utara" <?php echo ($EDIT['Provinsi']=='Maluku Utara')? 'selected="selected"' : ''; ?>>Maluku Utara</option>
                                                            <option value="Nusa Tenggara Barat" <?php echo ($EDIT['Provinsi']=='Nusa Tenggara Barat')? 'selected="selected"' : ''; ?>>Nusa Tenggara Barat</option>
                                                            <option value="Nusa Tenggara Timur" <?php echo ($EDIT['Provinsi']=='Nusa Tenggara Timur')? 'selected="selected"' : ''; ?>>Nusa Tenggara Timur</option>
                                                            <option value="Papua" <?php echo ($EDIT['Provinsi']=='Papua')? 'selected="selected"' : ''; ?>>Papua</option>
                                                            <option value="Papua Barat" <?php echo ($EDIT['Provinsi']=='Papua Barat')? 'selected="selected"' : ''; ?>>Papua Barat</option>
                                                            <option value="Riau" <?php echo ($EDIT['Provinsi']=='Riau')? 'selected="selected"' : ''; ?>>Riau</option>
                                                            <option value="Sulawesi Barat" <?php echo ($EDIT['Provinsi']=='Sulawesi Barat')? 'selected="selected"' : ''; ?>>Sulawesi Barat</option>
                                                            <option value="Sulawesi Selatan" <?php echo ($EDIT['Provinsi']=='Sulawesi Selatan')? 'selected="selected"' : ''; ?>>Sulawesi Selatan</option>
                                                            <option value="Sulawesi Tengah" <?php echo ($EDIT['Provinsi']=='Sulawesi Tengah')? 'selected="selected"' : ''; ?>>Sulawesi Tengah</option>
                                                            <option value="Sulawesi Tenggara" <?php echo ($EDIT['Provinsi']=='Sulawesi Tenggara')? 'selected="selected"' : ''; ?>>Sulawesi Tenggara</option>
                                                            <option value="Sulawesi Utara" <?php echo ($EDIT['Provinsi']=='Sulawesi Utara')? 'selected="selected"' : ''; ?>>Sulawesi Utara</option>
                                                            <option value="Sumatera Barat" <?php echo ($EDIT['Provinsi']=='Sumatera Barat')? 'selected="selected"' : ''; ?>>Sumatera Barat</option>
                                                            <option value="Sumatera Selatan" <?php echo ($EDIT['Provinsi']=='Sumatera Selatan')? 'selected="selected"' : ''; ?>>Sumatera Selatan</option>
                                                            <option value="Sumatera Utara" <?php echo ($EDIT['Provinsi']=='Sumatera Utara')? 'selected="selected"' : ''; ?>>Sumatera Utara</option>
                                                        </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kode Pos</label>
                                <div class="col-sm-6">
                                    <input type="text" name="kodepos" class="form-control" value="<?php echo (isset($EDIT['Kodepos'])? $EDIT['Kodepos'] : ''); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Profil</label>
                                <div class="col-sm-6">                                    
                                    <input type="file" name="foto_file" id="foto_file" data-show-upload="false" >
                                    <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Pernikahan</label>
                                <div class="col-sm-6">
                                    <label class="checkbox-inline">
                                        <input type="radio" name="status_kawin" value="belum menikah" checked="checked"> Belum Menikah
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="radio" name="status_kawin" value="menikah"> Menikah
                                    </label> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah Tanggungan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="jml_tanggungan" id="jml_tanggungan">
                                        <option value=""> -- Pilih --</option>
                                        <option value="0" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='0')? 'selected="selected"' : ''; ?>>0</option>
                                        <option value="1" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='1')? 'selected="selected"' : ''; ?> >1</option>
                                        <option value="2" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='2')? 'selected="selected"' : ''; ?> >2</option>
                                        <option value="3" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='3')? 'selected="selected"' : ''; ?> >3</option>
                                        <option value="4" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='4')? 'selected="selected"' : ''; ?> >4</option>
                                        <option value="5" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='5')? 'selected="selected"' : ''; ?> >5</option>
                                        <option value="6" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='6')? 'selected="selected"' : ''; ?> >6</option>
                                        <option value="7" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='7')? 'selected="selected"' : ''; ?> >7</option>
                                        <option value="8" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='8')? 'selected="selected"' : ''; ?> >8</option>
                                        <option value="9" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='9')? 'selected="selected"' : ''; ?> >9</option>
                                        <option value="10" <?php echo ($EDIT['How_many_people_do_you_financially_support']=='10')? 'selected="selected"' : ''; ?> >10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pendidikan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="pendidikan" id="pendidikan">
                                        <option value=""> -- Pilih --</option>
                                        <option value="1" <?php echo ($EDIT['Pendidikan']=='1')? 'selected="selected"' : ''; ?> >SD</option>
                                        <option value="2" <?php echo ($EDIT['Pendidikan']=='2')? 'selected="selected"' : ''; ?> >SLTP</option>
                                        <option value="3" <?php echo ($EDIT['Pendidikan']=='3')? 'selected="selected"' : ''; ?> >SLTA</option>
                                        <option value="4" <?php echo ($EDIT['Pendidikan']=='4')? 'selected="selected"' : ''; ?> >Diploma</option>
                                        <option value="5" <?php echo ($EDIT['Pendidikan']=='5')? 'selected="selected"' : ''; ?> >Sarjana</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah Penghasilan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="jumlah_penghasilan" class="form-control" value="<?php echo (isset($EDIT['average_monthly_salary'])? $EDIT['average_monthly_salary'] : ''); ?>" >
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pekerjaan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="pekerjaan" id="pekerjaan">
                                        <option value=""> -- Pilih --</option>
                                        <option value="1">PNS</option>
                                        <option value="2">BUMN</option>
                                        <option value="3">Swasta</option>
                                        <option value="4">Wiraswasta</option>
                                        <option value="5">Lain-lain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">NIK</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nomor_ktp" class="form-control" value="<?php echo (isset($EDIT['Id_ktp'])? $EDIT['Id_ktp'] : ''); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto NIK</label>
                                <div class="col-sm-6">
                                    <input type="file" name="ktp_file" id="ktp_file" data-show-upload="false" >
                                    <p class="help-block">* maksimum size 5 MB dengan jpg, png, gif</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nomor Rekening</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nomor_rekening" class="form-control" value="<?php echo (isset($EDIT['Nomor_rekening'])? $EDIT['Nomor_rekening'] : ''); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bank</label>
                                <div class="col-sm-6">
                                    
                                    <select class="form-control" name="nama_bank" id="nama_bank" >
                                        <option value="">- Pilih Bank -</option>
                                        <option value="Bank Mandiri" <?php echo ($EDIT['nama_bank']=='Bank Mandiri')? 'selected="selected"' : ''; ?> >Bank Mandiri</option>
                                        <option value="Bank BNI 46" <?php echo ($EDIT['nama_bank']=='Bank BNI 46')? 'selected="selected"' : ''; ?> >Bank BNI 46</option>
                                        <option value="Bank BRI" <?php echo ($EDIT['nama_bank']=='Bank BRI')? 'selected="selected"' : ''; ?> >Bank BRI</option>
                                        <option value="Bank BCA" <?php echo ($EDIT['nama_bank']=='Bank BCA')? 'selected="selected"' : ''; ?> >Bank BCA</option>
                                    </select>
                                </div>
                            </div>
                            

                            <div class="position-center">
                                <button type="submit" class="btn btn-primary">Submit</button> 
                                <button type="button" class="btn btn-white" id="btn-cancel" onclick="history.back(-1)">Cancel</button>
                            </div>  
                        </form>
                    </div>
                </section>
            </div>            
        </div>
    </section>

<script type="text/javascript">
    
    var IMG_profil = "<?php echo base64_encode($EDIT['Profile_photo']); ?>";
    var IMG_NIK = "<?php echo base64_encode($EDIT['Photo_id']); ?>";

$("#foto_file").fileinput({
  initialPreview: ["<img src=data:image/jpeg;base64,"+IMG_profil+" class='file-preview-image' />"],

    removeClass: "btn btn-danger",
    removeLabel: "Remove",
    removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 2000,
});

$("#ktp_file").fileinput({
  initialPreview: ["<img src=data:image/jpeg;base64,"+IMG_NIK+" class='file-preview-image' />"],

    removeClass: "btn btn-danger",
    removeLabel: "Remove",
    removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
    maxFileSize: 2000,
});
</script>