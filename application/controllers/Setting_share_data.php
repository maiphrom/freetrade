<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_share_data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	public function coop_share_rule(){
		$arr_data = array();
		$id = @$_GET['id'];
		if(@$id){
			$this->db->select(array('*'));
			$this->db->from('coop_share_rule');
			$this->db->where("share_rule_id  = '{$id}' ");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0];
		}else{	
			$this->db->select('COUNT(share_rule_id) as _c');
			$this->db->from('coop_share_rule');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 20 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');
			$this->db->from('( SELECT *, ROW_NUMBER() OVER (ORDER BY salary_rule ASC) as row FROM coop_share_rule ) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;
			
			$this->db->select(array('*'));
			$this->db->from('coop_share_setting');
			$this->db->where("setting_id  = '1' ");
			$rs_setting = $this->db->get()->result_array();
			$arr_data['row_setting'] = @$rs_setting[0];
			
		}
		$this->libraries->template('setting_share_data/coop_share_rule',$arr_data);
	}
	
	public function coop_share_rule_save(){
		$data_insert = array();
		$data_insert['salary_rule']	= @$_POST["salary_rule"];
		$data_insert['share_first']	= '';
		$data_insert['share_salary']  = @$_POST["share_salary"];

		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"] ;

		$table = "coop_share_rule";

		if ($type_add == 'add') {
			// add
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
			// add
		}else{
			// edit
			$this->db->where('share_rule_id', $id_edit);
			$this->db->update($table, $data_insert);
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");	
			// edit
		}		
		echo"<script> document.location.href='".PROJECTPATH."/setting_share_data/coop_share_rule' </script>";            
	}
	
	public function coop_share_rule_change(){
		$data_insert = array();
		$data_insert['setting_value']	= @$_POST["share_cost"];
		
		$id_edit = @$_POST["setting_id"] ;

		$table = "coop_share_setting";
		
		// edit
		$this->db->where('setting_id', $id_edit);
		$this->db->update($table, $data_insert);
		$this->center_function->toast("เปลี่ยนมูลค่าหุ้นเรียบร้อยแล้ว");	
		// edit

		echo"<script> document.location.href='".PROJECTPATH."/setting_share_data/coop_share_rule' </script>";            
	}
	
	function del_coop_share_data(){	
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
