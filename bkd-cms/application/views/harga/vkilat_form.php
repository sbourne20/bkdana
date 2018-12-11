    <section class="wrapper">
        <div class="row">
            <div class="col-lg-7">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> Harga Produk
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" id="formID" method="POST" >
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Jumlah Rupiah <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" name="harga" class="form-control numeric" value="<?php echo isset($EDIT['h_harga'])? $EDIT['h_harga'] : ''; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tenor <span class="text-danger">*</span><br>(Product Kilat)</label>
                                <div class="col-md-6">
                                  <select multiple name="product[]" id="select2" class="populate select2" style="width:250px;" data-validation-engine="validate[required]">
                                    <?php foreach ($products as $p) {
                                        $selected = '';
										$type_interest_rate = $p['type_of_interest_rate'];
                                        switch($type_interest_rate){
                                            case '1':
                                            //$('#tipe_loan_term').text('days');  
                                            $label_tenor = 'hari';
                                            break;
                                            case '2':
                                             $label_tenor = 'bulan';
                                           // $('#tipe_loan_term').text('months'); 
                                            break; 
                                            case '3':
                                              $label_tenor = 'minggu';
                                            //$('#tipe_loan_term').text('weeks');
                                            break; 
                                        }
                                        if (count($relasi_hp)>0){
                                            foreach ($relasi_hp as $hh) {
                                                
                                                if ($hh['hp_product_id'] == $p['Product_id']) {
                                                    $selected = 'selected="selected"';
                                                    break;
                                                }else{
                                                    
                                                }
                                            }
                                        }
                                    ?>
                                    <option value="<?php echo $p['Product_id']; ?>" <?php echo $selected; ?>> <?php echo $p['Loan_term'].' '.$label_tenor; ?> </option>
                                    <?php 
                                    } ?>
                                  </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-3">
                                    <select name="status" class="form-control">
                                        <option value="1" <?php echo ($mode==2 && $EDIT['h_status']==1)? 'selected="selected"' : ''; ?>> Active</option>
                                        <option value="0" <?php echo ($mode==2 && $EDIT['h_status']==0)? 'selected="selected"' : ''; ?>> Not Active</option>
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