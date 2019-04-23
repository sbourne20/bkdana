    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <?php echo ($mode==1)? 'ADD' : 'EDIT'; ?> Peminjam
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal form-validation" method="POST" id="formID" name='formpeminjam'>
                            
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
                           <!--  <div class="form-group">
                                <label class="col-sm-2 control-label">id_grup</label>
                                <div class="col-sm-6">
                                    <input type="text" name="id_user_group" class="form-control" value="<?php echo $EDIT['id_user_group'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah member</label>
                                <div class="col-sm-6">
                                    <input type="text" name="count" id="count" class="form-control" value="<?php echo $countgroup1 ?>">                                  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">ID grup js</label>
                                <div class="col-sm-6">
                                    <input type="text" name="count" class="form-control" value="<?php echo $id_grup ?>">                                  
                                </div>
                            </div>
 -->


                            <div class="form-group">
                                <label class="col-sm-2 control-label">Grade <span class="text-danger">*</span></label>
                                <div class="col-sm-2">
                                    <select name="grade" class="form-control" data-validation-engine="validate[required]" data-errormessage-value-missing="Harus diisi!">
                                        <option value="PILIH"> --- Pilih ---</option>
                                        <?php foreach ($grade as $key) { 
                                            $selected= (strtolower($key['grade_name']) == strtolower($EDIT['peringkat_pengguna']) )? 'selected="selected"' : '';
                                        ?>
                                            <option value="<?php echo $key['grade_name']; ?>" <?php echo $selected; ?>> <?php echo $key['grade_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>  

                            <?php
                            $a = $EDIT['mum_type_peminjam'];
                            if($a == '2'){
                            ?>

                           

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Member Group<span class="text-danger">*</span></label>
                                <div class="col-sm-2">
                                    <select name="membergroup" id="membergroup" class="form-control"  onchange="showData()">
                                         <option value="0" readonly> --- Tanpa Grup ---</option>
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

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah member</label>
                                <div class="col-sm-2">
                                    <input type="text" name="count" id="count" class="form-control" value="<?php echo $countgroup ?>"  disabled="disabled">
                                </div>
                            </div>

                            <!--  <div class="form-group">
                                <label class="col-sm-2 control-label">Jumlah orang</label>
                                <div class="col-sm-2">
                                    <input type="text" name="count" id="count" class="form-control" value="<?php echo $jmlmember ?>"  disabled="disabled">
                                </div>
                            </div> -->

                            <!-- <p id="firstP">&nbsp;</p>
                            <p id="secondP">&nbsp;</p>
                            <p id="thirdP">&nbsp;</p> -->

                            <?php
                            }
                            ?>

                             
                            <div class="position-center">
                                <button type="submit" class="btn btn-primary">Submit</button> 
                                <button type="button" class="btn btn-white" id="btn-cancel" onclick="history.back(-1)">Cancel</button>
                            </div>  
                            <!-- <script>document.write(theSelect[theSelect.selectedIndex].value)</script>  -->
                        </form>
                    </div>
                </section>
            </div>            
        </div>
    </section>

<script type="text/javascript">

    function showData() {
        var theSelect = $('#membergroup');
        
        var base_uri = window.location.href;
        
         var pData = {'memberid' : theSelect.val()}
         
        
        $.ajax({
            url: baseURL  + 'peminjam/getgroupcount',  // define here controller then function name
            type: 'POST',
             //method: 'POST',
            //dataType :'json',
            //data : {'secondP1' : secondP}; 
            data: pData,    // pass here your date variable into controller
            success: function(data) {
                //$('#sub_content').html('');
                //$('#sub_content').html(data);
                console.log(data);
                $('#count').val(data);
            },
            error: function(e) {
                //called when there is an error
                //console.log(e.message);
            }
        });

    };
    

     

      /*  $.ajax({
        url:'peminjam.php',
        type:'GET',
        data:{passval:passvalue}, //Pass your varibale in data
        success:function(getreturn){
        alert(getreturn); //you get return value in this varibale, us it anywhere
        }
        });*/

       /* var abcd = "testt";
        $('#coba').text('dafaf'); */
    //}

    //$(".radiocheck:checked").each(function(){
    //check =this.value;
    //switch(check){
    //case '1':
    //$('#tipe_loan_term').text('days');  
    //break;
    //case '2':
    //$('#tipe_loan_term').text('months'); 
    //break; 
    //case '3':
    //$('#tipe_loan_term').text('weeks');
   // break;  
   // }
    //});
</script>

 