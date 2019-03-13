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
                            <h1>Rekening Koran</h1>
                            <div class="sub-title"><!-- Kanal Pembayaran --></div>
                            
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="sub-title">Histori</div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="padding:5px">No.</th>
                                                    <th style="padding:5px">Tanggal</th>
                                                    <th style="padding:5px">No. Transaksi</th>
                                                    <th style="padding:5px">Deskripsi</th>
                                                    <th style="padding:5px">Tipe</th>
                                                    <th style="padding:5px">Jumlah</th>
                                                    <th style="padding:5px">Saldo Awal</th>
                                                    <th style="padding:5px">Saldo Akhir</th>
                                                    <th style="padding:5px">&nbsp;</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php 
                                                $useclass = 0;
                                                $inclass  = '';

                                                if (count($rk) > 0) {
                                                    $i = 1;
                                                    foreach ($rk as $data) {
                                                        if ($useclass==0) { 
                                                            $inclass=''; 
                                                        }else{
                                                            $inclass='class="warning"'; 
                                                        }

                                                       
                                                //tambahan filter login
                                                               if($tipe_pengguna['mum_type'] == '1'){
                                                               
                                                                if($tipe_pengguna['mum_type_peminjam'] == '1'){
                                                                    if ($data['tipe_dana'] == '1') {
                                                                    $link_detail2 = site_url('detail-rekening-koran-kredit-kilat/?tid='.$data['kode_transaksi'].'&tid2='.$data['Date_transaction']);
                                                                    }else{
                                                                    $link_detail2 = site_url('detail-rekening-koran-debet-kilat/?tid='.$data['kode_transaksi'].'&tid2='.$data['Date_transaction']); 
                                                                    }
                                                                }else{
                                                                    if ($data['tipe_dana'] == '1') {
                                                                    $link_detail2 = site_url('detail-rekening-koran-kredit-mikro/?tid='.$data['kode_transaksi'].'&tid2='.$data['Date_transaction']);
                                                                    }else{
                                                                    $link_detail2 = site_url('detail-rekening-koran-debet-mikro/?tid='.$data['kode_transaksi'].'&tid2='.$data['Date_transaction']); 
                                                                    }
                                                                }
                                                                   
                                                            }
                                                            if($tipe_pengguna['mum_type'] == '2'){
                                                                if ($data['tipe_dana'] == '1') {
                                                                   $link_detail2 = site_url('detail-rekening-koran-kredit-pendana/?tid='.$data['kode_transaksi'].'&tid2='.$data['Date_transaction']);
                                                                       }else{
                                                                             $link_detail2 = site_url('detail-rekening-koran-debet-pendana/?tid='.$data['kode_transaksi'].'&tid2='.$data['Date_transaction']); 
                                                                            }   
                                                            }
                                                    
                                                //end of tambahan filter logins
                                                    ?>
                                                    <tr>
                                                        <td style="padding:5px" <?php echo $inclass; ?>><?php echo $i; ?></td>
                                                        <td style="padding:5px" <?php echo $inclass; ?>><?php echo date('d/m/Y', strtotime($data['Date_transaction'])); ?></td>
                                                        <td style="padding:5px" <?php echo $inclass; ?>><?php echo $data['kode_transaksi']; ?></td>
                                                        <td style="padding:5px" <?php echo $inclass; ?>><?php echo $data['Notes']; ?></td>
                                                        <td style="padding:5px" <?php echo $inclass; ?>><?php echo ($data['tipe_dana']=='1')? 'Kredit' : 'Debet'; ?></td>
                                                        <td style="padding:5px" <?php echo $inclass; ?>> <?php echo number_format($data['amount_detail']); ?></td>
                                                         <td style="padding:5px" <?php echo $inclass; ?>> <?php 
                                                            if ($data['tipe_dana'] == '1'){
                                                                $sa= ($data['Balance'] - $data['amount_detail']);
                                                                echo number_format($sa);
                                                            }
                                                            else{
                                                                $sa= ($data['Balance'] + $data['amount_detail']);
                                                                echo number_format($sa);
                                                            }
                                                          ?></td>
                                                          <td style="padding:5px" <?php echo $inclass; ?>> <?php  echo number_format($data['Balance']); ?></td>
                                                        <td style="padding:5px">
                                                            <a href="<?php                                                    
                                                            //echo site_url('detail-rekening-koran-kredit-kilat/?tid='.$data['kode_transaksi']); 
                                                            echo $link_detail2
                                                            ?>" class="btn btn-action" title="Detail Rekening Koran">
                                                            <i class="far fa-clipboard"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                        if ($useclass == 0)
                                                        {
                                                            $useclass = 1;
                                                        }else{
                                                            $useclass = 0;
                                                        }

                                                        $i = $i+1;
                                                    } 
                                                } else { ?>
                                                    <tr><td colspan="4" class="text-center"><em>Tidak ada data</em></td></tr>
                                                <?php } ?>
                                                
                                            </tbody> 
                                        </table> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Top Up Transfer Manual -->
<div class="modal fade" id="modalTransferManual" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-register">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Form Top Up - Transfer Manual</h2><br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-offset-1 col-sm-9">
                            <form class="text-left form-validation" method="POST" action="<?php echo site_url('top_up/submit_manual'); ?>">
                                <div class="form-group">
                                    <label for="fullname">* Nama Rekening Anda</label>
                                    <input class="form-control" name="account_bank_name" id="account_bank_name" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Nama Rekening harus diisi!">
                                </div>
                                <div class="form-group">
                                    <label for="accounta_bank_no">* Nomor Rekening Anda</label>
                                    <input class="form-control" name="account_bank_number" id="account_bank_number" type="text" value="<?php echo $memberdata['Nomor_rekening']; ?>" data-validation-engine="validate[required]" data-errormessage-value-missing="Nomor Rekening harus diisi!">
                                </div>
                                <div class="form-group">
                                    <label for="bank">* Bank Anda</label>
                                    <select class="form-control" name="my_bank_name" id="my_bank_name">
                                        <option value="" selected="selected">- Pilih Bank -</option>
                                        <option value="Bank Mandiri" <?php echo ($memberdata['nama_bank']=='Bank Mandiri')? 'selected="selected"' : ''; ?>>Bank Mandiri</option>
                                        <option value="Bank BNI 46" <?php echo ($memberdata['nama_bank']=='Bank BNI 46')? 'selected="selected"' : ''; ?>>Bank BNI 46</option>
                                        <option value="Bank BRI" <?php echo ($memberdata['nama_bank']=='Bank BRI')? 'selected="selected"' : ''; ?>>Bank BRI</option>
                                        <option value="Bank BCA" <?php echo ($memberdata['nama_bank']=='Bank BCA')? 'selected="selected"' : ''; ?>>Bank BCA</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="accounta_bank_beneficiary">* Bank Tujuan</label>
                                    <select id="accounta_bank_beneficiary" class="form-control" disabled>
                                        <option value="Bank CIMB">CIMB - 123456789 - PT. Berkah Kelola Dana</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="handphone">* Jumlah Rp</label>
                                    <input class="form-control numeric" name="jml_topup" id="total" type="text" data-validation-engine="validate[required]" data-errormessage-value-missing="Jumlah Top Up harus diisi">
                                </div>

                                <input type="hidden" name="bank_destination" value="Bank CIMB">
                                <br><button type="submit" class="btn btn-purple">Submit</button><br><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Top Up Virtual Account -->
<div class="modal fade" id="modalTransferVirtual" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-register">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Form Top Up - Virtual Account</h2><br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-offset-1 col-sm-9">
                            <form class="text-left form-validation" method="POST" action="<?php echo site_url('top_up/submit_auto'); ?>">
                                <div class="form-group">
                                    <label for="fullname">* Nama Rekening Anda</label>
                                    <input class="form-control" name="account_bank_name" id="account_bank_name" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="accounta_bank_no">* Nomor Rekening Anda</label>
                                    <input class="form-control" name="account_bank_number" id="account_bank_number" type="text" value="<?php echo $memberdata['Nomor_rekening']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="bank">* Bank Anda</label>
                                    <select class="form-control" name="my_bank_name" id="my_bank_name">
                                        <option value="" selected="selected">- Pilih Bank -</option>
                                        <option value="Bank Mandiri" <?php echo ($memberdata['nama_bank']=='Bank Mandiri')? 'selected="selected"' : ''; ?>>Bank Mandiri</option>
                                        <option value="Bank BNI 46" <?php echo ($memberdata['nama_bank']=='Bank BNI 46')? 'selected="selected"' : ''; ?>>Bank BNI 46</option>
                                        <option value="Bank BRI" <?php echo ($memberdata['nama_bank']=='Bank BRI')? 'selected="selected"' : ''; ?>>Bank BRI</option>
                                        <option value="Bank BCA" <?php echo ($memberdata['nama_bank']=='Bank BCA')? 'selected="selected"' : ''; ?>>Bank BCA</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="accounta_bank_beneficiary">* Bank Tujuan</label>
                                    <select id="accounta_bank_beneficiary" class="form-control" disabled>
                                        <option value="Bank CIMB">CIMB - 123456789 - PT. Berkah Kelola Dana</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="handphone">* Jumlah</label>
                                    <input class="form-control" name="jml_topup" id="total" type="text">
                                </div>

                                <input type="hidden" name="bank_destination" value="Bank CIMB">
                                <br><button type="submit" class="btn btn-purple">Submit</button><br><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>