    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Top Up Koperasi
                    </header>
                    <div class="panel-body">
                            <form class="form-horizontal form-validation" method="POST" id="formID">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="fullname">Nama Rekening Anda</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="account_bank_name" id="account_bank_name" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Nama Rekening harus diisi!">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="accounta_bank_no">Nomor Rekening Anda</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="account_bank_number" id="account_bank_number" type="text" value="<?php echo $EDIT['Nomor_rekening']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Nomor Rekening harus diisi!">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="bank">Bank Anda</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="my_bank_name" id="my_bank_name">
                                            <option value="" selected="selected">- Pilih Bank -</option>
                                            <option value="Bank Mandiri" <?php echo ($EDIT['nama_bank']=='Bank Mandiri')? 'selected="selected"' : ''; ?>>Bank Mandiri</option>
                                            <option value="Bank BNI 46" <?php echo ($EDIT['nama_bank']=='Bank BNI 46')? 'selected="selected"' : ''; ?>>Bank BNI 46</option>
                                            <option value="Bank BRI" <?php echo ($EDIT['nama_bank']=='Bank BRI')? 'selected="selected"' : ''; ?>>Bank BRI</option>
                                            <option value="Bank BCA" <?php echo ($EDIT['nama_bank']=='Bank BCA')? 'selected="selected"' : ''; ?>>Bank BCA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="accounta_bank_beneficiary">Bank Tujuan</label>
                                    <div class="col-sm-4">
                                        <select id="accounta_bank_beneficiary" class="form-control" disabled>
                                            <option value="Bank CIMB">CIMB - 123456789 - PT. Berkah Kelola Dana</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="handphone">Jumlah Rp</label>
                                    <div class="col-sm-4">
                                        <input class="form-control numeric" name="jml_topup" id="total" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Top Up harus diisi">
                                    </div>
                                </div>

                                <div class="col-sm-2 text-right">
                                    <input type="hidden" name="bank_destination" value="Bank CIMB">
                                    <input type="hidden" name="Id_pengguna" value="<?php echo $EDIT['Id_pengguna']; ?>">
                                    <input type="hidden" name="memberID" value="<?php echo $EDIT['id_mod_user_member']; ?>">
                                    <br><button type="submit" class="btn btn-primary">Submit</button><br><br>
                                </div>
                            </form>
                        </div>
                </section>
            </div>            
        </div>
    </section>