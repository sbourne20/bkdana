    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        EDIT ACCESS ROLE
                    </header>
                    <div class="panel-body">
                    <form id="setting_frm" action="<?= site_url('user/setting_role') ?>" class="form-horizontal" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Role Name</label>
                        <div class="col-sm-4">
                            <input type="text" value="<?= $group['group_name'] ?>" readonly="readonly" class="form-control">
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-info" id="checkAll">Check All</button>
                    <button type="button" class="btn btn-sm btn-danger" id="uncheckAll">Uncheck All</button>

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="head0">Module</th>
                                <th class="head0" colspan="4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="gradeX">
                                <td>
                                    User Management
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/management]" value="user/management" <?php echo (check_role_access($EDIT, 'user/management')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/add]" value="user/add" <?php echo (check_role_access($EDIT, 'user/add')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/edit]" value="user/edit" <?php echo (check_role_access($EDIT, 'user/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/delete]" value="user/delete" <?php echo (check_role_access($EDIT, 'user/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>
                            <tr class="gradeX">
                                <td>
                                    User Group
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/group]" value="user/group" <?php echo (check_role_access($EDIT, 'user/group')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/add_group]" value="user/add_group" <?php echo (check_role_access($EDIT, 'user/add_group')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/setting_role]" value="user/setting_role" <?php echo (check_role_access($EDIT, 'user/setting_role')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[user/delete_group]" value="user/delete_group" <?php echo (check_role_access($EDIT, 'user/delete_group')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>
                            <tr class="gradeX">
                                <td>
                                    Member - Peminjam
                                </td>
                                <td>
                                    <input type="checkbox" name="access[peminjam/index]" value="peminjam/index" <?php echo (check_role_access($EDIT, 'peminjam/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[peminjam/detail]" value="peminjam/detail" <?php echo (check_role_access($EDIT, 'peminjam/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[peminjam/edit]" value="peminjam/edit" <?php echo (check_role_access($EDIT, 'peminjam/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[peminjam/delete]" value="peminjam/delete" <?php echo (check_role_access($EDIT, 'peminjam/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Member - Pendana
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pendana/index]" value="pendana/index" <?php echo (check_role_access($EDIT, 'pendana/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pendana/detail]" value="pendana/detail" <?php echo (check_role_access($EDIT, 'pendana/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pendana/edit]" value="pendana/edit" <?php echo (check_role_access($EDIT, 'pendana/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pendana/delete]" value="pendana/delete" <?php echo (check_role_access($EDIT, 'pendana/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pendana/activate]" value="pendana/activate" <?php echo (check_role_access($EDIT, 'pendana/activate')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Activate
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Product
                                </td>
                                <td>
                                    <input type="checkbox" name="access[product/index]" value="product/index" <?php echo (check_role_access($EDIT, 'product/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[product/add]" value="product/add" <?php echo (check_role_access($EDIT, 'product/add')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[product/edit]" value="product/edit" <?php echo (check_role_access($EDIT, 'product/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[product/delete]" value="product/delete" <?php echo (check_role_access($EDIT, 'product/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                                <td>
                                    <input type="checkbox" name="access[product/detail]" value="product/detail" <?php echo (check_role_access($EDIT, 'product/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Detail
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Type Of Business
                                </td>
                                <td>
                                    <input type="checkbox" name="access[type_business/index]" value="type_business/index" <?php echo (check_role_access($EDIT, 'type_business/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[type_business/add]" value="type_business/add" <?php echo (check_role_access($EDIT, 'type_business/add')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[type_business/edit]" value="type_business/edit" <?php echo (check_role_access($EDIT, 'type_business/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Grade User
                                </td>
                                <td>
                                    <input type="checkbox" name="access[grade_user/index]" value="grade_user/index" <?php echo (check_role_access($EDIT, 'grade_user/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[grade_user/add]" value="grade_user/add" <?php echo (check_role_access($EDIT, 'grade_user/add')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[grade_user/edit]" value="grade_user/edit" <?php echo (check_role_access($EDIT, 'grade_user/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[grade_user/delete]" value="grade_user/delete" <?php echo (check_role_access($EDIT, 'grade_user/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Transaksi DRAFT - Pinjaman Kilat
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-kilat-draft/index]" value="transaksi-pinjaman-kilat-draft/index" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-kilat-draft/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-kilat-draft/detail]" value="transaksi-pinjaman-kilat-draft/detail" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-kilat-draft/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Detail
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-kilat-draft/approve]" value="transaksi-pinjaman-kilat-draft/approve" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-kilat-draft/approve')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Approve
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-kilat-draft/edit]" value="transaksi-pinjaman-kilat-draft/edit" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-kilat-draft/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-kilat-draft/reject]" value="transaksi-pinjaman-kilat-draft/reject" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-kilat-draft/reject')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Reject
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-kilat-draft/delete]" value="transaksi-pinjaman-kilat-draft/delete" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-kilat-draft/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Transaksi DRAFT - Pinjaman Mikro
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-mikro-draft/index]" value="transaksi-pinjaman-mikro-draft/index" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-mikro-draft/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-mikro-draft/detail]" value="transaksi-pinjaman-mikro-draft/detail" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-mikro-draft/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Detail
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-mikro-draft/approve]" value="transaksi-pinjaman-mikro-draft/approve" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-mikro-draft/approve')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Approve
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-mikro-draft/edit]" value="transaksi-pinjaman-mikro-draft/edit" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-mikro-draft/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-mikro-draft/reject]" value="transaksi-pinjaman-mikro-draft/reject" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-mikro-draft/reject')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Reject
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi-pinjaman-mikro-draft/delete]" value="transaksi-pinjaman-mikro-draft/delete" <?php echo (check_role_access($EDIT, 'transaksi-pinjaman-mikro-draft/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Transaksi Pinjaman Kilat
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_kilat/index]" value="transaksi_pinjaman_kilat/index" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_kilat/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_kilat/detail]" value="transaksi_pinjaman_kilat/detail" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_kilat/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Detail
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_kilat/approve]" value="transaksi_pinjaman_kilat/approve" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_kilat/approve')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Approve
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_kilat/reject]" value="transaksi_pinjaman_kilat/reject" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_kilat/reject')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Reject
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_kilat/delete]" value="transaksi_pinjaman_kilat/delete" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_kilat/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>
                            <tr class="gradeX">
                                <td>
                                    Transaksi Pinjaman Mikro
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_mikro/index]" value="transaksi_pinjaman_mikro/index" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_mikro/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_mikro/detail]" value="transaksi_pinjaman_mikro/detail" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_mikro/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Detail
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_mikro/approve]" value="transaksi_pinjaman_mikro/approve" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_mikro/approve')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Approve
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_mikro/reject]" value="transaksi_pinjaman_mikro/reject" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_mikro/reject')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Reject
                                </td>
                                <td>
                                    <input type="checkbox" name="access[transaksi_pinjaman_mikro/delete]" value="transaksi_pinjaman_mikro/delete" <?php echo (check_role_access($EDIT, 'transaksi_pinjaman_mikro/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>
                            <tr class="gradeX">
                                <td>
                                    Transaksi Pendanaan
                                </td>
                                <td>
                                    <input type="checkbox" name="access[invest/index]" value="invest/index" <?php echo (check_role_access($EDIT, 'invest/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[invest/detail]" value="invest/detail" <?php echo (check_role_access($EDIT, 'invest/detail')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Detail
                                </td>
                                <td>
                                    <input type="checkbox" name="access[invest/verify]" value="invest/verify" <?php echo (check_role_access($EDIT, 'invest/verify')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Verifikasi
                                </td>
                                <td>
                                    <input type="checkbox" name="access[invest/delete]" value="invest/delete" <?php echo (check_role_access($EDIT, 'invest/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>

                            <tr class="gradeX">
                                <td>
                                    Agent
                                </td>
                                <td>
                                    <input type="checkbox" name="access[agent/index]" value="agent/index" <?php echo (check_role_access($EDIT, 'agent/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[agent/add]" value="agent/add" <?php echo (check_role_access($EDIT, 'agent/add')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Add
                                </td>
                                <td>
                                    <input type="checkbox" name="access[agent/edit]" value="agent/edit" <?php echo (check_role_access($EDIT, 'agent/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; edit
                                </td>
                                <td>
                                    <input type="checkbox" name="access[agent/delete]" value="agent/delete" <?php echo (check_role_access($EDIT, 'agent/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>
                            <tr class="gradeX">
                                <td>
                                    Top Up
                                </td>
                                <td>
                                    <input type="checkbox" name="access[top_up/index]" value="top_up/index" <?php echo (check_role_access($EDIT, 'top_up/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[top_up/approve]" value="top_up/approve" <?php echo (check_role_access($EDIT, 'top_up/approve')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Approve
                                </td>
                                <td>
                                    <input type="checkbox" name="access[top_up/delete]" value="top_up/delete" <?php echo (check_role_access($EDIT, 'top_up/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>
                            <tr class="gradeX">
                                <td>
                                    Redeem
                                </td>
                                <td>
                                    <input type="checkbox" name="access[redeem/index]" value="redeem/index" <?php echo (check_role_access($EDIT, 'redeem/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[redeem/approve]" value="redeem/approve" <?php echo (check_role_access($EDIT, 'redeem/approve')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Approve
                                </td>
                                <td>
                                    <input type="checkbox" name="access[redeem/reject]" value="redeem/reject" <?php echo (check_role_access($EDIT, 'redeem/reject')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Reject
                                </td>
                                <td>
                                    <input type="checkbox" name="access[redeem/delete]" value="redeem/delete" <?php echo (check_role_access($EDIT, 'redeem/delete')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Delete
                                </td>
                            </tr>
                            <tr class="gradeX">
                                <td>
                                    Pages
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pages/index]" value="pages/index" <?php echo (check_role_access($EDIT, 'pages/index')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; View
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pages/add]" value="pages/add" <?php echo (check_role_access($EDIT, 'pages/add')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Approve
                                </td>
                                <td>
                                    <input type="checkbox" name="access[pages/edit]" value="pages/edit" <?php echo (check_role_access($EDIT, 'pages/edit')=='TRUE')? 'checked="checked"' : ''; ?>> &nbsp; Reject
                                </td>
                            </tr>
                            
                            </tbody>
                    </table>

                    <hr>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-2">
                            <input type="hidden" name="idgroup" value="<?php echo $this->uri->segment(3); ?>">
                            <button type="submit" class="btn-primary btn">Save</button>
                            <a class="btn-default btn" href="javascript:history.go(-1);">Cancel</a>
                        </div>
                    </div>
                </form>
                    </div>
                </section>
            </div>            
        </div>
    </section>

<script type="text/javascript">
$("#checkAll").click(function(){
    $('form#setting_frm input:checkbox').each(function(){
        $(this).prop('checked',true);
    }) 
    $('.icheck').iCheck('update');
});

$("#uncheckAll").click(function(){
    $('form#setting_frm input:checkbox').each(function(){
        $(this).prop('checked',false);
    }) 
    $('.icheck').iCheck('update');
});
</script>