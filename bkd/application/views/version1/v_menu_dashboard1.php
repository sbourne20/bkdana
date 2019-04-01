                        <?php 
                        $currentpage = $this->uri->segment(1);
                        $grade_persen = grade_percentage($memberid);

                        $foto_profil = '';
                        $foto_ktp    = '';
                        $foto_usaha  = '';

                        if ($memberdata['images_foto_name'] != '')
                        {
                            $foto_profil = $this->config->item('images_uri') . '/member/'.$memberdata['id_mod_user_member']. '/foto/'. $memberdata['images_foto_name'];
                        }
                        
                        ?>
                
                            <div class="description">
                                <?php echo $memberdata['Nama_pengguna']; ?>
                                
                                
                                <hr>
                                <ul class="menu-profile">
                                    <li <?php echo ($currentpage == 'dashboard')? 'class="active"' : ''; ?>><a href="<?php echo site_url('dashboard'); ?>" title="Dashboard">Dashboard</a></li>
                                    <li <?php echo ($currentpage == 'ubah-profil')? 'class="active"' : ''; ?>><a href="<?php echo site_url('ubah-profil'); ?>" title="Ubah Profil">Ubah Profil</a></li>
                                    <li <?php echo ($currentpage == 'transaksi')? 'class="active"' : ''; ?>><a href="<?php echo site_url('transaksi'); ?>" title="Transaksi">Transaksi</a></li>

                                    <?php if ($logintype == '2') { ?>
                                    <li <?php echo ($currentpage == 'daftar-peminjam')? 'class="active"' : ''; ?>><a href="<?php echo site_url('daftar-peminjam'); ?>" title="Daftar Peminjam">Daftar Peminjam</a></li>
                                    <?php } ?>
                                    
                                    <li <?php echo ($currentpage == 'top-up')? 'class="active"' : ''; ?>><a href="<?php echo site_url('top-up'); ?>" title="Top Up">Top Up</a></li>
                                    <li <?php echo ($currentpage == 'redeem')? 'class="active"' : ''; ?>><a href="<?php echo site_url('redeem'); ?>" title="Redeem">Tarik Tunai</a></li>
                                    <li <?php echo ($currentpage == 'rekening_koran')? 'class="active"' : ''; ?>><a href="<?php echo site_url('rekening_koran'); ?>" title="Rekening Koran">Rekening Koran</a></li>
                                    <li><a href="javascript:;" id="sign-out-button" onclick="onSignOutClick()" title="Keluar">Keluar</a></li>
                                </ul>
                            </div>
                        