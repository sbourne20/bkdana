<style type="text/css">
	.panel-heading { text-transform:none; }
</style>

<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> <?php echo $PAGE_TITLE; ?></strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	        	<header class="panel-heading">
	        		<u>PINJAMAN</u><br>
	        		<?php echo ($EDIT['ltp_product_title']); ?>
	        		<br>
	        		No.Transaksi : <strong><?php echo ($EDIT['ltp_Master_loan_id']); ?></strong>

	        		<span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
                     </span>
	        	</header>
                <div class="panel-body">
                <div class="adv-table table-responsive">
	                <table class="display table table-bordered table-striped" width="100%">
	                <thead>
		                <tr>
		                    <th>Pinjaman Pokok</th>
		                    <th>Pinjaman disetujui<br>/dicairkan</th>
		                    <th>Repayment</th>
		                    <!-- <th>Frozen Fee</th> -->
		                    <th>Platform Fee </th>
		                    <!-- <th>LO Fee</th> -->
		                    <th>Lender Fee</th>
		                </tr>
	                </thead>
	                <tbody>
	                	<tr>
	                		<td>Rp <?php echo number_format($EDIT['ltp_total_pinjaman']); ?></td>
	                		<td>Rp <?php echo number_format($EDIT['ltp_total_pinjaman_disetujui']); ?></td>
	                		<td>Rp <?php echo number_format($EDIT['ltp_jml_angsuran']); ?></td>
	                		<!-- <td>Rp <?php echo number_format($EDIT['ltp_frozen']); ?></td> -->
	                		<td>Rp <?php echo number_format($EDIT['ltp_platform_fee']); ?></td>
	                		<!-- <td>Rp <?php echo number_format($EDIT['ltp_LO_fee']); ?></td> -->
	                		<td>Rp <?php echo number_format($EDIT['ltp_lender_fee']); ?></td>
	                	</tr>
	                </tbody>
	                </table>
                </div>
                </div>
	        </section>
	    </div>
	</div>

	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	        	<header class="panel-heading">
	        		<u>PENDANA</u>
	        		<span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
                     </span>
	        	</header>
                <div class="panel-body">
                <div class="adv-table table-responsive">
	                <table class="display table table-bordered table-striped" width="100%">
	                <thead>
		                <tr>
		                	<th width="2%">No.</th>
		                    <th>Nama Pendana</th>
		                    <th>Jumlah Pendanaan</th>
		                    <th>Cicilan Pokok</th>
		                    <th>Lender Fee</th>
		                    <th>PPH <?php echo $EDIT['ltp_product_pph']; ?>%</th>
		                    <th>Total Saldo dipotong PPH</th>
		                </tr>
	                </thead>
	                <tbody>
	                	<?php 
	                	if (count($pendana) > 0) {
	                		$i=1;
	                		foreach ($pendana as $pd) {
		                		$namaPendana = $pd['nama_pendana'];
		                		
		                		if (empty($pd['nama_pendana'])) {
		                			$member = $this->Log_transaksi_model->get_user_pendanaan($EDIT['ltp_Master_loan_id']);
		                			$namaPendana = $member['Nama_pengguna'];
		                		}
	                	?>
	                	<tr>
	                		<td><?php echo $i; ?></td>
	                		<td><?php echo html_entity_decode($namaPendana); ?></td>
	                		<td>Rp <?php echo number_format($pd['jml_pendanaan']); ?></td>
	                		<td>Rp <?php echo number_format($pd['cicilan_pokok']); ?></td>
	                		<td>Rp <?php echo number_format($pd['lender_fee']); ?></td>
	                		<td>Rp <?php echo number_format($pd['total_pajak']); ?></td>
	                		<td>Rp <?php echo number_format($pd['total_pendapatan']); ?></td>
	                	</tr>
	                	<?php  
		                	$i=$i+1;
			                }
		                }else{ ?>

		                	<tr>
		                		<td colspan="7" class="text-center"><em>No Data</em></td>
		                	</tr>
		                <?php } ?>
	                </tbody>
	                </table>
                </div>
                </div>
	        </section>
	    </div>
	</div>

	<div class="row">
	    <div class="col-sm-12">
	    	<section class="panel">
		    	<div class="panel-body">
		    		<u>PRODUK</u>
		            <ol type="A">
		                <li>Interest Rate: <?php echo $EDIT['ltp_product_interest_rate']; ?> %</li>
		                <li>Interest Rate Type: <?php echo ($EDIT['ltp_product_interest_rate_type']=='1')? 'Harian' : 'Bulanan'; ?></li>
		                <li>Loan Term: <?php echo $EDIT['ltp_product_loan_term']; ?> Hari</li>
		                <li>Platform Rate: <?php echo $EDIT['ltp_product_platform_rate']; ?> %</li>
		                <li>Loan Organizer: <?php echo $EDIT['ltp_product_loan_organizer']; ?> %</li>
		                <li>Lender Return: <?php echo $EDIT['ltp_product_investor_return']; ?> %</li>
		                <li>Fee Revenue Share: <?php echo $EDIT['ltp_product_revenue_share']; ?> %</li>
		                <li>Secured Loan : <?php echo $EDIT['ltp_product_secured_loan_fee']; ?> %</li>
		            </ol>

		            <u>FORMULA</u>
		            <ol type="1">
		            	<li>Cash Disburse (<i>pinjaman disetujui/dicairkan</i>): <br>
		            		P - (P * H)<br>
		            		<?php 
		            		$cash_label  = $EDIT['ltp_total_pinjaman']. ' - ('. $EDIT['ltp_total_pinjaman'] .' * '.$EDIT['ltp_product_secured_loan_fee']. '%)';
		            		$cash_hitung = $EDIT['ltp_total_pinjaman'] - ($EDIT['ltp_total_pinjaman']*$EDIT['ltp_product_secured_loan_fee']/100);
		            		echo '<i class="fa fa-arrow-right"></i> '. $cash_label .' = <strong>'. number_format($cash_hitung) .'</strong>';
		            		?>
		            	</li>
			            <li>Repayment (<i>Angsuran</i>): <br>
			            	P + (P * C * F) <br>
			               <?php 
			               $totalweeks     = 4 * $EDIT['ltp_product_loan_term'];
			               $formula_label  = $EDIT['ltp_total_pinjaman'].' + ('.$EDIT['ltp_total_pinjaman'].' * '.  $EDIT['ltp_product_loan_term'] .' * '. $EDIT['ltp_product_investor_return'] .'%)';
			               $formula_hitung = ($EDIT['ltp_total_pinjaman'] + (($EDIT['ltp_total_pinjaman']*$EDIT['ltp_product_loan_term']*$EDIT['ltp_product_investor_return'])/100 ));
			               
			               echo '<i class="fa fa-arrow-right"></i> '. $formula_label .' = <strong>'.number_format($formula_hitung) .'</strong>';
			               ?>
			           </li>
			           <!-- <li>Frozen Fee: <br>
			           		P * G <br>
			           		<?php 
			           		$frozen_label = $EDIT['ltp_total_pinjaman'] .' * '.$EDIT['ltp_product_revenue_share'] .'%';
			           		$frozen_hitung = $EDIT['ltp_total_pinjaman'] * $EDIT['ltp_product_revenue_share']/100;
			           		echo '<i class="fa fa-arrow-right"></i> '. $frozen_label .' = <strong>'. number_format($frozen_hitung) .'</strong>';
			           		?>
			           </li> -->
			           <li>
			           	Platform Fee: <br>
			           	P * H <br>

			           	<?php 
			           	$platform_label = $EDIT['ltp_total_pinjaman'] .' * '. $EDIT['ltp_product_secured_loan_fee'] .'%';
			           	$platform_hitung = $EDIT['ltp_total_pinjaman'] * $EDIT['ltp_product_secured_loan_fee']/100;
			           	echo '<i class="fa fa-arrow-right"></i> '. $platform_label . ' = <strong>' .number_format($platform_hitung) . '</strong>';
			           	?>
			           </li>
			           <li>
			           	Lender Fee: <br>
			           	 P * F * C <br>
			           	 <?php 
			           	 $lender_label =  $EDIT['ltp_total_pinjaman'] .' * '. $EDIT['ltp_product_investor_return'] .'%';
			           	 $lender_hitung = (($EDIT['ltp_total_pinjaman'] * $EDIT['ltp_product_investor_return'])/100) * $EDIT['ltp_product_loan_term'];
			           	 echo '<i class="fa fa-arrow-right"></i> '. $lender_label . ' = <strong>' .number_format($lender_hitung) . '</strong>';
			           	 ?>
			           </li>
		           </ol>
		        </div>
		    </section>
    	</div>
    </div>

</section>
