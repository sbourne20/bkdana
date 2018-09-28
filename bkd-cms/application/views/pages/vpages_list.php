<section class="wrapper">

	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumbs-alt">
                <li>
                    <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
                </li>
                <li>
                    <a class="active-trail current" href="javascript:;"><strong> Pages </strong></a>
                </li>
            </ul>
        </div>
    </div>

	<div class="row">
	    <div class="col-sm-12">
	        <section class="panel">
	            <header class="panel-heading">
                    <a class="btn btn-success btn-sm" href="<?= site_url($this->uri->segment(1).'/add') ?>"><i class="fa fa-plus"></i> New Page</a>
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <a href="javascript:;" class="fa fa-times"></a>
	                 </span>
	            </header>
                <div class="panel-body">
                <div class="adv-table">
	                <table  class="display table table-bordered table-striped" id="dynamic_table">
	                <thead>
		                <tr>
		                    <th>No</th>
		                    <th>Title</th>
		                    <th class="hidden-xs">Image</th>
		                    <th class="hidden-xs">Status</th>
		                    <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
	                </tbody>
	                <tfoot>
		                <tr>
		                    <th>No</th>
		                    <th>Title</th>
		                    <th class="hidden-xs">Image</th>
		                    <th class="hidden-xs">Status</th>
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit </h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal bucket-form" method="post" enctype="multipart/form-data" action="<?php echo site_url($this->uri->segment(1).'/update_type_title'); ?>">
	                	
                		<div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="title" id="title" value="<?php echo $type['ptype_title']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-sm-2 control-label">&nbsp;</label>
	                        <input type="hidden" name="pid" value="<?php echo $type['ptype_id']; ?>">
	                        <button type="submit" class="btn btn-info">Save</button>
	                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        </div>
                </form>

            </div>
            
        </div>
    </div>
</div>