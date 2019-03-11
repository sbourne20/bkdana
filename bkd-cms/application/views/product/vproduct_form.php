    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> PRODUCT
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" method="POST" id="formID">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Type of Business <span class="text-danger">*</span></label>
                                <div class="col-sm-2">
                                    
                                    <select class="form-control" id="type_bisnis" name="type_bisnis" data-validation-engine="validate[required]">
                                      <option value="" > -- Pilih -- </option>
                                    
                                    <?php foreach ($business as $b) {
                                        $selected = ($EDIT['Type_of_business']==$b['type_business_slug'])? 'selected="selected"' : '';
                                    ?>                                        
                                      <option value="<?php echo $b['id_mod_type_business'].'=='. $b['type_business_slug']; ?>" <?php echo $selected; ?> > <?php echo $b['type_business_name']; ?> </option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Title <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" name="title" class="form-control" value="<?php echo isset($EDIT['product_title'])? $EDIT['product_title'] : ''; ?>" data-validation-engine="validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fundraising Period <span class="text-danger">*</span></label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="fundraising_period" class="form-control" value="<?php echo isset($EDIT['Fundraising_period'])? $EDIT['Fundraising_period'] : ''; ?>" data-validation-engine="validate[required]">
                                        <span class="input-group-addon">days</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Product Sector </label>
                                <div class="col-sm-4">
                                    <input type="text" name="product_sector" class="form-control" value="<?php echo isset($EDIT['Product_sector'])? $EDIT['Product_sector'] : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Interest Rate </label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="interest_rate" class="form-control" value="<?php echo isset($EDIT['Interest_rate'])? $EDIT['Interest_rate'] : ''; ?>" data-validation-engine="validate[required]">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Interest Rate Type</label>
                                <label class="checkbox-inline">
                                    <input type="radio" class="radiocheck" name="type_interest_rate" value="1" <?php echo ($mode==2 && $EDIT['type_of_interest_rate']==1)? 'checked="checked"' : ''; ?> > Harian
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" class="radiocheck" name="type_interest_rate" value="3" <?php echo ($mode==2 && $EDIT['type_of_interest_rate']==3)? 'checked="checked"' : ''; ?> > Mingguan
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" class="radiocheck" name="type_interest_rate" value="2" <?php echo ($mode==2 && $EDIT['type_of_interest_rate']==2)? 'checked="checked"' : ''; ?> > Bulanan
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Loan Term </label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="loan_term" class="form-control" value="<?php echo isset($EDIT['Loan_term'])? $EDIT['Loan_term'] : ''; ?>" data-validation-engine="validate[required]">
                                        <span class="input-group-addon" id="tipe_loan_term"> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Max Loan </label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="max_loan" class="form-control" value="<?php echo isset($EDIT['Max_loan'])? $EDIT['Max_loan'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Platform Fee</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="platform_rate" class="form-control" value="<?php echo isset($EDIT['Platform_rate'])? $EDIT['Platform_rate'] : ''; ?>" data-validation-engine="validate[required]">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Loan Organizer</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                    <input type="text" name="loan_organizer" class="form-control" value="<?php echo isset($EDIT['Loan_organizer'])? $EDIT['Loan_organizer'] : ''; ?>">
                                    <span class="input-group-addon">%</span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lender Return</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="investor_return" class="form-control" value="<?php echo isset($EDIT['Investor_return'])? $EDIT['Investor_return'] : ''; ?>" data-validation-engine="validate[required]">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fee Revenue Share</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="fee_revenue_share" class="form-control" value="<?php echo isset($EDIT['Fee_revenue_share'])? $EDIT['Fee_revenue_share'] : ''; ?>">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"> Secured Loan </label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="secured_loan_fee" class="form-control" value="<?php echo isset($EDIT['Secured_loan_fee'])? $EDIT['Secured_loan_fee'] : ''; ?>" >
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">PPH</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="pajak" class="form-control" value="<?php echo isset($EDIT['PPH'])? $EDIT['PPH'] : ''; ?>">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Charge</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="charge" class="form-control" value="<?php echo isset($EDIT['charge'])? $EDIT['charge'] : ''; ?>">
                                        <span>
                                            <select class="form-control" name="charge_type" style="border:1px; background-color:#eeeeee; width:44%; margin-top: -34px; margin-left: 90px;">
                                                <option  value="1" <?php echo ($mode==2 && $EDIT['charge_type']==1)? 'selected="selected"' : ''; ?>>%</option>
                                                <option value="2" <?php echo ($mode==2 && $EDIT['charge_type']==2)? 'selected="selected"' : ''; ?>>IDR</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="col-sm-2 control-label">Charge Type</label>
                                <label class="checkbox-inline">
                                    <input type="radio" class="radiocheck" name="type_interest_rate" value="1" <?php echo ($mode==2 && $EDIT['charge_type']==1)? 'checked="checked"' : ''; ?> > Harian
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" class="radiocheck" name="type_interest_rate" value="3" <?php echo ($mode==2 && $EDIT['charge_type']==3)? 'checked="checked"' : ''; ?> > Mingguan
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" class="radiocheck" name="type_interest_rate" value="2" <?php echo ($mode==2 && $EDIT['charge_type']==2)? 'checked="checked"' : ''; ?> > Bulanan
                                </label>
                            </div> -->

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-2">
                                    <select name="prod_status" class="form-control">
                                        <option value="1" <?php echo ($mode==2 && $EDIT['product_status']==1)? 'selected="selected"' : ''; ?>> Active</option>
                                        <option value="0" <?php echo ($mode==2 && $EDIT['product_status']==0)? 'selected="selected"' : ''; ?>> Not Active</option>
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
<!-- 
    <script type="text/javascript">
        $('#type_bisnis').change(function() {
            var value = $(this).val();
            var split = value.split('==');
            var type = split[0];

            if (type == 1)
            {
                $('#tipe_loan_term').text('days');
            }else{
                $('#tipe_loan_term').text('months');                
            }
        });
    </script> -->



<!-- <script type="text/javascript">
//var value = null;
 $('#type_interest_rate').change(function(){
//$("input[name='category']").change(function() {
    var value = $(this).val();
    var type = value[0];
            if (type = 1)
            {
                $('#tipe_loan_term').text('days');       
            }
            if (type = 2)
            {
                $('#tipe_loan_term').text('weeks');       
            }else{
                $('#tipe_loan_term').text('months');                
            }
});
 </script> -->

 <script type="text/javascript">
var type_interest_rate;
$(':radio[name="type_interest_rate"]').change(function() {
    type_interest_rate=this.value;
             if (type_interest_rate == 1)
            {
                $('#tipe_loan_term').text('days');       
            }
            if (type_interest_rate == 3)
            {
                $('#tipe_loan_term').text('weeks');       
            }
            if(type_interest_rate == 2){
                $('#tipe_loan_term').text('months');                
            }
   // alert(type_interest_rate);
});

$(".radiocheck:checked").each(function(){
    check =this.value;
    switch(check){
    case '1':
    $('#tipe_loan_term').text('days');  
    break;
    case '2':
    $('#tipe_loan_term').text('months'); 
    break; 
    case '3':
    $('#tipe_loan_term').text('weeks');
    break;  
    }
});
 </script>