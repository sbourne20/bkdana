<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> User Log </strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	        	<header class="panel-heading">
                    <a class="btn btn-warning btn-sm" href="javascript:;" id="delete_logs"><i class="fa fa-times-circle"></i> Delete All Logs</a>
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <a href="javascript:;" class="fa fa-times"></a>
	                 </span>
	            </header>
                <div class="panel-body">
                <div class="adv-table">
	                <table class="display table table-bordered table-striped" id="dynamic-table">
	                <thead>
		                <tr>
		                    <th>No</th>
		                    <th>Date</th>
		                    <th>Time</th>
		                    <th>User</th>
		                    <th>Activity</th>
		                </tr>
	                </thead>
	                <tbody>
		                
	                </tbody>
	                <tfoot>
		                <tr>
		                    <th>No</th>
		                    <th>Date</th>
		                    <th>Time</th>
		                    <th>User</th>
		                    <th>Activity</th>
		                </tr>
	                </tfoot>
	                </table>
                </div>
                </div>
	        </section>
	    </div>
	</div>

</section>

<script type="text/javascript">
	$('#delete_logs').click(function(event) {

		bootbox.confirm("Delete All User Log ?", function(result) {
        if (result) {
          $.ajax({
                  type: "POST",
                  url: "<?= site_url('user/delete_log') ?>",
                  cache: false,
                  success: function(html){
                      window.location.reload(true);
                  }
          });
          event.preventDefault();
        }
	});
	});
</script>