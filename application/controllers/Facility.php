<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facility extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$arr_data = array();

		$this->db->select('COUNT(store_id) as _c');
		$this->db->from('coop_facility_store');
		$count = $this->db->get()->result_array();

		$num_rows = $count[0]["_c"] ;
		$per_page = 10 ;
		$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
		$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

		$page_start = (($per_page * $page) - $per_page);
		if($page_start==0){ $page_start = 1;}

		$this->db->select('*');
		$this->db->from('( SELECT *, ROW_NUMBER() OVER (ORDER BY store_id DESC) as row FROM coop_facility_store ) a');
		$this->db->where("store_status = '0' AND row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
		//$this->db->limit($page_start, $per_page);
		$this->db->order_by('store_id DESC');
		$row = $this->db->get()->result_array();
		//print_r($this->db->last_query());exit;

		$i = $page_start;


		$arr_data['num_rows'] = $num_rows;
		$arr_data['paging'] = $paging;
		$arr_data['row'] = $row;
		$arr_data['i'] = $i;


		$this->libraries->template('facility/index',$arr_data);
	}

	public function add(){
		$arr_data = array();
		$id = @$_GET['s_id'];
		if($id!=''){
			$this->db->select('*');
			$this->db->from('coop_facility_store');
			$this->db->where("store_id = '".$id."'");
			$rs = $this->db->get()->result_array();
			$row= @$rs[0];
			$arr_data['data'] = @$row;
			
			$facility_main_code = $row['facility_main_code'];
			$this->db->select('*');
			$this->db->from('coop_facility_store');
			$this->db->where("facility_main_code = '".$facility_main_code."' AND store_status = '0'");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs;
			//exit;
			
		}else{
			$arr_data['data'] = array();
		}
		$arr_data['id'] = $id;

		//จัดซื้อโดย
		$this->db->select('*');
		$this->db->from('coop_personnel');
		$row = $this->db->get()->result_array();
		$arr_data['personnel'] = @$row;
		
		//ประเภทการจัดซื้อ
		$this->db->select('*');
		$this->db->from('coop_means_purchase');
		$row = $this->db->get()->result_array();
		$arr_data['means'] = @$row;
		
		//ประเภทเงิน
		$this->db->select('*');
		$this->db->from('coop_type_money');
		$row = $this->db->get()->result_array();
		$arr_data['type_money'] = @$row;
		
		//หลักฐาน
		$this->db->select('*');
		$this->db->from('coop_type_evidence');
		$row = $this->db->get()->result_array();
		$arr_data['type_evidence'] = @$row;
		
		//ผู้ขาย
		$this->db->select(array('seller_id','seller_name'));
		$this->db->from('coop_seller');
		$row = $this->db->get()->result_array();
		$arr_data['seller'] = @$row;
		//print_r($this->db->last_query());exit;
		
		//หน่วยนับ
		$this->db->select(array('unit_type_id','unit_type_name'));
		$this->db->from('coop_unit_type');
		$rs_type = $this->db->get()->result_array();
		$arr_unit_type = array();
		if(!empty($rs_type)){
			foreach($rs_type AS $row_type){
				$arr_unit_type[$row_type['unit_type_id']] = $row_type['unit_type_name'];
			}
		}
		$arr_data['arr_unit_type'] = @$arr_unit_type;
		
		
		$this->libraries->template('facility/add',$arr_data);
	}

	function store_lb_upload(){
		$this->load->library('image');
		$this->load->view('facility/store_lb_upload');
	}

	function get_image(){
		if($_COOKIE["is_upload"]) {
			echo base_url().PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}";
		}
		exit();
	}

	function save_add(){
		$data_insert = array();
		$data = $this->input->post();
		$table = "coop_facility_store";
		$id_edit = @$data["store_id"] ;
		
		$store_code = @$data["store_code"];
		$facility_main_code_old = @$data["facility_main_code_old"];
		$store_run = @$data["store_run"];
		$facility_main_code = @$data["facility_main_code"];
		
		$this->db->select(array('MAX(store_run) AS last_run'));
		$this->db->from('coop_facility_store');
		$this->db->where("facility_main_code = '{$facility_main_code}'");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0]; 
		
		if(!empty($id_edit) && $facility_main_code == $facility_main_code_old){
			$store_run_now = $store_run;	
			$now_id = sprintf("%03d",$store_run);	
		}else{
			$store_run_now = $row['last_run']+1;	
			$now_id = sprintf("%03d",$store_run_now);
		}		
		$store_code_now = $facility_main_code.'-'.$now_id;
	
		$data_insert['facility_main_code'] = $facility_main_code;
		$data_insert['store_run'] = $store_run_now;
		$data_insert['budget_year'] = $data['budget_year'];
		$data_insert['store_name'] = $data['store_name'];
		$data_insert['unit_type_id'] = $data['unit_type_id'];
		$data_insert['store_price'] = $data['store_price'];
		$data_insert['department_id'] = $data['department_id'];
		$data_insert['department_name'] = $data['department_name'];
		$data_insert['store_no'] = $data['store_no'];
		$data_insert['receive_date'] = $this->center_function->ConvertToSQLDate($data['receive_date']);
		$data_insert['personnel_id'] = $data['personnel_id'];
		$data_insert['means_id'] = $data['means_id'];
		$data_insert['type_money_id'] = $data['type_money_id'];
		$data_insert['certificate_no'] = $data['certificate_no'];
		$data_insert['type_evidence_id'] = $data['type_evidence_id'];
		$data_insert['start_date'] = $this->center_function->ConvertToSQLDate($data['start_date']);
		$data_insert['seller_id'] = $data['seller_id'];
		$data_insert['store_code'] = $store_code_now;
		//$data_insert['store_serial'] = $data['store_serial'];
		$data_insert['store_status'] = '0';
		$data_insert['updatetime'] = date('Y-m-d H:i:s');
		
		if($id_edit!=''){
			$this->db->select(array('store_pic'));
			$this->db->from('coop_facility_store');
			$this->db->where("store_id = '".$data["store_id"]."'");
			$this->db->order_by('store_id DESC');
			$this->db->limit(1);
			$row = $this->db->get()->result_array();

			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/facility/";

			if(!empty($_COOKIE["is_upload"]) && !empty($_COOKIE["IMG"])) {
				$store_pic = $this->create_file_name($output_dir,$_COOKIE["IMG"]);
				@unlink($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/facility/".$row[0]['store_pic']);
				@copy($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}", $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/facility/{$store_pic}");
				@unlink($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}");
				
				setcookie("is_upload", "", time()-3600);
				setcookie("IMG", "", time()-3600);
				
				$data_insert['store_pic'] = $store_pic;
			}
			
			$this->db->where('store_id', $id_edit);
			$this->db->update($table, $data_insert);
			$store_id = $id_edit;
			$new = '';
		}else{
			$output_dir = $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/facility/";

			if(!empty($_COOKIE["is_upload"]) && !empty($_COOKIE["IMG"])) {
				$store_pic = $this->create_file_name($output_dir,$_COOKIE["IMG"]);
				@copy($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}", $_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/facility/{$store_pic}");
				@unlink($_SERVER["DOCUMENT_ROOT"].PROJECTPATH."/assets/uploads/tmp/{$_COOKIE["IMG"]}");
				setcookie("is_upload", "", time()-3600);
				setcookie("IMG", "", time()-3600);
				$data_insert['store_pic'] = $store_pic;
			}
			
			$data_insert['createdatetime'] = date('Y-m-d H:i:s');
			$this->db->insert($table, $data_insert);
			$store_id = $this->db->insert_id();
			$new = '&n_id=1'; //รายการที่เพิ่มใหม่

		}
		
		$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		
		echo"<script> document.location.href='".PROJECTPATH."/facility/add?s_id={$store_id}{$new}' </script>";
		exit;
	}
	
	function save_form_quantity(){
		$data = $this->input->post();
		$data_insert = array();		
		$table = "coop_facility_store";
		$id_edit = @$data["store_id"] ;		
		//echo '<pre>'; print_r($data); echo '</pre>';
		
		$this->db->select('*');
		$this->db->from('coop_facility_store');
		$this->db->where("store_id = '".$data['store_id']."'");
		$rs = $this->db->get()->result_array();
		$row= @$rs[0];
		$facility_main_code = @$row['facility_main_code'];
		$data_insert['facility_main_code'] = @$facility_main_code;		
		$data_insert['budget_year'] = @$row['budget_year'];
		$data_insert['store_name'] = @$row['store_name'];
		$data_insert['unit_type_id'] = @$row['unit_type_id'];
		$data_insert['store_price'] = @$row['store_price'];
		$data_insert['department_id'] = @$row['department_id'];
		$data_insert['department_name'] = @$row['department_name'];
		$data_insert['store_no'] = @$row['store_no'];
		$data_insert['receive_date'] = @$row['receive_date'];
		$data_insert['personnel_id'] = @$row['personnel_id'];
		$data_insert['means_id'] = @$row['means_id'];
		$data_insert['type_money_id'] = @$row['type_money_id'];
		$data_insert['certificate_no'] = @$row['certificate_no'];
		$data_insert['type_evidence_id'] = @$row['type_evidence_id'];
		$data_insert['start_date'] = @$row['start_date'];
		$data_insert['seller_id'] = @$row['seller_id'];
		$data_insert['store_status'] = '0';
		$data_insert['updatetime'] = date('Y-m-d H:i:s');
		$data_insert['createdatetime'] = date('Y-m-d H:i:s');
		$data_insert['store_pic'] = @$row['store_pic'];
		
		//หาเลขรันพัสดุที่มากสุด
		$this->db->select(array('MAX(store_run) AS last_run'));
		$this->db->from('coop_facility_store');
		$this->db->where("facility_main_code = '{$facility_main_code}'");
		$rs_max = $this->db->get()->result_array();
		$row_max = @$rs_max[0]; 
	
		$store_run_now = $row_max['last_run'];	
		for($i=0;$i<$data['store_quantity'];$i++){
			
			$store_run_now ++;
			$now_id = sprintf("%03d",$store_run_now);	
			$store_code_now = $facility_main_code.'-'.$now_id;
			
			$data_insert['store_run'] = @$store_run_now;
			$data_insert['store_code'] = @$store_code_now;
			$this->db->insert($table, $data_insert);
			//echo '<pre>'; print_r($data_insert); echo '</pre>';	
		}
		echo true;
		exit;
	}
	
	function save_form_serial(){
		$data = $this->input->post();
		$data_insert = array();		
		$table = "coop_facility_store";
		$id_edit = @$data["store_id_serial"] ;		
	
		$data_insert['store_serial'] = @$data['store_serial'];
		
		$this->db->where('store_id', $id_edit);
		$this->db->update($table, $data_insert);
		echo true;
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

	function get_search_facility(){
		$where = "
		 	(facility_main_id LIKE '%".$this->input->post('search_text')."%'
		 	OR facility_main_name LIKE '%".$this->input->post('search_text')."%')
		";
		$this->db->select(array('coop_facility_main.*','coop_unit_type.unit_type_name'));
		$this->db->from('coop_facility_main');
		$this->db->join('coop_unit_type', 'coop_unit_type.unit_type_id = coop_facility_main.unit_type_id', 'left');
		$this->db->where($where);
		$this->db->order_by('facility_main_id DESC');
		$row = $this->db->get()->result_array();
		$arr_data['data'] = @$row;
		$arr_data['form_target'] = $this->input->post('form_target');
		//echo"<pre>";print_r($arr_data['data']);exit;
		$this->load->view('facility/get_search_facility',$arr_data);
	}
	
	function get_search_department(){
		$where = "(department_name LIKE '%".$this->input->post('search_text')."%')";
		$this->db->select(array('department_id','department_name'));
		$this->db->from('coop_department');
		$this->db->where($where);
		$this->db->order_by('department_id DESC');
		$row = $this->db->get()->result_array();
		$arr_data['data'] = @$row;
		$arr_data['form_target'] = $this->input->post('form_target');
		//echo"<pre>";print_r($arr_data['data']);exit;
		$this->load->view('facility/get_search_department',$arr_data);
	}
	
	function get_search_store(){
		$where = "
		 	(store_no LIKE '%".$this->input->post('search_text')."%'
		 	OR facility_main_code LIKE '%".$this->input->post('search_text')."%' 
		 	OR store_name LIKE '%".$this->input->post('search_text')."%' 
		 	OR store_price LIKE '%".$this->input->post('search_text')."%' 
		 	OR department_name LIKE '%".$this->input->post('search_text')."%')
		";
		$this->db->select(array('store_id','store_no','facility_main_code','store_name','store_price','department_name'));
		$this->db->from('coop_facility_store');
		$this->db->where($where);
		$this->db->order_by('store_id DESC');
		$row = $this->db->get()->result_array();
		$arr_data['data'] = @$row;
		$arr_data['form_target'] = $this->input->post('form_target');
		//echo"<pre>";print_r($arr_data['data']);exit;
		$this->load->view('facility/get_search_store',$arr_data);
	}	

	function del_coop_data(){	
		$table = @$_POST['table'];
		$table_sub = @$_POST['table_sub'];
		$id = @$_POST['id'];
		$field = @$_POST['field'];


		if (!empty($table_sub)) {
			$this->db->where($field, $id );
			$this->db->delete($table_sub);	
        }

		$this->db->where($field, $id );
		$this->db->delete($table);
		$this->center_function->toast("ลบเรียบร้อยแล้ว");
		echo true;
		
	}
	
	function del_all(){
		$type_del = @$this->input->post('type_del');	
		$store_id = @$this->input->post('store_id');	
		
		$data = @$this->input->post('store_id');		
		foreach($data AS $value){
			$this->db->where('store_id', $value );
			$this->db->delete('coop_facility_store');
		}
		$this->center_function->toast("ลบเรียบร้อยแล้ว");
		
		/*if($type_del == 'add'){
			$add = '/add?s_id='.$store_id;
		}
		*/
		echo"<script> document.location.href='".PROJECTPATH."/facility' </script>";
		exit;
	}
	
	function take_facility(){
		$arr_data = array();
		$id = @$_GET['id'];
		if($id!=''){
			$this->db->select('*');
			$this->db->from('coop_facility_take');
			$this->db->where("facility_take_id = '".$id."'");
			$rs = $this->db->get()->result_array();
			$row= @$rs[0];
			$arr_data['data'] = @$row;
			
			$this->db->select(array('coop_facility_take_detail.facility_take_id','coop_facility_store.*'));
			$this->db->from('coop_facility_take_detail');
			$this->db->join('coop_facility_store', 'coop_facility_store.store_id = coop_facility_take_detail.store_id', 'left');
			$this->db->where("facility_take_id = '".$id."'");
			$row = $this->db->get()->result_array();
			$arr_data['detail'] = @$row;
		
		}else{
			$arr_data['data'] = array();
			
			$this->db->select('COUNT(facility_take_id) as _c');
			$this->db->from('coop_facility_take');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 10 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');
			$this->db->from('( SELECT coop_facility_take.*,coop_department.department_name, ROW_NUMBER() OVER (ORDER BY coop_facility_take.facility_take_id DESC) as row FROM coop_facility_take 
							LEFT JOIN coop_department ON coop_facility_take.department_id = coop_department.department_id) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			//$this->db->limit($page_start, $per_page);
			$this->db->order_by('facility_take_id DESC');
			$row = $this->db->get()->result_array();
			//print_r($this->db->last_query());exit;

			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['row'] = $row;
			$arr_data['i'] = $i;
		}		
		
		$this->db->select('*');
		$this->db->from('coop_type_evidence');
		$row = $this->db->get()->result_array();
		$arr_data['type_evidence'] = $row;
		
		$this->db->select('*');
		$this->db->from('coop_department');
		$row = $this->db->get()->result_array();
		$arr_data['department'] = $row;
		
		$this->libraries->template('facility/take_facility',$arr_data);
	}
	
	function get_store(){
		$this->db->select('*');
		$this->db->from('coop_facility_store');
		$this->db->where("department_id = '".$_POST['department_id']."' AND store_status = '0'");
		$row = $this->db->get()->result_array();
		$result = '';
		$i=1;
		foreach($row as $key => $value){
			$result .= "<tr class='tr_choose_store' id='tr_choose_id_".$value['store_id']."' store_id='".$value['store_id']."'>";
				$result .= "<td><input type='checkbox' id='store_chk_".$value['store_id']."' store_id='".$value['store_id']."' store_code='".$value['store_code']."' store_name='".$value['store_name']."' store_price='".$value['store_price']."' store_price_label='".number_format($value['store_price'],2)."' class='store_chk'></td>";
				//$result .= "<td>".$i++."</td>";
				$result .= "<td>".$value['store_code']."</td>";
				$result .= "<td>".$value['store_name']."</td>";
				$result .= "<td>".number_format($value['store_price'],2)."</td>";
			$result .= "</tr>";
		}
		echo $result;
		exit;
	}
	function take_facility_save(){
		//echo"<pre>";print_r($_POST);exit;
		$data_insert = array();
		$budget_year = $_POST['budget_year'];
		$id_edit = @$_POST["facility_take_id"] ;
		
		$this->db->select(array('MAX(receive_run) AS last_run'));
		$this->db->from('coop_facility_take');
		$this->db->where("budget_year = '{$budget_year}'");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0]; 
		
		$run_now = 0;
		if(empty($id_edit)){
			$run_now = $row['last_run']+1;	
			$receive_no = $budget_year.'-'.sprintf("%04d",$run_now);
		}
		
		$data_insert['receive_no'] = $receive_no;
		$data_insert['receive_run'] = $run_now;
		$data_insert['receive_date'] = $this->center_function->ConvertToSQLDate($_POST['receive_date']);
		$data_insert['budget_year'] = $_POST['budget_year'];
		$data_insert['voucher_no'] = $_POST['voucher_no'];
		$data_insert['type_evidence_id'] = $_POST['type_evidence_id'];
		$data_insert['sign_date'] = $this->center_function->ConvertToSQLDate($_POST['sign_date']);
		$data_insert['department_id'] = $_POST['department_id'];
		$data_insert['receive_name'] = $_POST['receive_name'];
		$this->db->insert('coop_facility_take', $data_insert);
		
		$facility_take_id = $this->db->insert_id();
		
		foreach($_POST['store_id'] as $key => $value){
			$data_insert = array();
			$data_insert['facility_take_id'] = $facility_take_id;
			$data_insert['store_id'] = $value;
			$this->db->insert('coop_facility_take_detail', $data_insert);
			
			$data_insert = array();
			$data_insert['store_status'] = '1';
			$this->db->where('store_id', $value);
			$this->db->update('coop_facility_store', $data_insert);
		}
		$this->center_function->toast('บันทึกข้อมูลเรียบร้อยแล้ว');
		echo "<script> document.location.href='".base_url(PROJECTPATH.'/facility/take_facility')."' </script>";
	}
	
	function get_search_take(){
		$where = "
		 	(receive_no LIKE '%".$this->input->post('search_text')."%'
		 	OR receive_name LIKE '%".$this->input->post('search_text')."%' 
		 	OR department_name LIKE '%".$this->input->post('search_text')."%')
		";
		
		$this->db->select(array('coop_facility_take.facility_take_id','coop_facility_take.receive_no','coop_facility_take.receive_date','coop_facility_take.receive_name','coop_department.department_name'));
		$this->db->from('coop_facility_take');
		$this->db->join('coop_department', 'coop_facility_take.department_id = coop_department.department_id', 'left');
		$this->db->where($where);
		$this->db->order_by('facility_take_id DESC');
		$row = $this->db->get()->result_array();
		$arr_data['data'] = @$row;
		$arr_data['form_target'] = $this->input->post('form_target');
		//echo"<pre>";print_r($arr_data['data']);exit;
		$this->load->view('facility/get_search_take',$arr_data);
	}
}
