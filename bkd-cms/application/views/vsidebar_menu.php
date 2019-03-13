<?php 
$active_menu = $this->uri->segment(1); 
$sub_menu    = $this->uri->segment(2);
?>
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a <?php echo ( ($active_menu=='dashboard')? 'class="active"' : ''); ?> href="<?php echo base_url('dashboard'); ?>">
                        <i class="fa fa-area-chart"></i> <span>Dashboard</span>
                    </a>
                </li>
                <?php //============= USER ==========
                $set_array = array( 'user' ); ?>
                <li class="sub-menu">
                    <a href="javascript:;" <?php echo (in_array($active_menu, $set_array)) ? "class='active'":"" ?>>
                        <i class="fa fa-user"></i> <span>User</span>
                    </a>
                    <ul class="sub">
                        <li <?php echo ($sub_menu=='management') ? "class='active'":"" ?>><a href="<?php echo base_url('user/management') ?>">User Management</a></li>
                        <li <?php echo ($sub_menu=='group') ? "class='active'":"" ?>><a href="<?php echo base_url('user/group') ?>">User Group</a></li>
                        <!-- <li <?php echo ($sub_menu=='log') ? "class='active'":"" ?>><a href="<?php echo base_url('user/log') ?>">User Log</a></li> -->
                    </ul>
                </li>

                <?php
                $set_array = array( 'member_group', 'peminjam', 'pendana', 'wallet', 'koperasi' ); ?>
                <li class="sub-menu">
                    <a href="javascript:;" <?php echo (in_array($active_menu, $set_array)) ? "class='active'":"" ?>>
                        <i class="fa fa-group"></i> <span>Member</span>
                    </a>
                    <ul class="sub">
                        <li <?php echo ($active_menu=='member_group') ? "class='active'":"" ?>><a href="<?php echo base_url('member/group') ?>"> Member Group</a></li>
                        <li <?php echo ($active_menu=='peminjam') ? "class='active'":"" ?>><a href="<?php echo base_url('peminjam') ?>"> Peminjam</a></li>
                        <li <?php echo ($active_menu=='pendana') ? "class='active'":"" ?>><a href="<?php echo base_url('pendana') ?>"> Pendana</a></li>
                        <li <?php echo ($active_menu=='wallet') ? "class='active'":"" ?>><a href="<?php echo base_url('wallet') ?>"> Wallet</a></li>
                        <li <?php echo ($active_menu=='koperasi') ? "class='active'":"" ?>><a href="<?php echo base_url('koperasi') ?>"> Koperasi</a></li>
                    </ul>
                </li>

                <?php
                $set_array = array( 'product', 'type_business', 'grade_user', 'harga_pinjaman_kilat' ); ?>
                <li class="sub-menu">
                    <a href="javascript:;" <?php echo (in_array($active_menu, $set_array)) ? "class='active'":"" ?>>
                        <i class="fa fa-th"></i> <span>Product</span>
                    </a>
                    <ul class="sub">
                        <li <?php echo ($active_menu=='product') ? "class='active'":"" ?>><a href="<?php echo base_url('product') ?>"> Product</a></li>
                        <li <?php echo ($active_menu=='type_business') ? "class='active'":"" ?>><a href="<?php echo base_url('type_business') ?>">Type Business</a></li>
                        <li <?php echo ($active_menu=='harga_pinjaman_kilat') ? "class='active'":"" ?>><a href="<?php echo base_url('harga_pinjaman_kilat') ?>">Harga Pinjaman Kilat</a></li>
                        <li <?php echo ($active_menu=='grade_user') ? "class='active'":"" ?>><a href="<?php echo base_url('grade_user') ?>">Grade User</a></li>
                    </ul>
                </li>

                <?php /*
                $set_array = array( 'transaksi-pinjaman-kilat-draft', 'transaksi-pinjaman-mikro-draft' ); ?>
                <li class="sub-menu">
                    <a href="javascript:;" <?php echo (in_array($active_menu, $set_array)) ? "class='active'":"" ?>>
                        <i class="fa fa-bar-chart-o"></i> <span>Transaksi Draft</span>
                    </a>
                    <ul class="sub">
                        <li <?php echo ($active_menu=='transaksi-pinjaman-kilat-draft') ? "class='active'":"" ?>><a href="<?php echo base_url('transaksi-pinjaman-kilat-draft') ?>"> Pinjaman Kilat</a></li>
                        <li <?php echo ($active_menu=='transaksi-pinjaman-mikro-draft') ? "class='active'":"" ?>><a href="<?php echo base_url('transaksi-pinjaman-mikro-draft') ?>">Pinjaman Mikro</a></li>
                    </ul>
                </li>
                */?>

                <?php 
                $set_array = array( 'transaksi_pinjaman_kilat', 'transaksi_pinjaman_mikro' ); ?>
                <li class="sub-menu">
                    <a href="javascript:;" <?php echo (in_array($active_menu, $set_array)) ? "class='active'":"" ?>>
                        <i class="fa fa-bar-chart-o"></i> <span>Transaksi Pinjaman</span>
                    </a>
                    <ul class="sub">
                        <li <?php echo ($active_menu=='transaksi_pinjaman_kilat') ? "class='active'":"" ?>><a href="<?php echo base_url('transaksi_pinjaman_kilat') ?>"> Pinjaman Kilat</a></li>
                        <li <?php echo ($active_menu=='transaksi_pinjaman_mikro') ? "class='active'":"" ?>><a href="<?php echo base_url('transaksi_pinjaman_mikro') ?>">Pinjaman Mikro</a></li>
                        <!-- <li <?php echo ($active_menu=='transaksi_jatuh_tempo') ? "class='active'":"" ?>><a href="<?php echo base_url('transaksi_jatuh_tempo') ?>">Pinjaman Jatuh Tempo</a></li> -->
                    </ul>
                </li>

                <li>
                    <a <?php echo ( ($active_menu=='invest')? 'class="active"' : ''); ?> href="<?= base_url('invest') ?>">
                        <i class="fa fa-money"></i> <span>Transaksi Pendanaan</span>
                    </a>
                </li>
                <li>
                    <a <?php echo ( ($active_menu=='bkdwallet')? 'class="active"' : ''); ?> href="<?= base_url('bkdwallet') ?>">
                        <i class="fa fa-dollar"></i> <span>BKD Wallet</span>
                    </a>
                </li>
                <li>
                    <a <?php echo ( ($active_menu=='reporting')? 'class="active"' : ''); ?> href="<?= base_url('reporting') ?>">
                        <i class="fa fa-list-alt"></i> <span>Reporting</span>
                    </a>
                </li>
              
                <li>
                    <a <?php echo ( ($active_menu=='agent')? 'class="active"' : ''); ?> href="<?= base_url('agent') ?>">
                        <i class="fa fa-group"></i> <span>Agent</span>
                    </a>
                </li>
                
                <li>
                    <a <?php echo ( ($active_menu=='top_up')? 'class="active"' : ''); ?> href="<?= base_url('top_up') ?>">
                        <i class="fa fa-cloud-upload"></i> <span>Top Up</span>
                    </a>
                </li>
                <li>
                    <a <?php echo ( ($active_menu=='redeem')? 'class="active"' : ''); ?> href="<?= base_url('redeem') ?>">
                        <i class="fa fa-cloud-download"></i> <span>Redeem</span>
                    </a>
                </li>
                <li>
                    <a <?php echo ( ($active_menu=='rekening_koran')? 'class="active"' : ''); ?> href="<?= base_url('rekening_koran') ?>">
                        <i class="fa fa-indent"></i> <span>Rekening Koran</span>
                    </a>
                </li>
                <li>
                    <a <?php echo ( ($active_menu=='platform')? 'class="active"' : ''); ?> href="<?= base_url('platform') ?>">
                        <i class="fa fa-gift"></i> <span>Platform</span>
                    </a>
                </li>
                <li>
                    <a <?php echo ( ($active_menu=='setting')? 'class="active"' : ''); ?> href="<?php echo base_url('setting'); ?>">
                        <i class="fa fa-gear"></i> <span>Setting</span>
                    </a>
                </li>
                <li>
                    <a <?php echo ( ($active_menu=='pages')? 'class="active"' : ''); ?> href="<?php echo base_url('pages'); ?>">
                        <i class="fa fa-book"></i> <span>Pages</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('log/out') ?>">
                        <i class="fa fa-sign-out"></i>
                        <span>Log Out</span>
                    </a>
                </li>
            </ul>            
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>