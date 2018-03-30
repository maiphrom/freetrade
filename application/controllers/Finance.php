<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function finance_month()
	{
		$arr_data = array();
		$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		$arr_data['month_arr'] = $month_arr;
		$this->libraries->template('finance/finance_month',$arr_data);
	}
	
	function finance_month_share_report(){
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
		
		$this->db->select(array('t1.*', 
			't2.prename_short'));
		$this->db->from('coop_mem_apply as t1');
		$this->db->join('coop_prename as t2','t1.prename_id = t2.prename_id','left');
		$this->db->where("member_status = '1'");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = $rs;

		$this->load->view('finance/finance_month_share_report',$arr_data);
	}
	
	function finance_month_loan_report(){
		$arr_data = array();
		$this->db->select(array('id','mem_group_name'));
		$this->db->from('coop_mem_group');
		$rs_group = $this->db->get()->result_array();
		$mem_group_arr = array();
		foreach($rs_group as $key => $row_group){
			$mem_group_arr[$row_group['id']] = $row_group['mem_group_name'];
		}
		$arr_data['mem_group_arr'] = $mem_group_arr;
		
		$this->db->select(array('id','interest_rate'));
		$this->db->from('coop_term_of_loan');
		$this->db->where("type_id = '1'");
		$row_term_of_loan = $this->db->get()->result_array();
		$interest_rate_arr = explode('.',$row_term_of_loan[0]['interest_rate']);
		if(@$interest_rate_arr[1] > 0){
			$interest_rate = $row_term_of_loan[0]['interest_rate'];
		}else{
			$interest_rate = $interest_rate_arr[0];
		}
		$arr_data['interest_rate'] = $interest_rate;
		
		$this->db->select(
			array(
				't2.id as loan_id',
				't2.loan_type',
				't2.loan_amount_balance',
				't2.interest_per_year',
				't3.member_id',
				't4.prename_short',
				't3.firstname_th',
				't3.lastname_th',
				't3.employee_id',
				't3.department',
				't3.faction',
				't3.level',
				't2.contract_number',
				't5.date_transfer'
			)
		);
		$this->db->from('coop_loan as t2');
		$this->db->join('coop_mem_apply as t3','t2.member_id = t3.member_id','inner');
		$this->db->join('coop_prename as t4','t3.prename_id = t4.prename_id','left');
		$this->db->join('coop_loan_transfer as t5','t2.id = t5.loan_id','inner');
		$this->db->where("
			t2.loan_type IN('1','2','5','6') 
			AND t2.loan_status = '1'
			AND t2.loan_amount_balance > 0
			AND t2.date_start_period <= '".($_GET['year']-543)."-".sprintf("%02d",$_GET['month'])."-".date('t',strtotime(($_GET['year']-543)."-".sprintf("%02d",$_GET['month'])."-01"))."'
		");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = $rs;

		$this->load->view('finance/finance_month_loan_report',$arr_data);
	}
	function finance_month_loan_emergent_report(){
		$arr_data = array();
		$this->db->select(array('id','mem_group_name'));
		$this->db->from('coop_mem_group');
		$rs_group = $this->db->get()->result_array();
		$mem_group_arr = array();
		foreach($rs_group as $key => $row_group){
			$mem_group_arr[$row_group['id']] = $row_group['mem_group_name'];
		}
		$arr_data['mem_group_arr'] = $mem_group_arr;
		
		$this->db->select(array('id','interest_rate'));
		$this->db->from('coop_term_of_loan');
		$this->db->where("type_id = '3'");
		$row_term_of_loan = $this->db->get()->result_array();
		$interest_rate_arr = explode('.',$row_term_of_loan[0]['interest_rate']);
		if(@$interest_rate_arr[1] > 0){
			$interest_rate = $row_term_of_loan[0]['interest_rate'];
		}else{
			$interest_rate = $interest_rate_arr[0];
		}
		$arr_data['interest_rate'] = $interest_rate;
		
		$this->db->select(
			array(
				't2.id as loan_id',
				't2.loan_type',
				't2.loan_amount_balance',
				't2.interest_per_year',
				't3.member_id',
				't4.prename_short',
				't3.firstname_th',
				't3.lastname_th',
				't3.employee_id',
				't3.department',
				't3.faction',
				't3.level',
				't2.contract_number',
				't5.date_transfer'
			)
		);
		$this->db->from('coop_loan as t2');
		$this->db->join('coop_mem_apply as t3','t2.member_id = t3.member_id','inner');
		$this->db->join('coop_prename as t4','t3.prename_id = t4.prename_id','left');
		$this->db->join('coop_loan_transfer as t5','t2.id = t5.loan_id','inner');
		$this->db->where("
			t2.loan_type IN('3','4') 
			AND t2.loan_status = '1'
			AND t2.loan_amount_balance > 0
			AND t2.date_start_period <= '".($_GET['year']-543)."-".sprintf("%02d",$_GET['month'])."-".date('t',strtotime(($_GET['year']-543)."-".sprintf("%02d",$_GET['month'])."-01"))."'
		");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = $rs;

		$this->load->view('finance/finance_month_loan_emergent_report',$arr_data);
	}
	
	function finance_month_all_report(){
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
		
		$this->db->select(array('t1.*', 
			't2.prename_short'));
		$this->db->from('coop_mem_apply as t1');
		$this->db->join('coop_prename as t2','t1.prename_id = t2.prename_id','left');
		$this->db->where("member_status = '1'");
		$rs = $this->db->get()->result_array();
		$arr_data['rs'] = $rs;

		$this->load->view('finance/finance_month_all_report',$arr_data);
	}
}
