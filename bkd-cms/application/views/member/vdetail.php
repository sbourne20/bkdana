<div class="modal-body">
	<table class="display table table-striped dataTable">
		<tr>
			<td>Join Date</td><td><?php echo date('d M Y', strtotime($data['Tgl_record'])); ?></td>
		</tr>
		<tr>
			<td>Email</td><td><?php echo $data['mum_email']; ?></td>
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
			<td>Jenis Kelamin</td><td><?php echo $data['Gender']; ?></td>
		</tr>
		<tr>
			<td>Alamat Sesuai dengan KTP</td><td><?php echo $data['Alamat']; ?></td>
		</tr>
		<tr>
			<td>Provinsi Sesuai dengan KTP</td><td><?php echo $data['Nama_Provinsi']; ?></td>
		</tr>		
		<tr>
			<td>Kota Sesuai dengan KTP</td><td><?php echo $data['Nama_Kota']; ?></td>
		</tr>
		<tr>
			<td>Kode Pos Sesuai dengan KTP</td><td><?php echo $data['Kodepos']; ?></td>
		</tr>
		<?php if ($data['mum_type_peminjam'] == '3') { ?>
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
		<?php } ?>
		<tr>
			<td>Foto Profil</td>
			<td>
				<img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/foto/'. $data['images_foto_name']); ?>" alt="" />		
			</td>
		</tr>
		<tr>
			<td>Pekerjaan</td>
			<td>
				<?php
					echo $data['Jenis_Pekerjaan'];
				?>
				
			</td>
		</tr>
		<tr>
			<td>No.KTP</td><td><?php echo $data['Id_ktp']; ?></td>
		</tr>
		<tr>
			<td>Foto KTP</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/ktp/'. $data['images_ktp_name']); ?>" alt="" /></td>
		</tr>
		<tr>
			<td>Nomor Rekening</td><td><?php echo $data['Nomor_rekening']; ?></td>
		</tr>
		<tr>
			<td>Bank</td><td><?php echo $data['Nama_Bank']; ?></td>
		</tr>
		<?php if (!empty($data['Pendidikan']) ) { ?>
		<tr>
			<td>pendidikan</td><td><?php echo $data['Pendidikan']; ?></td>
		</tr>
		<?php } ?>
		<?php if ($data['mum_type_peminjam'] == '3') { ?>
		<tr>
			<td>Bidang Pekerjaan</td><td><?php echo $data['Bidang_pekerjaan']; ?></td>
		</tr>
		<tr>
			<td>Agama</td><td><?php echo $data['Agama']; ?></td>
		</tr>
		<tr>
			<td>Status Pernikahan</td><td><?php echo $data['status_nikah']; ?></td>
		</tr>
		<tr>
			<td>Jumlah Tanggungan</td><td><?php echo $data['How_many_people_do_you_financially_support']; ?></td>
		</tr>
		<tr>
			<td>Status Tempat Tinggal</td><td><?php echo $data['status_tempat_tinggal']; ?></td>
		</tr>
		<tr>
			<td>Foto Pegang KTP</td>
			<td>
				<img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/pegang_ktp/'. $data['foto_pegang_ktp']); ?>" alt="" />		
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td>Grade</td><td><?php echo $data['peringkat_pengguna']; ?></td>
		</tr>

		<?php if ($data['mum_type_peminjam'] == '2') { ?>
		<?php if (!empty($data['What_is_the_name_of_your_business']) ) { ?>
		<tr>
			<td>Usaha</td><td><?php echo $data['What_is_the_name_of_your_business']; ?></td>
		</tr>
		<tr>
			<td>Deskripsi Usaha</td><td><?php echo $data['deskripsi_usaha']; ?></td>
		</tr>
		<tr>
			<td>Lama Usaha</td><td><?php 
			switch ($data['How_many_years_have_you_been_in_business']) {
				case '0':
					$lamausaha = 'kurang dari 1 tahun';
					break;
				case '11':
					$lamausaha = 'lebih dari 10 tahun';
					break;
				
				default:
					$lamausaha = $data['How_many_years_have_you_been_in_business']. ' tahun';
					break;
			}
			echo $lamausaha; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Omzet Usaha</td><td><?php echo $data['omzet_usaha']; ?></td>
		</tr>
		<tr>
			<td>Modal Usaha</td><td><?php echo $data['modal_usaha']; ?></td>
		</tr>
		<tr>
			<td>Margin Usaha</td><td><?php echo $data['margin_usaha']; ?></td>
		</tr>
		<tr>
			<td>Biaya Operasional</td><td><?php echo $data['biaya_operasional']; ?></td>
		</tr>
		<tr>
			<td>Laba Usaha</td><td><?php echo $data['laba_usaha']; ?></td>
		</tr>

		<?php if (!empty($data['images_usaha_name']) ) { ?>
		<tr>
			<td>Foto Usaha 1</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/usaha/'. $data['images_usaha_name']); ?>" alt="" /></td>
		</tr>
		<?php } ?>

		<?php if (!empty($data['images_usaha_name2']) ) { ?>
		<tr>
			<td>Foto Usaha 2</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/usaha2/'. $data['images_usaha_name2']); ?>" alt="" /></td>
		</tr>
		<?php } ?>

		<?php if (!empty($data['images_usaha_name3']) ) { ?>
		<tr>
			<td>Foto Usaha 3</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/usaha3/'. $data['images_usaha_name3']); ?>" alt="" /></td>
		</tr>
		<?php } ?>

		<?php if (!empty($data['images_usaha_name4']) ) { ?>
		<tr>
			<td>Foto Usaha 4</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/usaha4/'. $data['images_usaha_name4']); ?>" alt="" /></td>
		</tr>
		<?php } ?>

		<?php if (!empty($data['images_usaha_name5']) ) { ?>
		<tr>
			<td>Foto Usaha 5</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . urlencode('member/' . $data['id_mod_user_member'] .'/usaha5/'. $data['images_usaha_name5']); ?>" alt="" /></td>
		</tr>
		<?php } ?>	
		<?php } ?>
		<?php if ($data['mum_type'] == '2') { ?>
		<tr>
			<td>Status Pernikahan</td><td><?php echo $data['status_nikah']; ?></td>
		</tr>
		<tr>
			<td>Jumlah Tanggungan</td><td><?php echo $data['How_many_people_do_you_financially_support']; ?></td>
		</tr>
		<?php if (!empty($data['Pendidikan']) ) { ?>
		<tr>
			<td>pendidikan</td><td><?php echo $data['Pendidikan']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Jumlah Penghasilan</td><td><?php echo $data['average_monthly_salary']; ?></td>
		</tr>
		<tr>
			<td>NPWP</td><td><?php echo $data['npwp']; ?></td>
		</tr>
		<?php } ?>
	</table>
</div>