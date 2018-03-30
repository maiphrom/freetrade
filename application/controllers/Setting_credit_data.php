<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_credit_data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	public function coop_term_of_loan(){
		$arr_data = array();
		$id = @$_GET['id'];
		if(@$id){
			$this->db->select(array('*'));
			$this->db->from('coop_term_of_loan');
			$this->db->where("id  = '{$id}' ");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0];
		}else{	
			$this->db->select('COUNT(id) as _c');
			$this->db->from('coop_term_of_loan');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 20 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');
			$this->db->from('( SELECT *, ROW_NUMBER() OVER (ORDER BY id ASC) as row FROM coop_term_of_loan ) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;			
		}
		$this->db->select(array('*'));
		$this->db->from('coop_loan_type');
		$arr_data['loan_type'] = $this->db->get()->result_array();
		$this->libraries->template('setting_credit_data/coop_term_of_loan',$arr_data);
	}
	
	public function coop_term_of_loan_save(){
		$data_insert = array();
		//$data_insert_sub = array();		
		
		if(@$_POST["start_date"]!=''){
			$data_insert['start_date'] = $this->center_function->ConvertToSQLDate($_POST['start_date']);
		}
		$data_insert['type_id'] = @$_POST["type_id"];
		$data_insert['type_name'] = @$_POST["type_name"];
		$data_insert['less_than_multiple_salary']  = @$_POST["less_than_multiple_salary"];
		$data_insert['least_share_percent_for_loan']  = @$_POST["least_share_percent_for_loan"];
		$data_insert['min_month_member']  = @$_POST["min_month_member"];
		$data_insert['max_period']  = @$_POST["max_period"];
		$data_insert['min_installment_percent']	= @$_POST["min_installment_percent"];
		$data_insert['interest_rate']  = @$_POST["interest_rate"];
		$data_insert['num_guarantee']  = @$_POST["num_guarantee"];
		$data_insert['percent_share_guarantee']  = @$_POST["percent_share_guarantee"];
		$data_insert['percent_fund_quarantee']  = @$_POST["percent_fund_quarantee"];
		$data_insert['prefix_code']	= @$_POST["prefix_code"];	
		$data_insert['credit_limit']  = @$_POST["credit_limit"];
		$data_insert['credit_limit_share_percent']  = @$_POST["credit_limit_share_percent"];
		$data_insert['min_share_fund_money']  = @$_POST["min_share_fund_money"];
		$data_insert['min_month_share_period']  = @$_POST["min_month_share_period"];
		$data_insert['min_share_total']  = @$_POST["min_share_total"];	

		$data_insert['share_guarantee']  = @$_POST["share_guarantee"];
		$data_insert['person_guarantee']  = @$_POST["person_guarantee"];

		//$data_insert_sub['loan_type'] = @$_POST["type_name"];
		//echo"<pre>";print_r($data_insert);exit;
		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"];
		
		$table = "coop_term_of_loan";
		//$table_sub = "coop_loan_type";

		if ($type_add == 'add') {
			/*$this->db->select('MAX(type_id) as _max');
			$this->db->from('coop_term_of_loan');
			$max = $this->db->get()->result_array();
			$type_id = @$max[0]["_max"] + 1 ;
			
			$data_insert['type_id']  = @$type_id;	*/	
			// add
			$this->db->insert($table, $data_insert);

			//$data_insert_sub['id'] = @$type_id;
			//$this->db->insert($table_sub, $data_insert_sub);

			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
			// add		
		}else{
			// edit
			$this->db->where('id', $id_edit);
			$this->db->update($table, $data_insert);

			
			$this->db->select('type_id');
			$this->db->from('coop_term_of_loan');
			$this->db->where("id  = '{$id_edit}' ");
			$re = $this->db->get()->result_array();
			$type_id = @$re[0]["type_id"];

			$this->db->where('id', $type_id);
			//$this->db->update($table_sub, $data_insert_sub);

			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");	
			// edit			
		}		
		echo"<script> document.location.href='".PROJECTPATH."/setting_credit_data/coop_term_of_loan' </script>";            
	}
	
	function del_coop_credit_data(){	
		$table = @$_POST['table'];
		$id = @$_POST['id'];
		$field = @$_POST['field'];

		$this->db->select('type_id');
		$this->db->from('coop_term_of_loan');
		$this->db->where("id  = '{$id}' ");
		$re = $this->db->get()->result_array();
		$type_id = @$re[0]["type_id"];

		$this->db->where($field, $id );
		$this->db->delete($table);

		$this->db->where('id', $type_id );
		$this->db->delete('coop_loan_type');

		$this->center_function->toast("ลบเรียบร้อยแล้ว");
		echo true;
		
	}

	public function coop_loan_reason(){
		$arr_data = array();
		$id = @$_GET['id'];
		if(@$id){
			$this->db->select(array('*'));
			$this->db->from('coop_loan_reason');
			$this->db->where("loan_reason_id  = '{$id}' ");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0];
		}else{	
			$this->db->select('COUNT(loan_reason_id) as _c');
			$this->db->from('coop_loan_reason');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 20 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');
			$this->db->from('( SELECT *, ROW_NUMBER() OVER (ORDER BY loan_reason_id ASC) as row FROM coop_loan_reason ) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;			
		}
		$this->libraries->template('setting_credit_data/coop_loan_reason',$arr_data);
	}
	
	public function coop_loan_reason_save(){
		$data_insert = array();
		$data_insert['loan_reason']  = @$_POST["loan_reason"];

		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["loan_reason_id"];
		
		$table = "coop_loan_reason";

		if (@$_POST['loan_reason_id']!='') {	
			// edit
			$this->db->where('loan_reason_id', $id_edit);
			$this->db->update($table, $data_insert);
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");	
			// edit		
				
		}else{
			// add
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
			// add			
		}	

		echo"<script> document.location.href='".PROJECTPATH."/setting_credit_data/coop_loan_reason' </script>";            
	}
	
	function del_coop_reason_data(){	
		$table = @$_POST['table'];
		$id = @$_POST['id'];
		$field = @$_POST['field'];

		$this->db->where($field, $id );
		$this->db->delete($table);

		$this->center_function->toast("ลบเรียบร้อยแล้ว");
		echo true;
		
	}
	
	function check_use_type(){
		$this->db->select('*');
		$this->db->from('coop_term_of_loan');
		$this->db->where("id = '".$_POST['id']."'");
		$row = $this->db->get()->result_array();
		if(!empty($row)){
			echo "error";
		}else{
			echo "success";
		}
		exit;
	}
	
	function del_loan_type(){	
		$this->db->where('id', $_GET['id'] );
		$this->db->delete('coop_loan_type');
		$this->center_function->toast("ลบข้อมูลเรียบร้อยแล้ว");
		echo"<script> document.location.href='".PROJECTPATH."/setting_credit_data/coop_term_of_loan' </script>";       
		
	}
	
	function coop_loan_type_save(){
		//echo"<pre>";print_r($_POST);exit;
		$data_insert = array();
		if(@$_POST['loan_type_id']!=''){
			$data_insert['loan_type']  = @$_POST['loan_type'];	
			$this->db->where('id', @$_POST['loan_type_id']);
			$this->db->update('coop_loan_type', $data_insert);
		}else{
			$data_insert['loan_type']  = @$_POST['loan_type'];	
			$this->db->insert('coop_loan_type', $data_insert);
		}
		$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		echo"<script> document.location.href='".PROJECTPATH."/setting_credit_data/coop_term_of_loan' </script>";       
	}
	
}
