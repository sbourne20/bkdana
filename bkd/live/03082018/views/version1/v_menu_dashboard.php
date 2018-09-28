                        <?php 
                        $currentpage = $this->uri->segment(1);
                        $grade_persen = grade_percentage($memberid);
                        ?>
                        <div class="profile">
                            <a href="#" class="link-img" title="Ganti Gambar Profil">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($memberdata['Profile_photo']); ?>" class="img-responsive img-circle" width="100" height="100" />
                            </a>
                            <div class="description">
                                <?php echo $memberdata['mum_fullname']; ?>
                                <span class="saldo">Saldo : <?php echo number_format($total_saldo['Amount']); ?> IDR</span>
                                <div class="progress-profile">
                                    <div class="progress active">
                                        <div id="profil_complete" data-value="<?php echo $grade_persen; ?>" class="progress-bar progress-bar-info" style="width:0%"></div>
                                    </div>
                                    <?php echo $grade_persen; ?>% Profil Terselesaikan 
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" 
                                    title="Lengkapi profil untuk meningkatkan scoring anda">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </div>
                                
                                <hr>
                                <ul class="menu-profile">
                                    <li <?php echo ($currentpage == 'dashboard')? 'class="active"' : ''; ?>><a href="<?php echo site_url('dashboard'); ?>" title="Dashboard">Dashboard</a></li>
                                    <li <?php echo ($currentpage == 'ubah-profil')? 'class="active"' : ''; ?>><a href="<?php echo site_url('ubah-profil'); ?>" title="Ubah Profil">Ubah Profil</a></li>
                                    <li <?php echo ($currentpage == 'transaksi')? 'class="active"' : ''; ?>><a href="<?php echo site_url('transaksi'); ?>" title="Transaksi">Transaksi</a></li>

                                    <?php if ($logintype == '2') { ?>
                                    <li <?php echo ($currentpage == 'daftar-peminjam')? 'class="active"' : ''; ?>><a href="<?php echo site_url('daftar-peminjam'); ?>" title="Daftar Peminjam">Daftar Peminjam</a></li>
                                    <?php } ?>
                                    
                                    <li <?php echo ($currentpage == 'top-up')? 'class="active"' : ''; ?>><a href="<?php echo site_url('top-up'); ?>" title="Top Up">Top Up</a></li>
                                    <li <?php echo ($currentpage == 'redeem')? 'class="active"' : ''; ?>><a href="<?php echo site_url('redeem'); ?>" title="Redeem">Redeem</a></li>
                                    <?php /* <li><a href="<?php echo site_url('pengaturan'); ?>" title="Pengaturan">Pengaturan</a></li> */?>
                                    <li><a href="javascript:;" id="sign-out-button" onclick="onSignOutClick()" title="Keluar">Keluar</a></li>
                                </ul>
                            </div>
                        </div>