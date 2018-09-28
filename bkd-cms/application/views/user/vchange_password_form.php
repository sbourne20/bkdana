    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        Change Your Login Password
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" method="POST" id="formID">
                            <input type="hidden" name="uid" class="form-control" value="<?php echo $CU->id_system_user; ?>">
                            <input type="hidden" name="username" value="<?php echo $CU->username; ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Username</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" value="<?php echo $CU->username; ?>" disabled="disabled">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-6">
                                    <input type="password" name="password" id="password" class="form-control" data-validation-engine="validate[required,minSize[6]]" data-errormessage-value-missing="PASSWORD is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Re-type Password</label>
                                <div class="col-sm-6">
                                    <input type="password" name="password2" class="form-control" data-validation-engine="validate[required,equals[password]]" data-errormessage-value-missing="Re-type PASSWORD here">
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