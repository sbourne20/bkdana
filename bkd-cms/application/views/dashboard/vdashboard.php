<section class="wrapper">
	
	<!--mini statistics start-->
  <div class="row">
      <div class="col-md-4">
          <div class="mini-stat clearfix">
              <a href="<?php echo site_url('peminjam'); ?>">
                <span class="mini-stat-icon orange"><i class="fa fa-gavel"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo $total_peminjam['itotal']; ?></span>
                    Peminjam
                </div>
              </a>
          </div>
      </div>
      <div class="col-md-4">
          <div class="mini-stat clearfix">
              <a href="<?php echo site_url('pendana'); ?>">
                <span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
                  <div class="mini-stat-info">
                    <span><?php echo $total_pendana['itotal']; ?></span>
                    Pendana - <em>Status Pending</em>
                </div>
              </a>
          </div>
      </div>

      <div class="col-md-4">
          <div class="mini-stat clearfix">
              <a href="<?php echo site_url('transaksi_pinjaman_kilat'); ?>">
                <span class="mini-stat-icon pink"><i class="fa fa-money"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($kilat['itotal']); ?></span>
                    Pinjaman Kilat <br><em>Status Review</em>
                </div>
              </a>
          </div>
      </div>
      <div class="col-md-4">
          <div class="mini-stat clearfix">
              <a href="<?php echo site_url('transaksi_pinjaman_mikro'); ?>">
                <span class="mini-stat-icon kuning"><i class="fa fa-money"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($mikro['itotal']); ?></span>
                    Pinjaman Mikro <br><em>Status Review</em>
                </div>
              </a>
          </div>
      </div>
      <div class="col-md-4">
          <div class="mini-stat clearfix">
              <a href="<?php echo site_url('invest'); ?>">
                <span class="mini-stat-icon green"><i class="fa fa-money"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($pendanaan['itotal']); ?></span>
                    Pendanaan <br><em>Status Pending</em>
                </div>
              </a>
          </div>
      </div>

      <div class="col-md-4">
          <div class="mini-stat clearfix">
              <a href="<?php echo site_url('top_up'); ?>">
                <span class="mini-stat-icon green"><i class="fa fa-cloud-upload"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo $total_topup_pending['itotal']; ?></span>
                    Top Up <br> <em>Status Pending</em>
                </div>
              </a>
          </div>
      </div>
      <div class="col-md-4">
          <div class="mini-stat clearfix">
              <a href="<?php echo site_url('redeem'); ?>">
                <span class="mini-stat-icon secondary"><i class="fa fa-cloud-download"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo $total_redeem['itotal']; ?></span>
                    Redeem <br> <em>Status Pending</em>
                </div>
              </a>
          </div>
      </div>
  </div>
  <!--mini statistics end-->

</section>