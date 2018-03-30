<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_account_data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	public function coop_account_chart(){
		$arr_data = array();
		$id = @$_GET['id'];
		if(@$id){
			$this->db->select(array('*'));
			$this->db->from('coop_account_chart');
			$this->db->where("account_chart_id  = '{$id}' ");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0];
		}else{	
			$this->db->select('COUNT(account_chart_id) as _c');
			$this->db->from('coop_account_chart');
			$count = $this->db->get()->result_array();

			$num_rows = $count[0]["_c"] ;
			$per_page = 20 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');
			$this->db->from('( SELECT *, ROW_NUMBER() OVER (ORDER BY account_chart_id ASC) as row FROM coop_account_chart ) a');
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;


			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;			
		}
		$this->libraries->template('setting_account_data/coop_account_chart',$arr_data);
	}
	
	public function coop_account_chart_save(){
		$data_insert = array();
		$data_insert['account_chart_id']	= @$_POST["account_chart_id"];
		$data_insert['account_chart']  = @$_POST["account_chart"];

		$id_edit = @$_POST["old_account_chart_id"] ;

		$table = "coop_account_chart";

		if(@$_POST['old_account_chart_id']!=''){			
			// edit
			$this->db->where('account_chart_id', $id_edit);
			$this->db->update($table, $data_insert);
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");	
			// edit
		}else{
			// add
			$this->db->insert($table, $data_insert);
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
			// add
			
		}		
		echo"<script> document.location.href='".PROJECTPATH."/setting_account_data/coop_account_chart' </script>";            
	}
	
	public function check_account_chart(){
		$account_chart_id = trim(@$_POST['account_chart_id']);
		
		$this->db->select('COUNT(account_chart_id) as _c');
		$this->db->from('coop_account_chart');
		$this->db->where("account_chart_id  = '{$account_chart_id}' ");
		$count = $this->db->get()->result_array();
		$num_rows = $count[0]["_c"] ;
			
		if($num_rows > 0){
			echo "dupplicate";
		}else{
			echo "success";
		}
	}
	
	function del_coop_account_data(){	
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
	
	public function coop_account_receipt(){
		$arr_data = array();
		$id = @$_GET['id'];
		if(@$id){
			$this->db->select(array('*'));
			$this->db->from('coop_account_list');
			$this->db->join("coop_account_match", "coop_account_list.account_id = coop_account_match.match_id AND coop_account_match.match_type = 'account_list'", "left");
			$this->db->where("coop_account_list.account_id  = '{$id}' ");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0];
			//print_r($this->db->last_query());exit;
		}else{	
			$this->db->select('COUNT(account_id) as _c');
			$this->db->from('coop_account_list');
			$this->db->join("coop_account_match", "coop_account_list.account_id = coop_account_match.match_id AND coop_account_match.match_type = 'account_list'", "left");
			$count = $this->db->get()->result_array();
			
			$num_rows = $count[0]["_c"] ;
			$per_page = 20 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');
			$this->db->from("( SELECT coop_account_list.*,coop_account_match.account_chart_id, ROW_NUMBER() OVER (ORDER BY account_id ASC) as row FROM coop_account_list 
			LEFT JOIN coop_account_match ON coop_account_list.account_id = coop_account_match.match_id AND coop_account_match.match_type = 'account_list') a ");
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;
			//print_r($this->db->last_query());exit;
			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;			
		}
		
		$this->db->select(array('*'));
		$this->db->from('coop_account_chart');
		$rs_account_chart = $this->db->get()->result_array();
		$arr_data['rs_account_chart'] = @$rs_account_chart;
		
		$this->libraries->template('setting_account_data/coop_account_receipt',$arr_data);
	}
	
	public function coop_account_receipt_save(){
		$data_insert = array();
		$data_insert['account_list']	= @$_POST["account_list"];
		$data_insert['amount']  = @$_POST["amount"];
		
		$data_insert_match = array();
		
		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"];

		$table = "coop_account_list";
		$table_sub = "coop_account_match";

		if ($type_add == 'add') {
			// add
			$this->db->insert($table, $data_insert);			
			// add
			
			$this->db->select(array('*'));
			$this->db->from($table);
			$this->db->order_by('account_id DESC');
			$this->db->limit(1);
			$rs = $this->db->get()->result_array();
			$row = @$rs[0];
					
			$data_insert_match['match_type'] = 'account_list';
			$data_insert_match['account_chart_id'] = @$_POST["account_chart_id"];
			$data_insert_match['match_id'] = @$row['account_id'];
			$data_insert_match['match_id_description'] = 'id จากตาราง coop_account_list';
			
			$this->db->insert($table_sub, $data_insert_match);			
			
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		}else{
			// edit
			$this->db->where('account_id', $id_edit);
			$this->db->update($table, $data_insert);			
			// edit
			
			$data_insert_match['account_chart_id'] = @$_POST["account_chart_id"];
			$this->db->where("match_id = '{$id_edit}' AND match_type = 'account_list' ");
			$this->db->update($table_sub, $data_insert_match);	
	  
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");	
		}
		//print_r($this->db->last_query()); exit;	
		echo"<script> document.location.href='".PROJECTPATH."/setting_account_data/coop_account_receipt' </script>";            
	}
	
	function del_coop_account_receipt_data(){	
		$table = @$_POST['table'];
		$id = @$_POST['id'];
		$field = @$_POST['field'];

		$this->db->where($field, $id );
		$this->db->delete($table);
		
		$this->db->where("match_id = '".$id."' AND match_type = 'account_list'");
		$this->db->delete('coop_account_match');
		
		
		$this->center_function->toast("ลบเรียบร้อยแล้ว");
		echo true;
		
	}
	
	public function coop_account_buy(){
		$arr_data = array();
		$id = @$_GET['id'];
		if(@$id){
			$this->db->select(array('*'));
			$this->db->from('coop_account_buy_list');
			$this->db->join("coop_account_match", "coop_account_buy_list.account_id = coop_account_match.match_id AND coop_account_match.match_type = 'account_buy_list'", "left");
			$this->db->where("coop_account_buy_list.account_id  = '{$id}' ");
			$rs = $this->db->get()->result_array();
			$arr_data['row'] = @$rs[0];
			//print_r($this->db->last_query());exit;
		}else{	
			$this->db->select('COUNT(coop_account_buy_list.account_id) as _c');
			$this->db->from('coop_account_buy_list');
			$this->db->join("coop_account_match", "coop_account_buy_list.account_id = coop_account_match.match_id AND coop_account_match.match_type = 'account_buy_list'", "left");
			$count = $this->db->get()->result_array();
			
			$num_rows = $count[0]["_c"] ;
			$per_page = 20 ;
			$page = isset($_GET["page"]) ? ((int) $_GET["page"]) : 1;
			$paging = $this->pagination_center->paginating($page, $num_rows, $per_page, 20);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20

			$page_start = (($per_page * $page) - $per_page);
			if($page_start==0){ $page_start = 1;}

			$this->db->select('*');
			$this->db->from("( SELECT coop_account_buy_list.*,coop_account_match.account_chart_id, ROW_NUMBER() OVER (ORDER BY account_id ASC) as row FROM coop_account_buy_list 
			LEFT JOIN coop_account_match ON coop_account_buy_list.account_id = coop_account_match.match_id AND coop_account_match.match_type = 'account_buy_list') a ");
			$this->db->where("row >= ".$page_start." AND row <= ".($page_start+$per_page-1));
			$rs = $this->db->get()->result_array();
			
			$i = $page_start;
			//print_r($this->db->last_query());exit;
			$arr_data['num_rows'] = $num_rows;
			$arr_data['paging'] = $paging;
			$arr_data['rs'] = $rs;
			$arr_data['i'] = $i;			
		}
		
		$this->db->select(array('*'));
		$this->db->from('coop_account_chart');
		$rs_account_chart = $this->db->get()->result_array();
		$arr_data['rs_account_chart'] = @$rs_account_chart;
		
		$this->libraries->template('setting_account_data/coop_account_buy',$arr_data);
	}
	
	public function coop_account_buy_save(){
		$data_insert = array();
		$data_insert['account_list']	= @$_POST["account_list"];
		$data_insert['amount']  = @$_POST["amount"];
		
		$data_insert_match = array();
		
		$type_add = @$_POST["type_add"] ;
		$id_edit = @$_POST["id"];

		$table = "coop_account_buy_list";
		$table_sub = "coop_account_match";

		if ($type_add == 'add') {
			// add
			$this->db->insert($table, $data_insert);			
			// add
			
			$this->db->select(array('*'));
			$this->db->from($table);
			$this->db->order_by('account_id DESC');
			$this->db->limit(1);
			$rs = $this->db->get()->result_array();
			$row = @$rs[0];
					
			$data_insert_match['match_type'] = 'account_buy_list';
			$data_insert_match['account_chart_id'] = @$_POST["account_chart_id"];
			$data_insert_match['match_id'] = @$row['account_id'];
			$data_insert_match['match_id_description'] = 'id จากตาราง coop_account_buy_list';
			
			$this->db->insert($table_sub, $data_insert_match);			
			 
			$this->center_function->toast("บันทึกข้อมูลเรียบร้อยแล้ว");
		}else{
			// edit
			$this->db->where('account_id', $id_edit);
			$this->db->update($table, $data_insert);			
			// edit
			
			$data_insert_match['account_chart_id'] = @$_POST["account_chart_id"];
			$this->db->where("match_id = '{$id_edit}' AND match_type = 'account_buy_list' ");
			$this->db->update($table_sub, $data_insert_match);	
	  
			$this->center_function->toast("แก้ไขข้อมูลเรียบร้อยแล้ว");	
		}
		//print_r($this->db->last_query()); exit;	
		echo"<script> document.location.href='".PROJECTPATH."/setting_account_data/coop_account_buy' </script>";            
	}
	
	function del_coop_account_buy_data(){	
		$table = @$_POST['table'];
		$id = @$_POST['id'];
		$field = @$_POST['field'];

		$this->db->where($field, $id );
		$this->db->delete($table);
		
		$this->db->where("match_id = '".$id."' AND match_type = 'account_buy_list'");
		$this->db->delete('coop_account_match');
		
		
		$this->center_function->toast("ลบเรียบร้อยแล้ว");
		echo true;
		
	}
}