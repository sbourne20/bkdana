    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        
                        <a href="<?php echo site_url($this->uri->segment(1).'/topup'); ?>"><button type="button" class="btn btn-primary">Top Up</button></a>
                        &nbsp; KOPERASI
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" method="POST" id="formID">
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Saldo</label>
                                <div class="col-sm-6">
                                    <input type="text" name="fullname" class="form-control" value="Rp <?php echo number_format($saldo['Amount']); ?>" disabled="disabled">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fullname</label>
                                <div class="col-sm-6">
                                    <input type="text" name="fullname" class="form-control" value="<?php echo (isset($EDIT['Nama_pengguna'])? $EDIT['Nama_pengguna'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['mum_email'])? $EDIT['mum_email'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Telpon</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Mobileno'])? $EDIT['Mobileno'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tempat/Tanggal Lahir</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Tempat_lahir'])? $EDIT['Tempat_lahir'].date('d/m/Y', strtotime($EDIT['Tanggal_lahir'])) : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis Kelamin</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Jenis_kelamin'])? $EDIT['Jenis_kelamin'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo $EDIT['Alamat']; ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kota</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Kota'])? $EDIT['Kota'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Provinsi</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Provinsi'])? $EDIT['Provinsi'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kode Pos</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Kodepos'])? $EDIT['Kodepos'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status Pernikahan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['status_nikah'])? $EDIT['status_nikah'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah Tanggungan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['How_many_people_do_you_financially_support'])? $EDIT['How_many_people_do_you_financially_support'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>

                            <?php 
                            switch ($EDIT['Pendidikan']) {
                                case '1':
                                    $lulusan = 'SD';
                                    break;
                                case '2':
                                    $lulusan = 'SLTP';
                                    break;
                                case '3':
                                    $lulusan = 'SLTA';
                                    break;
                                case '4':
                                    $lulusan = 'Diploma';
                                    break;
                                case '5':
                                    $lulusan = 'Sarjana';
                                    break;
                                
                                default:
                                    $lulusan = $EDIT['Pendidikan'];
                                    break;
                            }
                            ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pendidikan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Pendidikan'])? $lulusan : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah Penghasilan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['average_monthly_salary'])? $EDIT['average_monthly_salary'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto Profil</label>
                                <div class="col-sm-6">
                                    <img width="300" src="data:image/jpeg;base64,<?php echo base64_encode($EDIT['Profile_photo']); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Pekerjaan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['mum_email'])? $EDIT['mum_email'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">NIK</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Id_ktp'])? $EDIT['Id_ktp'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Foto NIK</label>
                                <div class="col-sm-6">
                                    <img width="300" src="data:image/jpeg;base64,<?php echo base64_encode($EDIT['Photo_id']); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nomor Rekening</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['Nomor_rekening'])? $EDIT['Nomor_rekening'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bank</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['nama_bank'])? $EDIT['nama_bank'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Grade</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['peringkat_pengguna'])? $EDIT['peringkat_pengguna'] : ''); ?>" disabled="disabled">
                                </div>
                            </div>

                            <div class="position-center">
                                <a href="<?php echo site_url($this->uri->segment(1).'/edit'); ?>"><button type="button" class="btn btn-primary">EDIT</button></a>
                            </div>  
                        </form>
                    </div>
                </section>
            </div>            
        </div>
    </section>