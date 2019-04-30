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
				<?php
				if ($this->session->userdata('message')) {
					$message_show = $this->session->userdata('message');
					$msg_type = $this->session->userdata('message_type');
					if ($msg_type == 'error') {
						$icon  = 'error_icon.png';
						$color = 'danger';
					} else if ($msg_type == 'success') {
						$icon  = 'success_icon.png';
						$color = 'success';
					} else {
						$icon = 'info_icon.png';
						$color = 'info';
					}
					?>
					<div class="col-sm-12">
						<div class="alert alert-<?php echo $color; ?> text-center"><img src="<?php echo base_url(); ?>assets/images/<?php echo $icon; ?>" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
					</div>
				<?php } ?>

				<div class="col-sm-3">
					<div class="box plain left">
						<?php $this->load->view('version1/v_menu_dashboard'); ?>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="box plain right">
						<div class="content">
							<h1>Top Up</h1>
							<div class="sub-title">Kanal Pembayaran</div>

							<!-- <a data-toggle="modal" data-target="#modalTransferVirtual" href="#" title="Virtual Account">
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="payment-wrapp">
											<div class="row">
												<div class="col-sm-1 col-xs-6 text-center">
													<img src="<?php echo base_url(); ?>assets/images/virtual-account-ico.jpg" class="img-circle">
												</div>
												<div class="col-sm-10 col-xs-6 no-padding">
													<h5 class="title">Virtual Account</h5>
													<small class="text-muted">Top Up via Akun Virtual (Verifikasi Otomatis)</small>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a> -->

							<div class="panel-group" id="accordionTopUp">

								<div class="panel panel-default">
									<div class="panel-heading" data-toggle="collapse" data-parent="#accordionTopUp" href="#collapseTopUp">
										<div class="row">
											<div class="col-md-1">
												<img src="<?php echo base_url(); ?>assets/images/virtual-account-ico.jpg" class="img-circle" style="width:40px;margin-right:5px;">
											</div>
											<div class="col-md-11">
												<h4 class="panel-title">
													Cara Top-Up
												</h4>
												<small>Verifikasi top-up maksimal 1 hari kerja</small>
											</div>
										</div>
									</div>
									<div id="collapseTopUp" class="panel-collapse collapse">
										<div class="panel-body">
											<div class="panel-group" id="accordionBank">
											<div class="panel panel-default">
													<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBank" href="#collapseBCA">
														<h4 class="panel-title">
															Dari BCA
														</h4>
													</div>
													<div id="collapseBCA" class="panel-collapse collapse">
														<div class="panel-body">
															<div class="panel-group" id="accordionBCA">
																<div class="panel panel-default">
																	<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseBCA_ATM">
																		<h4 class="panel-title">
																			ATM
																		</h4>
																	</div>
																	<div id="collapseBCA_ATM" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Masukkan kartu ATM dan PIN BCA Anda</li>
																				<li>Pilih Menu <b>Transaksi Lainnya</b></li>
																				<li>Pilih menu <b>Transfer</b></li>
																				<li>Pilih menu <b>Ke Rek BCA Virtual Account</b></li>
																				<li>Masukkan <b>11069 + [Nomor ponsel anda]</b></li>
																				<li>Masukkan <b>Nominal Top up</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
																<div class="panel panel-default">
																	<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseBCA_mBCA">
																		<h4 class="panel-title">
																			m-BCA
																		</h4>
																	</div>
																	<div id="collapseBCA_mBCA" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Login ke akun <b>m-Bca anda</b></li>
																				<li>Pilih menu <b>m-Transfer</b></li>
																				<li>Pilih menu <b>BCA Virtual Account</b></li>
																				<li>Masukkan <b>11069 + [Nomor ponsel anda]</b></li>
																				<li>Masukkan <b>Nominal Top up</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
																<div class="panel panel-default">
																	<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseBCA_klikBCA">
																		<h4 class="panel-title">
																			Klik BCA
																		</h4>
																	</div>
																	<div id="collapseBCA_klikBCA" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Login ke akun Klik BCA anda</li>
																				<li>Pilih menu <b>Tranfer Dana</b></li>
																				<li>Pilih <b>Tranfer ke BCA Virtual Account</b></li>
																				<li>Masukkan <b>11069 + [Nomor ponsel anda]</b></li>
																				<li>Masukkan <b>Nominal Top up</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- CIMB -->
												<div class="panel panel-default">
													<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBank" href="#collapseCIMB">
														<h4 class="panel-title">
															Dari CIMB
														</h4>
													</div>
													<div id="collapseCIMB" class="panel-collapse collapse">
														<div class="panel-body">
															<div class="panel-group" id="accordionBCA">
																<div class="panel panel-default">
																	<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseCIMB_ATM">
																		<h4 class="panel-title">
																			ATM
																		</h4>
																	</div>
																	<div id="collapseCIMB_ATM" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Masukkan kartu ATM dan PIN CIMB Anda</li>
																				<li>Pilih menu <b>Transfer</b></li>
																				<li>Pilih menu <b>Rekening CIMB/Rekening Ponsel Lain</b></li>
																				<li>Pilih menu <b>Rekening Cimb Niaga Lain</b></li>
																				<li>Masukkan <b>Nominal Top up</b></li>
																				<li>Masukkan <b>[Kode VA CIMB] + [Nomor ponsel anda]</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
																<div class="panel panel-default">
																	<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseCIMB_CIMBClick">
																		<h4 class="panel-title">
																			m-BCA
																		</h4>
																	</div>
																	<div id="collapseCIMB_CIMBClick" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Login ke akun Cimb Cliks anda</li>
																				<li>Pilih menu <b>Transfer</b></li>
																				<li>Pilih <b>Other Account (CIMB Niaga/Rekening Ponsel)</b></li>
																				<li>Pilih <b>Bank CIMB NIAGA</b></li>
																				<li>Masukkan <b>[Kode VA CIMB NIAGA] + [Nomor ponsel anda]</b></li>
																				<li>Masukkan <b>Nominal Top up</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
																<div class="panel panel-default">
																	<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseCIMB_goMobile">
																		<h4 class="panel-title">
																			Klik BCA
																		</h4>
																	</div>
																	<div id="collapseCIMB_goMobile" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Login ke akun Go mobile anda</li>
																				<li>Pilih menu <b>Transfer</b></li>
																				<li>Pilih menu <b>Rekening Cimb Niaga Lain</b></li>
																				<li>Pilih <b>no Rekening Sumber Dana</b></li>
																				<li>Masukkan <b>[Kode VA CIMB NIAGA] + [Nomor ponsel anda]</b></li>
																				<li>Masukkan <b>Nominal Top up</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<!-- Bank Lain -->
												<div class="panel panel-default">
													<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBank" href="#collapseBankLain">
														<h4 class="panel-title">
															Dari Bank Lain
														</h4>
													</div>
													<div id="collapseBankLain" class="panel-collapse collapse">
														<div class="panel-body">
															<div class="panel-group" id="accordionBCA">
																<div class="panel panel-default">
																	<div class="panel-heading" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseBankLain_ATM">
																		<h4 class="panel-title">
																			ATM
																		</h4>
																	</div>
																	<div id="collapseBankLain_ATM" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Masukkan kartu ATM dan pin atm bank anda</li>
																				<li>Pilih menu <b>Transfer</b></li>
																				<li>Pilih menu <b>Transfer Ke Bank Lain</b></li>
																				<li>Pilih bank <b>Cimb niaga / Masukkan kode Bank 022</b></li>
																				<li>Masukkan <b>[Kode VA CIMB NIAGA] + [Nomor ponsel anda]</b></li>
																				<li>Masukkan <b>Nominal Transfer</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
																<div class="panel panel-default">
																	<div class="panel-heading">
																		<h4 class="panel-title" data-toggle="collapse" data-parent="#accordionBCA" href="#collapseBankLain_internetBanking">
																			Internet / Mobile Banking
																		</h4>
																	</div>
																	<div id="collapseBankLain_internetBanking" class="panel-collapse collapse">
																		<div class="panel-body">
																			<ul>
																				<li>Login ke akun Internet/Mobile banking Anda</li>
																				<li>Pilih menu <b>Transfer</b></li>
																				<li>Pilih menu <b>Transfer ka Bank Lain</b></li>
																				<li>Pilih bank <b>Cimb Niaga / Masukkan kode Bank 022</b></li>
																				<li>Masukkan <b>[Kode VA CIMB NIAGA] + [Nomor ponsel anda]</b></li>
																				<li>Masukkan <b>Nominal Transfer</b></li>
																				<li>Ikuti intruksi untuk menyelesaikan transaksi</li>
																			</ul>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

											</div>

										</div>
									</div>
								</div>

							</div>


							<div class="panel panel-default">
								<div class="panel-body">
									<div class="sub-title">Histori Top Up</div>
									<div class="table-responsive">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>No. Transaksi</th>
													<th>Tanggal</th>
													<th>Total</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$useclass = FALSE;
												$inclass  = '';

												if (count($history_topup) > 0) {
													foreach ($history_topup as $tu) {
														if ($useclass) {
															$inclass = 'class="warning"';
														}

														switch ($tu['status_top_up']) {
															case 'approve':
																$status = '<i><span class="text-success">Approve</span></i>';
																break;
															case 'reject':
																$status = '<i><span class="text-danger">Reject</span></i>';
																break;
															case 'pending':
																$status = '<i><span class="text-warning">Pending</span></i>';
																break;

															default:
																$status = '<i><span class="text-primary">' . $tu['status_top_up'] . '</span></i>';
																break;
														}

														if ($tu['payment_status'] == 'settlement') {
															$status = '<i><span class="text-success">Success</span></i>';
														}
														?>
														<tr>
															<td <?php echo $inclass; ?>><?php echo $tu['kode_top_up']; ?></td>
															<td <?php echo $inclass; ?>><?php echo date('d/m/Y', strtotime($tu['tgl_top_up'])); ?></td>
															<td <?php echo $inclass; ?>><?php echo number_format($tu['jml_top_up']); ?></td>
															<td <?php echo $inclass; ?>> <?php echo $status; ?></td>
														</tr>
														<?php
														if ($useclass === FALSE) {
															$useclass = TRUE;
														} else {
															$useclass = FALSE;
															$inclass  = '';
														}
													}
												} else { ?>
													<tr>
														<td colspan="4" class="text-center"><em>Tidak ada data</em></td>
													</tr>
												<?php } ?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<?php
/*
<!-- Modal Top Up Virtual Account -->
<div class="modal fade" id="modalTransferVirtual" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-register">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Form Top Up - Virtual Account</h2><br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-offset-1 col-sm-9">
                            <form class="text-left form-validation" id="form_topup_oto" method="POST" action="<?php echo site_url('top_up/submit_auto'); ?>">
<div class="form-group">
	<label for="fullname">* Nama Rekening Anda</label>
	<input class="form-control" name="account_bank_name" id="account_bank_name" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Nama Rekening harus diisi">
</div>
<div class="form-group">
	<label for="accounta_bank_no">* Nomor Rekening Anda</label>
	<input class="form-control" name="account_bank_number" id="account_bank_number" type="text" value="<?php echo $memberdata['Nomor_rekening']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Nomor harus diisi">
</div>
<div class="form-group">
	<label for="bank">* Bank Anda</label>
	<select class="form-control" name="my_bank_name" id="my_bank_name" data-validation-engine="validate[required]" data-errormessage-value-missing="Bank harus diisi">
		<option value="" selected="selected">- Pilih Bank -</option>
		<option value="Bank Mandiri" <?php echo ($memberdata['nama_bank']=='Bank Mandiri')? 'selected="selected"' : ''; ?>>Bank Mandiri</option>
		<option value="Bank BNI 46" <?php echo ($memberdata['nama_bank']=='Bank BNI 46')? 'selected="selected"' : ''; ?>>Bank BNI 46</option>
		<option value="Bank BRI" <?php echo ($memberdata['nama_bank']=='Bank BRI')? 'selected="selected"' : ''; ?>>Bank BRI</option>
		<option value="Bank BCA" <?php echo ($memberdata['nama_bank']=='Bank BCA')? 'selected="selected"' : ''; ?>>Bank BCA</option>
		<option value="Bank CIMB" <?php echo ($memberdata['nama_bank']=='Bank CIMB')? 'selected="selected"' : ''; ?>>Bank CIMB</option>
	</select>
</div>
<div class="form-group">
	<label for="accounta_bank_beneficiary">* Bank Tujuan</label>
	<select id="accounta_bank_beneficiary" class="form-control" disabled>
		<option value="Bank CIMB">CIMB - 123456789 - PT. Berkah Kelola Dana</option>
	</select>
</div>
<div class="form-group">
	<label for="handphone">* Jumlah</label>
	<input class="form-control numeric" name="jml_topup" id="total" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Top Up harus diisi">
</div>

<input type="hidden" name="bank_destination" value="Bank CIMB">
<br><button type="button" id="submit_topup_oto" class="btn btn-purple">Submit</button>
<img id="img_loading_oto" src="<?php echo base_url(); ?>assets/images/loading-text.gif" style="width: 75px; display: none;">
<br><br>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div> */
?>
