    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($add_mode==1)? 'ADD' : 'EDIT'; ?> GROUP
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" id="formID" action="<?php echo site_url('user/submit_group'); ?>">
                            <input type="hidden" name="idgroup" value="<?php  echo (isset($EDIT['id_group'])? $EDIT['id_group'] : ''); ?>">
                            <input type="hidden" name="add_mode" value="<?php  echo $add_mode; ?>">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Group Name</label>
                                <div class="col-sm-6">
                                    <input type="text" name="gname" class="form-control" value="<?php echo (isset($EDIT['group_name'])? $EDIT['group_name'] : ''); ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Group Name is required!">
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