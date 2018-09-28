<!-- Header -->
<header>
    <div class="container">
        
        <?php $this->load->view('common/navigation_all'); ?>
        
    </div>
</header>

<!-- Content -->
<div class="wrapper-content overflow-wrapp">
    <div class="section-member-page">
        
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="box plain left">
                        <?php $this->load->view('version1/v_menu_dashboard'); ?>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="box plain right">
                        <div class="content">
                            <h1>Daftar Peminjam</h1>
                            <div class="sub-title">Daftar Peminjam Yang Sedang Aktif</div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    
                                    &nbsp; 
                                    <div class="input-group grouping-search">
                                        <input type="text" class="form-control" placeholder="Pencarian Peminjam">
                                        <span class="input-group-btn">
                                            <button class="btn btn-action" type="button"><i class="fas fa-search action-ico"></i></button>
                                        </span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover link">
                                            <thead>
                                                <tr>
                                                    <th>No. Transaksi</th>
                                                    <th>Nama Peminjam</th>
                                                    <th>Total Pinjaman</th>
                                                    <th>Total Pendanaan</th>
                                                    <th>Lenders</th>
                                                    <th>Grade</th>
                                                    <th>Tenor</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php if ($total_peminjam > 0) { ?>
                                                <?php foreach ($list_transaksi as $tra) {  

                                                    if ($tra['tgl_approve'] != '0000-00-00 00:00:00')
                                                    {
                                                        $totalbln = (int)$tra['Loan_term'];
                                                        $jatuhtempo = date('d/m/Y', strtotime("+".$totalbln." months", strtotime($tra['tgl_approve'])));
                                                    }else{
                                                        $jatuhtempo = '';
                                                    }

                                                    $link_detail = site_url('daftar-peminjam-detail/?tid='.$tra['transaksi_id']);

                                                    $slot = round($tra['jml_kredit']/$tra['total_pinjam']*100);

                                                    if ($tra['id_mod_type_business'] == 1)
                                                    {
                                                        $label_tenor = 'Hari';
                                                    }else{
                                                        $label_tenor = 'Bulan';
                                                    }
                                                ?>
                                                
                                                <tr onclick="document.location='<?php echo $link_detail; ?>'" title="Klik Detail Peminjam">
                                                    <td><?php echo $tra['transaksi_id']; ?></td>
                                                    <td>
                                                        <?php echo $tra['nama_peminjam']; ?>
                                                        <div class="sub-description">Slot Pendanaan : <?php echo $slot; ?>%</div>
                                                        <div class="sub-description">Rate : </div>
                                                    </td>
                                                    <td><?php echo number_format($tra['total_pinjam']); ?></td>
                                                    <td><?php echo number_format($tra['jml_kredit']); ?></td>
                                                    <td><?php echo $tra['total_lender']; ?></td>
                                                    <td><?php echo $tra['peringkat_pengguna']; ?></td>
                                                    <td><?php echo $tra['Loan_term'] .' '. $label_tenor; ?> </td>
                                                </tr>
                                                <?php } 
                                                }else{ ?>
                                                <tr><td colspan="7" class="text-center"> <em>Tidak ada data</em></td></tr>
                                                <?php } ?>
                                                
                                            </tbody> 
                                        </table> 
                                    </div>
                            <div class="text-center">
                                <?php echo $pagination; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>