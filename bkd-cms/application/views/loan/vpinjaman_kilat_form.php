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
	                <form class="form-horizontal bucket-form" method="post" id="formID" name="formID" action="<?php echo site_url('transaksi-pinjaman-kilat-draft/submit_edit'); ?>">
	                	
	                	<div class="form-group">
	                        <label class="col-sm-2 control-label">No.Transaksi</label>
	                        <div class="col-sm-4">
	                            <input type="text" class="form-control" name="transaksi_id" value="<?php echo $EDIT['Master_loan_id']; ?>" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">Permohonan Pinjaman</label>
	                        <div class="col-sm-4">
	                            <input type="text" class="form-control" value="<?php echo number_format($EDIT['Jml_permohonan_pinjaman']); ?>" readonly="readonly">
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">Pinjaman Disetujui</label>
	                        <div class="col-sm-4">
	                            <input type="text" class="form-control numeric" value="<?php echo ($EDIT['Jml_permohonan_pinjaman_disetujui']); ?>" id="pinjaman_disetujui" name="pinjaman_disetujui" data-validation-engine="validate[required]" data-errormessage-value-missing="This field is required!">
	                        </div>
	                    </div>

	                    <div class="form-group">
	                        <label class="col-sm-2 control-label">&nbsp;</label>

	                        <input type="hidden" name="pinjaman_pokok" value="<?php echo $EDIT['Jml_permohonan_pinjaman']; ?>">
	                        <input type="hidden" name="pinjaman_disetujui_awal" value="<?php echo $EDIT['Jml_permohonan_pinjaman_disetujui']; ?>">

	                        <button type="submit" class="btn btn-info">Save</button>
	                        <button type="button" class="btn btn-danger" onclick="history.back(-1)">Cancel</button>
                        </div>
	                </form>
	            </div>
	        </section>
	    </div>
	</div>
	
</section>