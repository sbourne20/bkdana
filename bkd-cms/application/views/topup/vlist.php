<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> Top Up </strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	        	<header class="panel-heading">
                    <a class="btn btn-success btn-sm" href="<?= site_url('Top_up/add_topup') ?>"><i class="fa fa-plus"></i> Manual Top Up</a>
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
		                    <th>No.Transaksi</th>
		                    <th>Nama</th>
		                    <th>Jumlah</th>
		                    <th>Tanggal</th>
		                    <th>Status</th>
		                    <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
	                </tbody>
	                <tfoot>
		                <tr>
		                    <th>No</th>
		                    <th>No.Transaksi</th>
		                    <th>Nama</th>
		                    <th>Jumlah</th>
		                    <th>Tanggal</th>
		                    <th>Status</th>
		                    <th>Action</th>
		                </tr>
	                </tfoot>
	                </table>
                </div>
                </div>
	        </section>
	    </div>
	</div>

</section>