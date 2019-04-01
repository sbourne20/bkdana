<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> User Management </strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	            <header class="panel-heading">
                    <a class="btn btn-success btn-sm" href="<?= site_url('user/add') ?>"><i class="fa fa-plus"></i> Add User</a>
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
		                    <th>Username</th>
		                    <th class="hidden-xs">Email</th>
		                    <th class="hidden-xs">Privilege</th>
		                    <th>Active</th>
		                    <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
		                
	                </tbody>
	                <!-- <tfoot>
		                <tr>
		                    <th>No</th>
		                    <th>Username</th>
		                    <th class="hidden-xs">Email</th>
		                    <th class="hidden-xs">Privilege</th>
		                    <th>Active</th>
		                    <th>Action</th>
		                </tr>
	                </tfoot> -->
	                </table>
                </div>
                </div>
	        </section>
	    </div>
	</div>

</section>