

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
                            <h1>Detail Rekening Koran</h1>
                            <div class="sub-title">Detail Transaksi Anda</div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Detail</div>
                                    <div class="panel-body">
                                        <table class="table-custom">
                                            
                                            <tr>
                                                <td><span>No. Transaksi Pinjaman</span> <?php echo $walletkoran['kode_transaksi'];  ?></td>
                                                <td><span>Tanggal Transaksi</span><?php echo $walletkoran['Date_transaction'];  ?></td>
                                            </tr>
                                            <tr>
                                                <td><span>Notes</span> <?php echo $walletkoran['Notes'] ?></td>
                                                <td><span>Tipe Dana</span>  <?php
                        
                                                            $tipe =$walletkoran['tipe_dana'];
                                                            switch ($tipe) {
                                                                case '1':
                                                                    $teks_tipe = 'Kredit';
                                                                   
                                                                    break;
                                                                case '2':
                                                                    $teks_tipe = 'Debet';
                                                                    
                                                                    break;
                                                            }
                                                            echo $teks_tipe;
                                                    ?></td>
                                            </tr>
                                            <?php 
                                            if($walletkoran['Notes']=='Top Up'){
                                                ?>
                                            <tr>
                                                <td><span>Jumlah Top Up</span> <?php  echo number_format($walletkoran1['Amount']) ?></td>
                                            </tr>
                                               <tr>
                                                <td><span>Saldo Awal</span> <?php
                                                $sa= ($walletkoran1['balance'] - $walletkoran1['Amount']);
                                                echo number_format($sa);
                                                ?></td>
                                                <td><span>Saldo Akhir</span> <?php echo number_format($walletkoran1['balance']) ?> IDR</td>
                                            </tr>
                                            <?php }else{
                                            ?>
                                            <tr>
                                                <td><span>Jumlah Pinjaman</span> <?php echo $walletkoran['Amount'] ?> IDR</td>
                                                <td><span>Balance</span> <?php echo $walletkoran['balance'] ?> IDR</td>
                                            </tr>
                                            <?php } ?>

                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php /*<div class="panel panel-default">
                                <div class="panel-heading">Riwayat</div>
                                    <div class="panel-body">
                                        <ul class="cbp_tmtimeline">
                                            <?php 
                                            $k = 1;
                                            for ($i=0; $i < $transaksi['Loan_term']; $i++) {  

                                                if (isset($detail_transaksi[$i]['Date_create'])) {
                                                    $class = 'done';
                                                    $icon = '<i class="fas fa-check"></i>';
                                                    $status_cicilan = 'Lunas';
                                                }else{
                                                    $class = '';
                                                    $icon = '<i class="far fa-clipboard"></i>';
                                                    $status_cicilan = '';
                                                }

                                                if ($transaksi['pendanaan_status'] == 'verify') {
                                                    $cicilan_duedate = date('d/m/Y', strtotime("+".$k." months", strtotime($transaksi['tgl_pinjaman_disetujui'])));
                                                }else{
                                                    $cicilan_duedate = '';
                                                }
                                            ?>
                                            <li>
                                                <time class="cbp_tmtime"><span>Jatuh Tempo</span> <span>
                                                    <?php echo $cicilan_duedate; ?>
                                                </span></time>
                                                <div class="cbp_tmicon <?php echo $class; ?>"><?php echo $icon; ?></div>
                                                <div class="cbp_tmlabel">
                                                    <h4>Pembayaran Cicilan ke <?php echo $k; ?></h4>
                                                    <p><?php echo number_format($jml_cicilan); ?> IDR</p>
                                                    <span class="<?php echo $class; ?>">Status : <?php echo $status_cicilan; ?></span>
                                                </div>
                                            </li>
                                            <?php $k=$k+1; 
                                            } ?>
                                        </ul>
                                        
                                    </div>
                                </div>
                            </div> */?>
                            <div class="text-center">
                                <a href="" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>