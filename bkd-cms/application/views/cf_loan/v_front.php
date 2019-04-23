<style type="text/css">
	table tbody td .rowleft { padding-left: 20px; }
</style>

<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> Reporting </strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	        	<header class="panel-heading">
                    CF LOAN
	                
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <a href="javascript:;" class="fa fa-times"></a>
	                 </span>
	            </header>
                <div class="panel-body">
                <div class="adv-table table-responsive">
                	
                	<div class="btn-group">
                    	<form class="form-horizontal row-border" method="POST" action="<?php echo site_url('cf_loan/submit_report'); ?>">
		                	<div class="form-group">

		                        <label class="control-label col-md-2" style="margin-right: 20px;">start</label>
		                        <div class="col-md-6">
		                            <div class="input-group input-large" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
		                                <input type="text" class="form-control default-date-picker" name="from" id="from" value="<?php echo (isset($is_submit))? $is_from : ''; ?>" autocomplete="off">	 
		                                <span class="input-group-addon">s/d</span>
		                                <input type="text" class="form-control default-date-picker" name="to" id="to" value="<?php echo (isset($is_submit))? $is_to : ''; ?>" autocomplete="off">

		                            </div>
		                            <br>
		                            <button class="btn btn-primary">Submit</button>
		                        </div>
		                    </div>
	                    </form>
	                </div>

	                <?php if (count($data)>0) { ?>
	                <table class="display table table-bordered table-striped" id="dynamic-table" width="100%">
	                <thead>
		                <tr>
		                    <th>Uraian</th>
		                    <th><?php echo $bulan; ?>-<?php echo $bulan2; ?></th>
		                    
		                </tr>
	                </thead>
	                <tbody>
	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			1. Disbursement	                			
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">Total Disburse</td>
	                		<td><?php echo number_format($total_disburse['sum_disburse']); ?> IDR</td>
	                	</tr>

	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			2. Principal Payment	                			
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">Total Principal Payment</td>
	                		<td><?php echo number_format($total_principal['sum_amount']); ?> IDR</td>
	                	</tr>
	                	
	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			3. Interest Payment
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">a. Lender</td>
	                		<td><?php echo number_format($total_principal['sum_lender']); ?> IDR</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">b. BKD</td>
	                		<td><?php echo number_format($total_principal['sum_admin']); ?> IDR</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">c. Loan Organizer</td>
	                		<td><?php echo number_format($total_principal['sum_lo']);; ?> IDR</td>
	                	</tr>

	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			4. Upfront Fee
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">Total Upfront Fee</td>
	                		<td><?php echo number_format($total_principal['sum_frozen']); ?> IDR</td>
	                	</tr>

	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			5. Penalty
	                		</td>
	                	</tr>

	                	<tr>
	                		<td style="padding-left: 30px;">Total Penalty</td>
	                		<td><?php echo number_format($total_principal['sum_penalty']); ?> IDR</td>
	                	</tr>
	                	
	                </tbody>
	                </table>
		            
		            <?php } ?>

                </div>
                </div>
	        </section>
	    </div>
	</div>

</section>

<script type="text/javascript">
(function() {

    $('.default-date-picker').datepicker({
        format: "dd-mm-yyyy",
    	startView: "days", 
    	minViewMode: "days",
        autoclose: true,
        todayHighlight: true,
        endDate: "today",
        maxDate: "today",
        Default: {
            horizontal: 'auto',
            vertical: 'auto'
         }
    });
    /*$("#timepicker-24h").timepicker({
      minuteStep: 1,
      showSeconds: false,
      showMeridian: false
    });*/  

    }).call(this);

</script>	