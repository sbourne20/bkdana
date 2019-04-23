<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function get_email_u($email)
	{
		$email = $this->db->escape_str($email);

		$this->db->where('email', $email);
		$this->db->from($this->user);
		return $this->db->count_all_results();
	}

	function get_cms_user()
	{
		// ---- Get All CMS User show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array();
		$sql_where 		= '';
		$cols 			= array( "id_system_user", "username", "email", "u.id_group" );
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && trim($_GET['sSearch']) != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));

			$_sql_where[] = "
				(
					UCASE(username) LIKE '%".$search."%'
					OR 
					UCASE(email) LIKE '%".$search."%'
				)
			";
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM {$this->user}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT u.*, g.group_name 
					FROM {$this->user} u
					LEFT JOIN {$this->user_group} g ON(g.id_group=u.id_group)
					$sql_where
			";

		if($sort!='' && $sort_dir!='') $order = " ORDER BY $sort $sort_dir ";
		
		$query 	= $this->db->query($sql. $order. " LIMIT $start,$rows ");
		$data 	= $query->result();

		if( $search!='' ){
			$iFilteredTotal = count($query->result());
		}else{
			$iFilteredTotal = $iTotal;
		}
		
        //    * Output
         
         $output = array(
             "sEcho" => $this->Datatables_model->get_echo(),
             "iTotalRecords" => $iTotal,
             "iTotalDisplayRecords" => $iFilteredTotal,
             "aaData" => $data
         );

        $query->free_result();

		return json_encode($output);
	}

	// --- CMS USER ---
	public function insert_new_user($data)
	{
		$data = $this->Master_model->escape_all($data);

		$this->db->insert($this->user, $data);
		return $this->db->insert_id();
	}
	
	public function delete_cms_user($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->user, array('id_system_user'=>$id));
	}

	public function update_cms_user($data, $ID)
	{
		$ID = $this->db->escape_str($ID);
		$data = $this->Master_model->escape_all($data);

		$this->db->where('id_system_user', $ID);
		$this->db->update($this->user, $data);
		return $this->db->affected_rows();
	}

	public function update_cms_user_byemail($data, $email)
	{
		$email = $this->db->escape_str($email);
		$data = $this->Master_model->escape_all($data);

		$this->db->where('email', $email);
		$this->db->update($this->user, $data);
		return $this->db->affected_rows();
	}

	function get_cmsuser_byid($id)
	{
		$id = $this->db->escape_str($id);

		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->where('id_system_user', $id);
		$prod = $this->db->get();

		return $prod->row_array();
	}

	function get_all_active_user()
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->where('active', '1');
		$sql = $this->db->get();
		
		return $sql->result_array();
	}

	//=======================================================

	function user_exists($str)
	{
		$str = $this->db->escape_str($str);
		$query = $this->db->query(" SELECT * FROM {$this->user} WHERE UCASE(username)=UCASE('$str') ");
		if($query->num_rows() > 0) return TRUE;
		else return FALSE;
	}

	function get_user_by_id($id)
	{
		$id = $this->db->escape_str($id);
		$query = $this->db->query(" SELECT * FROM {$this->user} WHERE id_system_user='$id' ");
		return (OBJECT) $query->row();
	}

	function get_user_all()
	{
		$query = $this->db->query(" SELECT * FROM {$this->user} ORDER BY nama ASC ");
		return $query->result();
	}

	function do_login($u,$p)
	{
		$u = $this->db->escape_str($u);
		$p = $this->db->escape_str($p);
		$getdata = $this->get_username($u);

		if (is_array($getdata) && count($getdata) > 0){

			if (!empty($getdata['id_system_user']) && !empty($getdata['id_group'])) {
			
				$stored_password = $getdata['password'];

				if (password_verify(base64_encode(hash('sha256', ($p), true)), $stored_password)) {

					$data = array();
					$data['login_status'] = 1;
					$data['current_user'] = $getdata['id_system_user'];
					$data['role']         = $getdata['id_group'];
					$this->session->set_userdata($data);

					//_d($data); exit;
					return true;
				}else{
					return false;
				}
			}else{
				
				return false;
			}

		}else{
			return false;
		}
	}

	function current_user()
	{
		if($username = $this->session->userdata('current_user'))
		{
			$CU = $this->get_user_by_id($username);
			return $CU;
		}
		else
		{
			return (object) array();
		}
	}

	function is_login()
	{
		if($this->session->userdata('login_status')==1) return TRUE;
		else return FALSE;
	}

	function has_login( $checkAccess = TRUE )
	{
		$log_status = $this->session->userdata('login_status');
		$user = $this->session->userdata('current_user');
		$role = $this->session->userdata('role');

		if($log_status !=1 OR empty($user) OR empty($role) )
		{
			$this->session->set_userdata('message','You must log in to this application. ');
			$this->session->set_userdata('message_type','error');
			redirect('log/in');
			die();
		}

		$this->has_access();
		//$this->insert_log();
	}

	function has_access()
	{
	    $default_controller = isset($this->router->routes['default_controller']) ? $this->router->routes['default_controller'] : 'welcome';
		$default_method = 'index';

		$controller = $this->uri->segment(1, $default_controller);
		$action = $this->uri->segment(2, $default_method);

		$default_access = array(
			$default_controller => $default_controller,
			$default_controller.'/'.$default_method => $default_controller.'/'.$default_method,
			$controller.'/json'			=> $controller.'/json',
			$controller.'/json_modal'	=> $controller.'/json_modal',
			'dashboard/index'	=> 'dashboard/index'
		);

		$CU_role_id = $this->session->userdata('role');

		// echo 'role ID '.$CU_role_id;
		// exit();

		if ($CU_role_id != 1) {	// 1. Super Admin
			$acc        = $this->get_user_access($CU_role_id);
			$acc        = array_merge($default_access,$acc);

		//{echo 'acc '.$acc;
		//exit()};
			//_d($acc);exit();

			// {echo 'link'.$default_access;
			// exit()};

			if( ! isset($acc[$controller.'/'.$action]) ){
				$this->session->set_userdata('message',' to access / take action on the page that you are headed.');
				$this->session->set_userdata('message_type','error');
				$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
				redirect($ref);
				die();
			}
		}
	    
	}

	function get_user_access($id)
	{
		$acc = array();
		$id = $this->db->escape_str($id);
		$query = $this->db->query(" SELECT * FROM {$this->user_privileges} WHERE priv_id_group='$id' ");
		$res = $query->result();
		if(count($res)>0){
			foreach($res as $row){
				$acc[$row->access] = $row->access;
			
				//{echo 'access'.$acc[$row->access];
				//exit()};
			}
		}
		return $acc;
	}

	function logout()
	{
		$this->session->unset_userdata('login_status');
		$this->session->unset_userdata('current_user');
		$this->session->unset_userdata('role');
	
		$this->session->set_userdata('message','You have been logout. ');
		$this->session->set_userdata('message_type','info');
		redirect(base_url('/log/in'));
	}

	// ------------------ User Privilege Group  ----------------

	function get_allgroup()
	{
		$this->db->select('*');
		$this->db->from($this->user_group);
		$this->db->order_by('group_name', 'asc');
		$sql = $this->db->get();
		
		return $sql->result_array();
	}

	function get_user_group_dt()
	{		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array();
		$sql_where 		= '';
		$cols 			= array( "id_group", "group_name");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && trim($_GET['sSearch']) != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));
			
			$_sql_where[] = "( UCASE(group_name) LIKE '%".strtoupper($this->db->escape_str($search))."%' ) ";
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM {$this->user_group}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT * FROM {$this->user_group}
					$sql_where
			";

		if($sort!='' && $sort_dir!='') $order = " ORDER BY $sort $sort_dir ";
		
		$query 	= $this->db->query($sql. $order. " LIMIT $start,$rows ");
		$data 	= $query->result();

		if( $search!='' ){
			$iFilteredTotal = count($query->result());
		}else{
			$iFilteredTotal = $iTotal;
		}
		
        //    * Output
         
         $output = array(
             "sEcho" => $this->Datatables_model->get_echo(),
             "iTotalRecords" => $iTotal,
             "iTotalDisplayRecords" => $iFilteredTotal,
             "aaData" => $data
         );

        $query->free_result();

		return json_encode($output);
	}

	function insert_group($data)
	{
		$this->db->insert($this->user_group, $data);
		return $this->db->affected_rows();
	}

	function update_group($data,$id)
	{
		$this->db->where('id_group', $id);
		$this->db->update($this->user_group, $data);
		return $this->db->affected_rows();
	}

	function get_group_by($id)
	{
		$query = $this->db->query(" SELECT * FROM {$this->user_group} WHERE id_group='$id' ");
		return $query->row_array();
	}

	function delete_user_group($id)
	{
		$this->db->where('id_group', $id);
		$this->db->delete($this->user_group);
		return $this->db->affected_rows();
	}

	// ---- SYSTEM USER PRIVILEGES -----
	
	function get_role_access($id)
	{
		$acc = array();

		$this->db->select('access');
		$this->db->from($this->user_privileges);
		$this->db->where('priv_id_group', $id);
		$query = $this->db->get();
		$res = $query->result();
		if(count($res)>0){
			foreach($res as $row){
				$acc[$row->access] = $row->access;
			}
		}
		return $acc;
	}

	public function delete_role_access($groupID)
	{
		$groupID = $this->db->escape_str($groupID);
		return $this->db->delete($this->user_privileges, array('priv_id_group'=>$groupID));
	}

	public function insert_access_roles($data)
	{
		$data = $this->Master_model->escape_all($data);

		$this->db->insert($this->user_privileges, $data);
	}

	// ================== USER LOG ====================

	function get_user_log()
	{		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array();
		$sql_where 		= '';
		$cols 			= array( "id_system_user_log", "date", "time", "username", "activities");
		$sort 			= "desc";
		
		// get search value (if any) && no blank space
		if (isset($_GET['sSearch']) && trim($_GET['sSearch']) != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));

			$_sql_where[] = "( UCASE(activities) LIKE '%".$search."%' OR
								UCASE(username) LIKE '%".$search."%' OR
								UCASE(date) LIKE '%".$search."%' OR
								UCASE(time) LIKE '%".$search."%' 
							) ";
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM {$this->user_log}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT L.id_system_user_log, L.id_system_user, L.activities, L.time, date_format(L.date, '%d %M %Y') as ddate, L.remark , U.username
					FROM {$this->user_log} L
					LEFT JOIN {$this->user} U ON(U.id_system_user=L.id_system_user)
					$sql_where
			";

		if($sort!='' && $sort_dir!='') $order = " ORDER BY $sort $sort_dir ";
		
		$query 	= $this->db->query($sql. $order. " LIMIT $start,$rows ");
		$data 	= $query->result();

		if( $search!='' ){
			$iFilteredTotal = count($query->result());
		}else{
			$iFilteredTotal = $iTotal;
		}
		
        // * Output         
         $output = array(
             "sEcho" => $this->Datatables_model->get_echo(),
             "iTotalRecords" => $iTotal,
             "iTotalDisplayRecords" => $iFilteredTotal,
             "aaData" => $data
         );

        $query->free_result();

		return json_encode($output);
	}

	function delete_logs()
	{
		return $this->db->empty_table($this->user_log); 
		//return $this->db->truncate($this->user_log); 
	}

	public function insert_log()
	{
		$userid = $this->session->userdata('current_user');
		$ctrl   = $this->uri->segment(1);
		$action = $this->uri->segment(2);

		if (isset($userid)) :
			$data = array(
				'id_system_user' => $userid,
				'activities'     => $action .' '.$ctrl,
				'time'           => date("H:i:s"),
				'date'           => date("Y-m-d"),
				'remark'         => ''
				);
			$this->db->insert($this->user_log, $data);
		endif;
	}

	function get_username($username)
	{
		$this->db->select('id_system_user, id_group, username, password, fullname');
		$this->db->from($this->user.' u');
		$this->db->where('username', $username);
		$this->db->where('active',1);
		$sql = $this->db->get();
		$ret= $sql->row_array();
		$sql->free_result();
		return $ret;
	}

}