<?php 
switch ($detail_transaksi['Master_loan_status']) {
    case 'complete':
        $status_text = 'Proses Pembayaran';
        break;
    
    default:
        $status_text = ucfirst($detail_transaksi['Master_loan_status']);
        break;
}

$pph_potongan = $log_pendanaan['total_pajak'];
?>

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
                            <h1>Detail Transaksi</h1>
                            <div class="sub-title">Detail Transaksi Anda</div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Detail</div>
                                    <div class="panel-body">

                                        <table class="table-custom">
                                            <tr>
                                                <td><span>No. Transaksi Pendanaan</span> <?php echo $transaksi['Id']; ?></td>
                                                <td><span>No. Transaksi Pinjaman</span> <?php echo $transaksi['Master_loan_id']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><span>Nama Transaksi</span> Pendanaan</td>
                                                <td>
                                                    <span>Nama Peminjam</span>
                                                    <a href="javascript:;" class="text-warning"
                                                     data-toggle="tooltip" data-placement="right" data-original-title="Klik untuk melihat profil publik"><?php echo $detail_transaksi['nama_peminjam']; ?> <i class="fas fa-user-circle"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>Tenor</span> <?php echo $transaksi['Loan_term'] ?> Bulan</td>
                                                <td><span>Jatuh Tempo</span> <?php echo $jatuh_tempo; ?></td>
                                            </tr>
                                            <tr>
                                                <td><span>Jumlah Pendanaan</span> <?php echo number_format($transaksi['Jml_penawaran_pemberian_pinjaman']); ?> IDR</td>
                                                <td><span>Jumlah Pinjaman</span> <?php echo number_format($detail_transaksi['Jml_permohonan_pinjaman']); ?> IDR</td>
                                            </tr>
                                            <tr>
                                                <td><span>Jumlah Saldo diterima</span> 
                                                    <?php echo number_format($log_pendanaan['total_pendapatan']+$pph_potongan) .' - PPH '.$produk_pinjaman['PPH'].'% = '. number_format($log_pendanaan['total_pendapatan']); ?> IDR
                                                    <br>
                                                    (PPH <?php echo $produk_pinjaman['PPH']; ?>% : <?php echo number_format($pph_potongan); ?> IDR)
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><span>Status</span> <i class="text-primary"> <?php echo ucfirst($transaksi['pendanaan_status']); ?></i></td>
                                                <td><span>Status</span> <i class="text-primary"> <?php echo $status_text; ?></i></td>
                                            </tr>
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