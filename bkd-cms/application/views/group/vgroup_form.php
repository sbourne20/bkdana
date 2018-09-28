    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> GROUP
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" id="formID" method="POST" >

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Group Name <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" name="groupname" class="form-control" value="<?php echo isset($EDIT['group_name'])? $EDIT['group_name'] : ''; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-md-6">
                                  <select name="status" class="form-control" style="width:150px;">
                                    <option value="1" <?php echo (($mode==1 OR ($mode==2 && $EDIT['group_status']==1))? 'selected="selected"' : '') ?> >Active &nbsp; &nbsp;</option>
                                    <option value="0" <?php echo (($mode==2 && $EDIT['group_status']==0)? 'selected="selected"' : '') ?> >Not Active</option>
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