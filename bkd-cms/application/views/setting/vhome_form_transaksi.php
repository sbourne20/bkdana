<style type="text/css">
	.file-preview-image { width: 100%;}
	.file-preview-frame, .file-preview-image, .file-preview-other { height: auto !important; }
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
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Transaksi 1</label>
                            <div class="col-md-8">
                                <textarea id="home_1" name="home_1" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['home_8']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Transaksi 2</label>
                            <div class="col-md-8">
                                <textarea id="home_2" name="home_2" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['home_9']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Transaksi 3</label>
                            <div class="col-md-8">
                                <textarea id="home_3" name="home_3" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['home_10']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Transaksi 4</label>
                            <div class="col-md-8">
                                <textarea id="home_4" name="home_4" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['home_11']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Transaksi 5</label>
                            <div class="col-md-8">
                                <textarea id="home_5" name="home_5" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['home_12']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Transaksi 6</label>
                            <div class="col-md-8">
                                <textarea id="home_6" name="home_6" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['home_13']); ?></textarea>
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
	                        <button type="submit" class="btn btn-info">Save</button>
	                        <button type="button" class="btn btn-danger" onclick="history.back(-1)">Cancel</button>
                        </div>
	                </form>
	            </div>
	        </section>
	    </div>
	</div>
	
</section>

<script>
  // Enable CKEditor in all environments include mobile device, except IE7 and below.
  if ( window.CKEDITOR && ( !CKEDITOR.env.ie || CKEDITOR.env.version > 7 ) ) CKEDITOR.env.isCompatible = true;
  // Replace the <textarea id="editor1"> with a CKEditor
  CKEDITOR.replace( 'home_1', {
    height:['200px']
  });
  CKEDITOR.replace( 'home_2', {
  	height:['200px']
  });
  CKEDITOR.replace( 'home_3', {
    height:['200px']
  });
  CKEDITOR.replace( 'home_4', {
    height:['200px']
  });
  CKEDITOR.replace( 'home_5', {
    height:['200px']
  });
  CKEDITOR.replace( 'home_6', {
    height:['200px']
  });

</script>