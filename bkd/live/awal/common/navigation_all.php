        <!-- Navbar -->
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo site_url('home'); ?>"><img src="<?php echo base_url(); ?>assets/images/logo.png" class="img-responsive" alt="Keyword" title="Keyword" /></a>
            </div>
            <div class="navbar-collapse collapse" id="menu">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo site_url('pendanaan'); ?>" title="Pendanaan">Pendanaan</a></li>
                    <li><a href="<?php echo site_url('pinjaman'); ?>" title="Pinjaman">Pinjaman</a></li>
                    <li><a href="<?php echo site_url('tentang-kami'); ?>" title="Tentang Kami">Tentang Kami</a></li>

                    <?php
                        $login_status = isset($_SESSION['_bkdlog_'])? htmlentities(strip_tags($_SESSION['_bkdlog_'])) : '0';
                        $username = isset($_SESSION['_bkduser_'])? htmlentities(strip_tags($_SESSION['_bkduser_'])) : '0';

                        if ( $login_status != 1 && empty($username)) {
                    ?>
                    <li><a href="<?php echo site_url('login'); ?>" class="login" title="Masuk">Masuk</a></li>
                    <li class="dropdown"><a href="register.html" class="register dropdown-toggle" title="Daftar" data-toggle="dropdown">Daftar</a>
                        <ul class="dropdown-menu">
                            <li><a data-toggle="modal" data-target="#modalRegister" href="#">Daftar Peminjam</a></li>
                            <li><a href="<?php echo site_url('register-pendana'); ?>">Daftar Pendana</a></li>
                        </ul>
                    </li>

                    <?php }else{ ?>

                    <li class="dropdown"><a href="dashboard.html" class="dropdown-toggle" title="My Account" data-toggle="dropdown">My Account</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('ubah-profil'); ?>" title="Ubah Profil">Ubah Profil</a></li>
                            <li><a href="<?php echo site_url('transaksi'); ?>" title="Transaksi">Transaksi</a></li>
                            <li><a href="<?php echo site_url('top-up'); ?>" title="Top Up">Top Up</a></li>
                            <li><a href="<?php echo site_url('redeem'); ?>" title="Redeem">Redeem</a></li>
                            <li><a href="javascript:;" id="sign-out-button" onclick="onSignOutClick()">Keluar</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        