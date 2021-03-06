<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_deposit_data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	public function coop_deposit_setting(){
		$arr_data = array();		
			
		$this->db->select(array('*'));
		$this->db->from('coop_deposit_setting');
		$this->db->order_by('deposit_setting_id DESC');
		$rs = $this->db->get()->result_array();
		$arr_data['row'] = @$rs[0];
			
		$this->libraries->template('setting_deposit_data/coop_deposit_setting',$arr_data);
	}
	
	public function coop_deposit_setting_save(){
		$data_insert = array();
		
		foreach(@$_POST as $key => $value){
			if($key  != 'deposit_setting_id'){
				$data_insert[@$key]	= @$value;
			}		
		}

		$id_edit = @$_POST["deposit_setting_id"] ;

		$table = "coop_deposit_setting";

		
		// edit
		$this->db->where('deposit_setting_id', $id_edit);
		$this->db->update($table, $data_insert);
		$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");	
		// edit
				
		echo"<script> document.location.href='".PROJECTPATH."/setting_deposit_data/coop_deposit_setting' </script>";            
	}

	public function coop_deposit_type_setting()
	{
		$arr_data = array();
		$id = @$_GET['id'];
		$filter = @$_GET['filter'];
		
		if(!empty($id)){
			$this->db->select(array('*'));
			$this->db->from('coop_interest');
			$this->db->where("interest_id = '{$id}'");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0]; 	
		}else{	
			$where ='';
			if($filter != ''){
				$where = "AND  coop_interest.type_id = '{$filter}'";
			}
			
			$x=0;
			$join_arr = array();
			$join_arr[$x]['table'] = 'coop_deposit_type_setting';
			$join_arr[$x]['condition'] = 'coop_interest.type_id = coop_deposit_type_setting.type_id';
			$join_arr[$x]['type'] = 'left';
			
			$this->paginater_all->type(DB_TYPE);
			$this->paginater_all->select('coop_interest.*,coop_deposit_type_setting.type_name');
			$this->paginater_all->main_table('coop_interest');
			$this->paginater_all->where($where);
			$this->paginater_all->page_now(@$_GET["page"]);
			$this->paginater_all->per_page(10);
			$this->paginater_all->page_link_limit(20);
			$this->paginater_all->order_by('coop_interest.interest_id ASC');
			$this->paginater_all->join_arr($join_arr);
			$row = $this->paginater_all->paginater_process();
			//echo $this->db->last_query();exit;
			//echo"<pre>";print_r($row);exit;
			$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit'], $_GET);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
			
			$i = $row['page_start'];

			$arr_data['num_rows'] = $row['num_rows'];
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $row['data'];
			$arr_data['i'] = $i;			
		}
		
		$this->db->select(array('*'));
		$this->db->from('coop_deposit_type_setting');
		$rs_type = $this->db->get()->result_array();
		$arr_data['rs_type'] = @$rs_type;
		$this->libraries->template('setting_deposit_data/coop_deposit_type_setting',$arr_data);
	}
	
	public function coop_deposit_type_setting_save()
	{
		$data_insert = array();				
		$data_insert['type_name']      = @$_POST["type_name"];		
		$data_insert['updatetime']    = date('Y-m-d H:i:s');
		
		$id_edit = @$_POST["type_id"] ;
			

		$table = "coop_deposit_type_setting";

		if ($id_edit == '') {	
		// add		
			$data_insert['createdatetime'] = date('Y-m-d H:i:s');
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

		// add
		}else{
		// edit
			$this->db->where('type_id', $id_edit);
			$this->db->update($table, $data_insert);	
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");

		// edit
		}
		//print_r($this->db->last_query());exit;
		echo"<script> document.location.href='".PROJECTPATH."/setting_deposit_data/coop_deposit_type_setting' </script>"; 

	}	
	public function coop_interest_setting_save()
	{
		$data_insert = array();		
		$data_insert['type_id']      = @$_POST["type_id"];		
		$data_insert['interest_rate']    = @$_POST["interest_rate"];
		$data_insert['start_date']    = $this->center_function->ConvertToSQLDate(@$_POST["start_date"]);

		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;
			

		$table = "coop_interest";

		if ($type_add == 'add') {	
		// add		
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");

		// add
		}else{
		// edit
			$this->db->where('interest_id', $id_edit);
			$this->db->update($table, $data_insert);	
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");

		// edit
		}
		
		echo"<script> document.location.href='".PROJECTPATH."/setting_deposit_data/coop_deposit_type_setting' </script>"; 

	}
	
	function check_use_type(){	
		$id = @$_POST['id'];
		$this->db->select(array('*'));
		$this->db->from('coop_interest');
		$this->db->where("type_id = '{$id}'");
		$rs = $this->db->get()->result_array();
		$row = @$rs[0];
		if(@$row['type_id']){
			echo false;
		}else{
			echo true;
		}		
		exit;
	}
	
	function del_coop_deposit_type(){	
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
	
}
