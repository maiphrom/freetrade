<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashier extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$arr_data = array();
		
		if($this->input->get('member_id')!=''){
			$member_id = $this->input->get('member_id');
		}else{
			$member_id = '';
		}
		$arr_data['member_id'] = $member_id;
		
		$this->db->select('*');
		$this->db->from('coop_receipt');
		$this->db->where("receipt_id LIKE '".date("Ym")."%'");
		$this->db->order_by("receipt_id DESC");
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		if(!empty($row)) {
			$id = (int) substr($row[0]["receipt_id"], 6);
			$receipt_number = date("Ym").sprintf("%06d", $id + 1);
		}
		else {
			$receipt_number = date("Ym")."000001";
		}
		$arr_data['receipt_number'] = $receipt_number;
		
		$this->db->select('*');
		$this->db->from('coop_account_list');
		$this->db->where("account_type = '1'");
		$row = $this->db->get()->result_array();
		$arr_data['account_list'] = $row;
		
		if($member_id != '') {
			$this->db->select('*');
			$this->db->from('coop_mem_apply');
			$this->db->where("member_id = '" . $member_id . "'");
			$row = $this->db->get()->result_array();
			$arr_data['row_member'] = $row[0];
			
			$this->db->select(array('coop_loan.id','coop_loan.contract_number'));
			$this->db->from('coop_loan');
			$this->db->join('coop_loan_transfer', 'coop_loan_transfer.loan_id = coop_loan.id', 'inner');
			$this->db->where("coop_loan.member_id = '".$member_id."' AND coop_loan.loan_status = '1'");
			$row = $this->db->get()->result_array();
			$arr_data['row_loan'] = $row;
		}else{
			$arr_data['row_member'] = array();
			$arr_data['row_loan'] = array();
		}
		$this->libraries->template('cashier/index',$arr_data);
	}
	
	function cal_receipt(){
		$data = $this->input->post();
		$principal_payment = 0;
		$interest = 0;
		if($data['loan_id']!=''){
			if(date('d')>='21'){
				$principal_payment = $data['amount'];
				$interest = 0;
				
			}else{
				$this->db->select(
					array(
						'coop_loan.id as loan_id',
						'coop_loan.contract_number',
						'coop_loan.loan_amount_balance',
						'coop_loan.interest_per_year',
						'coop_loan_type.loan_type',
						'coop_loan_transfer.date_transfer'
					)
				);
				$this->db->from('coop_loan');
				$this->db->join('coop_loan_type', 'coop_loan_type.id = coop_loan.loan_type', 'inner');
				$this->db->join('coop_loan_transfer', 'coop_loan_transfer.loan_id = coop_loan.id', 'inner');
				$this->db->where("coop_loan.id = '".$data['loan_id']."'");
				$row = $this->db->get()->result_array();
				$row_normal_loan = $row[0];
				
				$date_interesting = date('Y-m-d');
				
				$this->db->select('payment_date');
				$this->db->from('coop_finance_transaction');
				$this->db->where("loan_id = '".$row_normal_loan['loan_id']."'");
				$this->db->order_by("payment_date DESC");
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				$row_date_prev_paid = $row[0];
				
				$date_prev_paid = $row_date_prev_paid['payment_date']!=''?$row_date_prev_paid['payment_date']:$row_normal_loan['date_transfer'];
				$diff = date_diff(date_create($date_prev_paid),date_create($date_interesting));
				$date_count = $diff->format("%a");
				$date_count = $date_count+1;
				
				$interest = ((($row_normal_loan['loan_amount_balance']*$row_normal_loan['interest_per_year'])/100)/365)*$date_count;
				$interest = round($interest);
				$principal_payment = $data['amount']-$interest;
			}
		}else{
			$principal_payment = $data['amount'];
			$interest = 0;
		}
		$data_arr = array();
		if($data['amount']<$interest){
			$data_arr['result'] = 'error';
			$data_arr['error_msg'] = 'กรุณากรอกจำนวนเงินมากกว่าจำนวนดอกเบี้ย';
		}else{
			$data_arr['account_list'] = $data['account_list'];
			$data_arr['account_list_text'] = $data['account_list_text']; 
			$data_arr['loan_id'] = $data['loan_id'];
			$data_arr['amount'] = $data['amount'];
			$data_arr['principal_payment'] = $principal_payment;
			$data_arr['interest'] = $interest;
		}
		echo json_encode($data_arr);
		exit;
	}

	function save(){
		$data_post = $this->input->post();
		//echo"<pre>";print_r($data_post);exit;

		$this->db->select('*');
		$this->db->from('coop_receipt');
		$this->db->where("receipt_id LIKE '".date("Ym")."%'");
		$this->db->order_by("receipt_id DESC");
		$this->db->limit(1);
		$row = $this->db->get()->result_array();
		if(!empty($row)) {
			$id = (int) substr($row[0]["receipt_id"], 6);
			$receipt_number = date("Ym").sprintf("%06d", $id + 1);
		}
		else {
			$receipt_number = date("Ym")."000001";
		}
		$data_insert = array();
		$data_insert['receipt_id'] = $receipt_number;
		$data_insert['member_id'] = $data_post['member_id'];
		$total = 0;
		foreach($data_post['amount'] as $key => $value){
			$total += $value;
		}
		$data_insert['sumcount'] = number_format($total,2,'.','');
		$data_insert['receipt_datetime'] = date('Y-m-d H:i:s');
		$data_insert['admin_id'] = $_SESSION['USER_ID'];
		$this->db->insert('coop_receipt', $data_insert);

		$data = array();
		$data['coop_account']['account_description'] = "รายการรับชำระเงิน";
		$data['coop_account']['account_datetime'] = date('Y-m-d H:i:s');
		
		$data['coop_account_detail'][10100]['account_type'] = 'debit';
		$data['coop_account_detail'][10100]['account_amount'] = $total;
		$data['coop_account_detail'][10100]['account_chart_id'] = '10100';
		
		$loan_amount_balance = 0;
		foreach($data_post['account_list'] as $key => $value){
			$data_insert = array();
			$data_insert['receipt_id'] = $receipt_number;
			$data_insert['receipt_list'] = $data_post['account_list'][$key];
			$data_insert['receipt_count'] = number_format($data_post['amount'][$key],2,'.','');
			$this->db->insert('coop_receipt_detail', $data_insert);
			
			if($data_post['loan_id'][$key]!=''){
				$this->db->select('loan_amount_balance');
				$this->db->from('coop_loan');
				$this->db->where("id = '".$data_post['loan_id'][$key]."'");
				$row = $this->db->get()->result_array();
				$row_loan = @$row[0];
				
				$loan_amount_balance = @$row_loan['loan_amount_balance'] - $data_post['principal_payment'][$key];
				if($loan_amount_balance<=0){
					$loan_amount_balance = 0;
					$data_insert = array();
					$data_insert['loan_amount_balance'] = $loan_amount_balance;
					$data_insert['loan_status'] = '4';
					$this->db->where('id', $data_post['loan_id'][$key]);
					$this->db->update('coop_loan', $data_insert);
				}else{
					$data_insert = array();
					$data_insert['loan_amount_balance'] = number_format($loan_amount_balance,2,'.','');
					$this->db->where('id', $data_post['loan_id'][$key]);
					$this->db->update('coop_loan', $data_insert);
				}
			}
			$data_insert = array();
			$data_insert['member_id'] = $data_post['member_id'];
			$data_insert['receipt_id'] = $receipt_number;
			$data_insert['loan_id'] = $data_post['loan_id'][$key];
			$data_insert['account_list_id'] = $data_post['account_list'][$key];
			$data_insert['principal_payment'] = number_format($data_post['principal_payment'][$key],2,'.','');
			$data_insert['interest'] = number_format($data_post['interest'][$key],2,'.','');
			$data_insert['total_amount'] = $data_post['amount'][$key];
			$data_insert['loan_amount_balance'] = number_format($loan_amount_balance,2,'.','');
			$data_insert['payment_date'] = date('Y-m-d H:i:s');
			$data_insert['createdatetime'] = date('Y-m-d H:i:s');
			$this->db->insert('coop_finance_transaction', $data_insert);
			
			$data['coop_account_detail'][40100]['account_type'] = 'credit';
			@$data['coop_account_detail'][40100]['account_amount'] += number_format($data_post['interest'][$key],2,'.','');
			$data['coop_account_detail'][40100]['account_chart_id'] = '40100';
			
			if($data_post['loan_id'][$key] == ''){
				$this->db->select('account_chart_id');
				$this->db->from('coop_account_match');
				$this->db->where("match_id = '".$data_post['account_list'][$key]."' AND match_type = 'account_list'");
				$row = $this->db->get()->result_array();
				$row_account_chart = @$row[0];
				$account_chart_id = @$row_account_chart['account_chart_id'];
			}else{
				$this->db->select('coop_account_match.account_chart_id');
				$this->db->from('coop_account_match');
				$this->db->join('coop_loan', 'coop_account_match.match_id = coop_loan.loan_type', 'left');
				$this->db->where("coop_loan.id = '".$data_post['loan_id'][$key]."' AND coop_account_match.match_type = 'loan'");
				$row = $this->db->get()->result_array();
				$row_account_chart = @$row[0];
				$account_chart_id = @$row_account_chart['account_chart_id'];
			}
			
			$data['coop_account_detail'][$key]['account_type'] = 'credit';
			$data['coop_account_detail'][$key]['account_amount'] = number_format($data_post['principal_payment'][$key],2,'.','');
			$data['coop_account_detail'][$key]['account_chart_id'] = $account_chart_id;
		}
		$this->account_transaction->account_process($data);
		
		echo"<script>document.location.href='".base_url(PROJECTPATH.'/admin/receipt_pdf?receipt_id='.$receipt_number)."'</script>";
		exit;
	} 
	function cashier_month(){
		$arr_data = array();
		$month_arr = array('1'=>'มกราคม','2'=>'กุมภาพันธ์','3'=>'มีนาคม','4'=>'เมษายน','5'=>'พฤษภาคม','6'=>'มิถุนายน','7'=>'กรกฎาคม','8'=>'สิงหาคม','9'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
		$arr_data['month_arr'] = $month_arr;
		
		if(@$this->input->get('month')!='' && @$this->input->get('year')!=''){
			$month = $_GET['month'];
			$year = $_GET['year'];
		}else{
			$month = date('m');
			$year = date('Y')+543;
		}
		$arr_data['month'] = $month;
		$arr_data['year'] = $year;
		
		$this->db->select('setting_value');
		$this->db->from('coop_share_setting');
		$this->db->where("setting_id = '1'");
		$row = $this->db->get()->result_array();
		$row_share_value = $row[0];
		$share_value = $row_share_value['setting_value'];
		
		$x=0;
		$join_arr = array();
		
		$this->paginater_all->type(DB_TYPE);
		$this->paginater_all->select('*');
		$this->paginater_all->main_table('coop_mem_apply');
		$this->paginater_all->where("member_status = '1'");
		$this->paginater_all->page_now(@$_GET["page"]);
		$this->paginater_all->per_page(10);
		$this->paginater_all->page_link_limit(20);
		$this->paginater_all->order_by('member_id ASC');
		$this->paginater_all->join_arr($join_arr);
		$row = $this->paginater_all->paginater_process();
		//echo"<pre>";print_r($row);exit;
		$paging = $this->pagination_center->paginating($row['page'], $row['num_rows'], $row['per_page'], $row['page_link_limit']);//$page_now = 1, $row_total = 1, $per_page = 20, $page_limit = 20
		$i = $row['page_start'];


		$arr_data['num_rows'] = $row['num_rows'];
		$arr_data['paging'] = $paging;
		$arr_data['data'] = $row['data'];
		$arr_data['i'] = $i;
		foreach($arr_data['data'] as $key => $value){
			$arr_data['data'][$key]['emergent_loan'] = 0;
			$arr_data['data'][$key]['normal_loan'] = 0;
			
			$this->db->select('change_value');
			$this->db->from('coop_change_share');
			$this->db->where("member_id = '".$value['member_id']."' AND (change_share_status = '1' OR change_share_status = '2')");
			$this->db->order_by('change_share_id DESC');
			$this->db->limit(1);
			$row = $this->db->get()->result_array();
			$row_change_share = @$row[0];
			
			if(@$row_change_share['change_value'] != ''){
				$num_share = $row_change_share['change_value'];
			}else{
				$this->db->select('share_salary');
				$this->db->from('coop_share_rule');
				$this->db->where("salary_rule <= '".$value['salary']."'");
				$this->db->order_by('salary_rule DESC');
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				$row_share_rule = @$row[0];
				
				$num_share = $row_share_rule['share_salary'];
			}
			$share = $num_share*$share_value;
			$arr_data['data'][$key]['share'] = $share;
			
			$this->db->select(
				array(
					'coop_loan.id',
					'coop_loan.loan_type',
					'coop_loan.contract_number',
					'coop_loan.loan_amount_balance',
					'coop_loan.interest_per_year',
					'coop_loan_transfer.date_transfer'
				)
			);
			$this->db->from('coop_loan');
			$this->db->join('coop_loan_transfer', 'coop_loan_transfer.loan_id = coop_loan.id', 'inner');
			$this->db->where("
				coop_loan.loan_amount_balance > 0
				AND coop_loan.member_id = '".$value['member_id']."'
				AND coop_loan.loan_type IN ('1','2','3','5','6')
				AND coop_loan.loan_status = '1'
				AND coop_loan.date_start_period <= '".($year-543)."-".$month."-".date('t',strtotime(($year-543)."-".$month."-01"))."'
			");
			$row = $this->db->get()->result_array();
			$normal_loan = 0;
			foreach($row as $key => $row_normal_loan){
				$this->db->select(
					array(
						'principal_payment'
					)
				);
				$this->db->from('coop_loan_period');
				$this->db->where("loan_id = '".$row_normal_loan['id']."'");
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				$row_principal_payment = $row[0];
				
				$date_interesting = date('Y-m-t',strtotime(($year-543)."-".sprintf("%02d",$month).'-01'));
				$this->db->select(
					array(
						'payment_date'
					)
				);
				$this->db->from('coop_finance_transaction');
				$this->db->where("loan_id = '".$row_normal_loan['id']."'");
				$this->db->order_by("payment_date DESC");
				$this->db->limit(1);
				$row = $this->db->get()->result_array();
				$row_date_prev_paid = @$row[0];
				
				$date_prev_paid = $row_date_prev_paid['payment_date']!=''?$row_date_prev_paid['payment_date']:$row_normal_loan['date_transfer'];
				$diff = date_diff(date_create($date_prev_paid),date_create($date_interesting));
				$date_count = $diff->format("%a");
				$date_count = $date_count+1;
				
				$interest = ((($row_normal_loan['loan_amount_balance']*$row_normal_loan['interest_per_year'])/100)/365)*$date_count;
				
				if($row_principal_payment['principal_payment'] > $row_normal_loan['loan_amount_balance']){
					$principal_payment = $row_normal_loan['loan_amount_balance'];
				}else{
					$principal_payment = $row_principal_payment['principal_payment'];
				}
				
				if($row_normal_loan['loan_type'] == '3'){
					$arr_data['data'][$key]['emergent_loan'] += $interest + $principal_payment;
				}else{
					$arr_data['data'][$key]['normal_loan'] += $interest + $principal_payment;
				}
				
				$this->db->select(
					array(
						'coop_receipt.receipt_id',
						'coop_loan.loan_type',
						'coop_finance_transaction.total_amount'
					)
				);
				$this->db->from('coop_receipt');
				$this->db->join('coop_finance_transaction', 'coop_receipt.receipt_id = coop_finance_transaction.receipt_id', 'inner');
				$this->db->join('coop_loan', 'coop_finance_transaction.loan_id = coop_loan.id', 'left');
				$this->db->where("
					coop_receipt.member_id = '".$value['member_id']."' 
					AND coop_receipt.month_receipt = '".$month."' 
					AND coop_receipt.year_receipt = '".$year."'
				");
				$row = $this->db->get()->result_array();
				$normal_loan_re = 0;
				$emergent_loan_re = 0;
				$receipt_id = '';
				foreach($row as $key2 => $row_check_receipt){
					$receipt_id = $row_check_receipt['receipt_id'];
					if(in_array($row_check_receipt['loan_type'], array('1','2','5','6'))){
						$normal_loan_re+=$row_check_receipt['total_amount'];
					}else if($row_check_receipt['loan_type'] == '3'){
						$emergent_loan_re+=$row_check_receipt['total_amount'];
					}
				}
				if($normal_loan_re > 0){
					$arr_data['data'][$key]['normal_loan'] = $normal_loan_re;
				}
				if($emergent_loan_re > 0){
					$arr_data['data'][$key]['emergent_loan'] = $emergent_loan_re;
				}
			}
		}
		
		$this->libraries->template('cashier/cashier_month',$arr_data);
	}
}
