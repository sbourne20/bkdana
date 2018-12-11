<!-- <style type="text/css">
	.file-preview-image { width: 100%;}
	.file-preview-frame, .file-preview-image, .file-preview-other { height: auto !important; }
</style> -->

<!-- <?php 
//$uri1= $this->uri->segment(1);
?> -->

<section class="wrapper">

	<div class="row">
	    <div class="col-lg-12">
	        <section class="panel">
	            <header class="panel-heading">
	                <?php echo ($add_mode==1)? 'ADD' : 'EDIT'; ?> Pinjaman Mikro
	                <button type="button" onclick="history.back(-1)" style="margin-top: -4px;" class="btn btn-danger btn-sm pull-right">Back</button>
	            </header>
	            <div class="panel-body">
	            	<!-- <?php 
		            	$fields = array('p_id', 'p_title', 'p_subtitle', 'p_summary', 'p_content', 'p_images', 'p_status', 'p_created_date', 'p_modified_date');
						foreach($fields as $field){
							$EDIT[$field] = isset($EDIT[$field]) ? $EDIT[$field] : $this->session->flashdata($field);
						}
	            	?> -->
	                <form class="form-horizontal form-validation" method="post" id="formID" name="formID">
	                	 <!-- action="<?php echo site_url('transaksi-pinjaman-mikro/submit_edit'); ?>" -->

	                	
	                	<div class="form-group">
	                        <label class="col-sm-3 control-label">User ID</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="user_id" value="<?php echo $data['User_id']; ?>" readonly="readonly">
	                        </div>
	                    </div>
	                	<div class="form-group">
	                        <label class="col-sm-3 control-label">No.Transaksi</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="transaksi_id" value="<?php echo $data['Master_loan_id']; ?>" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">Nama</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="nama_pengguna" value="<?php echo $data['nama_peminjam']; ?>" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">Product</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="produk" value="<?php echo $data['product_title']; ?>" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">Jumlah Pinjaman</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="jumlah_pinjaman" value="Rp <?php echo number_format($data['Jml_permohonan_pinjaman']); ?>" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">Jumlah Pinjaman Disetujui</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="Jml_permohonan_pinjaman_disetujui" value="<?php
	                         /*   $pinjaman_awal =  $data['Jml_permohonan_pinjaman'];
	                            $pinjaman_disetujui =  $data['Jml_permohonan_pinjaman_disetujui'];
	                            if($pinjaman_disetujui > $pinjaman_awal){
	                            	echo "<script>alert('same message');</script>";
	                            }else{*/
	                             echo number_format($data['Jml_permohonan_pinjaman_disetujui']); 
								//}
	                             ?>">
	                        	
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">Hasil Crowd Funding</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="hasil_cf" value="<?php echo $data['jml_kredit']; ?>" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">Tanggal</label>
	                        <div class="col-sm-6">
	                           <input type="text" class="form-control datepicker-dob" name="tgl_pinjam" id="tgl_pinjam" value="<?php echo $data['Tgl_permohonan_pinjaman']; ?>" readonly="readonly">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">Crowd Funding</label>
	                        <div class="col-sm-6">
	                            <input type="text" class="form-control" name="cf" value="<?php 
	                    	 		$pinjaman =  $data['Jml_permohonan_pinjaman_disetujui'];
                                    $kredit  = $data['jml_kredit'];
                                    $kuota = ($kredit/$pinjaman)*100; echo round($kuota);?> %"  readonly="readonly">
	                        </div>
	                    </div>
	                    	<!-- ".$_GET['width']."

	                    	 $kredit =  $data['Jml_permohonan_pinjaman'];
                                      $pinjaman  = $data['Jml_permohonan_pinjaman_disetujui'];
                                      $kuota = ($kredit/$pinjaman)*100 -->



	                    <div class="form-group">
	                        <label class="col-sm-3 control-label">&nbsp;</label>
<!-- 
	                        <input type="hidden" name="pinjaman_pokok" value="<?php echo $EDIT['Jml_permohonan_pinjaman']; ?>">
	                        <input type="hidden" name="pinjaman_disetujui_awal" value="<?php echo $EDIT['Jml_permohonan_pinjaman_disetujui']; ?>"> -->

	                        <button type="submit" class="btn btn-info">Save</button>
	                        <button type="button" class="btn btn-danger" onclick="history.back(-1)">Cancel</button>
                        </div>
	                </form>
	            </div>
	        </section>
	    </div>
	</div>
	
</section>