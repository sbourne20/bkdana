    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($add_mode==1)? 'ADD' : 'EDIT'; ?> MANUAL TOP UP
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" name="mantopup" id="formID" action="<?php echo site_url('Top_up/submit_topup'); ?>">
                            <input type="hidden" name="idgroup" value="">
                            <input type="hidden" name="add_mode" value="">

                      <!--    <div class="form-group">
                                <label class="col-sm-3 control-label">Member yang Dituju</label>
                                <div class="col-sm-6">
                                    <select name="member" class="form-control">
                                         <?php foreach ($member as $key) { 
                                            $selected= (strtolower($key['Id_pengguna']) == strtolower($EDIT['Id_pengguna']) )? 'selected="selected"' : '';
                                        ?>
                                            <option value="<?php echo $key['Id_pengguna']; ?> "data-member="<?php echo $key['Id_pengguna']; ?>" <?php echo $selected; ?>> <?php echo $key['Nama_pengguna']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> -->

                             <div class="form-group">
                                <label class="col-sm-3 control-label">Member yang Dituju</label>
                                <div class="col-sm-6">
                                <select class="form-control" name="member" id="member" data-validation-engine="validate[required]" data-errormessage-value-missing="member harus dipilih!">
                                    <option value=""> -- Pilih Nama Member--</option>
                                    <?php foreach ($member as $key) {
                                    ?>
                                    <option value="<?php echo $key['Id_pengguna'] ?>" data-member="<?php echo $key['Id_pengguna']; ?>"> <?php echo $key['Nama_pengguna']; ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            </div>


                            
                              <div class="form-group">
                                <label class="col-sm-3 control-label" for="darirekening">Rekening Tujuan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="darirekening" id="darirekening" data-validation-engine="validate[required]" >
                                    <option value=""> -- Pilih Rekening --</option>
                                    </select>
                                </div>
                            </div> 

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Dari Rekening</label>
                                <div class="col-sm-6">
                                    <input type="text" name="tujuan" class="form-control" value="" data-validation-engine="validate[required]" data-errormessage-value-missing="Destination Number is required!">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nominal</label>
                                <div class="col-sm-6">
                                    <input type="number" name="nominal" class="form-control" value="" data-validation-engine="validate[required]" data-errormessage-value-missing="Amount is required!">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tanggal</label>
                                    <div class="col-sm-6" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
                                        <input type="text" class="form-control default-date-picker" name="from" id="from">
                                    </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Catatan</label>
                                <div class="col-sm-6">
                                    <input type="text" name="catatan" class="form-control" value="" data-validation-engine="validate[required]" data-errormessage-value-missing="Notes is Required">
                                </div>
                            </div>
                            <!--  <div class="form-group">
                                <label class="col-sm-2 control-label">nomor rekening</label>
                                <div class="col-sm-6">
                                    <input type="text" name="count" class="form-control" value="<?php echo $id_grup ?>">                                  
                                </div>
                            </div> -->
                             
                            <div class="position-center">
                                <button type="submit" class="btn btn-primary">Top up</button> 
                                <button type="button" class="btn btn-white" id="btn-cancel" onclick="history.back(-1)">Cancel</button>
                            </div> 
                               
                        </form>
                    </div>
                </section>
            </div>            
        </div>
    </section>

    <script type="text/javascript">
(function() {

    $('.default-date-picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        endDate: "today",
        maxDate: "today",
        Default: {
            horizontal: 'auto',
            vertical: 'auto'
         }
    });

    }).call(this);

</script>

