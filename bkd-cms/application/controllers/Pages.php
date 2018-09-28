<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	/*  Static Page  */

	function __construct()
	{
		parent::__construct();

		$this->load->model('pages_model');
		$this->load->model('user_model');
		
		$this->user_model->has_login();
	}

	function index()
	{
		// error_reporting(E_ALL);
		$output['PAGE_TITLE'] = 'Pages';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';

		// use this js for this page
		$mainData['bottom_js'] .= add_js('js/data/pages.js');

		$mainData['mainContent']  = $this->load->view('pages/vpages_list', $output,true);
		$this->load->view('vbase',$mainData);
	}

	function json()
	{
		$data = $this->pages_model->get_pages();
		print_r($data);
	}

	function add()
	{
		// error_reporting(E_ALL);
		
		$mainData['CU']       = $this->user_model->current_user();
		$output['PAGE_TITLE'] = 'ADD PAGE';
		$output['add_mode']   = 1; // sbg tanda add new
		$output['EDIT']       = '';

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{
			$mainData['top_css']   ="";
			$mainData['top_js']    ="";
			$mainData['bottom_js'] ="";
			
			$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

			$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");

			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');
			
			$mainData['mainContent'] = $this->load->view('pages/vpages_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post();

			/*_d($post);
			exit;*/

			if($_FILES['imgfile']['name'] == ''){
				$file_image_name   = '';
			}else{
				// ----- Process Image Name -----
				$img_info          = pathinfo($_FILES['imgfile']['name']);
				$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
				$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
				$fileExt           = $img_info['extension'];
				$file_image_name   = $fileName.'.'.$fileExt;
				// ----- END Process Image Name -----
			}

			$data['p_title']            = in(trim($post['ptitle']));
			$data['p_subtitle']         = in(trim($post['subtitle']));
			$data['p_slug']             = trim($post['slug']);
			//$data['p_summary']          = in($post['summary']);
			$data['p_content']          = in($post['pcontent']);
			$data['p_images']           = $file_image_name;
			$data['p_created_date']     = date("Y-m-d H:i:s");
			$data['p_modified_date']    = date("Y-m-d H:i:s");
			$data['p_status']           = $post['pstatus'];

			$insertID = $this->pages_model->insert_new_page($data);
			if ($insertID){
				if($_FILES['imgfile']['name'] != ''){
					// ------- Upload Image file --------
					$destination = $this->config->item('pages_images_dir'). $insertID."/";
					if (!is_file($destination.$file_image_name)) {
						mkdir_r($destination);
					}
					move_uploaded_file($_FILES['imgfile']['tmp_name'], $destination.$file_image_name);

					// ---- Create Thumbnail ----
					// $this->create_img_thumbnail($destination.$file_image_name);
				}

				$this->session->set_userdata('message','Data berhasil disimpan.');
				$this->session->set_userdata('message_type','success');


				
				redirect($this->uri->segment(1)); 
			}
			exit();
		}
	}

	function edit()
	{
		// error_reporting(E_ALL);
		$ID                   = $this->uri->segment(3);
		$output['PAGE_TITLE'] = 'EDIT PAGES';
		$output['add_mode']   = 2; // sbg tanda edit
		$output['EDIT']       = $this->pages_model->get_page_byid($ID);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']   ="";
			$mainData['top_js']    ="";
			$mainData['bottom_js'] ="";
			
			$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

			$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");

			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');
			
			$mainData['mainContent'] = $this->load->view('pages/vpages_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post();

			$ID   = $post['pid'];
			$EDIT = $output['EDIT'];

			if($_FILES['imgfile']['name'] == ''){
				$file_image_name   = $EDIT['p_images'];
			}else{
				// ----- Process Image Name -----
				$img_info          = pathinfo($_FILES['imgfile']['name']);
				$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
				$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
				$fileExt           = $img_info['extension'];
				$file_image_name   = $fileName.'.'.$fileExt;
				// ----- END Process Image Name -----
			}

			$data['p_title']            = in(trim($post['ptitle']));
			$data['p_slug']             = trim($post['slug']);
			$data['p_subtitle']         = in(trim($post['subtitle']));
			//$data['p_summary']          = in($post['summary']);
			$data['p_content']          = in($post['pcontent']);
			$data['p_images']           = $file_image_name;
			$data['p_created_date']     = $post['pcreated'];
			$data['p_modified_date']    = date("Y-m-d H:i:s");
			$data['p_status']           = $post['pstatus'];

			$update = $this->pages_model->update_page($data, $ID);
			if ($update){
				if ($_FILES['imgfile']['name'] != '') {

					// Delete old Image
					unlink($this->config->item('pages_images_dir').$ID.'/'.$EDIT['p_images']);
					
					// Upload New Image
					$destination = $this->config->item('pages_images_dir'). $ID.'/';
					if (!is_file($destination.$file_image_name)) {
						mkdir_r($destination);
					}
					move_uploaded_file($_FILES['imgfile']['tmp_name'], $destination.$file_image_name);

					// ---- Create Thumbnail ----
					// $this->create_img_thumbnail($destination.$file_image_name);
				}
				$this->session->set_userdata('message','Data berhasil di-update.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','Tidak ada yang diubah.');
				$this->session->set_userdata('message_type','success');
			}
		 	//redirect($this->uri->segment(1));
		 	redirect('pages');
		}
	}

	function validation()
	{
		// error_reporting(E_ALL);
		$this->form_validation->set_rules('ptitle', 'Title', 'trim|required');
		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function update_type_title()
	{
		$post = $this->input->post();
		$ID = $post['pid'];
		$data['ptype_title'] = $post['title'];

		$update = $this->pages_model->update_page_type($data, $ID);
		if ($update){
			$this->session->set_userdata('message','Data berhasil di-update.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','Tidak ada yang diubah.');
			$this->session->set_userdata('message_type','success');
		}
	 	redirect($this->uri->segment(1));
	}

	function delete()
	{
		$ID = $this->uri->segment(3);
		$data         = $this->pages_model->get_page_byid($ID);
		$remove_quote = $this->pages_model->delete_page($ID);

		if($remove_quote){
			if (!empty($data['p_images'])) {
				// Delete image
				unlink($this->config->item('pages_images_dir') . $ID.'/'.$data['p_images']);
				rmdir($this->config->item('pages_images_dir') . $ID);
			}
			$this->session->set_userdata('message','Page berhasil dihapus.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','Tidak ada data yang dihapus.');
			$this->session->set_userdata('message_type','warning');
		}
		redirect('pages');
	}


	/*function create_img_thumbnail($fullpath)
	{
		// ------ Create Thumbnail -------
		$this->load->library('image_lib');
		$this->image_lib->clear();

		// create image thumbnail
		list($width, $height, $type, $attr) = getimagesize($fullpath);
		
		// landscape
		$config['image_library']  = 'gd2';
		$config['source_image']   = $fullpath;
		$config['create_thumb']   = true;
		$config['maintain_ratio'] = true;
        // we set to 25%
        if ($width >= $height) {
        	$res_width = 150;
        	$res_height = 100;
        } else {
        	$res_width = 0.25*$width;
        	$res_height = 0.25*$height;
        }
        $config['width'] = $res_width;
        $config['height'] = $res_height;
    	$this->image_lib->initialize($config);
        
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
	}*/

}

/* End of file pages.php */