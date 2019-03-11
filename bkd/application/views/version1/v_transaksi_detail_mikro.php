<?php 
//if ($transaksi['Master_loan_status'] == 'expired') 
switch ($transaksi['Master_loan_status']) {
    case 'review':
        $boleh_bayar = FALSE;
        $show_history = FALSE;
        break;
    case 'pending':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    case 'approve':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    case 'expired':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    case 'lunas':
        $boleh_bayar = FALSE;
        $show_history = TRUE;
        break;
    
    default:
        $boleh_bayar = TRUE;
        $show_history = TRUE;
        break;
}

$kuota = round(($transaksi['jml_kredit']/$transaksi['Amount']) * 100);
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

                <?php 
                if ($this->session->userdata('message')) {
                    $message_show = $this->session->userdata('message');
                    $msg_type = $this->session->userdata('message_type');
                    if ($msg_type == 'error') {
                        $icon  = 'error_icon.png';
                        $color = 'danger';
                    }else if ($msg_type == 'success') {
                        $icon  = 'success_icon.png';
                        $color = 'success';
                    }else{
                        $icon = 'info_icon.png';
                        $color = 'info';
                    }
                ?>
                <div class="col-sm-12">
                <div class="alert alert-<?php echo $color; ?> text-center"><img src="<?php echo base_url(); ?>assets/images/<?php echo $icon; ?>" style="width:25px;top: -1px;position: relative;margin-right: 5px;"> <?php echo $message_show; ?></div>
                </div>
                <?php } ?>

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

                                        <?php 
                                        // status bayar
                                        if ( $transaksi['Master_loan_status'] == 'complete' && ($transaksi['date_close'] == '0000-00-00' OR $transaksi['date_close'] == '0000-00-00 00:00:00' OR $transaksi['date_close'] == '') ) {
                                            $status_bayar = 'Pembayaran Cicilan';
                                        }else if ($transaksi['Master_loan_status'] == 'review') {
                                            $status_bayar = 'Proses Review';
                                        }else if ($transaksi['Master_loan_status'] == 'approve') {
                                            $status_bayar = 'Approve<br>Menunggu Pendanaan';
                                        }else{
                                            $status_bayar = $transaksi['Master_loan_status'];
                                        }

                                        //$tenor_label = 'Bulan';
                                        $max_looping = $transaksi['Loan_term'];
                                        
                                        if ($transaksi['type_of_business_id'] == 1)
                                        {
                                            // Pinjaman Kilat
											if($transaksi['type_of_interest_rate']==1){
												$tenor_label = 'Hari';
												$max_looping = 1;
												$submit_url  = site_url('submit-bayar-cicilan-kilat');   
                                            }
                                            if($transaksi['type_of_interest_rate']==2){
												$tenor_label = 'Bulan';
												$max_looping = 1;
												$submit_url  = site_url('submit-bayar-cicilan-kilat');   
                                            }
                                            if($transaksi['type_of_interest_rate']==3){
												$tenor_label = 'Minggu';
												$max_looping = 1;
												$submit_url  = site_url('submit-bayar-cicilan-kilat');   
                                            }
											
											
											
                                            //$tenor_label = 'Hari';
                                            //$max_looping = 1;
                                            //$submit_url  = site_url('submit-bayar-cicilan-kilat');
                                        }else{
                                            // Mikro
											if($transaksi['type_of_interest_rate']==1){
												$tenor_label = 'Hari';
												$submit_url  = site_url('submit-bayar-cicilan-mikro');  
                                            }
                                            if($transaksi['type_of_interest_rate']==2){
												$tenor_label = 'Bulan';
												$submit_url  = site_url('submit-bayar-cicilan-mikro');   
                                            }
                                            if($transaksi['type_of_interest_rate']==3){
												$tenor_label = 'Minggu';                                
												$submit_url  = site_url('submit-bayar-cicilan-mikro');   
                                            }
                                            //$submit_url  = site_url('submit-bayar-cicilan-mikro');
                                        }
                                        ?>

                                        <table class="table-custom">
                                            <tr>
                                                <td><span>No. Transaksi</span> <?php echo $transaksi['Master_loan_id']; ?></td>
                                                <td><span>Jenis</span> Pinjaman</td>
                                            </tr>
                                            <tr>
                                                <td><span>Nama Transaksi</span> <?php echo $transaksi['type_business_name']; ?></td>
                                                <td>
                                                    <span>Nama Peminjam</span>
                                                    <a href="javascript:;" class="text-warning"
                                                     data-toggle="tooltip" data-placement="right" data-original-title=""><?php echo $transaksi['Nama_pengguna']; ?> <i class="fas fa-user-circle"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>Tenor</span> <?php echo $transaksi['Loan_term'].' '. $tenor_label; ?></td>
                                                <td><span>Jatuh Tempo</span> <?php echo $jatuh_tempo; ?></td>
                                            </tr>
                                            <tr>
                                                <td><span>Jumlah</span> <?php echo number_format($transaksi['Amount']); ?> IDR</td>
                                                <td><span>Total</span> <?php echo number_format($total_bayar); ?> IDR</td>
                                            </tr>
                                            <tr>
                                                <td><span>Status</span> <i class="text-primary"> <?php echo ucfirst($status_bayar); ?></i></td>
                                                <td>
                                                    <span>Pendanaan</span>
                                                    <i class="text-primary">Kuota  <?php echo $kuota; ?>%</i>
                                                    <div class="progress-custom">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-success" style="width: <?php echo $kuota; ?>%;"></div>
                                                        </div>
                                                        <?php echo $transaksi['total_lender']; ?> Lender mengikuti Pendanaan ini 
                                                    </div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?php if ($show_history === TRUE) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">Riwayat</div>
                                    <div class="panel-body">
                                        <ul class="cbp_tmtimeline">
                                           <!-- <?php if (count($repayment) > 0) {
                                                    $i = 1;
                                                    foreach ($repayment as $dataq) {
                                                        
                                                        ?>
                                                    <h4>tanggal jatuh tempo <?php echo $dataq['tgl_jatuh_tempo']; ?></h4>
                                                    <h4>Pembayaran Cicilan ke <?php echo $dataq['notes_cicilan']; ?></h4>
                                                    <h4>pembayaran <?php echo $dataq['jumlah_cicilan']; ?></h4>
                                                <?php  $i = $i+1;
                                            }
                                            }?>  -->
                                            <!-- <?php
                                            if ($a=(count($repayment) > 0)) {

                                                    //$i = 1;
                                            foreach ($repayment as $data) {
                                             $cicilan_duedate = $repayment['tgl_jatuh_tempo'];
                                            ?>
                                            <h4>jml data <?php echo $a; ?></h4>
                                            <h4>Pembayaran Cicilan ke <?php echo $repayment['notes_cicilan']; ?></h4>
                                            <time class="cbp_tmtime"><span>Jatuh Tempo</span> <span>
                                                    <?php echo $cicilan_duedate; ?>
                                                </span></time>
                                                <?php  //$i = $i+1;
                                            }}?>  -->


                                            <?php 
                                            if (count($repayment) > 0) {
                                                    //$i = 1;
                                                    foreach ($repayment as $data) {
                                                         //$items[] = $data;
                                                         //$items = array($data);
                                            $k = $data['notes_cicilan'];

                                            //for ($i=0; $i < $lama_angsuran; $i++) {  


                                                //$jmlhari = 7 * $k;

                                                if ($data['status_cicilan']=='lunas') {
                                                    $class = 'done';
                                                    $icon = '<i class="fas fa-check"></i>';
                                                    $status_cicilan = 'Lunas';
                                                }else{
                                                    $class = '';
                                                    $icon = '<i class="far fa-clipboard"></i>';
                                                    $status_cicilan = '';
                                                }


                                                /*if (isset($detail_transaksi[$i]['Date_repaid'])) {
                                                    $class = 'done';
                                                    $icon = '<i class="fas fa-check"></i>';
                                                    $status_cicilan = 'Lunas';
                                                }else{
                                                    $class = '';
                                                    $icon = '<i class="far fa-clipboard"></i>';
                                                    $status_cicilan = '';
                                                }*/

                                                if ($transaksi['Master_loan_status'] == 'complete') {
                                                    //$cicilan_duedate = date('d/m/Y', strtotime("+".$jmlhari." day", strtotime($transaksi['tgl_pinjaman_disetujui'])));
                                                    $cicilan_duedate = $data['tgl_jatuh_tempo'];
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
                                                    <!-- <h4>Pembayaran Cicilan ke <?php echo $repayment['notes_cicilan']; ?></h4> -->
                                                    <p><?php echo number_format($data['jumlah_cicilan'], 2); ?> IDR</p>
                                                    <?php
                                                    //$tempo_denda=date('d/m/Y', time($jatuh_tempo. '+1 days'));
                                                    //$tempo_denda1 =date('d-m-Y', strtotime('+1 days', strtotime("$jatuh_tempo1")));
                                                    //$tempodenda2 = $jatuh_tempo->modify('+1 day');
                                                    //$tempo_denda=date('d/m/Y', time('+1 days', $jatuh_tempo));
                                                    $nowdate = date('Y-m-d');
                                                    //$cicilan_date=date('Y-m-d', time('$cicilan_duedate'));
                                                    //$denda_date = $cicilan_duedate->format('Y-m-d');

                                                    $denda1 = str_replace('/', '-', $cicilan_duedate);
                                                    $denda2 = date('Y-m-d', strtotime($denda1));


                                                    /*$date1=date_create("$jatuh_tempo1");
                                                    $date2=date_create("$nowdate");
                                                    $diff=date_diff($date1,$date2);
                                                    $diff1 = $diff->format("%R%a");*/
                                                    //secho $diff1;

                                                    //$tgl_disetujui = date('Y-m-d', strtotime("+7 day", strtotime($transaksi['tgl_pinjaman_disetujui'])));
                                                    //baru
                                                    $date1=date_create("$denda2");
                                                    //$date1=date_create("$tgl_disetujui");
                                                    $date2=date_create("$nowdate");
                                                    $diff=date_diff($date1,$date2);
                                                    $diff1 = $diff->format("%R%a");
                                                    //$items[] = $diff1;
                                                    //$diff3 = array($diff1);
                                                    //$diff4 =implode( $diff3 ); 
                                                    //[$first] = $diff3;
                                                    //var_dump($first);

                                                    //$nowdate1 = strtotime($nowdate);
                                                    //$jatuh_tempo1 = date($jatuh_tempo);
                                                    //$tempo = date('d/m/Y',strtotime($jatuh_tempo3));
                                                    //$tempo1 = ('d/m/Y');
                                                    //$tempo1 = new DateTime("+1 day $jatuh_tempo")
                                                    //$b=time($jatuh_tempo);
                                                     //$Date = "2010/09/17";
                                                        //$a = date('Y/m/d', strtotime($Date. ' + 1 days'));
                                                    //echo"$tgl_disetujui ||";
                                                    //echo"$denda2 ||";
                                                    //echo"$cicilan_date ||";
                                                    //echo"$cicilan_duedate ||";
                                                    //echo"$jatuh_tempo |";
                                                     //echo $repayment['tgl_jatuh_tempo'] ;
                                                    //echo"| $cicilan_duedate ||";
                                                        //echo"$tempo_denda |";
                                                        //echo"$nowdate |";
                                                       // echo "$diff1 |";
                                                        //print_r($items);
 

                                                        //print_r(array_values($items));
                                                        //echo "$diff3";

                                                    //echo $bayar_denda;
                                                    if(number_format($diff1 > 0)){
                                                   // if(time($nowdate) > time($jatuh_tempo)){ 
                                                        //echo"$jatuh_tempo |";
                                                        //echo"$tempo_denda |";
                                                        //echo"$nowdate |";
                                                        $bayar_denda = ($denda * $diff1);
                                                         //$items[] = $diff1;
                                                        //echo $bayar_denda;

                                                    }else if(number_format($diff1 < 0)){

                                                         $bayar_denda = 0;
                                                       // $bayar_denda = ($denda * $diff1);
                                                        //echo $bayar_denda;
                                                     
                                                         //$items[] = $diff1;

                                                    }
                                                    //$items[] = $bayar_denda;
                                                   

                                                     ?>
                                                    <h4>Denda Keterlambatan</h4>
                                                    <p><?php echo number_format($bayar_denda); ?> IDR</p>
                                                    <span class="<?php echo $class; ?>">Status : <?php echo $status_cicilan; ?></span>
                                                </div>
                                            </li>
                                            <?php //$k=$k+1; 
                                            }
                                            }
                                            //} ?>
                                        </ul>
                                        <div class="text-center">
                                            <a href="" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>

                                            <?php if ($boleh_bayar === TRUE) { ?>
                                            <a data-toggle="modal" data-target="#modalPayment" href="#" class="btn btn-purple" title="Bayar">Bayar</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal bayar -->
<div class="modal fade" id="modalPayment" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-payment-process">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Proses Pembayaran Cicilan</h2><br>
                        </div>
                    </div>
                    <?php
                     if (count($repaymentdenda) > 0) {
                            //$i = 1;
                                foreach ($repaymentdenda as $data) { 
                                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="balance">Saldo : <?php echo number_format($total_saldo['Amount']); ?> IDR</div>
                            <div class="bill">Tagihan : <?php echo number_format($data['jumlah_cicilan'], 2); ?> IDR</div>
                            <div class="bill">Denda : <?php echo number_format($data['jml_denda']); ?> IDR</div>
                            <br><br>
                            <?php
                            
/*                            for ($l = 0; $l <  count($items); $l++) {
                                $key=key($items);
                                $val=$items[$key];
                                //if ($val<> ' ') {
                                   //echo $key ." = ".  $val ." <br> ";
                                   print_r($key);
                                   echo " ";
                                   print_r($val);
                                   echo "|";
                                  // }
                                 next($items);
                            }*/
                            ?>

                            <?php 
                           /* if (count($repaymentdenda) > 0) {
                            //$i = 1;
                                foreach ($repaymentdenda as $data) {*/


                            if ($jml_cicilan <= $total_saldo['Amount']) { 


                                

/*                                for ($l = 0; $l <  count($items); $l++) {
                                $key=key($items);
                                $val=$items[$key];
                                //if ($val<> ' ') {
                                   //echo $key ." = ".  $val ." <br> ";
                                   print_r($key);
                                   echo " ";
                                   print_r($val);
                                   echo "|";
                                  // }
                                 next($items);
                            }*/
                                ?>
                                <form id="form_pembayaran" method="POST" action="<?php echo $submit_url; ?>">
                                    <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['Master_loan_id']; ?>">
                                    <input type="hidden" name="jatuh_tempo" value="<?php echo $data['tgl_jatuh_tempo']; ?>">
                                    <input type="hidden" name="jml_pinjaman" value="<?php echo $total_bayar; ?>">
                                    <input type="hidden" name="jml_cicilan" value="<?php echo $data['jumlah_cicilan']; ?>">
                                     <input type="hidden" name="bayar_denda" value="<?php echo $data['jml_denda']; ?>">
                                    <input type="hidden" name="id_peminjam" value="<?php echo $transaksi['Id_pengguna']; ?>">
                                    <input type="hidden" name="id_peminjam_member" value="<?php echo $transaksi['id_mod_user_member']; ?>">
                                    <div class="form-group">
                                        <label for="handphone">Jumlah Pembayaran</label>
                                        <input type="text" name="jml_bayar" class="form-control text-center numeric" value="<?php echo $data['jumlah_cicilan']+$data['jml_denda']; ?>">
                                    </div>
                                    <button type="button" id="submit_bayarcicilan" style="background: transparent; border: none;">
                                    <a href="javascript:;" data-dismiss="modal" class="btn btn-purple">Submit</a>
                                    </button>
                                    <br><br>
                                </form>

                            <?php } } }else{ ?>

                                <p>Saldo Anda tidak mencukupi untuk melakukan pembayaran ini.</p>
                                <a href="javascript:;" class="btn btn-default" disabled>Submit</a><br><br>
                            <?php } ?>

                            <p class="note muted"><i>* Catatan : Saldo Anda akan dikurangi tagihan Anda jika menekan tombol submit. Jika saldo Anda tidak mencukupi harap segera melakukan <strong>Top Up</strong> terlebih dahulu.</i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>