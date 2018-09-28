<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> Transaction </strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	        	<!-- <header class="panel-heading">
                    <a class="btn btn-success btn-sm" href="<?= site_url('product/add') ?>" title="Add Product"><i class="fa fa-plus"></i> Add</a>
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <a href="javascript:;" class="fa fa-times"></a>
	                 </span>
	            </header> -->
                <div class="panel-body">
                <div class="adv-table">
	                <table class="display table table-bordered table-striped" id="dynamic-table">
	                <thead>
		                <tr>
		                    <th>No</th>
		                    <th>Tanggal</th>
		                    <th>User</th>
		                    <th>Tipe Transaksi</th>
		                    <th>Jumlah</th>
		                    <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
	                </tbody>
	                <tfoot>
		                <tr>
		                    <th>No</th>
		                    <th>Tanggal</th>
		                    <th>User</th>
		                    <th>Tipe Transaksi</th>
		                    <th>Jumlah</th>
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

<!-- Modal window -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Member Detail</h4>
            </div>
            <div id="sub_content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>