<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_settings extends CI_Model {
	
	var $title = 'Pendanaan Gotong Royong Peer to Peer Lending | BKDana';
	
	var $meta_tag = array(
					// array('name' => 'keywords', 'content' => 'Keywords, Keywords, Keywords'),
					array('name' => 'description', 'content' => 'Pendanaan Gotong Royong Peer to Peer Lending'),
					array('name' => 'robots', 'content' => 'index,follow'),
					array('name' => 'author', 'content' => 'MD Creative Indonesia'),
					array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1'),
					array('name' => 'revisit-after', 'content' => '1 day')
					);
					
	public function title_sub_pages($pages)
	{
		return ucfirst($pages).$this->title;
	}
	
	public function title_dyn($tbl, $id)
	{
		$this->db->select('title');
		$where = "id_".$tbl." = $id";
		$this->db->where($where);
		$this->db->from($tbl);
		$query = $this->db->get();
		$hsl = $query->row_array();
		$total_row = $query->num_rows();
		if($total_row > 0)
		{
			return $hsl['title'].' | BKDana';
		}
		else
		{
			return '404 | BKDana';
		}
	}

	public function meta_default()
	{
		$this->db->select('*');
		$this->db->from('mod_setting');
		$this->db->where('id_mod_setting', '1');
		$query = $this->db->get();
		$hsl = $query->row_array();
		$total_row = $query->num_rows();

		//$query->free_result();

		if($total_row > 0)
		{
			return array(
				   array('name' => 'Content-Type', 'content' => 'text/html; charset=UTF-8', 'type' => 'equiv'),
				   array('name' => 'Content-Language', 'content' => 'en', 'type' => 'equiv'),
				   array('name' => 'keywords', 'content' => ''.$hsl['set_keywords'].''),
				   array('name' => 'description', 'content' => strip_tags(substr($hsl['set_descriptions'],0,160))),
				   array('name' => 'robots', 'content' => 'index,follow'),
				   array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'),
				   array('name' => 'revisit-after', 'content' => '1 day')
				   );
		}
		else
		{
			return FALSE;
		}
	}

	public function meta_tag_dynamics($keywords, $description)
	{
		return array(
			   array('name' => 'Content-Type', 'content' => 'text/html; charset=UTF-8', 'type' => 'equiv'),
			   array('name' => 'Content-Language', 'content' => 'en', 'type' => 'equiv'),
			   array('name' => 'keywords', 'content' => ''.$keywords.''),
			   array('name' => 'description', 'content' => substr($description,0,160)),
			   array('name' => 'robots', 'content' => 'index,follow'),
			   array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1'),
			   array('name' => 'revisit-after', 'content' => '1 day')
			   );
	}

	public function meta_tag_noindex($keywords, $description)
	{
		return array(
			   array('name' => 'Content-Type', 'content' => 'text/html; charset=UTF-8', 'type' => 'equiv'),
			   array('name' => 'Content-Language', 'content' => 'en', 'type' => 'equiv'),
			   array('name' => 'keywords', 'content' => ''.$keywords.''),
			   array('name' => 'description', 'content' => substr($description,0,160)),
			   array('name' => 'robots', 'content' => 'noindex,nofollow'),
			   array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1'),
			   array('name' => 'revisit-after', 'content' => '1 day')
			   );
	}

	public function title_default()
	{
		$this->db->select('set_title');
		$this->db->from('mod_setting');
		$this->db->where('id_mod_setting', '1');
		$query = $this->db->get();
		$hsl = $query->row_array();

		//$query->free_result();

		//echo $this->db->last_query();
		
		return $hsl['set_title'];
	}

	function get_address()
	{
		$this->db->select('*');
		$this->db->from('mod_setting');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_page_bytype($id)
	{
		$this->db->select('*');
		$this->db->from('mod_pages');
		$this->db->where('p_type', $id);
		$this->db->order_by('p_title', 'asc');
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}

	function get_page_type_name($id)
	{
		$this->db->select('*');
		$this->db->from('mod_pages_type');
		$this->db->where('ptype_id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_setting_home()
	{
		$this->db->select('*');
		$this->db->from('mod_setting_home');
		$this->db->where('setting_home_id', '1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}
	
}