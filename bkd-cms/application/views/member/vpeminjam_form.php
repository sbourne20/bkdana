    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> Peminjam
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" method="POST" id="formID">
                            
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
                                <label class="col-sm-2 control-label">Grade <span class="text-danger">*</span></label>
                                <div class="col-sm-2">
                                    <select name="grade" class="form-control" data-validation-engine="validate[required]" data-errormessage-value-missing="Harus diisi!">
                                        <option value=""> --- Pilih ---</option>
                                        <?php foreach ($grade as $key) { 
                                            $selected= (strtolower($key['grade_name']) == strtolower($EDIT['peringkat_pengguna']) )? 'selected="selected"' : '';
                                        ?>
                                            <option value="<?php echo $key['grade_name']; ?>" <?php echo $selected; ?>> <?php echo $key['grade_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>  

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Member Group<span class="text-danger">*</span></label>
                                <div class="col-sm-2">
                                    <select name="membergroup" class="form-control">
                                         <?php foreach ($membergroup as $key) { 
                                            $selected= (strtolower($key['id_user_group']) == strtolower($EDIT['id_user_group']) )? 'selected="selected"' : '';
                                        ?>
                                            <option value="<?php echo $key['id_user_group']; ?>" <?php echo $selected; ?>> <?php echo $key['user_group_name']; ?></option>
                                        <?php } ?>

<!-- 
                                            <?php 
                                        foreach ($membergroup as $key) {
                                            $selected = '';
                                            if ($key['id_user_group'] == $EDIT['id_user_group'])
                                            {
                                                $selected = 'selected="selected"';
                                            }
                                        ?>
                                        <option value="<?php echo $key['id_user_group']; ?>" <?php echo $selected; ?> ><?php echo $key['user_group_name']; ?></option>
                                        <?php } ?> -->

                                        <!-- <?php foreach ($membergroup as $key) { 
                                            $selected= (strtolower($key['user_name_group']) == strtolower($EDIT['user_name_group']) )? 'selected="selected"' : '';
                                        ?>
                                            <option value="<?php echo $key['user_name_group']; ?>" <?php echo $selected; ?>> <?php echo $key['user_name_group']; ?></option>
                                        <?php } ?> -->
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