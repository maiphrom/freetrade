<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_member_data extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	public function coop_report_member_retire(){
		$arr_data = array();
		$this->libraries->template('report_member_data/coop_report_member_retire',$arr_data);
	}
	
	function check_report_member_retire(){	
		if(@$_POST['report_date'] != ''){
			$date_arr = explode('/',@$_POST['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
			$where = "AND coop_mem_req_resign.resign_date LIKE '".@$year.'-'.sprintf("%02d",@$month)."-".sprintf("%02d",@$day)."%'";
		}else{
			if(@$_POST['month']!='' && @$_POST['year']!=''){
				$day = '';
				$month = @$_POST['month'];
				$year = (@$_POST['year']-543);
				$where = "AND coop_mem_req_resign.resign_date LIKE '".@$year.'-'.sprintf("%02d",@$month)."%'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_POST['year']-543);
				$where = "AND coop_mem_req_resign.resign_date LIKE '".@$year."%'";
			}
		}
		
		$this->db->select('coop_mem_apply.member_id');
		$this->db->from('coop_mem_req_resign');
		$this->db->join("coop_mem_apply", "coop_mem_req_resign.member_id = coop_mem_apply.member_id", "inner");
		$this->db->where("coop_mem_req_resign.req_resign_status = '1' {$where} ");
		$rs_check = $this->db->get()->result_array();
		$row_check = @$rs_check[0];
		//print_r($this->db->last_query());
		if(@$row_check['member_id'] != ''){
			echo "success";
		}		
	}
	
	function coop_report_member_retire_excel(){
		$arr_data = array();
		$this->db->select(array('id','mem_group_name'));
		$this->db->from('coop_mem_group');
		$rs_group = $this->db->get()->result_array();
		$mem_group_arr = array();
		foreach($rs_group as $key => $row_group){
			$mem_group_arr[$row_group['id']] = $row_group['mem_group_name'];
		}
		$arr_data['mem_group_arr'] = $mem_group_arr;
		
		$this->db->select(array('setting_value'));
		$this->db->from('coop_share_setting');
		$this->db->where("setting_id = '1'");
		$row_share_value = $this->db->get()->result_array();
		$share_value = $row_share_value[0]['setting_value'];
		$arr_data['share_value'] = $share_value;
		$this->load->view('report_member_data/coop_report_member_retire_excel',$arr_data);
	}
	
	public function coop_report_member_in_out(){
		$arr_data = array();
		$this->libraries->template('report_member_data/coop_report_member_in_out',$arr_data);
	}
	
	function check_report_member_in_out(){
		$where_check = '';
		$where_check2 = '';
		if(@$_POST['report_date'] != ''){
			$date_arr = explode('/',@$_POST['report_date']);
			$day = (int)@$date_arr[0];
			$month = (int)@$date_arr[1];
			$year = (int)@$date_arr[2];
			$year -= 543;
			$where_check = " AND t1.apply_date LIKE '".@$year.'-'.sprintf("%02d",@$month)."-".sprintf("%02d",@$day)."%'";
			$where_check2 = " AND t3.resign_date LIKE '".@$year.'-'.sprintf("%02d",@$month)."-".sprintf("%02d",@$day)."%'";
		}else{
			if(@$_POST['month']!='' && @$_POST['year']!=''){
				$day = '';
				$month = @$_POST['month'];
				$year = (@$_POST['year']-543);
				$where_check = " AND t1.apply_date LIKE '".@$year.'-'.sprintf("%02d",@$month)."%'";
				$where_check2 = " AND t3.resign_date LIKE '".@$year.'-'.sprintf("%02d",@$month)."%'";
			}else{
				$day = '';
				$month = '';
				$year = (@$_POST['year']-543);
				$where_check = " AND t1.apply_date LIKE '".@$year."%'";
				$where_check2 = " AND t3.resign_date LIKE '".@$year."%'";
			}
		}

		$this->db->select('t1.member_id');
		$this->db->from('coop_mem_apply as t1');
		$this->db->join("coop_prename as t2", "t1.prename_id = t2.prename_id", "left");
		$this->db->where("1=1 {$where_check }");
		$rs_check = $this->db->get()->result_array();
		$row_check = @$rs_check[0];
		
		$this->db->select('t1.member_id');
		$this->db->from('coop_mem_apply as t1');
		$this->db->join("coop_prename as t2 ", "t1.prename_id = t2.prename_id", "left");
		$this->db->join("coop_mem_req_resign as t3 ", "t1.member_id = t3.member_id", "inner");
		$this->db->join("coop_mem_resign_cause as t4 ", "t3.resign_cause_id = t4.resign_cause_id", "left");
		$this->db->where("t3.req_resign_status = '1'  {$where_check2} ");
		$rs_check2 = $this->db->get()->result_array();
		$row_check2 = @$rs_check2[0];
		
		
		if(@$row_check['member_id'] != '' || @$row_check2['member_id'] != ''){
			echo "success";
		}	
	}
	
	function coop_report_member_in_out_excel(){
		$arr_data = array();
		$this->db->select(array('id','mem_group_name'));
		$this->db->from('coop_mem_group');
		$rs_group = $this->db->get()->result_array();
		$mem_group_arr = array();
		foreach($rs_group as $key => $row_group){
			$mem_group_arr[$row_group['id']] = $row_group['mem_group_name'];
		}
		$arr_data['mem_group_arr'] = $mem_group_arr;
		
		$this->db->select(array('id','loan_type'));
		$this->db->from('coop_loan_type');
		$rs_loan_type = $this->db->get()->result_array();
		$loan_type = array();
		foreach($rs_loan_type as $key => $row_loan_type){
			$loan_type[$row_loan_type['id']] = $row_loan_type['loan_type'];
		}
		$arr_data['loan_type'] = $loan_type;

		$this->db->select(array('setting_value'));
		$this->db->from('coop_share_setting');
		$this->db->where("setting_id = '1'");
		$row_share_value = $this->db->get()->result_array();
		$share_value = $row_share_value[0]['setting_value'];
		$arr_data['share_value'] = $share_value;
		$this->load->view('report_member_data/coop_report_member_in_out_excel',$arr_data);
	}
	
	
}
