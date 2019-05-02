<div class="modal-body">
	<table class="display table table-striped dataTable">
		<tr>
			<td>Loan Status</td>
			<td>
				<?php 
				switch ($data['Master_loan_status']) {
					case 'approve':
						$html = '<span class="label label-primary">'.ucfirst($data['Master_loan_status']).'</span>';
						break;
					case 'lunas':
						$html = '<span class="label label-success">'.ucfirst($data['Master_loan_status']).'</span>';
						break;
					case 'reject':
						$html = '<span class="label label-danger">'.ucfirst($data['Master_loan_status']).'</span>';
						break;
					case 'review':
						$html = '<span class="label label-warning">'.ucfirst($data['Master_loan_status']).'</span>';
						break;
					case 'complete':
						$html = '<span class="label label-info">Telah didanai</span>';
						break;

					default:
						$html = '<span class="label label-default">'.ucfirst($data['Master_loan_status']).'</span>';
						break;
				}
				echo $html; 
				?>
			</td>
		</tr>
		<tr>
			<td>Tanggal Pengajuan</td><td><?php echo date('d F Y, H:i', strtotime($data['Tgl_permohonan_pinjaman'])); ?> WIB</td>
		</tr>
		<tr>
			<td>Nama</td><td><?php echo $data['Nama_pengguna']; ?></td>
		</tr>
		<?php if (!empty($data['mum_email']) ) { ?>
		<tr>
			<td>Email</td><td><?php echo $data['mum_email']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Telp</td><td><?php echo $data['Mobileno']; ?></td>
		</tr>
		<tr>
			<td>Tempat / Tanggal Lahir</td>
			<td><?php
			 $tglLahir = ($data['Tanggal_lahir'] == '0000-00-00' OR $data['Tanggal_lahir'] == '0000-00-00 00:00:00')? '' : date('d-m-Y', strtotime($data['Tanggal_lahir']));
			 echo $data['Tempat_lahir'] .' / '.$tglLahir;  ?></td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td><td><?php echo $data['Gender']; ?></td>
		</tr>
		<tr>
			<td>Alamat sesuai dengan ktp</td><td><?php echo $data['Alamat']; ?></td>
		</tr>

		<tr>
			<td>Provinsi sesuai dengan ktp</td><td><?php echo $data['Nama_Provinsi']; ?></td>
		</tr>		
		<tr>
			<td>Kota sesuai dengan ktp</td><td><?php echo $data['Nama_Kota']; ?></td>
		</tr>
		
		<tr>
			<td>Kode Pos sesuai dengan ktp</td><td><?php echo $data['Kodepos']; ?></td>
		</tr>
		<?php if ($data['Check_Domisili']=='0') { ?>
		<tr>
			<td>Alamat Domisili</td><td><?php echo $data['Alamat_Domisili']; ?></td>
		</tr>
		<tr>
			<td>Provinsi Domisili</td><td><?php echo $data['Provinsi_Domisili']; ?></td>
		</tr>		
		<tr>
			<td>Kota Domisili</td><td><?php echo $data['Kota_Domisili']; ?></td>
		</tr>
		<tr>
			<td>Kode Pos Domisili</td><td><?php echo $data['Kodepos_Domisili']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Foto Profil</td>
			<td>
				<img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/foto/'. $data['images_foto_name']); ?>" alt="" />		
			</td>
		</tr>
		<tr>
			<td>Pekerjaan</td>
			<td>
				<?php
				$array_pekerjaan = array('1'=>'PNS', '2'=>'BUMN', '3'=>'Swasta', '4'=>'Wiraswasta', '5'=>'Lain-lain'); 
				echo (empty($data['Pekerjaan']))? '' : $array_pekerjaan[$data['Pekerjaan']]; 
				?>
				
			</td>
		</tr>
		<tr>
			<td>No.NIK</td><td><?php echo $data['Id_ktp']; ?></td>
		</tr>
		<tr>
			<td>Foto NIK</td>
			<td><img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/ktp/'. $data['images_ktp_name']); ?>" alt="" /></td>
		</tr>
		<tr>
			<td>Nomor Rekening</td><td><?php echo $data['Nomor_rekening']; ?></td>
		</tr>
		<tr>
			<td>Bank</td><td><?php echo $data['Nama_Bank']; ?></td>
		</tr>
		<tr>
			<?php
				switch ($data['Pendidikan']) {
				    case '1':
				        $label_pendidikan = 'SD';
				        break;
				    case '2':
				        $label_pendidikan = 'SMP';
				        break;
				    case '3':
				        $label_pendidikan = 'SMA';
				        break;
				    case '4':
				        $label_pendidikan = 'DIPLOMA';
				        break;
				    case '4':
				        $label_pendidikan = 'SARJANA';
				        break;
				    //default:
				       // $label_pendidikan = 'lain-lain';
				        //break;
				}
			?>
			<td>Pendidikan</td><td><?php echo $label_pendidikan; ?></td>
		</tr>
		<tr>
			<td>Bidang Pekerjaan</td><td><?php echo $data['Bidang_pekerjaan']; ?></td>
		</tr>
		<tr>
			<td>Agama</td><td><?php echo $data['Jenis_Agama']; ?></td>
		</tr>
		<tr>
			<td>Status Pernikahan</td><td><?php echo $data['status_nikah']; ?></td>
		</tr>
		<tr>
			<td>Jumlah Tanggungan</td><td><?php echo $data['How_many_people_do_you_financially_support']; ?></td>
		</tr>
		<tr>
			<?php
				switch ($data['status_tempat_tinggal']) {
				    case '1':
				        $label_status_tempat_tinggal = 'Milik Keluarga';
				        break;
				    case '2':
				        $label_status_tempat_tinggal = 'Milik Sendiri';
				        break;
				    case '3':
				        $label_status_tempat_tinggal = 'Sewa';
				        break;
				    //default:
				       // $label_pendidikan = 'lain-lain';
				        //break;
				}
			?>
			<td>Status Tempat Tinggal</td><td><?php echo $label_status_tempat_tinggal; ?></td>
		</tr>
		<tr>
			<td>Foto Pegang IDCard/EKTP</td>
			<td><img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/pegang_ktp/'. $data['foto_pegang_ktp']); ?>" alt="" /></td>
		</tr>
		<tr>
			<td>Foto CF</td>
			<td><img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/cf/'. $data['images_cf_name']); ?>" alt="" /></td>
		</tr>
		<tr>
			<td>Foto Progress Report File</td>
			<td><img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/progress_report/'. $data['images_progress_report_name']); ?>" alt="" /></td>
		</tr>
		<!-- <tr>
			<td>Foto Hasil Panen 1</td>
			<td><img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/hasil_panen1/'. $data['images_hasil_panen_name1']); ?>" alt="" /></td>
		</tr>
		<tr>
			<td>Foto Hasil Panen 2</td>
			<td><img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/hasil_panen2/'. $data['images_hasil_panen_name2']); ?>" alt="" /></td>
		</tr>
		<tr>
			<td>Foto Progress Hasil Panen 3</td>
			<td><img width="300" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/hasil_panen3/'. $data['images_hasil_panen_name3']); ?>" alt="" /></td>
		</tr> -->

		<tr>
			<td>Grade</td><td><?php echo $data['peringkat_pengguna']; ?></td>
		</tr>
		<tr>
			<td>Jumlah Pinjaman</td><td>Rp <?php echo number_format($data['Jml_permohonan_pinjaman']); ?></td>
		</tr>
		<tr>
			<td>Jumlah Pinjaman disetujui</td><td>Rp <?php echo number_format($data['Jml_permohonan_pinjaman_disetujui']); ?></td>
		</tr>
		<tr>
			<td>Tenor</td><td><?php echo $data['loan_term_permohonan']; ?> Hari </td>
		</tr>
		

	</table>
</div>