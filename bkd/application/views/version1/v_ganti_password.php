<style type="text/css">
	.fileinput-remove-button, .btn-file { line-height: 0.9; position: relative;top: -1px !important; }
	.section-member-page .content .panel .counter, .section-member-page .content .panel .no-counter, .section-member-page .content .panel span { font-size: 13px; color:#FFF; }
</style>

<!-- Header -->
<header class="overflow-wrapp">
	<div class="container">
		
		<?php $this->load->view('common/navigation_all'); ?>
		
	</div>
</header>

<!-- Content -->
<div class="wrapper-content overflow-wrapp">
	<div class="section-member-page">
		
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="box plain left">
						<?php $this->load->view('version1/v_menu_dashboard'); ?>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="box plain right">
						<div class="content">
							<h1><?php echo $page_title; ?></h1>
							<div class="sub-title">Ubah Password Anda</div>
							<div class="panel panel-default">
								<div class="panel-body">
									<?php 
									if ($this->session->userdata('message')) {
										$message_show = $this->session->userdata('message');
										$msg_type = $this->session->userdata('message_type');
										if ($msg_type == 'error') {
											$icon  = 'error_icon.png';
											$color = 'danger';
										}else if ($msg_type == 'success') {
											$icon  = 'success_icon.png';
											$color = 'success';
										}else{
											$icon = 'info_icon.png';
											$color = 'info';
										}
									?>
									<div class="col-sm-12">
									<div class="alert alert-<?php echo $color; ?> text-center"><img src="<?php echo base_url(); ?>assets/images/<?php echo $icon; ?>" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
									</div>
									<?php } ?>

									<form class="form-validation" method="POST" enctype="multipart/form-data" action="<?php echo site_url('submit-ubah-password'); ?>">
										<div class="form-group">
											<label for="oldpassword">* Password Lama</label>
											<input class="form-control" id="oldpassword" name="oldpassword" type="password" >
										</div>
										<div class="form-group">
			                                <label for="newpassword"> New Password</label>
			                                <input class="form-control" id="newpassword" type="password" name="newpassword" data-validation-engine="validate[required, minSize[6]]" 
			                                data-errormessage-value-missing="Minimal 6 karakter"
			                                data-errormessage-range-underflow="min[6]" autofocus>
			                                <p class="help-block">* Minimal 6 karakter</p>
			                            </div>
			                            <div class="form-group">
			                                <label for="confirmpassword"> Re-type New Password</label>
			                                <input class="form-control" id="confirmpassword" type="password" name="confirmpassword" data-validation-engine="validate[required, equals[newpassword]]" 
			                                data-errormessage-value-missing="Ketik ulang password disini" autofocus>
			                            </div>
																					
										<button type="submit" class="btn btn-blue">Submit</button> &nbsp; 
										<button type="button" class="btn btn-default" onclick="window.history.go(-1); return false;">Cancel</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>