<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_facility_data_2 extends CI_Controller {
	function __construct()
	{
		parent::__construct();
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
	
	public function request_type()
	{
		$arr_data = array();
		$id = @$_GET['id'];
		if(!empty($id)){
			$this->db->select(array('*'));
			$this->db->from('coop_request_type');
			$this->db->where("request_type_id = '{$id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0]; 	
		}else{	
			$this->db->select('COUNT(request_type_id) as _c');
			$this->db->from('coop_request_type');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 10 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');			
			$this->db->from('( SELECT *, ROW_NUMBER() OVER (ORDER BY request_type_id DESC) as row FROM coop_request_type) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$this->db->order_by('request_type_id DESC');
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;
		}
		
		$this->libraries->template('setting_facility_data/request_type',$arr_data);
	}
	
	public function request_type_save()
	{
		$data_insert = array();			
		$data_insert['request_type']    = @$_POST["request_type"];

		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;
			

		$table = "coop_request_type";

		if ($type_add == 'add') {	
		// add		
			$data_insert['create_date'] = date('Y-m-d H:i:s');
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

		// add
		}else{
		// edit
			$this->db->where('request_type_id', $id_edit);
			$this->db->update($table, $data_insert);	
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");

		// edit
		}
		
		echo"<script> document.location.href='".PROJECTPATH."/setting_facility_data/request_type' </script>"; 
	}
	
	public function facility_type()
	{
		$arr_data = array();
		$id = @$_GET['id'];
		if(!empty($id)){
			$this->db->select(array('*'));
			$this->db->from('coop_facility_type');
			$this->db->where("facility_type_id = '{$id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0]; 	
		}else{	
			$this->db->select('COUNT(facility_type_id) as _c');
			$this->db->from('coop_facility_type');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 10 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');			
			$this->db->from('( SELECT *, ROW_NUMBER() OVER (ORDER BY facility_type_id DESC) as row FROM coop_facility_type) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$this->db->order_by('facility_type_id DESC');
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;
		}
		
		$this->libraries->template('setting_facility_data/facility_type',$arr_data);
	}
	
	public function facility_type_save()
	{
		$data_insert = array();			
		$data_insert['facility_type_name']    = @$_POST["facility_type_name"];
		$data_insert['updatetime'] = date('Y-m-d H:i:s');

		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;
			

		$table = "coop_facility_type";

		if ($type_add == 'add') {	
		// add		
			$data_insert['createdatetime'] = date('Y-m-d H:i:s');
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

		// add
		}else{
		// edit
			$this->db->where('facility_type_id', $id_edit);
			$this->db->update($table, $data_insert);	
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");

		// edit
		}
		
		echo"<script> document.location.href='".PROJECTPATH."/setting_facility_data/facility_type' </script>"; 
	}
	
	public function facility_main()
	{
		$arr_data = array();
		$id = @$_GET['id'];
		if(!empty($id)){
			$this->db->select(array('*'));
			$this->db->from('coop_facility_main');
			$this->db->where("facility_main_id = '{$id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0]; 	
		}else{	
			$this->db->select('COUNT(facility_main_id) as _c');
			$this->db->from('coop_facility_main');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 10 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');			
			$this->db->from('( SELECT coop_facility_main.*,coop_facility_type.facility_type_name, 
								coop_unit_type.unit_type_name, 
								coop_depreciation.depreciation_name, 
								ROW_NUMBER() OVER (ORDER BY coop_facility_main.facility_main_id DESC) as row FROM coop_facility_main
								LEFT JOIN coop_facility_type ON coop_facility_main.facility_type_id = coop_facility_type.facility_type_id
								LEFT JOIN coop_unit_type ON coop_facility_main.unit_type_id = coop_unit_type.unit_type_id
								LEFT JOIN coop_depreciation ON coop_facility_main.depreciation_id = coop_depreciation.depreciation_id
							) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$this->db->order_by('facility_main_id DESC');
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;
		}
		
		//รหัส >> เลือกจากกลุ่มพัสดุ
		$this->db->select(array('*'));
		$this->db->from('coop_facility_group');
		$this->db->where("facility_group_type = '3'");
		$rs_group = $this->db->get()->result_array();
		$arr_data['rs_group'] = @$rs_group; 
		//print_r($this->db->last_query()); exit;
		//ประเภทพัสดุ
		$this->db->select(array('*'));
		$this->db->from('coop_facility_type');
		$rs_type = $this->db->get()->result_array();
		$arr_data['rs_type'] = @$rs_type;
		
		//หน่วยนับ
		$this->db->select(array('*'));
		$this->db->from('coop_unit_type');
		$rs_unit = $this->db->get()->result_array();
		$arr_data['rs_unit'] = @$rs_unit; 
		
		//ค่าเสื่อม
		$this->db->select(array('*'));
		$this->db->from('coop_depreciation');
		$rs_depreciation = $this->db->get()->result_array();
		$arr_data['rs_depreciation'] = @$rs_depreciation; 
		
		$this->libraries->template('setting_facility_data/facility_main',$arr_data);
	}
	
	public function facility_main_save()
	{
		$data_insert = array();
		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;
		$table = "coop_facility_main";
		
		$facility_main_code = @$_POST["facility_main_code"];
		$facility_group_full_code = @$_POST["facility_group_full_code"];
		$facility_main_run = @$_POST["facility_main_run"];		
		
		$this->db->select(array('MAX(facility_main_run) AS last_run'));
		$this->db->from('coop_facility_main');
		$this->db->where("facility_group_full_code = '{$facility_main_code}'");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0]; 
		
		if(!empty($id_edit) && $facility_main_code == $facility_group_full_code){
			$main_run = $facility_main_run;	
			$main_id = sprintf("%04d",$facility_main_run);	
		}else{
			$main_run = $row['last_run']+1;	
			$main_id = sprintf("%04d",$main_run);
		}

		
		$facility_main_code = $_POST["facility_main_code"].'-'.$main_id;
		$data_insert['facility_main_run']  = @$main_run;
		$data_insert['facility_group_full_code']  = @$_POST["facility_main_code"];
		$data_insert['facility_main_code']  = @$facility_main_code;
		$data_insert['facility_type_id']    = @$_POST["facility_type_id"];
		$data_insert['facility_main_name']  = @$_POST["facility_main_name"];
		$data_insert['facility_main_note']  = @$_POST["facility_main_note"];
		$data_insert['facility_main_price'] = @$_POST["facility_main_price"];
		$data_insert['unit_type_id']    = @$_POST["unit_type_id"];
		$data_insert['depreciation_id']    = @$_POST["depreciation_id"];
		$data_insert['updatetime'] = date('Y-m-d H:i:s');			

		if ($type_add == 'add') {	
		// add		
			$data_insert['createdatetime'] = date('Y-m-d H:i:s');
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

		// add
		}else{
		// edit
			$this->db->where('facility_main_id', $id_edit);
			$this->db->update($table, $data_insert);	
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");

		// edit
		}
		
		echo"<script> document.location.href='".PROJECTPATH."/setting_facility_data/facility_main' </script>"; 
	}
	
}	
