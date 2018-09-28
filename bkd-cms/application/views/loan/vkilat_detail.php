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
		
		<?php if ($data['date_close'] != '0000-00-00 00:00:00' AND $data['date_close'] != '') { ?>
		<tr>
			<td>Tanggal Lunas</td><td><?php echo date('d M Y', strtotime($data['date_close'])); ?></td>
		</tr>
		<?php } ?>

		<tr>
			<td>Tanggal Pengajuan</td><td><?php echo date('d F Y, H:i', strtotime($data['Tgl_permohonan_pinjaman'])); ?> WIB</td>
		</tr>
		<tr>
			<td>Nama</td><td><?php echo $data['Nama_pengguna']; ?></td>
		</tr>
		<tr>
			<td>Telp</td><td><?php echo $data['Mobileno']; ?></td>
		</tr>
		<tr>
			<td>Tempat / Tanggal Lahir</td><td><?php echo $data['Tempat_lahir'] .' / '. date('d-m-Y', strtotime($data['Tanggal_lahir'])); ?></td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td><td><?php echo $data['Jenis_kelamin']; ?></td>
		</tr>
		<tr>
			<td>Alamat</td><td><?php echo $data['Alamat']; ?></td>
		</tr>		
		<tr>
			<td>Kota</td><td><?php echo $data['Kota']; ?></td>
		</tr>
		<tr>
			<td>Provinsi</td><td><?php echo $data['Provinsi']; ?></td>
		</tr>
		<tr>
			<td>Kode Pos</td><td><?php echo $data['Kodepos']; ?></td>
		</tr>
		<tr>
			<td>Foto Profil</td>
			<td>
				<img width="300" src="<?php echo $this->config->item('images_member_uri') . $data['id_mod_user_member'] .'/foto/'. $data['images_foto_name']; ?>" alt="" />		
			</td>
		</tr>
		<tr>
			<td>Pekerjaan</td>
			<td>
				<?php
				$array_pekerjaan = array('1'=>'PNS', '2'=>'BUMN', '3'=>'Swasta', '4'=>'Wiraswasta', '5'=>'Lain-lain'); 
				echo $array_pekerjaan[$data['Pekerjaan']]; 
				?>
				
			</td>
		</tr>
		<tr>
			<td>No.NIK</td><td><?php echo $data['Id_ktp']; ?></td>
		</tr>
		<tr>
			<td>Foto NIK</td>
			<td><img width="300" src="<?php echo $this->config->item('images_member_uri') . $data['id_mod_user_member'] .'/ktp/'. $data['images_ktp_name']; ?>" alt="" /></td>
		</tr>
		<tr>
			<td>Nomor Rekening</td><td><?php echo $data['Nomor_rekening']; ?></td>
		</tr>
		<tr>
			<td>Bank</td><td><?php echo $data['nama_bank']; ?></td>
		</tr>
		<tr>
			<td>Grade</td><td><?php echo $data['peringkat_pengguna']; ?></td>
		</tr>
		<tr>
			<td>Jumlah Pinjaman</td><td>Rp <?php echo number_format($data['Jml_permohonan_pinjaman']); ?></td>
		</tr>
		<tr>
			<td>Jumlah Pinjaman - Bunga</td><td>Rp <?php echo number_format($data['Jml_permohonan_pinjaman_disetujui']); ?></td>
		</tr>
		<tr>
			<td>Tenor</td><td><?php echo $data['Loan_term']; ?> Hari</td>
		</tr>

	</table>
</div>