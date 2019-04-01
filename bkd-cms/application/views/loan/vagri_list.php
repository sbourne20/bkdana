<section class="wrapper">
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> Pinjaman Agri </strong></a>
                </li>
            </ul>
        </div>
    </div>
	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
                <div class="panel-body">
                <div class="adv-table">
	                <table class="display table table-bordered table-striped" id="dynamic-table" width="100%">
	                <thead>
		                <tr>
		                    <th>No</th>
		                    <th>No.Transaksi</th>
		                    <th>Nama</th>
		                    <th>Product</th>
		                    <th>Jumlah Pinjaman</th>
		                    <th>Jumlah Pinjaman disetujui</th>
		                    <th>Dana Disburse</th>
		                    <th>Hasil Crowd Funding</th>
		                    <th>Tanggal</th>
		                    <th>Crowd Funding</th>
		                    <th>Status</th>
		                    <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
	                </tbody>
	               <!--  <tfoot>
		                <tr>
		                    <th>No</th>
		                    <th>No.Transaksi</th>
		                    <th>Nama</th>
		                    <th>Product</th>
		                    <th>Jumlah Pinjaman</th>
		                    <th>Jumlah Pinjaman disetujui</th>
		                    <th>Tanggal</th>
		                    <th>Crowd Funding</th>
		                    <th>Status</th>
		                    <th>Action</th>
		                </tr>
	                </tfoot> -->
	                </table>
                </div>
                </div>
	        </section>
	    </div>
	</div>

	<div class="row">
	    <div class="col-sm-12">
	        
        	<p>Keterangan Status:</p>
        	<ul>
        		<li><span class="text-warning"><strong>Review</strong></span> = Menunggu Approval oleh Admin CMS.</li>
        		<li><span class="text-primary"><strong>Approve</strong></span> = Sudah di Approve. Menunggu pendanaan.</li>
        		<li><span class="text-success"><strong>Lunas</strong></span> = Transaksi telah diangsur / dibayar secara lunas oleh peminjam</li>
        		<li><span class="text-default"><strong>Expired</strong></span> = Transaksi telah melewati masa <em>Fundraising Period</em> dan pendanaan tidak mencapai 80%</li>
        	</ul>
               
        </div>
    </div>

</section>

<!-- Modal window -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Detail Pinjaman Agri</h4>
            </div>
            <div id="sub_content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>