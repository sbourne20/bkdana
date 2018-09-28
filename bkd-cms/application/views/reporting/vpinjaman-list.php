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
                    SUMMARY REPORT PINJAMAN
	                
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <a href="javascript:;" class="fa fa-times"></a>
	                 </span>
	            </header>
                <div class="panel-body">
                <div class="adv-table table-responsive">
                	
                	<div class="btn-group">
                    	<form class="form-horizontal row-border" method="POST" action="<?php echo site_url('reporting/submit_pinjaman'); ?>">
		                	<div class="form-group">
		                        <label class="control-label col-md-2" style="margin-right: 20px;">Periode</label>
		                        <div class="col-md-6">
		                            <div class="input-group input-large" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
		                                <input type="text" class="form-control default-date-picker" name="from" id="from" value="<?php echo (isset($is_submit))? $is_from : ''; ?>" autocomplete="off">	                                
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
		                    <th><?php echo $bulan; ?></th>
		                </tr>
	                </thead>
	                <tbody>
	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			1. Permohonan Pinjaman	                			
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">a. Jumlah Orang</td>
	                		<td><?php echo $jml_orang['itotal']; ?></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">b. Jumlah Perusahaan</td>
	                		<td></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">c. Jumlah Uang (Rp Juta)</td>
	                		<td><?php echo number_format($jml_pinjaman['sum_pinjaman']); ?></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">d. Rata-rata tertimbang bunga (%)</td>
	                		<td><?php echo $bunga; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">e. Rata-rata tertimbang tenor (%)</td>
	                		<td><?php echo $tenor; ?> %</td>
	                	</tr>

	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			2. Persetujuan Pinjaman	                			
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">a. Jumlah Orang</td>
	                		<td><?php echo $jml_disetujui['itotal_user']; ?></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">b. Jumlah Perusahaan</td>
	                		<td></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">c. Jumlah Uang (Rp Juta)</td>
	                		<td><?php echo number_format($jml_disetujui['itotal_pinjaman']); ?></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">d. Rata-rata tertimbang bunga (%)</td>
	                		<td><?php echo $bunga_disetujui; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">e. Rata-rata tertimbang tenor (%)</td>
	                		<td><?php echo $tenor_disetujui; ?> %</td>
	                	</tr>

	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			3. Status Pinjaman
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">a. Rata-rata status lancar</td>
	                		<td><?php echo $lancar; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">b. Rata-rata status tidak lancar</td>
	                		<td><?php echo $notlancar; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">c. Rata-rata kredit macet</td>
	                		<td><?php echo $macet; ?> %</td>
	                	</tr>

	                	<tr style="font-weight: 800;">
	                		<td colspan="2">
	                			3. Status Akumulasi Pinjaman
	                		</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">a. Jumlah orang</td>
	                		<td><?php echo $jml_orang_ak['itotal']; ?></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">b. Jumlah perusahaan</td>
	                		<td></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">c. Jumlah uang</td>
	                		<td><?php echo number_format($jml_pinjaman_ak['sum_pinjaman']); ?></td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">d. Rata-rata tertimbang tingkat bunga</td>
	                		<td><?php echo $bunga_ak; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">e. Rata-rata tertimbang tenor</td>
	                		<td><?php echo $tenor_ak; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">f. Rata-rata status lancar</td>
	                		<td><?php echo $lancar_ak; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">g. Rata-rata status tidak lancar</td>
	                		<td><?php echo $notlancar_ak; ?> %</td>
	                	</tr>
	                	<tr>
	                		<td style="padding-left: 30px;">h. Rata-rata kredit macet</td>
	                		<td><?php echo $macet_ak; ?> %</td>
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
        format: "mm-yyyy",
    	startView: "months", 
    	minViewMode: "months",
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