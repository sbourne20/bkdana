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
				<img width="100%" src="<?php echo site_url('fileload?p=') . 'member/' . $data['id_mod_user_member'] .'/foto/'. $data['images_foto_name']; ?>" alt="" />		
			</td>
		</tr>
		<tr>
			<td>Pekerjaan</td>
			<td>
				<?php
				$array_pekerjaan = array('1'=>'PNS', '2'=>'BUMN', '3'=>'Swasta', '4'=>'Wiraswasta', '5'=>'Lain-lain'); 
				if (!empty($data['Pekerjaan'])) {
					echo $array_pekerjaan[$data['Pekerjaan']]; 
				}
				?>
				
			</td>
		</tr>
		<tr>
			<td>No.NIK</td><td><?php echo $data['Id_ktp']; ?></td>
		</tr>
		<tr>
			<td>Foto NIK</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . 'member/' . $data['id_mod_user_member'] .'/ktp/'. $data['images_ktp_name']; ?>" alt="" /></td>
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

		<?php if (!empty($data['What_is_the_name_of_your_business']) ) { ?>
		<tr>
			<td>Usaha</td><td><?php echo $data['What_is_the_name_of_your_business']; ?></td>
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

		<?php if (!empty($data['images_usaha_name']) ) { ?>
		<tr>
			<td>Foto Usaha</td>
			<td><img width="100%" src="<?php echo site_url('fileload?p=') . 'member/' . $data['id_mod_user_member'] .'/usaha/'. $data['images_usaha_name']; ?>" alt="" /></td>
		</tr>
		<?php } ?>
	</table>
</div>