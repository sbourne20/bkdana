    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> Type Of Business
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" id="formID" method="POST" >

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Name <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <input type="text" id="title" name="business_name" maxlength="20" class="form-control" value="<?php echo isset($EDIT['type_business_name'])? $EDIT['type_business_name'] : ''; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                    <p class="help-block">Max. 10 characters</p>
                                </div>
                            </div>

                            <input type="hidden" name="slug" id="slug" value="<?php echo (isset($EDIT['type_business_slug']))? $EDIT['type_business_slug'] : ''; ?>">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis</label>
                                <div class="col-md-4">
                                  <select name="category" class="form-control" style="width:150px;" data-validation-engine="validate[required]">
                                    <option value="">--- Pilih ---</option>
                                    <option value="1" <?php echo ($EDIT['type_business_category']==1)? 'selected="selected"' : ''; ?> > Pinjaman </option>
                                    <option value="2" <?php echo ($EDIT['type_business_category']==2)? 'selected="selected"' : ''; ?> > Pendanaan </option>
                                  </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-md-4">
                                  <select name="status" class="form-control" style="width:150px;">
                                    <option value="1" <?php echo (($mode==1 OR ($mode==2 && $EDIT['type_business_status']==1))? 'selected="selected"' : '') ?> >Active &nbsp; &nbsp;</option>
                                    <option value="0" <?php echo (($mode==2 && $EDIT['type_business_status']==0)? 'selected="selected"' : '') ?> >Not Active</option>
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