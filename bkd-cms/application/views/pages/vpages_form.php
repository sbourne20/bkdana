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
	            	<?php 
		            	$fields = array('p_id', 'p_title', 'p_subtitle', 'p_summary', 'p_content', 'p_images', 'p_status', 'p_created_date', 'p_modified_date');
						foreach($fields as $field){
							$EDIT[$field] = isset($EDIT[$field]) ? $EDIT[$field] : $this->session->flashdata($field);
						}
	            	?>
	                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data" id="formID" name="formID">
	                	
	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">Title *</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" value="<?= htmlspecialchars_decode($EDIT['p_title']); ?>" id="title" name="ptitle" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
	                        </div>
	                    </div>

	                    <div class="form-group">
                            <label class="col-sm-2 control-label">Slug (SEO URL)</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="slug" id="slug" value="<?php echo (isset($EDIT['p_slug']))? $EDIT['p_slug'] : ''; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
                            </div>
                        </div>

                        <div class="form-group">
	                        <label class="col-sm-2 control-label">SubTitle</label>
	                        <div class="col-sm-8">
	                            <input type="text" class="form-control" value="<?= htmlspecialchars_decode($EDIT['p_subtitle']); ?>" id="subtitle" name="subtitle" >
	                        </div>
	                    </div>

	                    <?php /*<div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Summary</label>
                            <div class="col-md-8">
                                <textarea name="summary" id="editor2" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['p_summary']); ?></textarea>
                            </div>
                        </div>*/?>

                        <div class="form-group">
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Content</label>
                            <div class="col-md-8">
                                <textarea name="pcontent" id="editor1" class="form-control" rows="9"><?php echo htmlspecialchars_decode($EDIT['p_content']); ?></textarea>
                            </div>
                        </div>

	                    <div class="form-group">
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
		                </div>
	                    
	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">Status</label>
	                        <div class="col-sm-2">
	                            <select class="form-control" name="pstatus">
	                            	<option value="1" <?= (empty($EDIT['p_status']) && $EDIT['p_status'] != 0 || $EDIT['p_status'] == '1' ) ? 'selected="selected"' : ''; ?>>Active</option>
	                            	<option value="0" <?= ($EDIT['p_status'] == '0' && $EDIT['p_status'] !='') ? 'selected="selected"' : ''; ?>>Not Active</option>
	                            </select>
	                        </div>
	                    </div>

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

<script type="text/javascript">
	var ID = "<?php echo $EDIT['p_id']?>";
	var baseimage = "<?php echo $this->config->item('images_pages_uri'); ?>";
	var IMAGES = "<?php echo $EDIT['p_images']?>";

$("#input-99").fileinput({
  <?php if (!empty($EDIT['p_images'])): ?>
  initialPreview: ["<img src='"+baseimage +ID+"/"+IMAGES+"' class='file-preview-image' />"],
  <?php endif; ?>

    allowedFileExtensions: ["jpg", "png", "gif"],
	removeClass: "btn btn-danger",
	removeLabel: "Remove",
	removeIcon: '<i class="fa fa-fw fa-trash-o"></i>',
	maxFileSize: 50,
});
</script>
<script>
  // Enable CKEditor in all environments include mobile device, except IE7 and below.
  if ( window.CKEDITOR && ( !CKEDITOR.env.ie || CKEDITOR.env.version > 7 ) ) CKEDITOR.env.isCompatible = true;
  // Replace the <textarea id="editor1"> with a CKEditor
  CKEDITOR.replace( 'editor1', {
  	filebrowserBrowseUrl: '<?php echo base_url(); ?>static/plugins/filemanager/index.html',
  	height:['400px']
  });
</script>