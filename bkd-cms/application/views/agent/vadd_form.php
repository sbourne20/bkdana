    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> AGENT
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" id="formID" method="POST" >
                            <input type="hidden" name="uid" class="form-control" value="<?php echo (isset($EDIT['id_system_user'])? $EDIT['id_system_user'] : ''); ?>">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" name="oname" class="form-control" value="<?php echo (isset($EDIT['organizer_name'])? $EDIT['organizer_name'] : ''); ?>" data-validation-engine="validate[required]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="oaddress" class="form-control" value="<?php echo (isset($EDIT['organizer_address'])? $EDIT['organizer_address'] : ''); ?>" data-validation-engine="validate[required]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Story <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="ostory" class="form-control" value="<?php echo (isset($EDIT['organizer_story'])? $EDIT['organizer_story'] : ''); ?>" data-validation-engine="validate[required]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mission <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="omission" class="form-control" value="<?php echo (isset($EDIT['organizer_mission'])? $EDIT['organizer_mission'] : ''); ?>" data-validation-engine="validate[required]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Note <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="onote" class="form-control" value="<?php echo (isset($EDIT['organizer_note'])? $EDIT['organizer_note'] : ''); ?>" data-validation-engine="validate[required]">
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