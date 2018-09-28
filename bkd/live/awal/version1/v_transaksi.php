<!-- Header -->
<header class="overflow-wrapp">
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
                            <h1>Transaksi</h1>
                            <div class="sub-title">Histori Transaksi Repayment Anda</div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    
                                    <div class="input-group grouping-search">
                                        <form method="post" action="<?php echo site_url('transaksi/search'); ?>">
                                            <input type="text" class="form-control" placeholder="Pencarian Transaksi" name="q" required="required">
                                            <span class="input-group-btn">
                                                <button class="btn btn-action" type="submit"><i class="fas fa-search action-ico"></i></button>
                                            </span>
                                        </form>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No. Transaksi</th>
                                                    <th>Jenis</th>
                                                    <th>Nama Transaksi</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php
                                                if (count($list_transaksi > 0)) { 
                                                foreach ($list_transaksi as $tra) { 

                                                    $tenor_label = 'Bulan';

                                                    if ($tra['tgl_approve'] != '0000-00-00 00:00:00')
                                                    {
                                                        $totalbln = (int)$tra['Loan_term'];
                                                        $jatuhtempo = date('d/m/Y', strtotime("+".$totalbln." months", strtotime($tra['tgl_approve'])));
                                                    }else{
                                                        $jatuhtempo = '';
                                                    }

                                                    if ($tra['id_mod_type_business'] == '1')
                                                    {
                                                        // pinjaman Kilat
                                                        $tenor_label = 'Hari';
                                                    }

                                                    // status bayar
                                                    if ( $tra['transaksi_status'] == 'approve' ) {
                                                        $status_bayar = 'Approve';
                                                    }else if ($tra['transaksi_status'] == 'review') {
                                                        $status_bayar = 'Dalam Review';
                                                    }else{
                                                        $status_bayar = ucfirst($tra['transaksi_status']);
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?php echo $tra['transaksi_id']; ?></td>
                                                    <td><span class="text-danger">Pinjaman</span></td>
                                                    <td><?php echo $tra['type_business_name']; ?>
                                                        <br> 
                                                        <div class="sub-description">Tenor : <?php echo $tra['Loan_term'] .' '. $tenor_label; ?> </div>
                                                        <div class="sub-description">Jatuh Tempo : <?php echo $jatuhtempo; ?></div>
                                                    </td>
                                                    <td><?php echo number_format($tra['totalrp']); ?></td>
                                                    <td><?php echo number_format($tra['total_approve']); ?></td>
                                                    <td><i class="text-warning"><?php echo $status_bayar; ?></i></td>
                                                    <td>
                                                        <a href="<?php echo site_url('transaksi/detail/?tid='.$tra['transaksi_id']); ?>" class="btn btn-action" title="Detil Transaksi">
                                                            <i class="far fa-clipboard"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php }
                                                }else{ ?>
                                                <tr><td colspan="6" class="text-center"> <em>Tidak ada transaksi</em></td></tr>
                                                <?php } ?>
                                                
                                            </tbody> 
                                        </table> 
                                    </div>
                                    <div class="text-center">
                                        <?php echo $pagination; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="note">
                                * Catatan : 
                                <ul>
                                    <li><i class="text-danger">Warna Merah merupakan indikator jenis transaksi Pinjaman</i></li>
                                    <li><i class="text-success">Warna Hijau merupakan indikator jenis transaksi Pendanaan</i></li>
                                    <li><i class="text-warning">Warna Orange merupakan indikator status transaksi sedang masa review oleh kami</i></li>
                                    <li><i class="text-primary">Warna Biru merupakan indikator status transaksi dalam masa berjalan</i></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>