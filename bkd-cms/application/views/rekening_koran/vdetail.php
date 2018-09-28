<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail" href="javascript:;"> Rekening Koran </a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> Detail </strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	        	<header class="panel-heading">
                    <i class="fa fa-arrow-right"></i> &nbsp;<?php echo $datamember['Nama_pengguna']; ?>
	                <span class="tools pull-right">
	                        <a href="javascript:;" class="fa fa-chevron-down"></a>
	                        <a href="javascript:;" class="fa fa-cog"></a>
	                        <a href="javascript:;" class="fa fa-times"></a>
                    </span>
                </header>
                <div class="panel-body">
                <div class="adv-table table-responsive">
                	<div class="clearfix">
                                <div class="btn-group">
                                	<form class="form-horizontal row-border">
				                	<div class="form-group">
				                        <label class="control-label col-md-1" style="margin-right: 20px;">Periode</label>
				                        <div class="col-md-6">
				                            <div class="input-group input-large" data-date="13/07/2013" data-date-format="mm/dd/yyyy">
				                                <input type="text" class="form-control default-date-picker" name="from" id="from">
				                                <span class="input-group-addon">s/d</span>
				                                <input type="text" class="form-control default-date-picker" name="to" id="to">
				                            </div>
				                        </div>
				                    </div>
				                    </form>
				                </div>
				            </div>
	                <table class="display table table-bordered table-striped" id="dynamic-table">
	                <thead>
		                <tr>
		                    <th>No</th>
		                    <th>Tanggal</th>
		                    <th>No.Ref</th>
		                    <th>Deskripsi</th>
		                    <th>Debet/Kredit</th>
		                    <th>Nilai</th>
		                    <th>Balance</th>
		                    <th>Balance</th>
		                </tr>
	                </thead>
	                <tbody>
	                </tbody>
	                <tfoot>
		                <tr>
		                    <th>No</th>
		                    <th>Tanggal</th>
		                    <th>No.Ref</th>
		                    <th>Deskripsi</th>
		                    <th>Debet/Kredit</th>
		                    <th>Nilai</th>
		                    <th>Balance</th>
		                    <th>Balance</th>
		                </tr>
	                </tfoot>
	                </table>
                </div>
                </div>
	        </section>
	    </div>
	</div>

</section>

<div id="member_id" data-value="<?php echo $member_id; ?>"></div>

<script type="text/javascript">
(function() {

    $('.default-date-picker').datepicker({
        format: 'dd-mm-yyyy',
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