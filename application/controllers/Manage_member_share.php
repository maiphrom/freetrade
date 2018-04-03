<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_member_share extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$arr_data = array();
		
		$x=0;
		$join_arr = array();
		$join_arr[$x]['table'] = 'coop_prename';
		$join_arr[$x]['condition'] = 'coop_prename.prename_id = coop_mem_apply.prename_id';
		$join_arr[$x]['type'] = 'left';
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('*');
		$this->paginater_all->main_table('coop_mem_apply');
		$this->paginater_all->where("");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(10);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('mem_apply_id DESC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		//echo"<pre>";print_r($row);exit;
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		$i = $row['page_start'];


		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['row'] = $row['data'];
		$arr_data['i'] = $i;


		$this->libraries->template('manage_member_share/index',$arr_data);
	}

	public function add($id=''){
		$arr_data = array();

		if($id!=''){
			$this->db->select('*');
			$this->db->from('coop_mem_apply');
			$this->db->where("id = '".$id."'");
			$row = $this->db->get()->result_array();
			$arr_data['data'] = $row[0];
		}else{
			$arr_data['data'] = array();
		}
		$arr_data['id'] = $id;

		$this->db->select('member_id');
		$this->db->from('coop_mem_apply');
		$this->db->order_by('member_id DESC');
		$this->db->limit(1);
		$mem_id = $this->db->get()->result_array();
		$auto_member_id = $mem_id[0]['member_id'] + 1;
		$arr_data['auto_member_id'] = $auto_member_id;

		$this->db->select('apply_type_id, apply_type_name');
		$this->db->from('coop_mem_apply_type');
		$row = $this->db->get()->result_array();
		$arr_data['mem_apply_type'] = $row;

		$this->db->select('prename_id, prename_full');
		$this->db->from('coop_prename');
		$row = $this->db->get()->result_array();
		$arr_data['prename'] = $row;

		$this->db->select('id, mem_group_name');
		$this->db->from('coop_mem_group');
		$this->db->where("mem_group_type='1'");
		$row = $this->db->get()->result_array();
		$arr_data['department'] = $row;

		if(@$arr_data['data']['department'] != ''){
			$this->db->select('id, mem_group_name');
			$this->db->from('coop_mem_group');
			$this->db->where("mem_group_parent_id = '".@$arr_data['data']['department']."' AND mem_group_type='2'");
			$row = $this->db->get()->result_array();
			$arr_data['faction'] = $row;
		}else{
			$arr_data['faction'] = array();
		}

		if(@$arr_data['data']['faction'] != '') {
			$this->db->select('id, mem_group_name');
			$this->db->from('coop_mem_group');
			$this->db->where("mem_group_parent_id = '".@$arr_data['data']['faction']."' AND mem_group_type='3'");
			$row = $this->db->get()->result_array();
			$arr_data['level'] = $row;
		}else{
			$arr_data['level'] = array();
		}

		$this->db->select('user_permission_id');
		$this->db->from('coop_user_permission');
		$this->db->where("user_id = '".$_SESSION['USER_ID']."' AND menu_id = '82'");
		$row = $this->db->get()->result_array();
		if($row[0]['user_permission_id']==''){
			$arr_data['salary_display'] = "display:none;";
		}else{
			$arr_data['salary_display'] = "";
		}

		$this->db->select('act_bank_id, act_bank_name');
		$this->db->from('coop_act_bank');
		$row = $this->db->get()->result_array();
		$arr_data['act_bank'] = $row;

		$this->db->select('bank_id, bank_name');
		$this->db->from('coop_bank');
		$row = $this->db->get()->result_array();
		$arr_data['bank'] = $row;

		$this->db->select('branch_id, branch_name');
		$this->db->from('coop_bank_branch');
		$this->db->where("bank_id = '".@$arr_data['data']["dividend_bank_id"]."'");
		$row = $this->db->get()->result_array();
		$arr_data['bank_branch'] = $row;

		$this->db->select('province_id, province_name');
		$this->db->from('coop_province');
		$this->db->order_by('province_name');
		$row = $this->db->get()->result_array();
		$arr_data['province'] = $row;

		if(@$arr_data['data']["province_id"]!=''){
			$this->db->select('amphur_id, amphur_name');
			$this->db->from('coop_amphur');
			$this->db->where("province_id = '".@$arr_data['data']["province_id"]."'");
			$this->db->order_by('amphur_name');
			$row = $this->db->get()->result_array();
			$arr_data['amphur'] = $row;
		}else{
			$arr_data['amphur'] = array();
		}

		if(@$arr_data['data']["amphur_id"]!=''){
			$this->db->select('district_id, district_name');
			$this->db->from('coop_district');
			$this->db->where("amphur_id = '".@$arr_data['data']["amphur_id"]."'");
			$this->db->order_by('district_name');
			$row = $this->db->get()->result_array();
			$arr_data['district'] = $row;
		}else{
			$arr_data['district'] = array();
		}

		if(@$arr_data['data']["c_province_id"]!=''){
			$this->db->select('amphur_id, amphur_name');
			$this->db->from('coop_amphur');
			$this->db->where("province_id = '".@$arr_data['data']["c_province_id"]."'");
			$this->db->order_by('amphur_name');
			$row = $this->db->get()->result_array();
			$arr_data['c_amphur'] = $row;
		}else{
			$arr_data['c_amphur'] = array();
		}

		if(@$arr_data['data']["c_amphur_id"]!=''){
			$this->db->select('district_id, district_name');
			$this->db->from('coop_district');
			$this->db->where("amphur_id = '".@$arr_data['data']["c_amphur_id"]."'");
			$this->db->order_by('district_name');
			$row = $this->db->get()->result_array();
			$arr_data['c_district'] = $row;
		}else{
			$arr_data['c_district'] = array();
		}

		if(@$arr_data['data']["m_province_id"]!=''){
			$this->db->select('amphur_id, amphur_name');
			$this->db->from('coop_amphur');
			$this->db->where("province_id = '".@$arr_data['data']["m_province_id"]."'");
			$this->db->order_by('amphur_name');
			$row = $this->db->get()->result_array();
			$arr_data['m_amphur'] = $row;
		}else{
			$arr_data['m_amphur'] = array();
		}

		if(@$arr_data['data']["m_amphur_id"]!=''){
			$this->db->select('district_id, district_name');
			$this->db->from('coop_district');
			$this->db->where("amphur_id = '".@$arr_data['data']["m_amphur_id"]."'");
			$this->db->order_by('district_name');
			$row = $this->db->get()->result_array();
			$arr_data['m_district'] = $row;
		}else{
			$arr_data['m_district'] = array();
		}
		$this->libraries->template('manage_member_share/add',$arr_data);
	}

	function member_lb_upload(){
		$this->load->library('image');
		$this->load->view('manage_member_share/member_lb_upload');
	}

	function get_image(){
		if($_COOKIE["is_upload"]) {
			echo base_url().PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}";
		}
		exit();
	}

	function save_add(){
		$data = $this->input->post();
		//echo"<pre>";
		//print_r($_FILES);
		//print_r($_COOKIE);
		//print_r($data);
		//exit;
		if($data['mem_apply_id']!=''){
			$this->db->select(array('signature','member_pic'));
			$this->db->from('coop_mem_apply');
			$this->db->where("mem_apply_id = '".$data['mem_apply_id']."'");
			$this->db->order_by('mem_apply_id DESC');
			$this->db->limit(1);
			$row = $this->db->get()->result_array();

			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/members/";

			if(!empty($_COOKIE["is_upload"]) && !empty($_COOKIE["IMG"])) {
				$member_pic = $this->create_file_name($output_dir,$_COOKIE["IMG"]);
				@copy($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}", $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/members/{$member_pic}");
				@unlink($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}");
				@unlink($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/members/".$row[0]['member_pic']);
				setcookie("is_upload", "", time()-3600);
				setcookie("IMG", "", time()-3600);
				$data['member_pic'] = $member_pic;
			}

			$_tmpfile = $_FILES["signature"];
			if(!empty($_tmpfile["tmp_name"])) {
				$new_file_name = $this->create_file_name($output_dir,$_tmpfile["name"]);
				if(!empty($new_file_name)) {
					copy($_tmpfile["tmp_name"], $output_dir.$new_file_name);
					@unlink($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/members/".$row[0]['member_pic']);
					$signature = $new_file_name;
					$data['signature'] = $signature;
				}
			}

			$data['apply_date'] = $this->center_function->ConvertToSQLDate($data['apply_date']);
			$data['member_date'] = $data['apply_date'];
			if($data['member_time']==''){
				$data['member_time'] = '1';
			}
			$data['birthday'] = $this->center_function->ConvertToSQLDate($data['birthday']);
			$data['work_date'] = $this->center_function->ConvertToSQLDate($data['work_date']);
			$data['retry_date'] = $this->center_function->ConvertToSQLDate($data['retry_date']);

			$this->db->where('mem_apply_id', $data['mem_apply_id']);
			$this->db->update('coop_mem_apply', $data);

		}else{
			$this->db->select('mem_apply_id');
			$this->db->from('coop_mem_apply');
			$this->db->where("mem_apply_id LIKE '".date("Ym")."%'");
			$this->db->order_by('mem_apply_id DESC');
			$this->db->limit(1);
			$row = $this->db->get()->result_array();
			if(!empty($row)) {
				$id = (int)substr($row[0]["mem_apply_id"], 6);
				$mem_apply_id = date("Ym").sprintf("%06d", $id + 1);
			}else {
				$mem_apply_id = date("Ym")."000001";
			}
			$data['mem_apply_id'] = $mem_apply_id;

			if(!isset($data['is_fix_member_id']) || $data['is_fix_member_id'] != '1') {
				$this->db->select('member_id');
				$this->db->from('coop_mem_apply');
				$this->db->order_by('member_id DESC');
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				if(!empty($row)) {
					$id = (int)$row[0]["member_id"];
					$member_id = sprintf("%06d", $id + 1);
				}else {
					$member_id = "000001";
				}
				$data['member_id'] = $member_id;
				$data['is_fix_member_id'] = '0';
			}
			$data['apply_date'] = $this->center_function->ConvertToSQLDate($data['apply_date']);
			$data['member_date'] = $data['apply_date'];
			if($data['member_time']==''){
				$data['member_time'] = '1';
			}
			$data['birthday'] = $this->center_function->ConvertToSQLDate($data['birthday']);
			$data['work_date'] = $this->center_function->ConvertToSQLDate($data['work_date']);
			$data['retry_date'] = $this->center_function->ConvertToSQLDate($data['retry_date']);
			$data['member_status'] = '1';
			$data['mem_type'] = '1';
			$data['mem_type_id'] = '0';
			$data['apply_status'] = '0';
			$data['is_fix_member_date'] = '0';


			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/members/";

			if(!empty($_COOKIE["is_upload"]) && !empty($_COOKIE["IMG"])) {
				$member_pic = $this->create_file_name($output_dir,$_COOKIE["IMG"]);
				@copy($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}", $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/members/{$member_pic}");
				@unlink($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}");
				setcookie("is_upload", "", time()-3600);
				setcookie("IMG", "", time()-3600);
				$data['member_pic'] = $member_pic;
			}

			$_tmpfile = $_FILES["signature"];
			if(!empty($_tmpfile["tmp_name"])) {
				$new_file_name = $this->create_file_name($output_dir,$_tmpfile["name"]);
				if(!empty($new_file_name)) {
					copy($_tmpfile["tmp_name"], $output_dir.$new_file_name);
					$signature = $new_file_name;
					$data['signature'] = $signature;
				}
			}
			$this->db->insert('coop_mem_apply', $data);

		}
		$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		echo"<script> document.location.href='".PROJECTPATH."/manage_member_share' </script>";
		exit;
	}

	function create_file_name($output_dir,$file_name){
		$list_dir = array();
		$cdir = scandir($output_dir);
		foreach ($cdir as $key => $value) {
			if (!in_array($value,array(".",".."))) {
				if (@is_dir(@$dir . DIRECTORY_SEPARATOR . $value)){
					$list_dir[$value] = dirToArray(@$dir . DIRECTORY_SEPARATOR . $value);
				}else{
					if(substr($value,0,8) == date('Ymd')){
						$list_dir[] = $value;
					}
				}
			}
		}
		$explode_arr=array();
		foreach($list_dir as $key => $value){
			$task = explode('.',$value);
			$task2 = explode('_',$task[0]);
			$explode_arr[] = $task2[1];
		}
		$max_run_num = sprintf("%04d",count($explode_arr)+1);
		$explode_old_file = explode('.',$file_name);
		$new_file_name = date('Ymd')."_".$max_run_num.".".$explode_old_file[(count($explode_old_file)-1)];
		return $new_file_name;
	}

	function get_amphur_list(){
			$arr_data = array();
			$this->db->select('amphur_id, amphur_name');
			$this->db->from('coop_amphur');
			$this->db->where("province_id = '".$this->input->post('province_id')."'");
			$this->db->order_by('amphur_name');
			$row = $this->db->get()->result_array();
			$arr_data['amphur'] = $row;

			$arr_data['id_input_amphur'] = $this->input->post('id_input_amphur');
			$arr_data['district_space'] = $this->input->post('district_space');
			$arr_data['id_input_district'] = $this->input->post('id_input_district');

			$this->load->view('manage_member_share/get_amphur_list',$arr_data);
	}

	function get_district_list(){
		$arr_data = array();
		$this->db->select('district_id, district_name');
		$this->db->from('coop_district');
		$this->db->where("amphur_id = '".$this->input->post('amphur_id')."'");
		$this->db->order_by('district_name');
		$row = $this->db->get()->result_array();
		$arr_data['district'] = $row;

		$arr_data['id_input_district'] = $this->input->post('id_input_district');

		$this->load->view('manage_member_share/get_district_list',$arr_data);
	}

	function get_bank_branch_list(){
		$arr_data = array();
		$this->db->select('branch_id, branch_name');
		$this->db->from('coop_bank_branch');
		$this->db->where("bank_id = '".$this->input->post('bank_id')."'");
		$this->db->order_by('branch_name');
		$row = $this->db->get()->result_array();
		$arr_data['bank_branch'] = $row;

		$this->load->view('manage_member_share/get_bank_branch_list',$arr_data);
	}

	function get_mem_group_list(){
		$arr_data = array();
		$this->db->select('id, mem_group_name');
		$this->db->from('coop_mem_group');
		$this->db->where("mem_group_parent_id = '".$this->input->post('mem_group_id')."'");
		$this->db->order_by('mem_group_name');
		$row = $this->db->get()->result_array();
		$arr_data['mem_group'] = $row;

		$this->load->view('manage_member_share/get_mem_group_list',$arr_data);
	}

	function check_register(){

		$this->db->select('id_card');
		$this->db->from('coop_mem_apply');
		$this->db->where("id_card = '".$this->input->post('id_card')."'");
		$row = $this->db->get()->result_array();

		if(!empty($row) != ''){
			echo "พบข้อมูลเลขประจำตัวประชาชนของท่านในระบบ";
		}else{
			echo 'success';
		}
		exit;
	}

	function get_search_member(){
		$where = "
		 	(member_id LIKE '%".$this->input->post('search_text')."%'
		 	OR firstname_th LIKE '%".$this->input->post('search_text')."%'
			OR lastname_th LIKE '%".$this->input->post('search_text')."%')
		";
		$this->db->select(array('id','member_id','firstname_th','lastname_th','apply_date','mem_apply_id'));
		$this->db->from('coop_mem_apply');
		$this->db->where($where);
		$this->db->order_by('mem_apply_id DESC');
		$row = $this->db->get()->result_array();
		$arr_data['data'] = $row;
		$arr_data['form_target'] = $this->input->post('form_target');
		//echo"<pre>";print_r($arr_data['data']);exit;
		$this->load->view('manage_member_share/get_search_member',$arr_data);
	}

	function check_resign_date(){
		$this->db->select(array('year_quite'));
		$this->db->from('coop_quite_setting');
		$row = $this->db->get()->result_array();
		$year_quite = $row[0]['year_quite'];

		$this->db->select('coop_mem_req_resign.resign_date');
		$this->db->from('coop_mem_req_resign');
		$this->db->join('coop_mem_apply', 'coop_mem_req_resign.member_id = coop_mem_apply.member_id', 'inner');
		$this->db->where("coop_mem_apply.id = '".$this->input->post('id')."'");
		$row = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;

		$date_now = date('Y-m-d');
		$resign_date = $row[0]['resign_date'];
		$return_date = date('Y-m-d',strtotime('+'.$year_quite.' year',strtotime($resign_date)));
		$date1=date_create($resign_date);
		$date2=date_create($date_now);
		$diff=date_diff($date1,$date2);

		if($diff->days <= (365*$year_quite)){
			echo 'โดยจะครบ '.$year_quite.' ปี ในวันที่ '.$this->center_function->ConvertToThaiDate($return_date);
		}else{
			echo 'success';
		}
		exit;
	}
}
