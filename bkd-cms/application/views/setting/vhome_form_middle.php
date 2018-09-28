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
                            <label class="control-label col-md-2" style="padding-top:50px !important;">Home Middle</label>
                            <div class="col-md-8">
                                <textarea id="home_1" name="home_1" class="form-control" rows="5"><?php echo htmlspecialchars_decode($EDIT['home_7']); ?></textarea>
                            </div>
                        </div>
                       
	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">&nbsp;</label>
	                        
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

</script>