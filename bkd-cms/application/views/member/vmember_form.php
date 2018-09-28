    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($add_mode==1)? 'ADD' : 'EDIT'; ?> MEMBER
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" id="formID">
                            <input type="hidden" name="uid" class="form-control" value="<?php echo (isset($EDIT['id_mod_member'])? $EDIT['id_mod_member'] : ''); ?>">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" value="<?php echo (isset($EDIT['email'])? $EDIT['email'] : ''); ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Harus diisi!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fullname</label>
                                <div class="col-sm-6">
                                    <input type="text" name="fullname" class="form-control" value="<?php echo (isset($EDIT['fullname'])? $EDIT['fullname'] : ''); ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" name="telp" class="form-control" value="<?php echo (isset($EDIT['phone'])? $EDIT['phone'] : ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" name="address" class="form-control" value="<?php echo (isset($EDIT['address'])? $EDIT['address'] : ''); ?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Distributor</label>
                                <div class="col-sm-2">
                                    <?php $isdisributor =  (isset($EDIT['is_distributor'])? $EDIT['is_distributor'] : ''); ?>
                                    <select class="form-control" name="distributor">
                                      <option value="1" <?= ($isdisributor=='1') ? 'selected="selected"' : ''; ?>> Ya </option>
                                      <option value="0" <?= ($isdisributor=='0') ? 'selected="selected"' : ''; ?>> Tidak </option>
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