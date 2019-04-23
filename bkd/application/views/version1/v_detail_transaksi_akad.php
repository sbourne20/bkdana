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
                                        }else if($transaksi['type_of_business_id'] == 3){
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
                                        }else{
                                            // Agri
                                            if($transaksi['type_of_interest_rate']==1){
                                                $tenor_label = 'Hari';
                                                $submit_url  = site_url('submit-bayar-cicilan-agri');  
                                            }
                                            if($transaksi['type_of_interest_rate']==2){
                                                $tenor_label = 'Bulan';
                                                $submit_url  = site_url('submit-bayar-cicilan-agri');   
                                            }
                                            if($transaksi['type_of_interest_rate']==3){
                                                $tenor_label = 'Minggu';                                
                                                $submit_url  = site_url('submit-bayar-cicilan-agri');   
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
                                               
                                            </tr>
                                            <tr>
                                                <td><span>Pinjaman Disetujui</span> <?php echo number_format($transaksi['Amount']); ?> IDR</td>
                                                <td><span>Dana Cair</span> <?php echo number_format($total_bayar); ?> IDR</td>
                                            </tr>
                                           
                                        </table>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="panel panel-default">
                                
                                    <div class="panel-body">
                                        
                                        <div class="text-center">
                                            <a href="" class="btn btn-green" title="Kembali" onclick="window.history.go(-1); return false;">Kembali</a>

                                            
                                            <a data-toggle="modal" data-target="#modalAkad" href="#" class="btn btn-purple" title="Approve">Approve</a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php 

$approval_update = site_url('approval-akad');
$nowdate = date('d-m-Y');
$day=date('Y-m-d');
$notelepon = $transaksi['Mobileno'];
$tele = substr_replace($notelepon,'0',0,3);
?>

<!-- Modal bayar -->
<div class="modal fade" id="modalAkad" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="width:720px;margin:-50px">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="section-payment-process">    
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>PERJANJIAN PINJAMAN USAHA KOORDINATOR TANI</h4><br>
                            <h5><strong>No[<?php echo $log_pinjaman['Master_loan_id'] ?>]</strong></h5></br></br>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <p align="justify">Perjanjian ini dibuat pada hari <?php echo hari_ini()?> tanggal <?php echo tgl_indo($day); ?> antara: </br>

PT Berkah Kelola Dana, suatu perseroan terbatas yang didirikan berdasarkan akta nomor 11 pada tanggal 12 Oktober 2017 beralamat di Jalan Guntur No. 45 RT.13/RW.5, Kel. Pasar Manggis, Kec. Setia Budi, Jakarta Selatan sebagai Penyelenggara, bertindak atas nama Pemberi Pinjaman. Dalam hal ini diwakilkan oleh Eindrata Tanukusuma selaku Head of Marketing PT. Berkah Kelola Dana berdasarkan surat kuasa direksi nomor <b>No. 02/BKD/OPS/III/2019</b>. </br>

<?php echo $transaksi['Nama_pengguna']; ?> , perseorangan yang memiliki kontrak Pertanian dengan PT Lentera Panen Mandiri dengan Nomor KTP: <?php echo $transaksi['Id_ktp']; ?> yang bertempat tinggal di <?php echo $transaksi['Alamat']; ?>, selanjutnya dalam perjanjian ini bertindak sebagai Peminjam.</p> </br>

Telah sepakat bahwa: </br></br>

<p align="center">Pasal 1</p> </br>

<p align="justify">Definisi </br></br>

BK Dana berarti PT Berkah Kelola Dana atau Penyelenggara 

Peminjam berarti perseorangan atau badan usaha yang menggunakan layanan pinjam meminjam berbasis teknologi informasi melalui platform BK Dana yang bermaksud untuk menerima pinjaman dari Pemberi Pinjaman 

Pemberi Pinjaman berarti perseorangan atau badan usaha yang bermaksud untuk memberikan pinjaman kepada Peminjam dengan menunjuk BK Dana sebagai penerima kuasa dalam Perjanjian Pemberi Pinjaman. Pemberian pinjaman dilakukan dengan menggunakan layanan pinjam meminjam berbasis teknologi informasi melalui platform BK Dana. 

Para Pihak berarti PT Berkah Kelola Dana, Peminjam, dan Pemberi Pinjaman 

Fasilitas Pinjaman berarti layanan pinjam meminjam uang yang disediakan oleh BK Dana</p> </br></br>

<p align="center">Pasal 2 </p></br>

<p align="justify">Pinjaman Usaha Koordinator Tani</br></br> 

Peminjam menyetujui telah menerima Fasilitas Pinjaman Usaha Koordinator Tani dari Pemberi Pinjaman sebesar Rp. <?php echo number_format($transaksi['Amount']); ?> untuk mendukung kegiatan pembiayaan perdagangan hasil pertanian. Berdasarkan pinjaman tersebut, Peminjam dikenakan bunga sebesar 0,2% per hari dari jumlah pinjaman. Bunga dihitung dari hari pencairan sampai hari pelunasan pinjaman. 

Peminjam memberikan izin kepada PT Lentera Panen Mandiri untuk: 

Melakukan transfer dana penjualan hasil pertanian Peminjam ke Virtual Account Peminjam pada rekening Escrow BK Dana 

Memberikan data Peminjam yang ada pada PT Lentera Panen Mandiri ke BK Dana untuk tujuan verifikasi data. 

Peminjam memberikan kuasa kepada BK Dana untuk memotong dana penjualan hasil pertanian dari PT Lentera Panen Mandiri yang ditransfer ke Virtual Account Peminjam pada rekening Escrow BK Dana. Pemotongan dana hanya untuk membayar pokok pinjaman beserta bunga, biaya, dan denda. Sisa dana akan ditransfer ke rekening Peminjam yang terdaftar pada platform BK Dana. 

Peminjam mengizinkan BK Dana untuk menyerahkan data Peminjam yang ada pada BK Dana ke PT Lentera Panen Mandiri dengan maksud memberikan informasi keperluan pembiayaan usaha. 

Pinjaman akan ditransfer ke rekening Peminjam yang terdaftar di platform BK Dana 1 hari kerja setelah proses penggalangan dana dari Pemberi Pinjaman sudah mencapai 100%. Jika penggalangan dana tidak mencapai 100% dalam waktu 2 hari kerja setelah tanggap perjanjian ini, maka pinjaman dianggap gagal dan perjanjian ini dianggap berakhir. 
</p></br></br>
 

<p align="center">Pasal 3</p></br> 

<p align="justify">Jangka Waktu, Pelunasan, dan Biaya Administrasi</br></br> 

Pinjaman Usaha Koordinator Tani ini diberikan dengan jangka waktu <?php echo number_format($log_pinjaman['ltp_product_loan_term']); ?> hari kerja terhitung sejak tanggal pencairan. 

Peminjam melakukan pelunasan dalam perjanjian ini paling lambat pada tanggal pencairan ditambah jangka waktu. 

Pelunasan dipercepat diperbolehkan dengan syarat paling cepat dilakukan 3 hari setelah tanggal pencairan. 

Semua biaya yang timbul dari akad pinjaman ditanggung oleh Pihak Peminjam. 

Peminjam diwajibkan membayar : </br>

Administrasi & Provisi 0,5% dari pencairan Pinjaman : Rp. <?php echo number_format($log_pinjaman['ltp_admin_fee']); ?> </br>

Materai         : Rp. 12.000,- </br>

Biaya Transfer Bank: Rp. 6.500,- </br>

Biaya-biaya tersebut akan dipotong langsung dari pencairan pinjaman. 
</p>
</br>
</br> 

<p align="center">Pasal 4</p></br>

<p align="justify">Kewajiban Peminjam </br></br>

Sehubungan dengan Pinjaman Koordinator Tani ini maka Peminjam berkewajiban untuk melakukan hal-hal sebagai berikut : 

Mengembalikan seluruh jumlah pokok dan bunga yang ditetapkan oleh BK Dana  paling lambat saat jatuh tempo perjanjian pinjaman. 

Memastikan pembayaran yang telah jatuh tempo ditransfer ke rekening PT. Berkah Kelola Dana, <?php echo ($transaksi['nama_bank']); ?> dengan nomor rekening : 54321-<?php echo $tele; ?>. 

Memberikan laporan sebelumnya sehubungan dengan adanya perubahan  alamat, usaha, kepemilikan, dan lain-lain yang dapat mengganggu perjanjian ini. 

Peminjam wajib menyetor pembayaran yang akan jatuh tempo sehari sebelumnya, apabila jatuh tempo angsuran di  hari Libur Nasional.  
</p>
</br>
</br> 

<p align="center">Pasal 5</p></br> 

<p align="justify">Pelanggaran Atas Syarat-Syarat Perjanjian </br></br>

Bahwa Peminjam dianggap melanggar perjanjian jika : 

Melanggar dan/atau tidak dapat memenuhi peraturan-peraturan dan ketentuan dalam perjanjian ini atau tidak dapat memenuhi syarat-syarat perjanjian ini serta perjanjian lainnya yang bersangkutan dan atau syarat-syarat serta ketentuan yang ditetapkan oleh PT Berkah Kelola Dana baik surat-surat/ dokumen. 

Menggunakan pinjaman yang diberikan oleh BK Dana untuk keperluan dan kepentingan di luar pembiayaan usaha perdagangan hasil pertanian. 

Lalai dalam melaksanakan kewajiban (pembayaran) menurut perjanjian ini.  
</p>
</br>
</br> 

<p align="center">Pasal 6</p></br> 

<p align="justify">Sanksi </br></br>

Apabila   Peminjam   melanggar   salah   satu   dari   ketentuan   yang   ada   padaPerjanjian ini, atau Peminjam lalai melaksanakan kewajibannya sebagai-manatercantum   dalam   Perjanjian   ini,   maka   Peminjam   dikenakan     denda[Rp.100.000],- dan tambahan bunga [0.1%] per hari dari jumlah pinjaman.
Adanya   pembayaran   tersebut   tidak   mengurangi   hak   BK   Dana   untukmelakukan tindakan upaya hukum lainnya kepada Peminjam sebagai akibatdari terjadinya pelanggaran dan atau kelalaian Peminjam. 
</p>
 

<p align="justify">Hal-hal yang belum cukup diatur dalam perjanjian ini, akan diatur berdasarkan kesepakatan Para Pihak dalam surat/akta yang merupakan satu kesatuan dengan perjanjian ini. Demikian perjanjian ini dibuat dan ditanda tangani pada hari ini, tanggal <?php echo $nowdate; ?> sebagaimana tercantum di atas. </p>
                            <br><br>
                            <form id="form_akad" method="POST" action="<?php echo $approval_update; ?>">
                                 <input type="hidden" name="transaksi_id" value="<?php echo $transaksi['Master_loan_id']; ?>">
                                 <input type="hidden" name="id_peminjam" value="<?php echo $transaksi['Id_pengguna']; ?>">
                                 <input type="hidden" name="jmlpinjaman_disetujui" value="<?php echo $total_bayar ?>">
                                 <input type="hidden" name="Master_loan_status" value="complete">
                                <button type="button" id="approve_akad" style="background: transparent; border: none;">
                                    <a href="javascript:;" data-dismiss="modal" class="btn btn-purple">Approve</a>
                                    </button>
                            </form>
                                   
                                    <br><br>

                            <p class="note muted"><i>* Catatan : Anda akan dianggap menyetujui surat perjanjian yang terlampir jika Anda menekan tombol Approve.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
function tgl_indo($tanggal){
    $bulan = array (
        1 =>'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);
 
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}


function hari_ini(){
    $hari = date ("D");
 
    switch($hari){
        case 'Sun':
            $hari_ini = "Minggu";
        break;
 
        case 'Mon':         
            $hari_ini = "Senin";
        break;
 
        case 'Tue':
            $hari_ini = "Selasa";
        break;
 
        case 'Wed':
            $hari_ini = "Rabu";
        break;
 
        case 'Thu':
            $hari_ini = "Kamis";
        break;
 
        case 'Fri':
            $hari_ini = "Jumat";
        break;
 
        case 'Sat':
            $hari_ini = "Sabtu";
        break;
        
        default:
            $hari_ini = "Tidak di ketahui";     
        break;
    }
 
    return  $hari_ini;
 
}
?>