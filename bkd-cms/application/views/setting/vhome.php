<style type="text/css">
	.file-preview-image { width: 100%;}
	.file-preview-frame, .file-preview-image, .file-preview-other { height: auto !important; }
	.well h2, h3 {font-size: 15px !important; font-weight: 600; color: #202020;}
</style>

<?php 
$uri1= $this->uri->segment(1);
?>

<section class="wrapper">

	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	            <header class="panel-heading">
	                <?= $PAGE_TITLE ?>
	                <button type="button" onclick="history.back(-1)" style="margin-top: -4px;" class="btn btn-danger btn-sm pull-right">Back</button>
	            </header>
	            <div class="panel-body">
	            	
	                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data" id="formID" name="formID">

                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home 1</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_1']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home 2</label>
                            <div class="col-md-8 well">
                                <?php echo htmlspecialchars_decode($EDIT['home_2']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home 3</label>
                            <div class="col-md-8 well">
                                <?php echo htmlspecialchars_decode($EDIT['home_3']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home 4</label>
                            <div class="col-md-8 well">
                                <?php echo htmlspecialchars_decode($EDIT['home_4']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home 5</label>
                            <div class="col-md-8 well">
                                <?php echo htmlspecialchars_decode($EDIT['home_5']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home 6</label>
                            <div class="col-md-8 well">
                                <?php echo htmlspecialchars_decode($EDIT['home_6']); ?>
                            </div>
                        </div>

	                    <!-- <div class="form-group">
		                    <label class="control-label col-md-2">Image</label>
		                    <div class="col-md-8">
		                      <div class="form-group" style="margin-left:1px;">
		                          <input id="input-99" type="file" class="file" data-show-upload="false" data-show-caption="true" name="imgfile">
		                          <span class="help-block"><p><span class="label label-danger">NOTE!</span></p>
	                            		<span>* Maximum size: <strong>50 Kb</strong></span><br />
	                            		<span>* File extension: <strong>JPEG (.jpg), PNG (.png)</strong></span><br />
	                            	</span>
		                      </div>
		                    </div>
		                </div> -->
	                    

	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">&nbsp;</label>
	                        <input type="hidden" name="pid" value="<?php echo (empty($EDIT['p_id']))? '' : $EDIT['p_id']; ?>">
	                        <input type="hidden" name="pcreated" value="<?php echo (empty($EDIT['p_created_date']))? '' : $EDIT['p_created_date']; ?>">
	                        <a href="<?php echo site_url('setting/edit/1'); ?>"><button type="button" class="btn btn-info">EDIT</button></a>
                        </div>
	                </form>
	            </div>
	        </section>
	    </div>
	</div>

	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	            <header class="panel-heading">
	                HOME MIDDLE
	            </header>
	            <div class="panel-body">
	            	
	                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data" id="formID" name="formID">

                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Middle</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_7']); ?>
                            </div>
                        </div>
	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">&nbsp;</label>
	                        <input type="hidden" name="pid" value="<?php echo (empty($EDIT['p_id']))? '' : $EDIT['p_id']; ?>">
	                        <input type="hidden" name="pcreated" value="<?php echo (empty($EDIT['p_created_date']))? '' : $EDIT['p_created_date']; ?>">
	                        <a href="<?php echo site_url('setting/edit_home_middle/1'); ?>"><button type="button" class="btn btn-info">EDIT</button></a>
                        </div>
	                </form>
	            </div>
	        </section>
	    </div>
	</div>

	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	            <header class="panel-heading">
	                HOME LIST TRANSAKSI
	            </header>
	            <div class="panel-body">
	            	
	                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data" id="formID" name="formID">

                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Transaksi 1</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_8']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Transaksi 2</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_9']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Transaksi 3</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_10']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Transaksi 4</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_11']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Transaksi 5</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_12']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Transaksi 6</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_13']); ?>
                            </div>
                        </div>
	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">&nbsp;</label>
	                        <input type="hidden" name="pid" value="<?php echo (empty($EDIT['p_id']))? '' : $EDIT['p_id']; ?>">
	                        <input type="hidden" name="pcreated" value="<?php echo (empty($EDIT['p_created_date']))? '' : $EDIT['p_created_date']; ?>">
	                        <a href="<?php echo site_url('setting/edit_home_transaksi/1'); ?>"><button type="button" class="btn btn-info">EDIT</button></a>
                        </div>
	                </form>
	            </div>
	        </section>
	    </div>
	</div>

	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	            <header class="panel-heading">
	                HOME BOTTOM
	            </header>
	            <div class="panel-body">
	            	
	                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data" id="formID" name="formID">

                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Bottom Left</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_14']); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Bottom Right</label>
                            <div class="col-md-8 well">
                                <?php echo html_entity_decode($EDIT['home_15']); ?>
                            </div>
                        </div>
	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">&nbsp;</label>
	                        <input type="hidden" name="pid" value="<?php echo (empty($EDIT['p_id']))? '' : $EDIT['p_id']; ?>">
	                        <input type="hidden" name="pcreated" value="<?php echo (empty($EDIT['p_created_date']))? '' : $EDIT['p_created_date']; ?>">
	                        <a href="<?php echo site_url('setting/edit_home_bottom/1'); ?>"><button type="button" class="btn btn-info">EDIT</button></a>
                        </div>
	                </form>
	            </div>
	        </section>
	    </div>
	</div>
	
</section>