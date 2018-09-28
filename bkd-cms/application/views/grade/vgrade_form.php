    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> Grade
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" id="formID" method="POST" >

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Grade <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" name="grade" class="form-control" value="<?php echo isset($EDIT['grade_name'])? $EDIT['grade_name'] : ''; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Profile Completeness (%) <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" name="completeness" placeholder="Numeric" class="form-control" value="<?php echo isset($EDIT['completeness_profile'])? $EDIT['completeness_profile'] : ''; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
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